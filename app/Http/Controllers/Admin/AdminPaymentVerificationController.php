<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Customer\CustomerOrderFulfillmentController;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class AdminPaymentVerificationController extends Controller
{
    
/**
     * Display list of pending payment verifications
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $query = Payment::with([
                'order.customer',
                'order.rider',
                'order.deliveryAddress'
            ])
            ->where('payment_method_used', 'online_payment');

        // Filter by status
        $filterStatus = $request->get('filter_status', 'pending_review');
        if ($filterStatus && $filterStatus !== 'all') {
            $query->where('admin_verification_status', $filterStatus);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('order', function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'pending_count' => Payment::where('payment_method_used', 'online_payment')
                ->where('admin_verification_status', 'pending_review')
                ->count(),
            'approved_today' => Payment::where('payment_method_used', 'online_payment')
                ->where('admin_verification_status', 'approved')
                ->whereDate('payment_processed_at', today())
                ->count(),
            'rejected_today' => Payment::where('payment_method_used', 'online_payment')
                ->where('admin_verification_status', 'rejected')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('admin.financial.payment.index', compact('payments', 'stats', 'filterStatus'));
    }

    /**
     * Display payment details for review
     */
    public function show(Payment $payment)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Load relationships
        $payment->load([
            'order.customer',
            'order.rider',
            'order.deliveryAddress.district',
            'order.orderItems.product.vendor',
            'verifiedBy'
        ]);

        // Check if payment is for online payment method
        if ($payment->payment_method_used !== 'online_payment') {
            return redirect()->route('admin.payments.pending')
                ->with('error', 'This payment is not an online payment.');
        }

        return view('admin.financial.payment.show', compact('payment'));
    }

    /**
     * Approve payment and trigger order fulfillment
     */
    public function approve(Request $request, Payment $payment)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        // Check if already verified
        if ($payment->admin_verification_status !== 'pending_review') {
            return redirect()->back()
                ->with('error', 'This payment has already been ' . $payment->admin_verification_status . '.');
        }

        DB::beginTransaction();

        try {
            $admin = Auth::user();
            $order = $payment->order;

            // Update payment record
            $payment->update([
                'status' => 'completed',
                'admin_verification_status' => 'approved',
                'verified_by_user_id' => $admin->id,
                'admin_notes' => $validated['admin_notes'],
                'payment_processed_at' => now(),
            ]);

            // Update order status
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
            ]);

            // Log status change in order history
            $this->logOrderStatusChange(
                $order->id,
                'processing',
                'Payment approved by admin: ' . $admin->name . '. ' . ($validated['admin_notes'] ?? ''),
                $admin->id
            );

            DB::commit();

            // Trigger order fulfillment
            $fulfillmentController = new CustomerOrderFulfillmentController();
            $fulfillmentController->finalizeOrder($order);

            // Send notifications
            $this->notifyCustomerPaymentApproved($order, $payment);
            $this->notifyVendorsOfApprovedOrder($order);

            Log::info('Payment approved by admin', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'admin_id' => $admin->id,
            ]);

            return redirect()->route('admin.payments.pending')
                ->with('success', 'Payment approved successfully! Order is now being processed.');

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Payment approval failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'admin_id' => Auth::id(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to approve payment. Please try again.');
        }
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, Payment $payment)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Please provide a reason for rejection.',
        ]);

        // Check if already verified
        if ($payment->admin_verification_status !== 'pending_review') {
            return redirect()->back()
                ->with('error', 'This payment has already been ' . $payment->admin_verification_status . '.');
        }

        DB::beginTransaction();

        try {
            $admin = Auth::user();
            $order = $payment->order;

            // Update payment record
            $payment->update([
                'status' => 'failed',
                'admin_verification_status' => 'rejected',
                'verified_by_user_id' => $admin->id,
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Update order status
            $order->update([
                'status' => 'failed',
                'payment_status' => 'failed',
            ]);

            // Log status change in order history
            $this->logOrderStatusChange(
                $order->id,
                'failed',
                'Payment rejected by admin: ' . $admin->name . '. Reason: ' . $validated['admin_notes'],
                $admin->id
            );

            DB::commit();

            // Send notification to customer
            $this->notifyCustomerPaymentRejected($order, $payment, $validated['admin_notes']);

            Log::info('Payment rejected by admin', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'admin_id' => $admin->id,
                'reason' => $validated['admin_notes'],
            ]);

            return redirect()->route('admin.payments.pending')
                ->with('success', 'Payment rejected. Customer has been notified.');

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Payment rejection failed: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'admin_id' => Auth::id(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reject payment. Please try again.');
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Log order status change in history
     */
    private function logOrderStatusChange($orderId, $status, $notes = null, $updatedByUserId = null)
    {
        try {
            \App\Models\OrderStatusHistory::create([
                'order_id' => $orderId,
                'status' => $status,
                'notes' => $notes,
                'updated_by_user_id' => $updatedByUserId,
                'created_at' => now(),
            ]);
        } catch (Exception $e) {
            Log::error("Failed to log order status change: " . $e->getMessage());
        }
    }

    /**
     * Create notification
     */
    private function createNotification($userId, $type, $title, $message, $entityType = null, $entityId = null)
    {
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'related_entity_type' => $entityType,
            'related_entity_id' => $entityId,
        ]);
    }

    /**
     * Notify customer that payment was approved
     */
    private function notifyCustomerPaymentApproved(Order $order, Payment $payment)
    {
        $this->createNotification(
            $order->customer_user_id,
            'payment_approved',
            'Payment Verified Successfully',
            [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'amount' => '₱' . number_format($payment->amount_paid, 2),
                'message' => 'Your payment has been verified and approved. Your order is now being prepared by our vendors.'
            ],
            Order::class,
            $order->id
        );
    }

    /**
     * Notify customer that payment was rejected
     */
    private function notifyCustomerPaymentRejected(Order $order, Payment $payment, $reason)
    {
        $this->createNotification(
            $order->customer_user_id,
            'payment_rejected',
            'Payment Verification Failed',
            [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'amount' => '₱' . number_format($payment->amount_paid, 2),
                'reason' => $reason,
                'message' => 'Your payment could not be verified. Reason: ' . $reason . '. Please contact support or resubmit your payment proof.'
            ],
            Order::class,
            $order->id
        );
    }

    /**
     * Notify all vendors involved in approved order
     */
    private function notifyVendorsOfApprovedOrder(Order $order)
    {
        $vendorIds = $order->orderItems()
            ->with('product.vendor')
            ->get()
            ->pluck('product.vendor.user_id')
            ->unique();

        foreach ($vendorIds as $vendorUserId) {
            $this->createNotification(
                $vendorUserId,
                'new_order',
                'New Order Received - Payment Verified',
                [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer->name,
                    'item_count' => $order->orderItems->count(),
                    'message' => 'You have received a new order with verified payment. Please prepare the items for pickup.'
                ],
                Order::class,
                $order->id
            );
        }
    }

}
