<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(Request $request)
    {
        // Get the authenticated customer
        $customer = Auth::user();
        
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(403, 'Unauthorized access.');
        }

        // Build the query for customer's orders
        $query = Order::where('customer_user_id', $customer->id)
            ->with([
                'orderItems.product',
                'orderItems.substitutedProduct',
                'deliveryAddress',
                'rider',
                'payment'
            ])
            ->orderBy('order_date', 'desc');

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date range filter if provided
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        // Paginate the results
        $orders = $query->paginate(10)->appends($request->query());

        // Get order status counts for filter badges
        $statusCounts = Order::where('customer_user_id', $customer->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('customer.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated customer
        if ($order->customer_user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        // Load relationships
        $order->load([
            'orderItems.product.vendor',
            'orderItems.substitutedProduct',
            'deliveryAddress',
            'rider',
            'payment',
            'statusHistory.updatedBy',
            'ratings'
        ]);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Allow customer to confirm delivery after reviewing proof.
     */
    public function confirmDelivery(Request $request, Order $order)
    {
        if ($order->customer_user_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this order.');
        }

        if ($order->status !== 'pending_customer_confirmation') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order is not awaiting confirmation.');
        }

        try {
            DB::beginTransaction();

            $order->update(['status' => 'delivered']);

            $this->logOrderStatusChange(
                $order->id,
                'delivered',
                'Customer confirmed delivery completion.',
                Auth::id()
            );

            if ($order->rider_user_id) {
                $this->createNotification(
                    $order->rider_user_id,
                    'delivery_confirmed',
                    'Delivery Confirmed by Customer',
                    [
                        'order_id' => $order->id,
                        'customer_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                        'message' => 'The customer confirmed delivery for order #' . $order->id . '.'
                    ],
                    Order::class,
                    $order->id
                );
            }

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Delivery confirmed! You can now rate this order.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming delivery: ' . $e->getMessage());

            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Failed to confirm delivery. Please try again.');
        }
    }

    /**
     * Record order status transitions for audit trail.
     */
    private function logOrderStatusChange($orderId, $status, $notes = null, $updatedByUserId = null)
    {
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'updated_by_user_id' => $updatedByUserId,
            'created_at' => now(),
        ]);
    }

    /**
     * Helper to create notifications following existing pattern.
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
