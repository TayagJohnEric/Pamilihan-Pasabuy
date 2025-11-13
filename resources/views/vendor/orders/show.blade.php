@extends('layout.vendor')

@section('title', 'Order Details #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="mb-3 sm:mb-4">
                        <a href="{{ route('vendor.orders.index') }}" 
                           class="inline-flex items-center text-green-600 hover:text-green-700 font-medium transition-colors text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-1 break-words">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600 text-sm sm:text-base">Manage your items in this order</p>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center px-3 sm:px-4 py-2 font-medium rounded-lg sm:rounded-xl border text-sm sm:text-base
                        @if($order->status === 'processing') bg-amber-50 text-amber-700 border-amber-200
                        @elseif($order->status === 'awaiting_rider_assignment') bg-blue-50 text-blue-700 border-blue-200
                        @elseif($order->status === 'out_for_delivery') bg-purple-50 text-purple-700 border-purple-200
                        @elseif($order->status === 'delivered') bg-green-50 text-green-700 border-green-200
                        @else bg-gray-50 text-gray-700 border-gray-200
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
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
                        <div class="flex flex-col space-y-3 sm:space-y-4">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Your Items to Fulfill</h2>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <button type="button" id="bulk-ready-btn" 
                                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg sm:rounded-xl transition-colors shadow-sm hover:shadow-md disabled:bg-gray-400 disabled:cursor-not-allowed text-sm sm:text-base"
                                        disabled>
                                    <span class="btn-loading hidden">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <span class="btn-text">Mark Selected as Ready</span>
                                </button>
                                <div class="inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                    <span id="selected-count">0</span> selected
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="p-3 sm:p-6">
                        <div class="space-y-4 sm:space-y-6">
                            @foreach($order->orderItems as $item)
                                <div class="border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-6 hover:border-green-200 hover:shadow-sm transition-all duration-200" data-item-id="{{ $item->id }}">
                                    <!-- Item Header -->
                                    <div class="flex items-start gap-3 mb-4 sm:mb-6">
                                        <input type="checkbox" 
                                               class="item-checkbox mt-1 sm:mt-2 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 flex-shrink-0"
                                               data-item-id="{{ $item->id }}"
                                               @if($item->status === 'ready_for_pickup') disabled @endif>
                                        
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

                                    <!-- Status Controls - Mobile Optimized -->
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4 sm:mb-6 pb-4 border-b border-gray-100">
                                        <select class="status-select flex-1 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                data-item-id="{{ $item->id }}" 
                                                data-original-value="{{ $item->status }}"
                                                @if($item->status === 'ready_for_pickup') disabled @endif>
                                            <option value="pending" @if($item->status === 'pending') selected @endif>Pending</option>
                                            <option value="preparing" @if($item->status === 'preparing') selected @endif>Preparing</option>
                                            <option value="ready_for_pickup" @if($item->status === 'ready_for_pickup') selected @endif>Ready for Pickup</option>
                                        </select>
                                        <div class="flex items-center gap-2">
                                            <div class="status-indicator w-3 h-3 sm:w-4 sm:h-4 rounded-full shadow-sm flex-shrink-0
                                                @if($item->status === 'ready_for_pickup') bg-green-500
                                                @elseif($item->status === 'preparing') bg-blue-500
                                                @else bg-amber-400
                                                @endif">
                                            </div>
                                            @if($item->status === 'ready_for_pickup')
                                                <div class="flex items-center text-green-600 text-xs font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Locked
                                                </div>
                                            @endif
                                        </div>
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

                                    <!-- Item Details Form -->
                                    <div class="grid grid-cols-1 gap-4 mb-4 sm:mb-6">
                                        @if($item->product->is_budget_based)
                                            <!-- Budget-based item fields -->
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Quantity Description</label>
                                                <input type="text" 
                                                       class="vendor-quantity-desc w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                       data-item-id="{{ $item->id }}"
                                                       data-original-value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                       value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                       placeholder="e.g., 1.25 kg, 3 pieces"
                                                       @if($item->status === 'ready_for_pickup') disabled readonly @endif>
                                            </div>
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Actual Price</label>
                                                <input type="number" 
                                                       class="actual-price w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                       data-item-id="{{ $item->id }}"
                                                       data-original-value="{{ $item->actual_item_price ?? '' }}"
                                                       value="{{ $item->actual_item_price ?? '' }}"
                                                       step="0.01"
                                                       min="0"
                                                       placeholder="₱0.00"
                                                       @if($item->status === 'ready_for_pickup') disabled readonly @endif>
                                            </div>
                                        @else
                                            <!-- Regular item fields -->
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Quantity Description (Optional)</label>
                                                <input type="text" 
                                                       class="vendor-quantity-desc w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                       data-item-id="{{ $item->id }}"
                                                       data-original-value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                       value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                       placeholder="Additional quantity details"
                                                       @if($item->status === 'ready_for_pickup') disabled readonly @endif>
                                            </div>
                                            <div>
                                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Final Price</label>
                                                <input type="number" 
                                                       class="actual-price w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                       data-item-id="{{ $item->id }}"
                                                       data-original-value="{{ $item->actual_item_price ?? $item->unit_price_snapshot * $item->quantity_requested }}"
                                                       value="{{ $item->actual_item_price ?? $item->unit_price_snapshot * $item->quantity_requested }}"
                                                       step="0.01"
                                                       min="0"
                                                       placeholder="₱0.00"
                                                       @if($item->status === 'ready_for_pickup') disabled readonly @endif>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Fulfillment Notes -->
                                    <div class="mb-4 sm:mb-6">
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Fulfillment Notes</label>
                                        <textarea class="fulfillment-notes w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none @if($item->status === 'ready_for_pickup') bg-gray-100 cursor-not-allowed @endif"
                                                  data-item-id="{{ $item->id }}"
                                                  data-original-value="{{ $item->vendor_fulfillment_notes ?? '' }}"
                                                  rows="3"
                                                  placeholder="Any notes about this item..."
                                                  @if($item->status === 'ready_for_pickup') disabled readonly @endif>{{ $item->vendor_fulfillment_notes ?? '' }}</textarea>
                                    </div>

                                    <!-- Update Button -->
                                    <div class="flex flex-col gap-3 border-t border-gray-100 pt-4">
                                        <span class="changes-indicator inline-flex items-center justify-center px-3 py-1.5 bg-amber-100 text-amber-800 text-xs sm:text-sm font-medium rounded-full hidden">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            Unsaved changes
                                        </span>
                                        @if($item->status === 'ready_for_pickup')
                                            <div class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-100 text-green-800 font-medium rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Item Ready - No Changes Allowed
                                            </div>
                                        @else
                                            <button type="button" 
                                                    class="update-item-btn inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg sm:rounded-xl transition-colors shadow-sm hover:shadow-md disabled:bg-gray-400 disabled:cursor-not-allowed text-sm sm:text-base"
                                                    data-item-id="{{ $item->id }}"
                                                    disabled>
                                                <span class="btn-loading hidden">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                                <span class="btn-text">Update Item</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information Sidebar -->
            <div class="hidden lg:block lg:col-span-1 order-1 lg:order-2 space-y-4 sm:space-y-6">
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

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 left-4 sm:left-auto p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-lg z-50 hidden transition-all duration-300 max-w-sm mx-auto sm:mx-0">
        <div id="toast-content" class="flex items-start space-x-2 sm:space-x-3">
            <div id="toast-icon" class="flex-shrink-0"></div>
            <span id="toast-message" class="text-xs sm:text-sm font-medium"></span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedItems = new Set();

            // CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle checkbox selection
            $('.item-checkbox').on('change', function() {
                const itemId = $(this).data('item-id');
                
                if ($(this).is(':checked')) {
                    selectedItems.add(itemId);
                } else {
                    selectedItems.delete(itemId);
                }
                
                updateBulkButton();
            });

            // Update bulk button state
            function updateBulkButton() {
                const count = selectedItems.size;
                $('#selected-count').text(count);
                $('#bulk-ready-btn').prop('disabled', count === 0);
            }

            // Handle bulk ready action
            $('#bulk-ready-btn').on('click', function() {
                if (selectedItems.size === 0) return;

                const $btn = $(this);
                const $btnText = $btn.find('.btn-text');
                const $btnLoading = $btn.find('.btn-loading');

                // Disable button and show loading
                $btn.prop('disabled', true);
                $btnText.hide();
                $btnLoading.removeClass('hidden');

                // Prepare data
                const itemIds = Array.from(selectedItems);

                $.ajax({
                    url: '{{ route("vendor.orders.items.bulk_ready") }}',
                    method: 'POST',
                    data: {
                        order_item_ids: itemIds
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            
                            // Update UI for each item
                            itemIds.forEach(function(itemId) {
                                const $container = $(`[data-item-id="${itemId}"]`);
                                const $select = $container.find('.status-select');
                                const $indicator = $container.find('.status-indicator');
                                const $checkbox = $container.find('.item-checkbox');
                                const $inputs = $container.find('.vendor-quantity-desc, .actual-price, .fulfillment-notes');
                                const $updateBtn = $container.find('.update-item-btn');
                                
                                // Update status and disable dropdown
                                $select.val('ready_for_pickup').attr('data-original-value', 'ready_for_pickup')
                                       .prop('disabled', true).addClass('bg-gray-100 cursor-not-allowed');
                                
                                // Update indicator
                                $indicator.removeClass('bg-amber-400 bg-blue-500').addClass('bg-green-500');
                                
                                // Disable checkbox
                                $checkbox.prop('disabled', true).prop('checked', false);
                                
                                // Disable all input fields
                                $inputs.prop('disabled', true).prop('readonly', true).addClass('bg-gray-100 cursor-not-allowed');
                                
                                // Replace update button with locked message
                                $updateBtn.replaceWith(`
                                    <div class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-100 text-green-800 font-medium rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Item Ready - No Changes Allowed
                                    </div>
                                `);
                                
                                // Add locked indicator to status controls
                                const $statusControls = $container.find('.flex.flex-col.sm\\:flex-row.sm\\:items-center.gap-3');
                                if (!$statusControls.find('.text-green-600').length) {
                                    $statusControls.find('.flex.items-center.gap-2').append(`
                                        <div class="flex items-center text-green-600 text-xs font-medium ml-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Locked
                                        </div>
                                    `);
                                }
                                
                                // Clear from selected items
                                selectedItems.delete(itemId);
                            });
                            
                            updateBulkButton();
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to update items. Please try again.';
                        showToast('error', message);
                    },
                    complete: function() {
                        // Re-enable button and hide loading
                        $btn.prop('disabled', false);
                        $btnText.show();
                        $btnLoading.addClass('hidden');
                    }
                });
            });

            // Handle status dropdown changes
            $('.status-select').on('change', function() {
                const itemId = $(this).data('item-id');
                const newStatus = $(this).val();
                const originalStatus = $(this).attr('data-original-value');
                const itemContainer = $(`[data-item-id="${itemId}"]`);
                
                // Prevent changes if item is already ready for pickup
                if (originalStatus === 'ready_for_pickup') {
                    $(this).val(originalStatus);
                    showToast('error', 'Cannot modify items that are already ready for pickup.');
                    return;
                }
                
                // Update status indicator immediately for better UX
                updateStatusIndicator(itemContainer, newStatus);
                
                // Check if there are changes and enable/disable update button
                checkForChanges(itemId);
            });

            // Handle input field changes
            $('.vendor-quantity-desc, .actual-price, .fulfillment-notes').on('input', function() {
                const itemId = $(this).data('item-id');
                checkForChanges(itemId);
            });

            // Check for changes in item data
            function checkForChanges(itemId) {
                const $container = $(`[data-item-id="${itemId}"]`);
                const $updateBtn = $container.find('.update-item-btn');
                const $changesIndicator = $container.find('.changes-indicator');
                
                const $status = $container.find('.status-select');
                const $quantityDesc = $container.find('.vendor-quantity-desc');
                const $price = $container.find('.actual-price');
                const $notes = $container.find('.fulfillment-notes');
                
                const hasChanges = 
                    $status.val() !== $status.attr('data-original-value') ||
                    $quantityDesc.val() !== $quantityDesc.attr('data-original-value') ||
                    $price.val() !== $price.attr('data-original-value') ||
                    $notes.val() !== $notes.attr('data-original-value');
                
                $updateBtn.prop('disabled', !hasChanges);
                
                if (hasChanges) {
                    $changesIndicator.removeClass('hidden');
                } else {
                    $changesIndicator.addClass('hidden');
                }
            }

            // Update status indicator visual
            function updateStatusIndicator($container, status) {
                const $indicator = $container.find('.status-indicator');
                $indicator.removeClass('bg-amber-400 bg-blue-500 bg-green-500');
                
                if (status === 'ready_for_pickup') {
                    $indicator.addClass('bg-green-500');
                } else if (status === 'preparing') {
                    $indicator.addClass('bg-blue-500');
                } else {
                    $indicator.addClass('bg-amber-400');
                }
            }

            // Handle individual item updates
            $('.update-item-btn').on('click', function() {
                const itemId = $(this).data('item-id');
                const $container = $(`[data-item-id="${itemId}"]`);
                const $btn = $(this);
                const $btnText = $btn.find('.btn-text');
                const $btnLoading = $btn.find('.btn-loading');

                // Collect form data
                const data = {
                    status: $container.find('.status-select').val(),
                    vendor_assigned_quantity_description: $container.find('.vendor-quantity-desc').val(),
                    actual_item_price: $container.find('.actual-price').val(),
                    vendor_fulfillment_notes: $container.find('.fulfillment-notes').val()
                };

                // Disable button and show loading
                $btn.prop('disabled', true);
                $btnText.hide();
                $btnLoading.removeClass('hidden');

                $.ajax({
                    url: `/vendor/orders/items/${itemId}`,
                    method: 'PATCH',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            
                            // Update original values to current values
                            $container.find('.status-select').attr('data-original-value', data.status);
                            $container.find('.vendor-quantity-desc').attr('data-original-value', data.vendor_assigned_quantity_description);
                            $container.find('.actual-price').attr('data-original-value', data.actual_item_price);
                            $container.find('.fulfillment-notes').attr('data-original-value', data.vendor_fulfillment_notes);
                            
                            // Hide changes indicator
                            $container.find('.changes-indicator').addClass('hidden');
                            
                            // If status is ready_for_pickup, lock the entire item
                            if (data.status === 'ready_for_pickup') {
                                const $select = $container.find('.status-select');
                                const $checkbox = $container.find('.item-checkbox');
                                const $inputs = $container.find('.vendor-quantity-desc, .actual-price, .fulfillment-notes');
                                
                                // Disable and style all form elements
                                $select.prop('disabled', true).addClass('bg-gray-100 cursor-not-allowed');
                                $checkbox.prop('disabled', true).prop('checked', false);
                                $inputs.prop('disabled', true).prop('readonly', true).addClass('bg-gray-100 cursor-not-allowed');
                                
                                // Replace update button with locked message
                                $btn.replaceWith(`
                                    <div class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-100 text-green-800 font-medium rounded-lg sm:rounded-xl border border-green-200 text-sm sm:text-base">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Item Ready - No Changes Allowed
                                    </div>
                                `);
                                
                                // Add locked indicator to status controls
                                const $statusControls = $container.find('.flex.flex-col.sm\\:flex-row.sm\\:items-center.gap-3');
                                if (!$statusControls.find('.text-green-600').length) {
                                    $statusControls.find('.flex.items-center.gap-2').append(`
                                        <div class="flex items-center text-green-600 text-xs font-medium ml-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Locked
                                        </div>
                                    `);
                                }
                                
                                selectedItems.delete(itemId);
                                updateBulkButton();
                            }
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to update item. Please try again.';
                        showToast('error', message);
                    },
                    complete: function() {
                        // Re-enable button and hide loading
                        $btn.prop('disabled', false);
                        $btnText.show();
                        $btnLoading.addClass('hidden');
                    }
                });
            });

            // Toast notification function
            function showToast(type, message) {
                const $toast = $('#toast');
                const $toastIcon = $('#toast-icon');
                const $toastMessage = $('#toast-message');

                // Reset classes
                $toast.removeClass('bg-green-50 bg-red-50 border-green-200 border-red-200 text-green-800 text-red-800');
                $toastIcon.empty();

                if (type === 'success') {
                    $toast.addClass('bg-green-50 border border-green-200 text-green-800');
                    $toastIcon.html('<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>');
                } else {
                    $toast.addClass('bg-red-50 border border-red-200 text-red-800');
                    $toastIcon.html('<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>');
                }

                $toastMessage.text(message);
                $toast.removeClass('hidden');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    $toast.addClass('hidden');
                }, 5000);
            }

            // Display any server-side flash messages
            @if(session('success'))
                showToast('success', '{{ session("success") }}');
            @endif

            @if(session('error'))
                showToast('error', '{{ session("error") }}');
            @endif
        });
    </script>
@endsection