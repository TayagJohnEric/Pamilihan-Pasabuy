@extends('layout.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    
   <!-- Header Section with Shop Info (Toggle untouched) -->
<div class="bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $dashboardStats['vendor_info']['shop_name'] }}</h1>
            <p class="text-xs sm:text-sm md:text-base text-gray-600 mt-1">Welcome back, {{ $dashboardStats['vendor_info']['name'] }}!</p>
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-2 space-y-1 sm:space-y-0">
                @if($dashboardStats['vendor_info']['stall_number'])
                    <span class="text-xs sm:text-sm text-gray-500">Stall: {{ $dashboardStats['vendor_info']['stall_number'] }}</span>
                @endif
                @if($dashboardStats['vendor_info']['market_section'])
                    <span class="text-xs sm:text-sm text-gray-500">{{ $dashboardStats['vendor_info']['market_section'] }}</span>
                @endif
                @if($dashboardStats['vendor_info']['business_hours'])
                    <span class="text-xs sm:text-sm text-gray-500">Hours: {{ $dashboardStats['vendor_info']['business_hours'] }}</span>
                @endif
            </div>
        </div>

        <div class="mt-3 md:mt-0 flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
            <!-- Order Acceptance Toggle (unchanged) -->
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-gray-700">Accepting Orders:</span>
                <button 
                    id="order-acceptance-toggle" 
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'bg-emerald-500' : 'bg-gray-300' }}"
                    data-accepting="{{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'true' : 'false' }}"
                >
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>
            
            <!-- Status Badge -->
            <div class="flex items-center space-x-2">
                <div id="status-dot" class="h-2 w-2 sm:h-3 sm:w-3 rounded-full {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'bg-emerald-400' : 'bg-gray-400' }}"></div>
                <span id="status-text" class="text-xs sm:text-sm font-medium {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'text-emerald-600' : 'text-gray-600' }}">
                    {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'Open' : 'Closed' }}
                </span>
            </div>
        </div>
    </div>
</div>


    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
       <!-- Pending Orders Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-orange-100">
            <svg class="h-4 w-4 sm:h-6 sm:w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Pending Orders</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $dashboardStats['today_stats']['pending_orders'] }}</p>
        </div>
    </div>
</div>

<!-- Sales Today Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M20 11H4"/>
                <path d="M20 7H4"/>
                <path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Sales Today</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">₱{{ number_format($dashboardStats['today_stats']['sales_amount'], 2) }}</p>
        </div>
    </div>
</div>

<!-- Shop Rating Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-yellow-100">
            <svg class="h-4 w-4 sm:h-6 sm:w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Shop Rating</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">
                {{ number_format($dashboardStats['vendor_info']['rating'], 1) }}
                <span class="text-xs sm:text-sm text-gray-500">/5.0</span>
            </p>
        </div>
    </div>
</div>

<!-- Total Products Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Total Products</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $dashboardStats['product_stats']['total_products'] }}</p>
        </div>
    </div>
</div>

    </div>

    <!-- Business Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Today's Summary -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Today's Summary</h3>
            <div class="space-y-2 sm:space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Orders</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['today_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Orders Completed</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['today_stats']['completed_orders'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Items Sold</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['today_stats']['items_sold'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Average Order Value</span>
                    <span class="text-sm sm:font-medium text-gray-900">
                        ₱{{ $dashboardStats['today_stats']['orders_count'] > 0 ? number_format($dashboardStats['today_stats']['sales_amount'] / $dashboardStats['today_stats']['orders_count'], 2) : '0.00' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Weekly Overview -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">This Week</h3>
            <div class="space-y-2 sm:space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Sales</span>
                    <span class="text-sm sm:font-medium text-green-600">₱{{ number_format($dashboardStats['weekly_stats']['sales_amount'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Orders Received</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['weekly_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Average Order Value</span>
                    <span class="text-sm sm:font-medium text-gray-900">₱{{ number_format($dashboardStats['weekly_stats']['average_order_value'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Customer Rating</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ number_format($dashboardStats['weekly_stats']['customer_rating'], 1) }}/5.0</span>
                </div>
            </div>
        </div>

        <!-- Monthly Overview -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">This Month</h3>
            <div class="space-y-2 sm:space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Sales</span>
                    <span class="text-sm sm:font-medium text-green-600">₱{{ number_format($dashboardStats['monthly_stats']['sales_amount'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Orders</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Unique Customers</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['unique_customers'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Fulfillment Rate</span>
                    <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['fulfillment_rate'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product & Inventory Overview -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Inventory Status</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ $dashboardStats['product_stats']['total_products'] }}</div>
                <p class="text-xs sm:text-sm text-gray-600">Total Products</p>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $dashboardStats['product_stats']['active_products'] }}</div>
                <p class="text-xs sm:text-sm text-gray-600">Active Products</p>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-red-600">{{ $dashboardStats['product_stats']['out_of_stock'] }}</div>
                <p class="text-xs sm:text-sm text-gray-600">Out of Stock</p>
            </div>
            <div class="text-center">
                <div class="text-xl sm:text-2xl font-bold text-orange-600">{{ $dashboardStats['product_stats']['low_stock'] }}</div>
                <p class="text-xs sm:text-sm text-gray-600">Low Stock</p>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 text-center">
          <a href="{{ route('vendor.products.index') }}" 
   class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 border border-transparent text-xs sm:text-sm font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors">
    Manage Inventory
</a>

        </div>
    </div>

    <!-- Pending Orders and Top Product -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        
        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Pending Orders</h3>
                <a href="{{ route('vendor.orders.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
    View All
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 ml-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M5 12h14"/>
        <path d="m12 5 7 7-7 7"/>
    </svg>
</a>

            </div>
            
            @if($dashboardStats['pending_orders']->count() > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($dashboardStats['pending_orders']->take(5) as $order)
                        <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-1 sm:space-x-2">
                                        <span class="text-sm sm:text-base font-medium text-gray-900">Order #{{ $order['id'] }}</span>
                                        <span class="px-1 py-0.5 sm:px-2 sm:py-1 text-[10px] sm:text-xs font-medium rounded-full
                                            @if($order['status'] == 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($order['status'] == 'awaiting_rider_assignment') bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                        </span>
                                        <span class="px-1 py-0.5 sm:px-2 sm:py-1 text-[10px] sm:text-xs font-medium rounded-full
                                            @if($order['payment_status'] == 'paid') bg-green-100 text-green-800
                                            @elseif($order['payment_status'] == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order['payment_status']) }}
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $order['customer_name'] }}</p>
                                    <div class="flex items-center justify-between mt-1 sm:mt-2">
                                        <span class="text-xs sm:text-sm text-gray-500">{{ $order['items_count'] }} items</span>
                                        <span class="text-xs sm:text-sm font-medium text-gray-900">₱{{ number_format($order['total_amount'], 2) }}</span>
                                    </div>
                                    <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ $order['time_ago'] }}</p>
                                </div>
                                <div class="ml-2 sm:ml-4">
                                    <a href="{{ route('vendor.orders.show', $order['id']) }}" 
                                       class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 border border-transparent text-[10px] sm:text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                        Process
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 sm:py-8">
                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">No pending orders</p>
                    <p class="text-[10px] sm:text-xs text-gray-400">{{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'All caught up!' : 'Turn on order acceptance to receive orders' }}</p>
                </div>
            @endif
        </div>

 <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Top Products</h3>
                <!-- Manage Products Link -->
<a href="{{ route('vendor.products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
    Manage Products
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 ml-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M5 12h14"/>
        <path d="m12 5 7 7-7 7"/>
    </svg>
</a>

                </a>
            </div>
            
            @if($dashboardStats['top_products']->count() > 0)
                <div class="space-y-2 sm:space-y-3">
                    @foreach($dashboardStats['top_products'] as $product)
                        <div class="flex items-center space-x-2 sm:space-x-3 py-1 sm:py-2">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if($product['image'])
                                    <img src="{{ asset('storage/' . $product['image']) }}"
                                         alt="{{ $product['name'] }}"
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm sm:text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <div class="flex items-center space-x-2 sm:space-x-4 mt-0.5 sm:mt-1">
                                    <span class="text-[10px] sm:text-xs text-gray-500">{{ $product['sales_count'] }} sold</span>
                                    <span class="text-[10px] sm:text-xs text-gray-500">₱{{ number_format($product['price'], 2) }}</span>
                                    <span class="text-[10px] sm:text-xs text-gray-500">{{ $product['stock'] }} in stock</span>
                                </div>
                            </div>
                            <!-- Sales Count -->
                            <div class="text-right flex-shrink-0">
                                <div class="text-sm sm:text-sm font-medium text-gray-900">{{ $product['sales_count'] }}</div>
                                <div class="text-[10px] sm:text-xs text-gray-500">orders</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 sm:py-8">
                    <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">No sales data yet</p>
                    <p class="text-[10px] sm:text-xs text-gray-400">Top selling products will appear here</p>
                </div>
            @endif
        </div>

    </div>

</div>

<script>
// Order Acceptance Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('order-acceptance-toggle');
    const toggleSpan = toggleButton.querySelector('span');
    
    toggleButton.addEventListener('click', function() {
        const currentStatus = this.getAttribute('data-accepting') === 'true';
        
        // Disable button during request
        this.disabled = true;
        this.classList.add('opacity-50');
        
        // Send AJAX request
        fetch('{{ route("vendor.toggle-order-acceptance") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button appearance
                const newStatus = data.is_accepting_orders;
                this.setAttribute('data-accepting', newStatus);
                
                if (newStatus) {
                    this.classList.remove('bg-gray-300');
                    this.classList.add('bg-emerald-500');
                    toggleSpan.classList.remove('translate-x-1');
                    toggleSpan.classList.add('translate-x-6');
                } else {
                    this.classList.remove('bg-emerald-500');
                    this.classList.add('bg-gray-300');
                    toggleSpan.classList.remove('translate-x-6');
                    toggleSpan.classList.add('translate-x-1');
                }
                
                // Update status indicator
                const statusDot = document.getElementById('status-dot');
                const statusText = document.getElementById('status-text');
                
                if (!statusDot || !statusText) {
                    console.error('Status elements not found');
                    return;
                }
                
                if (newStatus) {
                    statusDot.classList.remove('bg-gray-400');
                    statusDot.classList.add('bg-emerald-400');
                    statusText.classList.remove('text-gray-600');
                    statusText.classList.add('text-emerald-600');
                    statusText.textContent = 'Open';
                } else {
                    statusDot.classList.remove('bg-emerald-400');
                    statusDot.classList.add('bg-gray-400');
                    statusText.classList.remove('text-emerald-600');
                    statusText.classList.add('text-gray-600');
                    statusText.textContent = 'Closed';
                }
                
                // Show success message
                showNotification(data.message, 'success');
            } else {
                showNotification('Failed to update order acceptance status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating status', 'error');
        })
        .finally(() => {
            // Re-enable button
            this.disabled = false;
            this.classList.remove('opacity-50');
        });
    });
    
    // Simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Auto-refresh pending orders count every 30 seconds
    setInterval(function() {
        if (document.querySelector('[data-accepting="true"]')) {
            // Only refresh if accepting orders
            fetch('{{ route("vendor.dashboard") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update pending orders count (you could implement a more sophisticated update)
                console.log('Dashboard refreshed');
            })
            .catch(error => {
                console.log('Auto-refresh error:', error);
            });
        }
    }, 30000); // 30 seconds
});
</script>

@endsection