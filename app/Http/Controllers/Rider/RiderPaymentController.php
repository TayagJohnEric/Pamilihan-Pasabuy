<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RiderPaymentController extends Controller
{
    /**
     * Display list of payments pending rider verification
     */
    public function index(Request $request)
    {
        $rider = Auth::user();
        
        if ($rider->role !== 'rider') {
            abort(403, 'Unauthorized access');
        }
        
        // Get payments for orders assigned to this rider
        $query = Payment::with([
                'order.customer',
                'order.deliveryAddress.district',
                'order.orderItems'
            ])
            ->whereHas('order', function($q) use ($rider) {
                $q->where('rider_user_id', $rider->id);
            })
            ->where('payment_method_used', 'online_payment');
        
        // Filter by verification status
        $filterStatus = $request->get('filter_status', 'pending');
        if ($filterStatus && $filterStatus !== 'all') {
            $query->where('rider_verification_status', $filterStatus);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics
        $stats = [
            'pending_count' => Payment::whereHas('order', function($q) use ($rider) {
                    $q->where('rider_user_id', $rider->id);
                })
                ->where('rider_verification_status', 'pending')
                ->count(),
            'verified_today' => Payment::whereHas('order', function($q) use ($rider) {
                    $q->where('rider_user_id', $rider->id);
                })
                ->where('rider_verification_status', 'verified')
                ->whereDate('rider_verified_at', today())
                ->count(),
            'rejected_today' => Payment::whereHas('order', function($q) use ($rider) {
                    $q->where('rider_user_id', $rider->id);
                })
                ->where('rider_verification_status', 'rejected')
                ->whereDate('updated_at', today())
                ->count(),
        ];
        
        return view('rider.payments.index', compact('payments', 'stats', 'filterStatus'));
    }
    
    /**
     * Display specific payment for verification
     */
    public function show(Payment $payment)
    {
        $rider = Auth::user();
        
        if ($rider->role !== 'rider') {
            abort(403, 'Unauthorized access');
        }
        
        // Load relationships
        $payment->load([
            'order.customer',
            'order.rider',
            'order.deliveryAddress.district',
            'order.orderItems.product.vendor',
            'verifiedByRider'
        ]);
        
        // Verify this payment is for an order assigned to this rider
        if ($payment->order->rider_user_id !== $rider->id) {
            abort(403, 'This payment is not assigned to you.');
        }
        
        // Check if payment is for online payment method
        if ($payment->payment_method_used !== 'online_payment') {
            return redirect()->route('rider.payments.index')
                ->with('error', 'This payment is not an online payment.');
        }
        
        return view('rider.payments.show', compact('payment'));
    }
    
    /**
     * Verify payment as received
     */
    public function verify(Request $request, Payment $payment)
    {
        $rider = Auth::user();
        
        if ($rider->role !== 'rider') {
            abort(403, 'Unauthorized access');
        }
        
        // Load necessary relationships
        $payment->load([
            'order.customer',
            'order.rider',
            'order.orderItems.product.vendor'
        ]);
        
        // Verify this payment is for an order assigned to this rider
        if ($payment->order->rider_user_id !== $rider->id) {
            abort(403, 'This payment is not assigned to you.');
        }
        
        // Check if already verified
        if ($payment->rider_verification_status === 'verified') {
            return redirect()->route('rider.payments.show', $payment)
                ->with('info', 'This payment has already been verified.');
        }
        
        $validated = $request->validate([
            'verification_notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update payment verification
            $payment->update([
                'rider_verification_status' => 'verified',
                'rider_verified_at' => now(),
                'rider_verification_notes' => $validated['verification_notes'] ?? 'Payment confirmed received in GCash',
                'verified_by_rider_id' => $rider->id,
                'status' => 'completed', // Mark payment as completed (matches DB constraint)
            ]);
            
            // Update order payment status
            $payment->order->update([
                'payment_status' => 'paid'
            ]);
            
            // Log order status change
            $this->logOrderStatusChange(
                $payment->order->id,
                $payment->order->status,
                'Payment verified by rider - ready for pickup',
                $rider->id
            );
            
            DB::commit();
            
            // Send notifications (outside transaction to avoid rollback issues)
            try {
                $this->notifyCustomerPaymentVerified($payment);
                $this->notifyAdminPaymentVerified($payment);
                $this->notifyVendorsReadyForPickup($payment->order);
            } catch (\Exception $notificationError) {
                // Log notification errors but don't fail the verification
                Log::warning('Payment verified but notification failed', [
                    'payment_id' => $payment->id,
                    'error' => $notificationError->getMessage()
                ]);
            }
            
            // Clear any previous errors and redirect to order details page
            session()->forget('error');
            return redirect()->route('rider.orders.show', $payment->order->id)
                ->with('success', 'Payment verified successfully! You can now proceed with pickup and delivery.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rider payment verification failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('rider.orders.show', $payment->order->id)
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject payment verification
     */
    public function reject(Request $request, Payment $payment)
    {
        $rider = Auth::user();
        
        if ($rider->role !== 'rider') {
            abort(403, 'Unauthorized access');
        }
        
        // Load necessary relationships
        $payment->load([
            'order.customer',
            'order.rider'
        ]);
        
        // Verify this payment is for an order assigned to this rider
        if ($payment->order->rider_user_id !== $rider->id) {
            abort(403, 'This payment is not assigned to you.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update payment verification
            $payment->update([
                'rider_verification_status' => 'rejected',
                'rider_verification_notes' => $validated['rejection_reason'],
                'verified_by_rider_id' => $rider->id,
            ]);
            
            // Log order status change
            $this->logOrderStatusChange(
                $payment->order->id,
                $payment->order->status,
                'Payment verification rejected by rider: ' . $validated['rejection_reason'],
                $rider->id
            );
            
            DB::commit();
            
            // Send notifications (outside transaction to avoid rollback issues)
            try {
                $this->notifyCustomerPaymentRejected($payment, $validated['rejection_reason']);
                $this->notifyAdminPaymentDispute($payment, $validated['rejection_reason']);
            } catch (\Exception $notificationError) {
                // Log notification errors but don't fail the rejection
                Log::warning('Payment rejected but notification failed', [
                    'payment_id' => $payment->id,
                    'error' => $notificationError->getMessage()
                ]);
            }
            
            // Clear any previous errors and redirect to order details page
            session()->forget('error');
            return redirect()->route('rider.orders.show', $payment->order->id)
                ->with('success', 'Payment marked as not received. Customer and admin have been notified.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rider payment rejection failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('rider.orders.show', $payment->order->id)
                ->with('error', 'Payment rejection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Log order status change
     */
    private function logOrderStatusChange($orderId, $status, $notes, $userId)
    {
        DB::table('order_status_history')->insert([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'updated_by_user_id' => $userId, // Fixed: correct column name
            'created_at' => now(),
        ]);
    }
    
    /**
     * Notify customer that payment is verified
     */
    private function notifyCustomerPaymentVerified($payment)
    {
        Notification::create([
            'user_id' => $payment->order->customer_user_id,
            'type' => 'payment_verified',
            'title' => 'Payment Confirmed!',
            'message' => [
                'order_id' => $payment->order->id,
                'message' => 'Your payment has been confirmed by the rider. Your order will be picked up and delivered soon.',
                'amount' => $payment->amount_paid,
            ],
            'related_entity_type' => Order::class,
            'related_entity_id' => $payment->order->id,
        ]);
    }
    
    /**
     * Notify customer that payment is rejected
     */
    private function notifyCustomerPaymentRejected($payment, $reason)
    {
        Notification::create([
            'user_id' => $payment->order->customer_user_id,
            'type' => 'payment_rejected',
            'title' => 'Payment Verification Issue',
            'message' => [
                'order_id' => $payment->order->id,
                'message' => 'The rider has not received your payment. Please check your GCash transaction and resubmit proof if needed.',
                'reason' => $reason,
                'payment_id' => $payment->id,
            ],
            'related_entity_type' => Order::class,
            'related_entity_id' => $payment->order->id,
        ]);
    }
    
    /**
     * Notify admin about payment verification (for records)
     */
    private function notifyAdminPaymentVerified($payment)
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'payment_verified_by_rider',
                'title' => 'Payment Verified by Rider',
                'message' => [
                    'order_id' => $payment->order->id,
                    'rider_name' => $payment->order->rider->name,
                    'amount' => $payment->amount_paid,
                    'message' => 'Rider has confirmed payment receipt for Order #' . $payment->order->id,
                ],
                'related_entity_type' => Payment::class,
                'related_entity_id' => $payment->id,
            ]);
        }
    }
    
    /**
     * Notify admin about payment dispute
     */
    private function notifyAdminPaymentDispute($payment, $reason)
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'payment_dispute',
                'title' => 'Payment Dispute - Requires Review',
                'message' => [
                    'order_id' => $payment->order->id,
                    'rider_name' => $payment->order->rider->name,
                    'customer_name' => $payment->order->customer->name,
                    'amount' => $payment->amount_paid,
                    'reason' => $reason,
                    'message' => 'Rider reports payment not received for Order #' . $payment->order->id . '. Please review and resolve.',
                ],
                'related_entity_type' => Payment::class,
                'related_entity_id' => $payment->id,
            ]);
        }
    }
    
    /**
     * Notify vendors that order is ready for pickup
     */
    private function notifyVendorsReadyForPickup($order)
    {
        // Get unique vendor user IDs from order items
        $vendorUserIds = $order->orderItems
            ->map(function ($item) {
                return $item->product && $item->product->vendor ? $item->product->vendor->user_id : null;
            })
            ->filter()
            ->unique();
        
        foreach ($vendorUserIds as $vendorUserId) {
            try {
                Notification::create([
                    'user_id' => $vendorUserId,
                    'type' => 'order_ready_for_pickup',
                    'title' => 'Order Ready for Pickup',
                    'message' => [
                        'order_id' => $order->id,
                        'rider_name' => $order->rider->name ?? 'Rider',
                        'message' => 'Payment confirmed. Rider will pick up items soon.',
                    ],
                    'related_entity_type' => Order::class,
                    'related_entity_id' => $order->id,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to notify vendor', [
                    'vendor_user_id' => $vendorUserId,
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
