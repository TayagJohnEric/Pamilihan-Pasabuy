@extends('layout.customer')

@section('title', 'My Orders')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-[90rem] mx-auto  ">
            <!-- Header Section -->
           <!-- Header Section -->
<div class="hidden sm:block mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} total
            </p>
        </div>
    </div>
</div>


           <!-- Filters Section -->
<div class="hidden sm:block bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" action="{{ route('customer.orders.index') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div class="space-y-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    <option value="">All Status</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="awaiting_rider_assignment" {{ request('status') === 'awaiting_rider_assignment' ? 'selected' : '' }}>Awaiting Rider</option>
                    <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="space-y-2">
                <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            <!-- Date To -->
            <div class="space-y-2">
                <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-3">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white rounded-lg font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700">
                    Apply
                </button>
                <a href="{{ route('customer.orders.index') }}"
                   class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Clear
                </a>
            </div>
        </div>
    </form>
</div>


           <!-- Status Counts -->
                @if(!empty($statusCounts))
                <div class="hidden sm:flex flex-wrap gap-3 mb-8">
                    @foreach($statusCounts as $status => $count)
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full border border-gray-100 text-sm shadow-sm">
                        <span class="font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                        <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded-full text-xs font-semibold">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
                @endif


            <!-- Orders List -->
            @if($orders->count() > 0)
                      <div class="space-y-4">
    @foreach($orders as $order)
    <a href="{{ route('customer.orders.show', $order) }}" class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex justify-between items-start sm:items-center">
            
            <!-- Left Column -->
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900">
                        Order #{{ $order->id }}
                    </h3>
                    <div class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium
                        @switch($order->status)
                            @case('pending_payment') bg-amber-50 text-amber-700 border border-amber-200 @break
                            @case('processing') bg-blue-50 text-blue-700 border border-blue-200 @break
                            @case('awaiting_rider_assignment') bg-orange-50 text-orange-700 border border-orange-200 @break
                            @case('out_for_delivery') bg-purple-50 text-purple-700 border border-purple-200 @break
                            @case('delivered') bg-green-50 text-green-700 border border-green-200 @break
                            @case('cancelled') bg-red-50 text-red-700 border border-red-200 @break
                            @case('failed') bg-gray-50 text-gray-700 border border-gray-200 @break
                            @default bg-gray-50 text-gray-700 border border-gray-200
                        @endswitch">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </div>
                </div>
                <p class="text-[10px] sm:text-xs text-gray-500 mt-0.5">
                    {{ $order->order_date->format('M d, Y \a\t h:i A') }}
                </p>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col items-end">
                <p class="text-sm sm:text-lg font-bold text-gray-900">
                    ₱{{ number_format($order->final_total_amount, 2) }}
                </p>
                <div class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium mt-0.5
                    @switch($order->payment_status)
                        @case('pending') bg-amber-50 text-amber-700 @break
                        @case('paid') bg-green-50 text-green-700 @break
                        @case('failed') bg-red-50 text-red-700 @break
                        @default bg-gray-50 text-gray-700
                    @endswitch">
                    {{ ucfirst($order->payment_status) }}
                </div>
            </div>

        </div>
    </a>
    @endforeach
</div>




                
                <!-- Pagination -->
                <div class="mt-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto mb-6 bg-green-50 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">No orders found</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto leading-relaxed">
                            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                We couldn't find any orders matching your filters. Try adjusting your search criteria or 
                                <a href="{{ route('customer.orders.index') }}" class="text-green-600 hover:text-green-700 font-medium">clear all filters</a>.
                            @else
                                You haven't placed any orders yet. Start exploring our products and place your first order!
                            @endif
                        </p>
                        @if(!request()->hasAny(['status', 'date_from', 'date_to']))
                            <a href="{{ route('products.index') ?? '#' }}" 
                               class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Start Shopping
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection