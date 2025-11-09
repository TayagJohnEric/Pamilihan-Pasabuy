<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Notification;
use App\Models\Rider;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RiderOrderController extends Controller
{
    /**
     * Display dashboard with pending assignments and current deliveries
     * Shows orders that need rider action or are currently being handled
     */
    public function index()
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Get pending assignments (awaiting rider acceptance)
        $pendingAssignments = Order::where('rider_user_id', Auth::id())
            ->where('status', 'awaiting_rider_assignment')
            ->with([
                'customer',
                'deliveryAddress.district',
                'orderItems.product.vendor'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current active deliveries (accepted, picked up, or already on the way)
        $activeDeliveries = Order::where('rider_user_id', Auth::id())
            ->whereIn('status', ['assigned', 'pickup_confirmed', 'out_for_delivery'])
            ->with([
                'customer',
                'deliveryAddress.district',
                'orderItems.product.vendor'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get delivery history
        $deliveryHistory = Order::where('rider_user_id', Auth::id())
            ->whereIn('status', ['delivered', 'cancelled'])
            ->with([
                'customer',
                'deliveryAddress.district'
            ])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('rider.orders.index', compact('pendingAssignments', 'activeDeliveries', 'deliveryHistory'));
    }

    /**
     * Show detailed view of a specific order
     * Displays all order information including pickup and delivery details
     */
    public function show(Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            return redirect()->route('rider.orders.index')->with('error', 'Order not found or not assigned to you.');
        }

        // Load order with all necessary relationships
        $order->load([
            'customer',
            'deliveryAddress.district',
            'orderItems.product.vendor',
            'statusHistory.updatedBy'
        ]);

        // Group order items by vendor for easier pickup organization
        $vendorGroups = $order->orderItems->groupBy('product.vendor.id');

        return view('rider.orders.show', compact('order', 'vendorGroups'));
    }

    /**
     * Accept an assigned order
     * Changes status from awaiting_rider_assignment to assigned
     */
    public function acceptOrder(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order not assigned to you.'], 403);
            }
            return redirect()->back()->with('error', 'Order not assigned to you.');
        }

        // Check if order is still pending acceptance
        if ($order->status !== 'awaiting_rider_assignment') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order is no longer available for acceptance.'], 400);
            }
            return redirect()->back()->with('error', 'Order is no longer available for acceptance.');
        }

        try {
            DB::beginTransaction();

            // Update order status to assigned
            $order->update(['status' => 'assigned']);

            // Log status change
            $this->logOrderStatusChange(
                $order->id,
                'assigned',
                'Rider accepted delivery assignment',
                Auth::id()
            );

            // Notify customer that rider accepted the assignment
            $notificationMessage = 'Your order has been assigned to a rider and will be picked up soon.';
            $notificationType = 'rider_assigned';
            
            // If online payment and not yet paid, notify customer to complete payment
            if ($order->payment_method === 'online_payment' && $order->payment_status === 'pending') {
                $notificationMessage = 'A rider has accepted your order! Please complete your payment to proceed with delivery.';
                $notificationType = 'payment_required';
            }
            
            $this->createNotification(
                $order->customer_user_id,
                $notificationType,
                'Rider Assigned to Your Order',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'rider_phone' => Auth::user()->phone_number,
                    'message' => $notificationMessage,
                    'requires_payment' => ($order->payment_method === 'online_payment' && $order->payment_status === 'pending')
                ],
                Order::class,
                $order->id
            );

            // Set rider availability to false upon accepting an order
            $rider->update(['is_available' => false]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order accepted successfully!',
                    'redirect_url' => route('rider.orders.show', $order)
                ]);
            }

            return redirect()->route('rider.orders.show', $order)->with('success', 'Order accepted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to accept order. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to accept order. Please try again.');
        }
    }

    /**
     * Attempt rider reassignment after a decline
     * - Use specific rider chosen by customer when available and not the declining rider
     * - Otherwise assign a random available rider (excluding the declining rider)
     * Keeps order status as 'awaiting_rider_assignment' until accepted by the new rider
     */
    private function reassignRiderAfterDecline(Order $order, $declinedUserId)
    {
        try {
            // Try preferred rider first if set and not the one who declined
            if ($order->preferred_rider_id && $order->preferred_rider_id !== $declinedUserId) {
                $preferredRider = Rider::with('user')
                    ->where('user_id', $order->preferred_rider_id)
                    ->where('is_available', true)
                    ->where('verification_status', 'verified')
                    ->whereHas('user', function ($q) {
                        $q->where('is_active', true);
                    })
                    ->first();

                if ($preferredRider) {
                    Log::info("[Rider Reassignment] Assigning preferred rider {$preferredRider->id} to order {$order->id}");
                    $this->completeRiderAssignment($order, $preferredRider);
                    return;
                }
                Log::info("[Rider Reassignment] Preferred rider not available for order {$order->id}, falling back to random available rider");
            }

            // Find a random available rider excluding the declining rider
            $newRider = Rider::with('user')
                ->where('is_available', true)
                ->where('verification_status', 'verified')
                ->where('user_id', '!=', $declinedUserId)
                ->whereHas('user', function ($q) {
                    $q->where('is_active', true);
                })
                ->inRandomOrder()
                ->first();

            if ($newRider) {
                Log::info("[Rider Reassignment] Assigning new rider {$newRider->id} to order {$order->id}");
                $this->completeRiderAssignment($order, $newRider);
                return;
            }

            // No riders available - let customer know
            $this->handleNoAvailableRiders($order);
        } catch (\Exception $e) {
            Log::error('[Rider Reassignment] Error during reassignment: ' . $e->getMessage());
        }
    }

    /**
     * Complete the rider assignment process (awaiting acceptance)
     * - Assigns rider to order (rider_user_id)
     * - Logs status as awaiting_rider_assignment
     * - Notifies customer and the rider
     */
    private function completeRiderAssignment(Order $order, Rider $rider)
    {
        // Assign rider but keep status as awaiting_rider_assignment
        $order->update(['rider_user_id' => $rider->user_id]);

        $this->logOrderStatusChange(
            $order->id,
            'awaiting_rider_assignment',
            "Rider assigned (awaiting acceptance): {$rider->user->first_name} {$rider->user->last_name}",
            null
        );

        // Notify customer that a rider is being assigned
        $this->createNotification(
            $order->customer_user_id,
            'rider_assignment_pending',
            'Rider Assignment in Progress',
            [
                'order_id' => $order->id,
                'message' => 'A rider is being assigned to your order. Please wait for confirmation.'
            ],
            Order::class,
            $order->id
        );

        // Notify rider about new delivery assignment (awaiting acceptance)
        $this->createNotification(
            $rider->user_id,
            'delivery_assigned_pending',
            'New Delivery Assignment (Action Required)',
            [
                'order_id' => $order->id,
                'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                'delivery_fee' => $order->delivery_fee,
                'message' => 'You have been assigned a new delivery. Please accept or decline.'
            ],
            Order::class,
            $order->id
        );
    }

    /**
     * When no riders are available, inform the customer
     */
    private function handleNoAvailableRiders(Order $order)
    {
        Log::warning("[Rider Reassignment] No available riders for order {$order->id}");

        $this->createNotification(
            $order->customer_user_id,
            'rider_assignment_delayed',
            'Rider Assignment Delayed',
            [
                'order_id' => $order->id,
                'message' => 'We are currently looking for an available rider for your order.'
            ],
            Order::class,
            $order->id
        );
    }

    /**
     * Start delivery (rider en route)
     * Transitions order from pickup_confirmed to out_for_delivery and notifies customer
     */
    public function startDelivery(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify rider and current status
        if ($order->rider_user_id !== Auth::id() || $order->status !== 'pickup_confirmed') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unable to start delivery for this order.'], 403);
            }
            return redirect()->back()->with('error', 'Unable to start delivery for this order.');
        }

        try {
            DB::beginTransaction();

            // Update status to out_for_delivery
            $order->update(['status' => 'out_for_delivery']);

            // Log status change
            $this->logOrderStatusChange(
                $order->id,
                'out_for_delivery',
                'Rider started delivery - en route to customer',
                Auth::id()
            );

            // Notify customer that order is on the way
            $this->createNotification(
                $order->customer_user_id,
                'order_out_for_delivery',
                'Your Order is On the Way!',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'message' => 'Your order is now out for delivery.'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Delivery started successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Delivery started successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error starting delivery: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to start delivery. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to start delivery. Please try again.');
        }
    }

    /**
     * Decline an assigned order
     * Triggers reassignment process to find another rider
     */
    public function declineOrder(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order not assigned to you.'], 403);
            }
            return redirect()->back()->with('error', 'Order not assigned to you.');
        }

        // Check if order is still pending acceptance
        if ($order->status !== 'awaiting_rider_assignment') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order is no longer available for decline.'], 400);
            }
            return redirect()->back()->with('error', 'Order is no longer available for decline.');
        }

        try {
            DB::beginTransaction();

            // Remove rider assignment but keep status as awaiting_rider_assignment
            $order->update(['rider_user_id' => null]);

            // Log the decline
            $this->logOrderStatusChange(
                $order->id,
                'awaiting_rider_assignment',
                'Rider declined assignment - reassigning to another rider',
                Auth::id()
            );

            // Trigger reassignment process (assign preferred rider if possible, otherwise random available rider)
            Log::info("Order {$order->id} declined by rider {$rider->id} - triggering reassignment");
            $this->reassignRiderAfterDecline($order, Auth::id());

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order declined. The system will assign it to another rider.',
                    'redirect_url' => route('rider.orders.index')
                ]);
            }

            return redirect()->route('rider.orders.index')->with('info', 'Order declined. The system will assign it to another rider.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error declining order: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to decline order. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to decline order. Please try again.');
        }
    }

    /**
     * Confirm pickup from vendor
     * Updates order status to pickup_confirmed and notifies customer
     */
    public function confirmPickup(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order and order is in assigned state
        if ($order->rider_user_id !== Auth::id() || $order->status !== 'assigned') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unable to confirm pickup for this order.'], 403);
            }
            return redirect()->back()->with('error', 'Unable to confirm pickup for this order.');
        }

        try {
            DB::beginTransaction();

            // Update order status to pickup_confirmed and log
            $order->update(['status' => 'pickup_confirmed']);

            $this->logOrderStatusChange(
                $order->id,
                'pickup_confirmed',
                'Items picked up from vendor - ready to start delivery',
                Auth::id()
            );

            // Also mark all order items that were ready_for_pickup as picked_up
            // This ensures vendors see the correct per-item pickup status
            $order->orderItems()
                ->where('status', 'ready_for_pickup')
                ->update(['status' => 'picked_up']);

            // Notify customer that items have been picked up
            $this->createNotification(
                $order->customer_user_id,
                'order_picked_up',
                'Order Items Picked Up',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'message' => 'Your items have been picked up. The rider will start delivery shortly.'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pickup confirmed successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Pickup confirmed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming pickup: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to confirm pickup. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to confirm pickup. Please try again.');
        }
    }

    /**
     * Mark order as delivered
     * Updates order status, rider stats, payment status for COD orders, and handles delivery proof image
     */
    public function markDelivered(Request $request, Order $order)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        // Verify this rider is assigned to this order
        if ($order->rider_user_id !== Auth::id() || $order->status !== 'out_for_delivery') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unable to mark this order as delivered.'], 403);
            }
            return redirect()->back()->with('error', 'Unable to mark this order as delivered.');
        }

        // Validate delivery proof image if provided
        $deliveryProofPath = null;
        if ($request->hasFile('delivery_proof_image')) {
            $request->validate([
                'delivery_proof_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ]);
        }

        try {
            DB::beginTransaction();

            // Handle delivery proof image upload
            if ($request->hasFile('delivery_proof_image')) {
                $image = $request->file('delivery_proof_image');
                $timestamp = now()->format('Y-m-d_H-i-s');
                $filename = "delivery_proof_order_{$order->id}_{$timestamp}." . $image->getClientOriginalExtension();
                
                // Store the image using Laravel's 'public' disk
                $deliveryProofPath = $image->storeAs('delivery_proofs', $filename, 'public');
                
                Log::info("Delivery proof image uploaded for order {$order->id}: {$deliveryProofPath}");
            }

            // Update order status to delivered and save delivery proof image path
            $updateData = ['status' => 'delivered'];
            if ($deliveryProofPath) {
                $updateData['delivery_proof_image'] = $deliveryProofPath;
            }
            $order->update($updateData);

            // If payment method is COD, mark payment as paid
            if ($order->payment_method === 'cod') {
                $order->update(['payment_status' => 'paid']);
            }

            // Update rider performance stats
            $rider->increment('total_deliveries');
            $rider->increment('daily_deliveries');

            // If no other active orders, set rider as available again
            $activeCount = Order::where('rider_user_id', Auth::id())
                ->whereIn('status', ['assigned', 'pickup_confirmed', 'out_for_delivery'])
                ->count();
            if ($activeCount === 0) {
                $rider->update(['is_available' => true]);
            }

            // Log delivery completion with proof image info
            $notes = 'Order successfully delivered to customer';
            if ($deliveryProofPath) {
                $notes .= ' with delivery proof image uploaded';
            }
            
            $this->logOrderStatusChange(
                $order->id,
                'delivered',
                $notes,
                Auth::id()
            );

            // Notify customer about successful delivery and prompt for rating
            $this->createNotification(
                $order->customer_user_id,
                'order_delivered',
                'Order Delivered Successfully!',
                [
                    'order_id' => $order->id,
                    'rider_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                    'message' => 'Your order has been delivered! Please rate your experience.'
                ],
                Order::class,
                $order->id
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order marked as delivered successfully with proof of delivery!',
                    'redirect_url' => route('rider.orders.index')
                ]);
            }

            return redirect()->route('rider.orders.index')->with('success', 'Order marked as delivered successfully with proof of delivery!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if there was an error
            if ($deliveryProofPath && Storage::disk('public')->exists($deliveryProofPath)) {
                Storage::disk('public')->delete($deliveryProofPath);
            }
            
            Log::error('Error marking order as delivered: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to mark order as delivered. Please try again.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to mark order as delivered. Please try again.');
        }
    }

    /**
     * Toggle rider availability status
     * Controls whether rider receives new order assignments
     */
    public function toggleAvailability(Request $request)
    {
        $rider = Auth::user()->rider;
        
        if (!$rider) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Rider profile not found.'], 403);
            }
            return redirect()->route('rider.dashboard')->with('error', 'Rider profile not found.');
        }

        try {
            $newStatus = !$rider->is_available;
            $rider->update(['is_available' => $newStatus]);

            $message = $newStatus ? 'You are now available for deliveries!' : 'You are no longer available for deliveries.';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_available' => $newStatus
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error toggling rider availability: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update availability status.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update availability status.');
        }
    }

    // ==================== HELPER METHODS ====================

    /**
     * Log order status changes for audit trail
     * Records who made the change and any relevant notes
     */
    private function logOrderStatusChange($orderId, $status, $notes = null, $userId = null)
    {
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'updated_by_user_id' => $userId,
            'created_at' => now(),
        ]);
    }

    /**
     * Create notification records for users
     * Follows the same pattern as existing notification system
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
}