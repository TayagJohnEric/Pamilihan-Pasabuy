<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShoppingCartItem;
use App\Models\SavedAddress;
use App\Models\District;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerCheckoutController extends Controller
{
    /**
     * Display the checkout page with all necessary information
     * 
     * This method handles the unified checkout step where customers make all
     * their final decisions before placing an order including:
     * - Reviewing cart items
     * - Selecting delivery address
     * - Choosing payment method
     * - Selecting rider preference
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get cart items with product and vendor relationships
        $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
            ->where('user_id', $user->id)
            ->get();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty. Add some items before checkout.');
        }
        
        // Validate cart items (check product availability and stock)
        $invalidItems = [];
        foreach ($cartItems as $item) {
            if (!$item->is_valid) {
                $invalidItems[] = $item->product->product_name;
            }
        }
        
        if (!empty($invalidItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'Some items in your cart are no longer available: ' . implode(', ', $invalidItems));
        }
        
        // Get user's saved addresses with district information
        $savedAddresses = SavedAddress::with('district')
            ->where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get available districts if user has no saved addresses
        $availableDistricts = District::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get available riders for "Choose My Rider" option
        $riderSearchTerm = $request->query('rider_search');

        $availableRidersQuery = User::with('rider')
            ->whereHas('rider', function ($query) {
                $query->where('is_available', true)
                    ->where('verification_status', 'verified');
            })
            ->where('role', 'rider')
            ->where('is_active', true)
            ->select(['id', 'first_name', 'last_name', 'profile_image_url']);

        if ($riderSearchTerm) {
            $availableRidersQuery->where(function ($query) use ($riderSearchTerm) {
                $likeTerm = '%' . $riderSearchTerm . '%';
                $query->where('first_name', 'like', $likeTerm)
                    ->orWhere('last_name', 'like', $likeTerm)
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', $likeTerm);
            });
        }

        $availableRiders = $availableRidersQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        
        // Calculate cart summary
        $subtotal = $cartItems->sum('subtotal');
        $itemCount = $cartItems->count();
        $hasBudgetItems = $cartItems->where('customer_budget', '!=', null)->count() > 0;
        
        // Payment methods available
        $paymentMethods = [
            'cod' => 'Cash on Delivery',
            'online_payment' => 'Online Payment'
        ];
        
        return view('customer.checkout.index', compact(
            'cartItems',
            'savedAddresses', 
            'availableDistricts',
            'availableRiders',
            'subtotal',
            'itemCount',
            'hasBudgetItems',
            'paymentMethods',
            'riderSearchTerm'
        ));
    }
    
    /**
     * Get delivery fee for a specific address via AJAX
     * 
     * This method is called when user selects a delivery address
     * to instantly display the delivery fee from the district
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeliveryFee(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:saved_addresses,id'
        ]);
        
        $address = SavedAddress::with('district')
            ->where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'delivery_fee' => $address->district->delivery_fee,
            'district_name' => $address->district->name,
            'formatted_fee' => '₱' . number_format($address->district->delivery_fee, 2)
        ]);
    }
    

    /**
     * Process the checkout form and prepare order placement
     * 
     * This method handles all the customer's final decisions:
     * - Validates the form data
     * - Prepares order data for final confirmation
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
public function process(Request $request)
{
    $user = Auth::user();

    // Validate the checkout form
    $validated = $request->validate([
        'delivery_address_id' => [
            'required',
            'exists:saved_addresses,id',
            function ($attribute, $value, $fail) use ($user) {
                $address = SavedAddress::where('id', $value)
                    ->where('user_id', $user->id)
                    ->first();
                if (!$address) {
                    $fail('Invalid delivery address selected.');
                }
            }
        ],
        'payment_method' => [
            'required',
            Rule::in(['cod', 'online_payment'])
        ],
        'rider_selection_type' => [
            'required',
            Rule::in(['choose_rider', 'system_assign'])
        ],
        'selected_rider_id' => [
            'required_if:rider_selection_type,choose_rider',
            'nullable',
            'exists:users,id',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->rider_selection_type === 'choose_rider' && $value) {
                    $rider = User::with('rider')
                        ->where('id', $value)
                        ->where('role', 'rider')
                        ->where('is_active', true)
                        ->whereHas('rider', function ($query) {
                            $query->where('is_available', true)
                                ->where('verification_status', 'verified');
                        })
                        ->first();

                    if (!$rider) {
                        $fail('Selected rider is not available.');
                    }
                }
            }
        ]
    ]);

    // Re-validate cart items
    $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
        ->where('user_id', $user->id)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')
            ->with('error', 'Your cart is empty.');
    }

    // Final validation of cart items
    foreach ($cartItems as $item) {
        if (!$item->is_valid) {
            return redirect()->route('cart.index')
                ->with('error', 'Some items in your cart are no longer available. Please review your cart.');
        }
    }

    // Get delivery address with district
    $deliveryAddress = SavedAddress::with('district')
        ->where('id', $validated['delivery_address_id'])
        ->where('user_id', $user->id)
        ->first();

    // Rider selection logic
    $selectedRider = null;
    if ($validated['rider_selection_type'] === 'choose_rider') {
        $selectedRider = User::with('rider')
            ->where('id', $validated['selected_rider_id'])
            ->first();
    } elseif ($validated['rider_selection_type'] === 'system_assign') {
        $selectedRider = null;
    } else {
        return redirect()->back()
            ->with('error', 'No riders are currently available. Please try again later.')
            ->withInput();
    }

    // Calculate totals
    $subtotal = $cartItems->sum('subtotal');
    $deliveryFee = $deliveryAddress->district->delivery_fee;
    $totalAmount = $subtotal + $deliveryFee;

    // Prepare order summary data
    $orderSummary = [
        'cart_items' => $cartItems,
        'delivery_address' => $deliveryAddress,
        'selected_rider' => $selectedRider,
        'payment_method' => $validated['payment_method'],
        'rider_selection_type' => $validated['rider_selection_type'],
        'subtotal' => $subtotal,
        'delivery_fee' => $deliveryFee,
        'total_amount' => $totalAmount,
        'item_count' => $cartItems->count(),
        'has_budget_items' => $cartItems->where('customer_budget', '!=', null)->count() > 0
    ];

    // Store order summary in session for final confirmation
    session(['order_summary' => $orderSummary]);

    // Redirect based on payment method
    if ($validated['payment_method'] === 'online_payment') {
        return redirect()->route('checkout.payment-confirmation')
            ->with('success', 'Please review your order and proceed to payment.');
    } else {
        return redirect()->route('checkout.confirmation')
            ->with('success', 'Please review your order before final confirmation.');
    }
}





    /**
 * Display final confirmation page for COD orders
 * 
 * This method shows the final confirmation page before placing a COD order.
 * It validates the order summary from session and prepares COD-specific data.
 * 
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function confirmation()
{
    // Get order summary from session (set by checkout process)
    $orderSummary = session('order_summary');
    
    if (!$orderSummary) {
        return redirect()->route('checkout.index')
            ->with('error', 'Order session expired. Please complete checkout again.');
    }
    
            // Validate that payment method is COD
        if ($orderSummary['payment_method'] !== 'cod') {
        return redirect()->route('checkout.payment-confirmation')
            ->with('info', 'Redirected to payment confirmation for online payment.');
    }
    
    // Re-validate cart items to ensure they're still available
    $cartItems = ShoppingCartItem::with(['product.vendor', 'product'])
        ->where('user_id', Auth::id())
        ->get();
    
    if ($cartItems->isEmpty() || $cartItems->count() !== $orderSummary['item_count']) {
        return redirect()->route('cart.index')
            ->with('error', 'Your cart has changed. Please review and try again.');
    }
    
    return view('customer.checkout.order-confirmation', compact('orderSummary'));
}
}
