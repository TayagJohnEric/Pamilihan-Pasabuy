<!-- Updated Notification Component for Order Fulfillment & Payouts -->
<div class="relative">
    <!-- Notification Button -->
    <button id="notification-button" type="button"
        class="p-2 rounded-full border-2 border-gray-200 bg-white hover:bg-gray-50 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 relative">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="h-6 w-6 text-gray-500 hover:text-gray-600">
            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
        </svg>
        <!-- Badge -->
        <span id="notification-count"
            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium {{ $unreadCount ? '' : 'hidden' }}">
            {{ $unreadCount }}
        </span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown"
        class="hidden fixed md:absolute right-2 md:right-0 left-2 md:left-auto mt-2 md:w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 transform scale-95 opacity-0 transition-all duration-200 origin-top-right">
        <div class="flex justify-between items-center p-3 md:p-4 border-b border-gray-200 rounded-t-lg">
            <h3 class="text-base md:text-lg font-semibold text-gray-800">Notifications</h3>
            <div class="flex items-center space-x-1 md:space-x-2">
                <button id="mark-all-read" class="text-xs md:text-sm text-green-600 hover:text-green-700 font-medium whitespace-nowrap">
                    Mark all as read
                </button>
                <button id="close-notification-dropdown" type="button" class="text-gray-500 hover:text-gray-700 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="notification-list" class="max-h-60 md:max-h-64 overflow-y-auto">
            @forelse ($notifications as $notification)
                <div class="p-3 md:p-4 border-b border-gray-100 hover:bg-gray-50 flex justify-between items-start cursor-pointer notification-item"
                    data-id="{{ $notification->id }}" data-type="{{ $notification->type }}">
                    <div class="flex items-start space-x-2 md:space-x-3 flex-1 min-w-0">
                        <!-- Notification Type Icon -->
                        <div class="flex-shrink-0 mt-1">
                            @switch($notification->type)
                                @case('order_processing')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-blue-500' }} rounded-full"></div>
                                    @break
                                @case('rider_assigned')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-green-500' }} rounded-full"></div>
                                    @break
                                @case('payment_failed')
                                @case('order_failed')
                                @case('rider_payout_failed')
                                @case('vendor_payout_failed')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-red-500' }} rounded-full"></div>
                                    @break
                                @case('rider_assignment_delayed')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-yellow-500' }} rounded-full"></div>
                                    @break
                                @case('new_order')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-purple-500' }} rounded-full"></div>
                                    @break
                                @case('new_payment_review')
                                @case('payment_pending_review')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-orange-500' }} rounded-full"></div>
                                    @break
                                @case('delivery_assigned')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-indigo-500' }} rounded-full"></div>
                                    @break
                                @case('rider_payout_paid')
                                @case('vendor_payout_paid')
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-green-500' }} rounded-full"></div>
                                    @break
                                @default
                                    <div class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-blue-500' }} rounded-full"></div>
                            @endswitch
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-xs md:text-sm font-medium text-gray-800 break-words">
                                {{ $notification->title ?? 'New Notification' }}
                            </p>
                            
                            <!-- Message content based on notification type -->
                            <p class="text-xs md:text-sm text-gray-600 break-words">
                                @if(is_array($notification->message))
                                    @switch($notification->type)
                                        @case('order_processing')
                                            Order #{{ $notification->message['order_id'] ?? '' }} is being prepared
                                            @break
                                        @case('rider_assigned')
                                            {{ $notification->message['rider_name'] ?? 'A rider' }} has been assigned to your order
                                            @break
                                        @case('payment_failed')
                                            Payment failed for Order #{{ $notification->message['order_id'] ?? '' }}
                                            @break
                                        @case('order_failed')
                                            Order #{{ $notification->message['order_id'] ?? '' }} processing failed
                                            @break
                                        @case('new_order')
                                            New order from {{ $notification->message['customer_name'] ?? 'customer' }}
                                            @break
                                        @case('delivery_assigned')
                                            New delivery assignment for Order #{{ $notification->message['order_id'] ?? '' }}
                                            @break
                                        @case('rider_assignment_delayed')
                                            Rider assignment delayed for Order #{{ $notification->message['order_id'] ?? '' }}
                                            @break
                                        @case('new_payment_review')
                                        @case('payment_pending_review')
                                            New payment to review for Order #{{ $notification->message['order_id'] ?? '' }}
                                            @if(isset($notification->message['amount']))
                                                <span class="text-xs text-gray-500 block">Amount: ₱{{ number_format($notification->message['amount'], 2) }}</span>
                                            @endif
                                            @break
                                        @case('rider_payout_paid')
                                            Payout of ₱{{ number_format($notification->message['amount'] ?? 0, 2) }} has been processed
                                            @if(isset($notification->message['period']))
                                                <span class="text-xs text-gray-500 block">Period: {{ $notification->message['period'] }}</span>
                                            @endif
                                            @break
                                        @case('rider_payout_failed')
                                            Payout of ₱{{ number_format($notification->message['amount'] ?? 0, 2) }} processing failed
                                            @if(isset($notification->message['period']))
                                                <span class="text-xs text-gray-500 block">Period: {{ $notification->message['period'] }}</span>
                                            @endif
                                            @break
                                        @case('vendor_payout_paid')
                                            Payout of ₱{{ number_format($notification->message['amount'] ?? 0, 2) }} has been processed
                                            @if(isset($notification->message['period']))
                                                <span class="text-xs text-gray-500 block">Period: {{ $notification->message['period'] }}</span>
                                            @endif
                                            @break
                                        @case('vendor_payout_failed')
                                            Payout of ₱{{ number_format($notification->message['amount'] ?? 0, 2) }} processing failed
                                            @if(isset($notification->message['period']))
                                                <span class="text-xs text-gray-500 block">Period: {{ $notification->message['period'] }}</span>
                                            @endif
                                            @break
                                        @default
                                            {{ $notification->message['message'] ?? 'You have a new notification' }}
                                    @endswitch
                                @else
                                    {{ $notification->message }}
                                @endif
                            </p>
                            
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <!-- Action links based on notification type -->
                    @if(in_array($notification->type, ['order_processing', 'rider_assigned', 'payment_failed', 'order_failed', 'rider_assignment_delayed']) && isset($notification->message['order_id']))
                        <a href="{{ route('customer.orders.show', ['order' => $notification->message['order_id']]) }}"
                            class="text-green-600 hover:text-green-700 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                            View
                        </a>
                    @elseif(in_array($notification->type, ['new_payment_review', 'payment_pending_review']))
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.payments.pending') }}"
                                class="text-orange-600 hover:text-orange-700 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                                View
                            </a>
                        @endif
                    @elseif(in_array($notification->type, ['rider_payout_paid', 'rider_payout_failed']) && isset($notification->message['payout_id']))
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.payouts.riders.show', ['id' => $notification->message['payout_id']]) }}"
                                class="text-blue-600 hover:text-blue-700 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                                View
                            </a>
                        @else
                            <span class="text-green-600 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                                Payout
                            </span>
                        @endif
                    @elseif(in_array($notification->type, ['vendor_payout_paid', 'vendor_payout_failed']) && isset($notification->message['payout_id']))
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.payouts.vendors.show', ['id' => $notification->message['payout_id']]) }}"
                                class="text-blue-600 hover:text-blue-700 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                                View
                            </a>
                        @else
                            <span class="text-green-600 text-xs md:text-sm font-medium ml-1 md:ml-2 flex-shrink-0">
                                Payout
                            </span>
                        @endif
                    @endif
                </div>
            @empty
                <p class="p-3 md:p-4 text-gray-500 text-xs md:text-sm">No new notifications</p>
            @endforelse
        </div>
        <div class="p-2 md:p-3 border-t border-gray-200">
            <button class="w-full text-center text-xs md:text-sm text-green-600 hover:text-green-700 font-medium">
                View All Notifications
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('notification-button');
        const dropdown = document.getElementById('notification-dropdown');
        const closeBtn = document.getElementById('close-notification-dropdown');
        const markAllBtn = document.getElementById('mark-all-read');
        const countBadge = document.getElementById('notification-count');

        // Function to refresh notification count
        function refreshNotificationCount() {
            fetch('/notifications/unread-count')
                .then(res => res.json())
                .then(data => {
                    const unreadCount = data.unread_count;
                    if (unreadCount > 0) {
                        countBadge.textContent = unreadCount;
                        countBadge.classList.remove('hidden');
                    } else {
                        countBadge.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing notification count:', error);
                });
        }

        // Toggle dropdown
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const isHidden = dropdown.classList.contains('hidden');
            
            if (isHidden) {
                dropdown.classList.remove('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.add('scale-100', 'opacity-100');
            } else {
                dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.remove('scale-100', 'opacity-100');
            }
        });

        // Close dropdown
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
            dropdown.classList.remove('scale-100', 'opacity-100');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.remove('scale-100', 'opacity-100');
            }
        });

        // Mark single notification as read
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Don't mark as read if clicking on a link
                if (e.target.tagName === 'A') {
                    return;
                }
                
                const id = item.dataset.id;
                const dot = item.querySelector('div.w-2');
                
                // Only process if notification is unread
                if (!dot.classList.contains('bg-gray-400')) {
                    // Add loading state
                    dot.classList.add('animate-pulse');
                    
                    fetch(`/notifications/${id}/read`, { 
                        method: 'PATCH', 
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(() => {
                        dot.classList.remove('animate-pulse');
                        dot.classList.remove('bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-purple-500', 'bg-indigo-500', 'bg-orange-500');
                        dot.classList.add('bg-gray-400');
                        // Refresh the count to ensure accuracy
                        refreshNotificationCount();
                    })
                    .catch(error => {
                        dot.classList.remove('animate-pulse');
                        console.error('Error marking notification as read:', error);
                    });
                }
            });
        });

        // Mark all notifications as read
        markAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading state
            markAllBtn.disabled = true;
            markAllBtn.textContent = 'Processing...';
            
            fetch('/notifications/mark-all-as-read', { 
                method: 'PATCH', 
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(() => {
                // Mark all notification dots as read
                document.querySelectorAll('.notification-item div.w-2').forEach(dot => {
                    dot.classList.remove('bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-purple-500', 'bg-indigo-500', 'bg-orange-500');
                    dot.classList.add('bg-gray-400');
                });
                countBadge.classList.add('hidden');
                
                // Reset button state
                markAllBtn.disabled = false;
                markAllBtn.textContent = 'Mark all as read';
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
                
                // Reset button state on error
                markAllBtn.disabled = false;
                markAllBtn.textContent = 'Mark all as read';
            });
        });

        // Auto-refresh notification count every 30 seconds
        setInterval(refreshNotificationCount, 30000);
    });
</script>