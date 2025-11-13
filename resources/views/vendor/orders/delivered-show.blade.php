@extends('layout.vendor')

@section('title', 'Delivered Order #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="mb-3 sm:mb-4">
                        <a href="{{ route('vendor.orders.delivered') }}" 
                           class="inline-flex items-center text-green-600 hover:text-green-700 font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Delivered Orders
                        </a>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-1 break-words">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600 text-sm sm:text-base">View delivered order details</p>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-3 sm:px-4 py-2 font-medium rounded-lg sm:rounded-xl border text-sm sm:text-base bg-green-50 text-green-700 border-green-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Delivered
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Order Items Section -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Items Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-white to-green-50/30">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Order Items</h2>
                            <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                {{ $order->orderItems->count() }} {{ Str::plural('item', $order->orderItems->count()) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="p-3 sm:p-6">
                        <div class="space-y-4 sm:space-y-6">
                            @foreach($order->orderItems as $item)
                                <div class="border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-6 bg-gray-50">
                                    <!-- Item Header -->
                                    <div class="flex items-start gap-3 mb-4 sm:mb-6">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ asset('storage/' . $item->product->image_url) }}" 
                                                     alt="{{ $item->product_name_snapshot }}"
                                                     class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover border border-gray-200">
                                            @else
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3 break-words">{{ $item->product_name_snapshot }}</h3>
                                            <div class="flex flex-col gap-2 text-xs sm:text-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    <span class="text-gray-600">
                                                        <span class="font-medium">Qty:</span> {{ $item->quantity_requested }} {{ $item->product->unit }}
                                                    </span>
                                                </div>
                                                @if($item->customer_budget_requested)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        <span class="text-gray-600">
                                                            <span class="font-medium">Budget:</span> ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        <span class="text-gray-600">
                                                            <span class="font-medium">Unit Price:</span> ₱{{ number_format($item->unit_price_snapshot, 2) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-200">
                                        <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-green-500 shadow-sm flex-shrink-0"></div>
                                        <span class="text-xs sm:text-sm font-medium text-green-700">
                                            {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </div>

                                    @if($item->customerNotes_snapshot)
                                        <div class="mb-4 sm:mb-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs sm:text-sm font-medium text-blue-800 mb-1">Customer Notes:</p>
                                                    <p class="text-xs sm:text-sm text-blue-700 break-words">{{ $item->customerNotes_snapshot }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Item Details (Read-only) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                        @if($item->vendor_assigned_quantity_description)
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Quantity Description</label>
                                                <div class="px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 rounded-lg sm:rounded-xl text-sm bg-white">
                                                    {{ $item->vendor_assigned_quantity_description }}
                                                </div>
                                            </div>
                                        @endif
                                        @if($item->actual_item_price)
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                                    @if($item->product->is_budget_based)
                                                        Actual Price
                                                    @else
                                                        Final Price
                                                    @endif
                                                </label>
                                                <div class="px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 rounded-lg sm:rounded-xl text-sm bg-white font-medium">
                                                    ₱{{ number_format($item->actual_item_price, 2) }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Fulfillment Notes (Read-only) -->
                                    @if($item->vendor_fulfillment_notes)
                                        <div class="mb-4">
                                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Fulfillment Notes</label>
                                            <div class="px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 rounded-lg sm:rounded-xl text-sm bg-white break-words">
                                                {{ $item->vendor_fulfillment_notes }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Completed Indicator -->
                                    <div class="flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-100 text-green-800 font-medium rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Item Completed
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information Sidebar -->
            <div class="lg:col-span-1 order-1 lg:order-2 space-y-4 sm:space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Name</p>
                            <p class="text-sm sm:text-base text-gray-900 font-medium break-words">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Email</p>
                            <p class="text-sm sm:text-base text-gray-900 break-all">{{ $order->customer->email }}</p>
                        </div>
                        @if($order->customer->phone_number)
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Phone</p>
                                <p class="text-sm sm:text-base text-gray-900">{{ $order->customer->phone_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Delivery Information</h3>
                    </div>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Address</p>
                            <p class="text-sm sm:text-base text-gray-900 break-words">{{ $order->deliveryAddress->address_line1 }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">District</p>
                            <p class="text-sm sm:text-base text-gray-900">{{ $order->deliveryAddress->district->name }}</p>
                        </div>
                        @if($order->deliveryAddress->delivery_notes)
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Delivery Notes</p>
                                <p class="text-sm sm:text-base text-gray-900 break-words">{{ $order->deliveryAddress->delivery_notes }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Delivery Fee</p>
                            <p class="text-sm sm:text-base font-semibold text-gray-900">₱{{ number_format($order->delivery_fee, 2) }}</p>
                        </div>
                        @if($order->rider)
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Rider</p>
                                <p class="text-sm sm:text-base text-gray-900 font-medium">{{ $order->rider->first_name }} {{ $order->rider->last_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <svg class="w-5 h-5 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Order Summary</h3>
                    </div>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Order Date</p>
                            <p class="text-sm sm:text-base text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($order->updated_at && $order->status === 'delivered')
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Delivered Date</p>
                                <p class="text-sm sm:text-base text-gray-900">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Payment Method</p>
                            <p class="text-sm sm:text-base text-gray-900">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Payment Status</p>
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-1 text-xs font-medium rounded-full
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-amber-100 text-amber-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if($order->special_instructions)
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Special Instructions</p>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm sm:text-base text-gray-900 break-words">{{ $order->special_instructions }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="border-t border-gray-100 pt-3 sm:pt-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total Amount</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
