<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;  //Built In Notification



// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('landing.page');




// Notification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    Route::patch('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('notifications.unread-count');
});



//-------------------------------------------------Customer Route-------------------------------------------------// 

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerPasswordController;
use App\Http\Controllers\Customer\CustomerSavedAddressController;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CustomerShoppingCartController;
use App\Http\Controllers\Customer\CustomerCheckoutController;
use App\Http\Controllers\Customer\CustomerPaymentController;
use App\Http\Controllers\Customer\CustomerOrderFulfillmentController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\CustomerRatingController;
use App\Http\Controllers\Customer\CustomerMeritSystemController;






//Customer Authentication Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);

    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});

//Customer Logout (requires auth)
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('customer.logout');

//Customer Protected Routes (requires auth & customer role)
Route::middleware(['auth', 'role:customer'])->name('customer.')->group(function () {

    // Dashboard
    Route::get('/home', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');

    // Password Management
    Route::get('/profile/password/edit', [CustomerPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/profile/password', [CustomerPasswordController::class, 'update'])->name('password.update');

     //Saved Addresses
    Route::prefix('saved_addresses')->name('saved_addresses.')->group(function () {
        Route::get('/', [CustomerSavedAddressController::class, 'index'])->name('index');
        Route::get('/create', [CustomerSavedAddressController::class, 'create'])->name('create');
        Route::post('/', [CustomerSavedAddressController::class, 'store'])->name('store');
        Route::get('/{saved_address}/edit', [CustomerSavedAddressController::class, 'edit'])->name('edit');
        Route::put('/{saved_address}', [CustomerSavedAddressController::class, 'update'])->name('update');
        Route::delete('/{saved_address}', [CustomerSavedAddressController::class, 'destroy'])->name('destroy');
    });
});

// Product browsing routes
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [CustomerProductController::class, 'search'])->name('products.search');
Route::get('/products/{id}', [CustomerProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{categoryId}', [CustomerProductController::class, 'category'])->name('products.category');
Route::get('/products/vendor/{vendorId}', [CustomerProductController::class, 'vendor'])->name('products.vendor');


// Shopping Cart Routes (for authenticated customers)
Route::middleware(['auth'])->group(function () {
    // Cart display and management
    Route::get('/cart', [CustomerShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CustomerShoppingCartController::class, 'store'])->name('cart.store');

Route::put('/cart/{cartItem}', [CustomerShoppingCartController::class, 'update'])
    ->name('cart.update')
    ->where('cartItem', '[0-9]+'); // Ensure only numeric IDs

Route::delete('/cart/{cartItem}', [CustomerShoppingCartController::class, 'destroy'])
    ->name('cart.destroy')
    ->where('cartItem', '[0-9]+');
    
    Route::delete('/cart', [CustomerShoppingCartController::class, 'clear'])->name('cart.clear');
    
    // AJAX route for cart count
    Route::get('/cart/count', [CustomerShoppingCartController::class, 'getCartCount'])->name('cart.count');
});

//Checkout Route
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CustomerCheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/delivery-fee', [CustomerCheckoutController::class, 'getDeliveryFee'])->name('checkout.delivery-fee');
    Route::post('/checkout/process', [CustomerCheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/confirmation', [CustomerCheckoutController::class, 'confirmation'])->name('checkout.confirmation'); 
    Route::get('/checkout/payment-confirmation', [CustomerPaymentController::class, 'paymentConfirmation'])->name('checkout.payment-confirmation');
});

// Payment Routes - Authenticated users only
Route::middleware(['auth'])->group(function () {
    // Payment confirmation page (handles both GET and POST)
    Route::match(['GET', 'POST'], '/payment/confirmation', [CustomerPaymentController::class, 'paymentConfirmation'])
        ->name('payment.confirmation');

    // Payment processing routes
    Route::post('/payment/process-cod', [CustomerPaymentController::class, 'processCOD'])
        ->name('payment.process-cod');
    
    // Confirm online payment order (without immediate payment)
    Route::post('/payment/confirm-online-order', [CustomerPaymentController::class, 'confirmOnlinePaymentOrder'])
        ->name('payment.confirm-online-order');
});

// Manual GCash Payment Routes
Route::middleware(['auth'])->group(function () {
    // GCash instructions - can be accessed during checkout or after rider acceptance
    Route::get('/payment/gcash-instructions/{orderId?}', [CustomerPaymentController::class, 'showGCashInstructions'])
        ->name('payment.gcash-instructions');
    
    Route::post('/payment/gcash-submit-proof', [CustomerPaymentController::class, 'submitGCashProof'])
        ->name('payment.gcash-submit-proof');
});

// Customer Order Routes
        Route::get('/orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders.index')
        ->middleware('auth');

        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
        ->name('customer.orders.show')
        ->middleware('auth');


// Order Fulfillment Routes
Route::middleware(['auth'])->group(function () {
    // Real-time order status updates (AJAX)
    Route::get('/orders/{order}/status-update', [CustomerOrderFulfillmentController::class, 'getOrderStatusUpdate'])
        ->name('orders.status.update')
        ->middleware('can:view,order');

    // Vendor Routes - Mark items as ready for pickup
    Route::patch('/orders/items/ready', [CustomerOrderFulfillmentController::class, 'handleVendorItemReady'])
        ->name('orders.items.ready')
        ->middleware('role:vendor');
});

// Webhook Routes (No authentication - external services)
Route::post('/webhooks/paymongo/payment-verified', [CustomerOrderFulfillmentController::class, 'handlePaymentWebhook']);



Route::prefix('customer')->middleware(['auth'])->name('customer.')->group(function () {
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
        Route::get('{order}', [CustomerOrderController::class, 'show'])->name('show');
        Route::get('{order}/rate', [CustomerRatingController::class, 'showRatingForm'])->name('rate');
        Route::post('{order}/rate', [CustomerRatingController::class, 'submitRating'])->name('rate.submit');
        Route::patch('{order}/confirm-delivery', [CustomerOrderController::class, 'confirmDelivery'])->name('confirm-delivery');
    });
});

// Merit System Routes - Add these to your existing routes/web.php file
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::prefix('merit-system')->name('merit-system.')->group(function () {
        // Display all riders ranked by merit score
        Route::get('/', [CustomerMeritSystemController::class, 'index'])->name('index');
        
        // Display specific rider profile with detailed information
        Route::get('/rider/{riderId}', [CustomerMeritSystemController::class, 'showRiderProfile'])
             ->name('rider.profile')
             ->where('riderId', '[0-9]+'); // Ensure riderId is numeric
        
        // AJAX route for refreshing rider rankings without page reload
        Route::post('/refresh-rankings', [CustomerMeritSystemController::class, 'refreshRankings'])
             ->name('refresh.rankings');
    });
});


    


//-------------------------------------------------Vendor Route-------------------------------------------------// 

use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorEarningController;
use App\Http\Controllers\Vendor\VendorRatingController;
use App\Http\Controllers\Vendor\VendorOrderController;


//Vendor Registration & Login Routes
Route::middleware('guest')->group(function () {
    // Vendor Application (Registration)
    Route::get('/vendor/application', [VendorAuthController::class, 'create'])->name('vendor-applications.create');
    Route::post('/vendor/application', [VendorAuthController::class, 'store'])->name('vendor-applications.store');

    // Vendor Login
   Route::get('/vendor/login', [VendorAuthController::class, 'showLoginForm'])->name('vendor.login.form');
Route::post('/vendor/login', [VendorAuthController::class, 'login'])->name('vendor.login');
});

// Vendor Dashboard Routes
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard route
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])
        ->name('dashboard');
    
    // AJAX route for toggling order acceptance
    Route::post('/toggle-order-acceptance', [VendorDashboardController::class, 'toggleOrderAcceptance'])
        ->name('toggle-order-acceptance');      
    
});

//Vendor Protected Routes (requires auth & vendor role)
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {


    // Logout
    Route::post('logout', [VendorAuthController::class, 'logout'])->name('logout');

    //Profile Management
    Route::get('profile', [VendorProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [VendorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [VendorProfileController::class, 'update'])->name('profile.update');

    // Shop Ratings (optional grouping if you want to split later)
    Route::get('shop/ratings', [VendorProfileController::class, 'ratings'])->name('profile.ratings');

    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [VendorProductController::class, 'index'])->name('index');
        Route::get('/create', [VendorProductController::class, 'create'])->name('create');
        Route::post('/', [VendorProductController::class, 'store'])->name('store');
        Route::get('/{product}', [VendorProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [VendorProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [VendorProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [VendorProductController::class, 'destroy'])->name('destroy');

        // Toggle product availability
        Route::patch('/{product}/toggle-availability', [VendorProductController::class, 'toggleAvailability'])->name('toggle-availability');
    });

    // Earnings and Payouts
    Route::prefix('earnings')->name('earnings.')->group(function () {
        Route::get('/', [VendorEarningController::class, 'index'])->name('index');
        Route::get('/payout/{id}', [VendorEarningController::class, 'showPayout'])->name('payout.details');
    });

   // Ratings
    Route::get('ratings', [VendorRatingController::class, 'index'])->name('ratings.index');
});

//Vendor Order Management Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    // Order Management Routes
    Route::prefix('orders')->name('orders.')->group(function () {
        // Display all orders containing vendor's items (excluding delivered)
        Route::get('/', [VendorOrderController::class, 'index'])
            ->name('index');
        
        // Display delivered orders containing vendor's items
        Route::get('/delivered', [VendorOrderController::class, 'delivered'])
            ->name('delivered');
        
        // Show delivered order details (read-only)
        Route::get('/delivered/{order}', [VendorOrderController::class, 'deliveredShow'])
            ->name('delivered.show');
        
        // Show detailed view of specific order
        Route::get('/{order}', [VendorOrderController::class, 'show'])
            ->name('show');
        
        // Update individual order item (AJAX endpoint) - Fixed route structure
        Route::patch('/items/{orderItem}', [VendorOrderController::class, 'updateOrderItem'])
            ->name('items.update');
        
        // Bulk mark items as ready (AJAX endpoint) - Fixed route name
        Route::post('/items/bulk-ready', [VendorOrderController::class, 'bulkMarkReady'])
            ->name('items.bulk_ready');
    }); 
});




//-------------------------------------------------Rider Route-------------------------------------------------// 


use App\Http\Controllers\Auth\RiderAuthController;
use App\Http\Controllers\Rider\RiderDashboardController;
use App\Http\Controllers\Rider\RiderProfileController;
use App\Http\Controllers\Rider\RiderEarningsController;
use App\Http\Controllers\Rider\RiderRatingController;
use App\Http\Controllers\Rider\RiderOrderController;

// Rider Registration & Login Routes
Route::middleware('guest')->group(function () {
    // Rider Application (Registration)
    Route::get('/rider/application', [RiderAuthController::class, 'create'])->name('rider-applications.create');
    Route::post('/rider/application', [RiderAuthController::class, 'store'])->name('rider-applications.store');

    // Rider Login
    Route::get('/rider/login', [RiderAuthController::class, 'showLoginForm'])->name('rider.login');
    Route::post('/rider/login', [RiderAuthController::class, 'login']);
});

// Rider Logout (requires auth)
Route::post('/rider/logout', [RiderAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('rider.logout');

// Rider Selfie Verification Routes
Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/selfie-verification', [RiderAuthController::class, 'showSelfieVerificationForm'])
        ->name('selfie-verification.show');
    Route::post('/selfie-verification', [RiderAuthController::class, 'uploadSelfieVerification'])
        ->name('selfie-verification.upload');
});

    // Rider Dashboard Routes
Route::prefix('rider')->name('rider.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard route
    Route::get('/dashboard', [App\Http\Controllers\Rider\RiderDashboardController::class, 'index'])
        ->name('dashboard');
    
    // AJAX route for toggling availability
    Route::post('/toggle-availability', [App\Http\Controllers\Rider\RiderDashboardController::class, 'toggleAvailability'])
        ->name('toggle-availability');
        
});

// Rider Protected Routes (requires auth & rider role)
Route::middleware(['auth'])->prefix('rider')->name('rider.')->group(function () {

    // Rider Earnings, Payouts & Ratings
    Route::get('earnings', [RiderEarningsController::class, 'earnings'])->name('earnings');
    Route::get('payouts', [RiderEarningsController::class, 'payouts'])->name('payouts');
    Route::get('ratings', [RiderRatingController::class, 'ratings'])->name('ratings');
});


// Rider Order Management Routes
Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {
    
    // Order management routes
    Route::prefix('orders')->name('orders.')->group(function () {
        // Display rider dashboard with pending assignments and active deliveries
        Route::get('/', [App\Http\Controllers\Rider\RiderOrderController::class, 'index'])
            ->name('index');
        
        // Show detailed view of specific order
        Route::get('/{order}', [App\Http\Controllers\Rider\RiderOrderController::class, 'show'])
            ->name('show');
        
        // Accept an assigned order (AJAX + regular)
        Route::patch('/{order}/accept', [App\Http\Controllers\Rider\RiderOrderController::class, 'acceptOrder'])
            ->name('accept');
        
        // Decline an assigned order (AJAX + regular)
        Route::patch('/{order}/decline', [App\Http\Controllers\Rider\RiderOrderController::class, 'declineOrder'])
            ->name('decline');
        
        // Confirm pickup from vendor (AJAX + regular)
        Route::patch('/{order}/pickup', [App\Http\Controllers\Rider\RiderOrderController::class, 'confirmPickup'])
            ->name('pickup');
        
        // Start delivery (AJAX + regular)
        Route::patch('/{order}/start-delivery', [App\Http\Controllers\Rider\RiderOrderController::class, 'startDelivery'])
            ->name('start-delivery');
        
        // Mark order as delivered (AJAX + regular)
        Route::patch('/{order}/delivered', [App\Http\Controllers\Rider\RiderOrderController::class, 'markDelivered'])
            ->name('delivered');
    });
    
    // Rider availability toggle (AJAX + regular)
    Route::patch('/availability/toggle', [App\Http\Controllers\Rider\RiderOrderController::class, 'toggleAvailability'])
        ->name('availability.toggle');
    
    // Payment Verification routes
    Route::prefix('payments')->name('payments.')->group(function () {
        // Display list of payments pending verification
        Route::get('/', [App\Http\Controllers\Rider\RiderPaymentController::class, 'index'])
            ->name('index');
        
        // Show specific payment for verification
        Route::get('/{payment}', [App\Http\Controllers\Rider\RiderPaymentController::class, 'show'])
            ->name('show');
        
        // Verify payment as received
        Route::post('/{payment}/verify', [App\Http\Controllers\Rider\RiderPaymentController::class, 'verify'])
            ->name('verify');
        
        // Reject payment verification
        Route::post('/{payment}/reject', [App\Http\Controllers\Rider\RiderPaymentController::class, 'reject'])
            ->name('reject');
    });
});


// Add these routes to your web.php file
Route::middleware(['auth'])->prefix('rider')->name('rider.')->group(function () {
    // Rider Profile Routes
    Route::get('/profile', [RiderProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [RiderProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [RiderProfileController::class, 'update'])->name('profile.update');
});



//-------------------------------------------------Admin Route-------------------------------------------------// 

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDistrictController;
use App\Http\Controllers\Admin\AdminRiderApplicationController;
use App\Http\Controllers\Admin\AdminVendorApplicationController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminPayoutController;
use App\Http\Controllers\Admin\AdminSystemSettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminPaymentVerificationController;








//Admin Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

    // Admin Dashboard Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Main Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // AJAX endpoints for dashboard data
    Route::get('/dashboard/data', [AdminDashboardController::class, 'getDashboardData'])->name('dashboard.data');
    
    // Export functionality
    Route::get('/dashboard/export', [AdminDashboardController::class, 'exportData'])->name('dashboard.export');
    
});

//Admin Protected Routes (Requires auth & admin role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    //User Management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    //Rider Applications
    Route::prefix('rider-applications')->name('rider_applications.')->group(function () {
        Route::get('/', [AdminRiderApplicationController::class, 'index'])->name('index');
        Route::get('{rider_application}', [AdminRiderApplicationController::class, 'show'])->name('show');
        Route::delete('{rider_application}', [AdminRiderApplicationController::class, 'destroy'])->name('destroy');
        Route::get('{id}/create-rider', [AdminRiderApplicationController::class, 'createRiderFromApplication'])->name('createRider');
        Route::post('{id}/store-rider', [AdminRiderApplicationController::class, 'storeRiderFromApplication'])->name('storeRider');
    });

    // Vendor Applications
    Route::prefix('vendor-applications')->name('vendor_applications.')->group(function () {
        Route::get('/', [AdminVendorApplicationController::class, 'index'])->name('index');
        Route::get('{vendor_application}', [AdminVendorApplicationController::class, 'show'])->name('show');
        Route::delete('{vendor_application}', [AdminVendorApplicationController::class, 'destroy'])->name('destroy');
        Route::get('{id}/create-vendor', [AdminVendorApplicationController::class, 'createVendorFromApplication'])->name('createVendor');
        Route::post('{id}/store-vendor', [AdminVendorApplicationController::class, 'storeVendorFromApplication'])->name('storeVendor');
    });

    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{id}/assign-rider', [AdminOrderController::class, 'assignRider'])->name('orders.assignRider');

    // Vendors Management
    Route::get('vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::post('vendors/{id}/toggle', [AdminVendorController::class, 'toggleStatus'])->name('vendors.toggle-status');

   // Products Management
    Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
    Route::patch('products/{id}/toggle-availability', [AdminProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories Management
    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Trashed & Restore
    Route::get('categories/trashed/list', [AdminCategoryController::class, 'trashed'])->name('categories.trashed');
    Route::patch('categories/{id}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');

    // Districts Management
    Route::get('districts', [AdminDistrictController::class, 'index'])->name('districts.index');
    Route::get('districts/create', [AdminDistrictController::class, 'create'])->name('districts.create');
    Route::post('districts', [AdminDistrictController::class, 'store'])->name('districts.store');
    Route::get('districts/{district}', [AdminDistrictController::class, 'show'])->name('districts.show');
    Route::get('districts/{district}/edit', [AdminDistrictController::class, 'edit'])->name('districts.edit');
    Route::put('districts/{district}', [AdminDistrictController::class, 'update'])->name('districts.update');
    Route::delete('districts/{district}', [AdminDistrictController::class, 'destroy'])->name('districts.destroy');

    // Extra Actions
    Route::patch('districts/{district}/toggle-status', [AdminDistrictController::class, 'toggleStatus'])->name('districts.toggle-status');
    Route::post('districts/bulk-update-fees', [AdminDistrictController::class, 'bulkUpdateFees'])->name('districts.bulk-update-fees');
});


Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');

Route::middleware(['auth', 'role:admin'])->group(function () {
Route::get('/admin/ratings', [AdminRatingController::class, 'index'])->name('admin.ratings.index');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
});

// Admin Payout Management Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rider Payouts Routes
    Route::get('/payouts/riders', [AdminPayoutController::class, 'riderPayouts'])->name('payouts.riders');
    Route::get('/payouts/riders/{id}', [AdminPayoutController::class, 'showRiderPayout'])->name('payouts.riders.show');
    Route::patch('/payouts/riders/{id}/update', [AdminPayoutController::class, 'updateRiderPayout'])->name('payouts.riders.update');
    Route::patch('/payouts/riders/{id}/mark-paid', [AdminPayoutController::class, 'markRiderPayoutAsPaid'])->name('payouts.riders.mark-paid');
    Route::patch('/payouts/riders/{id}/mark-failed', [AdminPayoutController::class, 'markRiderPayoutAsFailed'])->name('payouts.riders.mark-failed');
    
    // Vendor Payouts Routes
    Route::get('/payouts/vendors', [AdminPayoutController::class, 'vendorPayouts'])->name('payouts.vendors');
    Route::get('/payouts/vendors/{id}', [AdminPayoutController::class, 'showVendorPayout'])->name('payouts.vendors.show');
    Route::patch('/payouts/vendors/{id}/update', [AdminPayoutController::class, 'updateVendorPayout'])->name('payouts.vendors.update');
    Route::patch('/payouts/vendors/{id}/mark-paid', [AdminPayoutController::class, 'markVendorPayoutAsPaid'])->name('payouts.vendors.mark-paid');
    Route::patch('/payouts/vendors/{id}/mark-failed', [AdminPayoutController::class, 'markVendorPayoutAsFailed'])->name('payouts.vendors.mark-failed');
    
    // Payout Generation Routes
    Route::get('/payouts/dashboard', [AdminPayoutController::class, 'dashboard'])->name('payouts.dashboard');
    Route::post('/payouts/generate', [AdminPayoutController::class, 'generatePayouts'])->name('payouts.generate');
    Route::post('/payouts/generate-weekly', [AdminPayoutController::class, 'generateWeeklyPayouts'])->name('payouts.generate-weekly');
    Route::post('/payouts/generate-monthly', [AdminPayoutController::class, 'generateMonthlyPayouts'])->name('payouts.generate-monthly');
    Route::get('/payouts/summary', [AdminPayoutController::class, 'getPayoutSummary'])->name('payouts.summary');
    Route::get('/payouts/recent-activity', [AdminPayoutController::class, 'getRecentActivity'])->name('payouts.recent-activity');
    
});
//System Settings CRUD
Route::resource('system-settings', AdminSystemSettingController::class);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
Route::put('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
Route::get('password', [AdminProfileController::class, 'editPassword'])->name('admin.password.edit');
Route::put('password', [AdminProfileController::class, 'updatePassword'])->name('admin.password.update');
});


// Admin Payment Verification Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments/pending', [AdminPaymentVerificationController::class, 'index'])
        ->name('payments.pending');
    
    Route::get('/payments/{payment}/review', [AdminPaymentVerificationController::class, 'show'])
        ->name('payments.review');
    
    Route::post('/payments/{payment}/approve', [AdminPaymentVerificationController::class, 'approve'])
        ->name('payments.approve');
    
    Route::post('/payments/{payment}/reject', [AdminPaymentVerificationController::class, 'reject'])
        ->name('payments.reject');
});