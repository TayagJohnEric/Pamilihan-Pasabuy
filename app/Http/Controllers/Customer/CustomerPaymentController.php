<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\CustomerOrderFulfillmentController;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SavedAddress;
use App\Models\ShoppingCartItem;
use App\Models\User;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class CustomerPaymentController extends Controller
{
    /**
     * Display the order confirmation page for online payments
     * Order will be created without payment - payment happens after rider acceptance
     */
    public function paymentConfirmation(Request $request)
    {
        $user = Auth::user();
        
        Log::info('Payment confirmation accessed', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'has_order_summary' => session()->has('order_summary')
        ]);
        
        if ($request->isMethod('post')) {
            $orderSummary = $this->processCheckoutForm($request);
            session(['order_summary' => $orderSummary]);
        } else {
            $orderSummary = session('order_summary');
            
            if (!$orderSummary) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Order session expired. Please complete checkout again.');
            }
            
            if ($orderSummary['payment_method'] !== 'online_payment') {
                return redirect()->route('checkout.confirmation')
                    ->with('info', 'Redirected to order confirmation for COD.');
            }
        }
        
        // Validate cart items are still available
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty() || $cartItems->count() !== $orderSummary['item_count']) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart has changed. Please review and try again.');
        }
        
        return view('customer.checkout.payment-confirmation', compact('orderSummary'));
    }
    
    /**
     * Display GCash payment instructions and upload page
     * This is now shown AFTER rider has accepted the order
     */
    public function showGCashInstructions(Request $request, $orderId = null)
    {
        $user = Auth::user();
        
        // If order ID is provided, fetch order details (payment after rider acceptance)
        if ($orderId) {
            $order = Order::with(['rider', 'customer', 'deliveryAddress.district', 'orderItems'])
                ->where('id', $orderId)
                ->where('customer_user_id', $user->id)
                ->first();
            
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            // Verify order is in correct state for payment
            if ($order->payment_method !== 'online_payment') {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('error', 'This order does not require online payment.');
            }
            
            if ($order->payment_status !== 'pending') {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('info', 'Payment has already been submitted or processed.');
            }
            
            if ($order->status !== 'assigned') {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('error', 'Payment can only be made after rider accepts the order.');
            }
            
            // Build order summary from existing order
            $orderSummary = [
                'order_id' => $order->id,
                'total_amount' => $order->final_total_amount,
                'subtotal' => $order->subtotal_amount,
                'delivery_fee' => $order->delivery_fee,
                'item_count' => $order->orderItems->count(),
                'selected_rider' => $order->rider, 
                'rider_selection_type' => $order->rider_user_id ? 'assigned' : 'system_assign',
                'payment_method' => 'online_payment',
            ];
        } else {
            // Legacy flow: from checkout session (keeping for backward compatibility)
            $orderSummary = session('order_summary');
            
            if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
                return redirect()->route('checkout.index')
                    ->with('error', 'Invalid payment session. Please try again.');
            }
        }
        
        // Get rider's GCash details from the assigned rider
        $riderGCashDetails = null;
        
        if ($orderSummary['selected_rider']) {
            $rider = Rider::where('user_id', $orderSummary['selected_rider']->id)
                ->with('user')
                ->first();
            
            if ($rider && $rider->gcash_number) {
                $riderGCashDetails = [
                    'rider_name' => $orderSummary['selected_rider']->name,
                    'gcash_name' => $rider->gcash_name ?? $orderSummary['selected_rider']->name,
                    'gcash_number' => $rider->gcash_number,
                    'gcash_qr_path' => $rider->gcash_qr_path,
                ];
            }
        }
        
        // If no rider selected or no GCash details, get a default/system GCash account
        if (!$riderGCashDetails) {
            // Option 1: Get first available rider with GCash details
            $availableRider = Rider::whereNotNull('gcash_number')
                ->where('is_available', true)
                ->where('verification_status', 'verified')
                ->with('user')
                ->first();
            
            if ($availableRider) {
                $riderGCashDetails = [
                    'rider_name' => $availableRider->user->name,
                    'gcash_name' => $availableRider->gcash_name ?? $availableRider->user->name,
                    'gcash_number' => $availableRider->gcash_number,
                    'gcash_qr_path' => $availableRider->gcash_qr_path,
                ];
                
                // Update order summary with this rider
                $orderSummary['selected_rider'] = $availableRider->user;
                $orderSummary['rider_selection_type'] = 'system_assign';
                session(['order_summary' => $orderSummary]);
            } else {
                // No riders with GCash available
                return redirect()->route('checkout.index')
                    ->with('error', 'No payment recipient available. Please try again later or contact support.');
            }
        }
        
        return view('customer.checkout.gcash-payment-instructions', compact('orderSummary', 'riderGCashDetails'));
    }
    
    /**
     * Process GCash payment proof submission
     * Can handle both new orders (from checkout) and existing orders (after rider acceptance)
     */
    public function submitGCashProof(Request $request)
    {
        Log::info('GCash payment proof submission started', [
            'user_id' => Auth::id(),
            'has_file' => $request->hasFile('payment_proof'),
            'has_order_id' => $request->has('order_id'),
            'request_all' => $request->except('payment_proof')
        ]);
        
        $user = Auth::user();
        $orderId = $request->input('order_id');
        
        // Check if this is for an existing order (after rider acceptance)
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('customer_user_id', $user->id)
                ->first();
            
            if (!$order) {
                return redirect()->route('customer.orders.index')
                    ->with('error', 'Order not found.');
            }
            
            // Verify order state
            if ($order->payment_method !== 'online_payment' || $order->payment_status !== 'pending') {
                return redirect()->route('customer.orders.show', $order->id)
                    ->with('error', 'This order cannot accept payment at this time.');
            }
        } else {
            // Legacy flow: from checkout session
            $orderSummary = session('order_summary');
            
            Log::info('Order summary from session', [
                'has_summary' => !is_null($orderSummary),
                'payment_method' => $orderSummary['payment_method'] ?? 'N/A',
                'summary_keys' => $orderSummary ? array_keys($orderSummary) : []
            ]);
            
            if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
                Log::warning('Invalid payment session detected', [
                    'has_summary' => !is_null($orderSummary),
                    'payment_method' => $orderSummary['payment_method'] ?? 'N/A'
                ]);
                return redirect()->route('checkout.index')
                    ->with('error', 'Invalid payment session. Please try again.');
            }
        }
        
        // Validate request
        try {
            $validated = $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
                'customer_reference_code' => 'nullable|string|max:100',
                'special_instructions' => 'nullable|string|max:500',
            ], [
                'payment_proof.required' => 'Please upload a screenshot of your payment.',
                'payment_proof.image' => 'Payment proof must be an image file.',
                'payment_proof.mimes' => 'Payment proof must be a JPEG, PNG, or JPG file.',
                'payment_proof.max' => 'Payment proof must not exceed 5MB.',
            ]);
            
            Log::info('Validation passed', ['validated' => array_keys($validated)]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            throw $e;
        }
        
        DB::beginTransaction();
        
        try {
            // If no existing order, create one (legacy flow)
            if (!isset($order)) {
                $orderSummary = session('order_summary');
                
                // Create order with pending_payment status
                $order = $this->createOrderFromSession(
                    $orderSummary, 
                    'online_payment', 
                    $request->input('special_instructions')
                );
                
                Log::info('Order created successfully', ['order_id' => $order->id]);
                
                // Create order items
                $this->createOrderItems($order, $orderSummary['cart_items']);
                
                Log::info('Order items created', ['order_id' => $order->id, 'item_count' => $orderSummary['cart_items']->count()]);
            }
            
            // Upload payment proof
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'payment_proof_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $paymentProofPath = $file->storeAs('payment_proofs', $filename, 'public');
                
                Log::info('Payment proof uploaded', ['path' => $paymentProofPath]);
            }
            
            // Create or update payment record with proof
            $payment = Payment::where('order_id', $order->id)->first();
            
            if ($payment) {
                // Update existing payment record
                $payment->update([
                    'payment_proof_url' => $paymentProofPath,
                    'customer_reference_code' => $validated['customer_reference_code'],
                    'admin_verification_status' => 'pending_review',
                ]);
            } else {
                // Create new payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount_paid' => $order->final_total_amount,
                    'payment_method_used' => 'online_payment',
                    'status' => 'pending',
                    'payment_proof_url' => $paymentProofPath,
                    'customer_reference_code' => $validated['customer_reference_code'],
                    'admin_verification_status' => 'pending_review',
                ]);
            }
            
            Log::info('Payment record created', ['payment_id' => $payment->id, 'status' => $payment->admin_verification_status]);
            
            // Log order status
            $this->logOrderStatusChange(
                $order->id, 
                'pending_payment', 
                'Payment proof submitted, awaiting rider verification', 
                Auth::id()
            );
            
            DB::commit();
            
            // Clear order summary from session
            session()->forget('order_summary');
            
            // Send notifications
            $this->notifyCustomerPaymentSubmitted($order);
            $this->notifyRiderPaymentReview($order, $payment); // Changed: Notify rider instead of admin
            $this->notifyAdminPaymentSubmitted($order, $payment); // Admin gets FYI notification only
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Payment proof submitted successfully! Your payment is being verified by the rider.');
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('GCash payment proof submission failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit payment proof. Please try again.');
        }
    }
    
    /**
     * Confirm and place order for online payment without immediate payment
     * Payment will be collected after rider accepts
     */
    public function confirmOnlinePaymentOrder(Request $request)
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'online_payment') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid order session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create order with processing status (no payment yet)
            $order = $this->createOrderFromSession($orderSummary, 'online_payment', $request->input('special_instructions'));
            
            // Create order items
            $this->createOrderItems($order, $orderSummary['cart_items']);
            
            // Create payment record placeholder
            $this->createPaymentRecord($order, $orderSummary['total_amount'], 'online_payment');
            
            DB::commit();
            
            // Trigger order finalization (handles stock, cart clearing, notifications)
            $fulfillmentController = new CustomerOrderFulfillmentController();
            $fulfillmentController->finalizeOrder($order);
            
            session()->forget('order_summary');
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! You will be notified when a rider accepts your order. Payment will be required after rider confirmation.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Online payment order creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Order placement failed. Please try again.');
        }
    }
    
    /**
     * Process Cash on Delivery (COD) order
     */
    public function processCOD(Request $request)
    {
        $orderSummary = session('order_summary');
        
        if (!$orderSummary || $orderSummary['payment_method'] !== 'cod') {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid order session. Please try again.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create order with processing status
            $order = $this->createOrderFromSession($orderSummary, 'cod', $request->input('special_instructions'));
            
            // Create order items
            $this->createOrderItems($order, $orderSummary['cart_items']);
            
            // Create payment record
            $this->createPaymentRecord($order, $orderSummary['total_amount'], 'cod');
            
            DB::commit();
            
            // Trigger order finalization (handles stock, cart clearing, notifications)
            $fulfillmentController = new CustomerOrderFulfillmentController();
            $fulfillmentController->finalizeOrder($order);
            
            session()->forget('order_summary');
            
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully! Your order will be prepared for delivery.');
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('COD order creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return redirect()->back()
                ->with('error', 'Order placement failed. Please try again.');
        }
    }
    
    // ==================== PRIVATE HELPER METHODS ====================
    
    /**
     * Process checkout form data into order summary
     */
    private function processCheckoutForm(Request $request): array
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'delivery_address_id' => 'required|exists:saved_addresses,id',
            'payment_method' => 'required|in:online_payment',
            'rider_selection_type' => 'required|in:choose_rider,system_assign',
            'selected_rider_id' => 'required_if:rider_selection_type,choose_rider|nullable|exists:users,id'
        ]);
        
        // Get cart items and delivery address
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty()) {
            throw new Exception('Cart is empty');
        }
        
        $deliveryAddress = SavedAddress::with('district')
            ->where('id', $validated['delivery_address_id'])
            ->where('user_id', $user->id)
            ->first();
        
        // Process rider selection
        $selectedRider = null;
        if ($validated['rider_selection_type'] === 'choose_rider') {
            $selectedRider = User::find($validated['selected_rider_id']);
        }
        
        // Calculate totals
        $subtotal = $cartItems->sum('subtotal');
        $deliveryFee = $deliveryAddress->district->delivery_fee;
        $totalAmount = $subtotal + $deliveryFee;
        
        return [
            'cart_items' => $cartItems,
            'delivery_address' => $deliveryAddress,
            'selected_rider' => $selectedRider,
            'payment_method' => 'online_payment',
            'rider_selection_type' => $validated['rider_selection_type'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'item_count' => $cartItems->count(),
            'has_budget_items' => $cartItems->where('customer_budget', '!=', null)->count() > 0
        ];
    }
    
    /**
     * Create order record from session data
     */
    private function createOrderFromSession(array $orderSummary, string $paymentMethod, ?string $specialInstructions = null): Order
    {
        $user = Auth::user();
        
        // For online payment orders, rider assignment happens later when items are ready_for_pickup
        // For COD orders, we can set rider preference but actual assignment still happens when items are ready
        // This ensures rider notification is only sent after vendor prepares items
        
        return Order::create([
            'customer_user_id' => $user->id,
            // Rider will be assigned later when vendor marks items as ready_for_pickup
            // This ensures rider notification only happens after items are prepared
            'rider_user_id' => null,
            // Store customer's rider preference (if any) to use during assignment
            'preferred_rider_id' => $orderSummary['rider_selection_type'] === 'choose_rider' 
                ? $orderSummary['selected_rider']->id : null,
            'delivery_address_id' => $orderSummary['delivery_address']->id,
            'order_date' => now(),
            'status' => $paymentMethod === 'cod' ? 'processing' : 'pending_payment',
            'delivery_fee' => $orderSummary['delivery_fee'],
            'final_total_amount' => $orderSummary['total_amount'],
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'special_instructions' => $specialInstructions,
        ]);
    }
    
    /**
     * Create order items from cart items
     */
    private function createOrderItems(Order $order, $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'status' => 'pending',
                'product_name_snapshot' => $cartItem->product->product_name,
                'quantity_requested' => $cartItem->quantity ?? 1,
                'unit_price_snapshot' => $cartItem->product->price,
                'customer_budget_requested' => $cartItem->customer_budget,
                'customerNotes_snapshot' => $cartItem->customer_notes,
            ]);
        }
    }
    
    /**
     * Create payment record
     */
    private function createPaymentRecord(Order $order, float $amount, string $paymentMethod): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'amount_paid' => $amount,
            'payment_method_used' => $paymentMethod,
            'status' => 'pending',
        ]);
    }
    
    /**
     * Log order status change
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
        } catch (\Exception $e) {
            Log::error("Failed to log order status change: " . $e->getMessage());
        }
    }
    
    /**
     * Notify customer that payment proof was submitted
     */
    private function notifyCustomerPaymentSubmitted(Order $order)
    {
        $fulfillmentController = new CustomerOrderFulfillmentController();
        $fulfillmentController->createNotification(
            $order->customer_user_id,
            'payment_submitted',
            'Payment Proof Submitted',
            [
                'order_id' => $order->id,
                'message' => 'Your payment proof has been submitted and is awaiting verification by our admin team.'
            ],
            Order::class,
            $order->id
        );
    }
    
    /**
     * Notify rider to verify payment (PRIMARY NOTIFICATION)
     */
    private function notifyRiderPaymentReview(Order $order, Payment $payment)
    {
        if (!$order->rider_user_id) {
            Log::warning('Cannot notify rider - no rider assigned to order', ['order_id' => $order->id]);
            return;
        }
        
        $fulfillmentController = new CustomerOrderFulfillmentController();
        
        $fulfillmentController->createNotification(
            $order->rider_user_id,
            'payment_verification_required',
            'Payment Proof Submitted - Please Verify',
            [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'customer_name' => $order->customer->name,
                'amount' => '₱' . number_format($order->final_total_amount, 2),
                'message' => 'Customer has submitted payment proof. Please check your GCash and verify receipt.'
            ],
            Payment::class,
            $payment->id
        );
    }
    
    /**
     * Notify admin of payment submission (FYI ONLY - for oversight)
     */
    private function notifyAdminPaymentSubmitted(Order $order, Payment $payment)
    {
        // Get all admin users
        $adminUsers = User::where('role', 'admin')->where('is_active', true)->get();
        
        $fulfillmentController = new CustomerOrderFulfillmentController();
        
        foreach ($adminUsers as $admin) {
            $fulfillmentController->createNotification(
                $admin->id,
                'payment_submitted_info',
                'Payment Proof Submitted (Rider Verification)',
                [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'customer_name' => $order->customer->name,
                    'rider_name' => $order->rider->name ?? 'N/A',
                    'amount' => '₱' . number_format($order->final_total_amount, 2),
                    'message' => 'Payment proof submitted. Rider will verify. (FYI only - no action needed unless disputed)'
                ],
                Payment::class,
                $payment->id
            );
        }
    }
}