@extends('layout.rider')

@section('title', 'Rider Dashboard')

@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    
    <!-- Header Section with Rider Info and Availability Toggle -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Welcome back, {{ $dashboardStats['rider_info']['name'] }}!
                </h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    Here's your delivery dashboard overview
                </p>
            </div>

            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <!-- Availability Toggle -->
                <div class="flex items-center space-x-3">
                   <span class="text-sm sm:text-base font-medium text-gray-700">
                        Available for deliveries:
                    </span>

                    <button 
                        id="availability-toggle" 
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $dashboardStats['rider_info']['is_available'] ? 'bg-emerald-500' : 'bg-gray-300' }}"
                        data-available="{{ $dashboardStats['rider_info']['is_available'] ? 'true' : 'false' }}"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $dashboardStats['rider_info']['is_available'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
                
                <!-- Status Badge -->
                <div class="flex items-center space-x-2">
                    <div class="h-3 w-3 rounded-full {{ $dashboardStats['rider_info']['is_available'] ? 'bg-emerald-400' : 'bg-gray-400' }}"></div>
                    <span class="text-sm font-medium {{ $dashboardStats['rider_info']['is_available'] ? 'text-emerald-600' : 'text-gray-600' }}">
                        {{ $dashboardStats['rider_info']['is_available'] ? 'Online' : 'Offline' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
       

         <!-- Rider Rating Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-gray-100">
            <svg class="h-4 w-4 sm:h-6 sm:w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Your Rating</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">
                {{ number_format($dashboardStats['rider_info']['rating'], 1) }}
                <span class="text-xs sm:text-sm text-gray-500">/5.0</span>
            </p>
        </div>
    </div>
</div>

<!-- Total Deliveries Today Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="m16 16 2 2 4-4"/>
                <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/>
                <path d="m7.5 4.27 9 5.15"/>
                <polyline points="3.29 7 12 12 20.71 7"/>
                <line x1="12" x2="12" y1="22" y2="12"/>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Deliveries Today</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $dashboardStats['today_stats']['total_deliveries'] }}</p>
        </div>
    </div>
</div>

<!-- Today's Earnings Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M20 11H4"/>
                <path d="M20 7H4"/>
                <path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Today's Earnings</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">₱{{ number_format($dashboardStats['today_stats']['earnings'], 2) }}</p>
        </div>
    </div>
</div>

<!-- Performance Score Card -->
<div class="bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex items-center">
        <div class="p-2 sm:p-3 rounded-full bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/>
            </svg>
        </div>
        <div class="ml-2 sm:ml-4">
            <p class="text-xs sm:text-sm font-medium text-gray-600">Completion Rate</p>
            <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $dashboardStats['performance']['completion_rate'] }}%</p>
        </div>
    </div>
</div>

    </div>

   <!-- Today's Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Today's Details -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Today's Summary</h3>
        <div class="space-y-2 sm:space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Orders Completed</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['today_stats']['completed_orders'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Distance Covered</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['today_stats']['distance_covered'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Average per Delivery</span>
                <span class="text-sm sm:font-medium text-green-600">
                    ₱{{ $dashboardStats['today_stats']['total_deliveries'] > 0 ? number_format($dashboardStats['today_stats']['earnings'] / $dashboardStats['today_stats']['total_deliveries'], 2) : '0.00' }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Vehicle Type</span>
                <span class="text-sm sm:font-medium text-gray-900 capitalize">{{ $dashboardStats['rider_info']['vehicle_type'] }}</span>
            </div>
        </div>
    </div>

    <!-- Weekly Overview -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">This Week</h3>
        <div class="space-y-2 sm:space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Total Deliveries</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['weekly_stats']['total_deliveries'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Completed Orders</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['weekly_stats']['completed_orders'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Weekly Earnings</span>
                <span class="text-sm sm:font-medium text-green-600">₱{{ number_format($dashboardStats['weekly_stats']['earnings'], 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Average Rating</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ number_format($dashboardStats['weekly_stats']['average_rating'], 1) }}/5.0</span>
            </div>
        </div>
    </div>

    <!-- Monthly Overview -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">This Month</h3>
        <div class="space-y-2 sm:space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Total Orders</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['total_deliveries'] }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Success Rate</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['success_rate'] }}%</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Monthly Earnings</span>
                <span class="text-sm sm:font-medium text-green-600">₱{{ number_format($dashboardStats['monthly_stats']['earnings'], 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm sm:text-gray-600 text-gray-600">Lifetime Deliveries</span>
                <span class="text-sm sm:font-medium text-gray-900">{{ $dashboardStats['performance']['total_lifetime_deliveries'] }}</span>
            </div>
        </div>
    </div>
</div>


<!-- Current Orders and Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
    
    <!-- Current Orders -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Current Orders</h3>
          <a href="{{ route('rider.orders.index') }}" 
   class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium gap-1">
    <span>View All</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M5 12h14"/>
        <path d="m12 5 7 7-7 7"/>
    </svg>
</a>        
        </div>
        
        @if($dashboardStats['current_orders']->count() > 0)
            <div class="space-y-2 sm:space-y-3">
                @foreach($dashboardStats['current_orders']->take(3) as $order)
                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <span class="text-sm sm:text-base font-medium text-gray-900">Order #{{ $order['id'] }}</span>
                                    <span class="px-1 py-0.5 sm:px-2 sm:py-1 text-[10px] sm:text-xs font-medium rounded-full
                                        @if($order['status'] == 'assigned') bg-yellow-100 text-yellow-800
                                        @elseif($order['status'] == 'pickup_confirmed') bg-blue-100 text-blue-800
                                        @elseif($order['status'] == 'out_for_delivery') bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                    </span>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $order['customer_name'] }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-500 mt-1">{{ Str::limit($order['address'], 40) }}</p>
                                <div class="flex items-center justify-between mt-1 sm:mt-2">
                                    <span class="text-sm sm:text-base font-medium text-gray-900">₱{{ number_format($order['delivery_fee'], 2) }}</span>
                                    <span class="text-[10px] sm:text-xs text-gray-500">{{ $order['created_at'] }}</span>
                                </div>
                            </div>
                            <div class="ml-2 sm:ml-4">
                                <a href="{{ route('rider.orders.show', $order['id']) }}" 
                                   class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 border border-transparent text-[10px] sm:text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                    View
                                </a>
                            </div>
                        </div>
                        @if($order['special_instructions'])
                            <div class="mt-1 sm:mt-2 p-1 sm:p-2 bg-amber-50 border border-amber-200 rounded">
                                <p class="text-[10px] sm:text-xs text-amber-800">
                                    <span class="font-medium">Note:</span> {{ Str::limit($order['special_instructions'], 80) }}
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 sm:py-8">
                <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5"></path>
                </svg>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">No current orders</p>
                <p class="text-[10px] sm:text-xs text-gray-400">{{ $dashboardStats['rider_info']['is_available'] ? 'You\'re available for new orders' : 'Turn on availability to receive orders' }}</p>
            </div>
        @endif
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Activity</h3>
            <a href="{{ route('rider.orders.index') }}" 
                class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium gap-1">
                    <span>View History</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
            </a>

        </div>
        
        @if($dashboardStats['recent_activity']->count() > 0)
            <div class="space-y-2 sm:space-y-3">
                @foreach($dashboardStats['recent_activity']->take(5) as $activity)
                    <div class="flex items-center space-x-2 sm:space-x-3 py-1 sm:py-2">
                        <div class="flex-shrink-0">
                            <div class="inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 
                                    @if($activity['status'] == 'delivered') text-green-500
                                    @elseif($activity['status'] == 'out_for_delivery') text-blue-500
                                    @elseif($activity['status'] == 'pickup_confirmed') text-yellow-500
                                    @else text-gray-400
                                    @endif" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                                    <path d="M12 22V12"/>
                                    <polyline points="3.29 7 12 12 20.71 7"/>
                                    <path d="m7.5 4.27 9 5.15"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm sm:text-sm font-medium text-gray-900 truncate">
                                    Order #{{ $activity['id'] }}
                                </p>
                                <p class="text-[10px] sm:text-xs text-gray-500">{{ $activity['time'] }}</p>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600">{{ $activity['customer_name'] }}</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[10px] sm:text-xs px-1 sm:px-2 py-0.5 sm:py-1 rounded-full
                                    @if($activity['status'] == 'delivered') bg-green-100 text-green-800
                                    @elseif($activity['status'] == 'out_for_delivery') bg-blue-100 text-blue-800
                                    @elseif($activity['status'] == 'pickup_confirmed') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                </span>
                                <span class="text-[10px] sm:text-xs font-medium text-gray-900">₱{{ number_format($activity['delivery_fee'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 sm:py-8">
                <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">No recent activity</p>
                <p class="text-[10px] sm:text-xs text-gray-400">Your delivery history will appear here</p>
            </div>
        @endif
    </div>
</div>

</div>

<script>
// Availability Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('availability-toggle');
    const toggleSpan = toggleButton.querySelector('span');
    
    toggleButton.addEventListener('click', function() {
        const currentAvailability = this.getAttribute('data-available') === 'true';
        
        // Disable button during request
        this.disabled = true;
        this.classList.add('opacity-50');
        
        // Send AJAX request
        fetch('{{ route("rider.toggle-availability") }}', {
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
                const newAvailability = data.is_available;
                this.setAttribute('data-available', newAvailability);
                
                if (newAvailability) {
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
                const statusDot = document.querySelector('.h-3.w-3.rounded-full');
                const statusText = statusDot.nextElementSibling;
                
                if (newAvailability) {
                    statusDot.classList.remove('bg-gray-400');
                    statusDot.classList.add('bg-emerald-400');
                    statusText.classList.remove('text-gray-600');
                    statusText.classList.add('text-emerald-600');
                    statusText.textContent = 'Online';
                } else {
                    statusDot.classList.remove('bg-emerald-400');
                    statusDot.classList.add('bg-gray-400');
                    statusText.classList.remove('text-emerald-600');
                    statusText.classList.add('text-gray-600');
                    statusText.textContent = 'Offline';
                }
                
                // Show success message (you can customize this)
                showNotification(data.message, 'success');
            } else {
                showNotification('Failed to update availability status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating availability', 'error');
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
});
</script>

@endsection