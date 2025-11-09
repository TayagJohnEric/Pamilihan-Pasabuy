@extends('layout.customer')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[90rem] mx-auto">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-emerald-400 to-emerald-600 rounded-full"></div>
                            <h1 class="text-2xl sm:text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                        </div>
                        <p class="text-gray-600 text-sm sm:text-base">
                            Placed on {{ $order->order_date->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 lg:text-right">
                        <div class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium
                            @switch($order->status)
                                @case('pending_payment')
                                    bg-amber-50 text-amber-700 border border-amber-200
                                    @break
                                @case('processing')
                                    bg-blue-50 text-blue-700 border border-blue-200
                                    @break
                                @case('awaiting_rider_assignment')
                                    bg-orange-50 text-orange-700 border border-orange-200
                                    @break
                                @case('out_for_delivery')
                                    bg-purple-50 text-purple-700 border border-purple-200
                                    @break
                                @case('delivered')
                                    bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @break
                                @case('cancelled')
                                    bg-red-50 text-red-700 border border-red-200
                                    @break
                                @case('failed')
                                    bg-gray-50 text-gray-700 border border-gray-200
                                    @break
                                @default
                                    bg-gray-50 text-gray-700 border border-gray-200
                            @endswitch">
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </div>
                        
                        <div class="text-sm text-gray-600 flex items-center gap-2">
                            <span>Payment:</span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                @switch($order->payment_status)
                                    @case('pending')
                                        bg-amber-50 text-amber-700 border border-amber-200
                                        @break
                                    @case('paid')
                                        bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @break
                                    @case('failed')
                                        bg-red-50 text-red-700 border border-red-200
                                        @break
                                    @default
                                        bg-gray-50 text-gray-700 border border-gray-200
                                @endswitch">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('customer.orders.index') }}" 
                       class="inline-flex items-center text-emerald-600 hover:text-emerald-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Orders
                    </a>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mt-4">
                    @if($order->status === 'assigned' && $order->payment_method === 'online_payment' && $order->payment_status === 'pending')
                        <!-- Payment Required Button -->
                        <div class="w-full p-4 bg-yellow-50 border border-yellow-200 rounded-xl mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-yellow-800 mb-1">Payment Required</h3>
                                    <p class="text-sm text-yellow-700 mb-3">A rider has accepted your order! Please complete your payment to proceed with delivery.</p>
                                    <a href="{{ route('payment.gcash-instructions', $order->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium text-sm shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Complete Payment Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->status === 'pending_payment')
                         <h2 class="px-6 py-2.5 text-xl font-bold text-gray-900">Payment submitted, awaiting verification.</h2>
                    @endif
                    
                    @if($order->status === 'delivered')
                        @php
                            $hasRated = isset($order->ratings)
                                ? $order->ratings->where('user_id', auth()->id())->isNotEmpty()
                                : false;
                        @endphp
                        @if(!$hasRated)
                            <a href="{{ route('customer.orders.rate', $order) }}"
                               class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors font-medium text-sm shadow-sm">
                                Rate Order
                            </a>
                        @else
                            <span class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl cursor-not-allowed font-medium text-sm">
                                Rated
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h2 class="text-xl font-bold text-gray-900">Order Items</h2>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($order->orderItems as $item)
                                <div class="border border-gray-100 rounded-xl p-5 hover:shadow-sm transition-shadow">
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ asset('storage/' . $item->product->image_url ) }}" 
                                                     alt="{{ $item->product_name_snapshot }}"
                                                     class="w-20 h-20 object-cover rounded-xl shadow-sm">
                                            @else
                                                <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="flex-1 space-y-4">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $item->product_name_snapshot }}</h3>
                                                
                                                @if($item->product && $item->product->vendor)
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        <span class="font-medium">Vendor:</span> {{ $item->product->vendor->business_name ?? $item->product->vendor->name }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Quantity</span>
                                                    <p class="text-gray-900 font-semibold mt-1">{{ $item->quantity_requested }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Unit Price</span>
                                                    <p class="text-gray-900 font-semibold mt-1">₱{{ number_format($item->unit_price_snapshot, 2) }}</p>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium mt-1
                                                        @switch($item->status)
                                                            @case('pending')
                                                                bg-amber-100 text-amber-800
                                                                @break
                                                            @case('preparing')
                                                                bg-blue-100 text-blue-800
                                                                @break
                                                            @case('ready_for_pickup')
                                                                bg-emerald-100 text-emerald-800
                                                                @break
                                                            @default
                                                                bg-gray-100 text-gray-800
                                                        @endswitch">
                                                        {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                                    </span>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Subtotal</span>
                                                    <p class="text-gray-900 font-semibold mt-1">
                                                        ₱{{ number_format(($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested, 2) }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Customer Budget -->
                                            @if($item->customer_budget_requested)
                                                <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                                    <span class="text-xs font-medium text-blue-700 uppercase tracking-wide">Customer Budget</span>
                                                    <p class="text-blue-900 font-semibold mt-1">₱{{ number_format($item->customer_budget_requested, 2) }}</p>
                                                </div>
                                            @endif

                                            <!-- Substitution Info -->
                                            @if($item->is_substituted && $item->substitutedProduct)
                                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                        <div>
                                                            <span class="font-medium text-orange-800 text-sm">Substituted with:</span>
                                                            <p class="text-orange-700 mt-1">{{ $item->substitutedProduct->product_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Notes Section -->
                                            @if($item->vendor_fulfillment_notes || $item->customerNotes_snapshot)
                                                <div class="space-y-3">
                                                    @if($item->vendor_fulfillment_notes)
                                                        <div class="bg-gray-50 rounded-lg p-4">
                                                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wide">Vendor Notes</span>
                                                            <p class="text-gray-600 mt-2 text-sm leading-relaxed">{{ $item->vendor_fulfillment_notes }}</p>
                                                        </div>
                                                    @endif

                                                    @if($item->customerNotes_snapshot)
                                                        <div class="bg-emerald-50 rounded-lg p-4">
                                                            <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide">Your Notes</span>
                                                            <p class="text-emerald-800 mt-2 text-sm leading-relaxed">{{ $item->customerNotes_snapshot }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Status History -->
                    @if($order->statusHistory->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                <h2 class="text-xl font-bold text-gray-900">Order Timeline</h2>
                            </div>
                            
                            <div class="relative">
                                @foreach($order->statusHistory->sortBy('created_at') as $history)
                                    <div class="flex gap-4 pb-8 {{ !$loop->last ? 'border-l-2 border-gray-100 ml-6' : '' }}">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 -ml-6 shadow-sm
                                            @switch($history->status)
                                                @case('processing')
                                                    bg-blue-100 border-2 border-blue-200
                                                    @break
                                                @case('out_for_delivery')
                                                    bg-purple-100 border-2 border-purple-200
                                                    @break
                                                @case('delivered')
                                                    bg-emerald-100 border-2 border-emerald-200
                                                    @break
                                                @case('cancelled')
                                                    bg-red-100 border-2 border-red-200
                                                    @break
                                                @default
                                                    bg-gray-100 border-2 border-gray-200
                                            @endswitch">
                                            <div class="w-4 h-4 rounded-full
                                                @switch($history->status)
                                                    @case('processing')
                                                        bg-blue-500
                                                        @break
                                                    @case('out_for_delivery')
                                                        bg-purple-500
                                                        @break
                                                    @case('delivered')
                                                        bg-emerald-500
                                                        @break
                                                    @case('cancelled')
                                                        bg-red-500
                                                        @break
                                                    @default
                                                        bg-gray-500
                                                @endswitch"></div>
                                        </div>
                                        
                                        <div class="flex-1 pb-6">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                                <h3 class="font-semibold text-gray-900">
                                                    {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                                </h3>
                                                <span class="text-sm text-gray-500 font-medium">
                                                    {{ $history->created_at->format('M d, Y h:i A') }}
                                                </span>
                                            </div>
                                            
                                            @if($history->notes)
                                                <p class="text-gray-600 text-sm mt-2 leading-relaxed">{{ $history->notes }}</p>
                                            @endif
                                            
                                            @if($history->updatedBy)
                                                <p class="text-gray-500 text-xs mt-2">
                                                    Updated by {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                <h2 class="text-xl font-bold text-gray-900">Special Instructions</h2>
                            </div>
                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                                <p class="text-amber-800 leading-relaxed">{{ $order->special_instructions }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h2 class="text-lg font-bold text-gray-900">Order Summary</h2>
                        </div>
                        
                        <div class="space-y-4">
                            @php
                                $itemsTotal = $order->orderItems->sum(function($item) {
                                    return ($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested;
                                });
                            @endphp
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Items Total ({{ $order->orderItems->count() }} items)</span>
                                <span class="text-gray-900 font-semibold">₱{{ number_format($itemsTotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="text-gray-900 font-semibold">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            
                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Total Amount</span>
                                    <span class="text-xl font-bold text-emerald-600">₱{{ number_format($order->final_total_amount, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Payment Method</span>
                                        <span class="text-sm text-gray-900 font-semibold">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h2 class="text-lg font-bold text-gray-900">Delivery Information</h2>
                        </div>
                        
                        @if($order->deliveryAddress)
                            <div class="space-y-4">
                                @if($order->deliveryAddress->district)
                                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                                        <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide block mb-1">District</span>
                                        <span class="text-emerald-800 font-semibold">{{ $order->deliveryAddress->district->name }}, San Fernando, Pampanga</span>
                                    </div>
                                @endif
                                
                                <div>
                                    <span class="text-xs font-medium text-gray-700 uppercase tracking-wide block mb-3">Delivery Address</span>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                        <p class="text-gray-900 font-medium">{{ $order->deliveryAddress->address_line1 }}</p>
                                        <p class="text-gray-700">{{ $order->deliveryAddress->address_label }}</p>
                                        <p class="text-gray-600 text-sm">{{ $order->deliveryAddress->delivery_notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->rider)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wide block mb-3">Assigned Rider</span>
                                <div class="flex items-center gap-4 bg-gray-50 rounded-lg p-4">
                                    @if($order->rider->profile_image_url)
                                        <img src="{{ asset('storage/' . $order->rider->profile_image_url ) }}" 
                                             alt="{{ $order->rider->first_name }}"
                                             class="w-12 h-12 rounded-xl object-cover shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center border border-emerald-200">
                                            <span class="text-emerald-700 font-bold">
                                                {{ substr($order->rider->first_name, 0, 1) }}{{ substr($order->rider->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">
                                            {{ $order->rider->first_name }} {{ $order->rider->last_name }}
                                        </p>
                                        @if($order->rider->phone_number)
                                            <p class="text-sm text-gray-600">{{ $order->rider->phone_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Information -->
                    @if($order->payment)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                <h2 class="text-lg font-bold text-gray-900">Payment Details</h2>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600">Amount Paid</span>
                                    <span class="text-gray-900 font-semibold">₱{{ number_format($order->payment->amount_paid, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600">Payment Method</span>
                                    <span class="text-gray-900 font-semibold">{{ ucwords(str_replace('_', ' ', $order->payment->payment_method_used)) }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600">Status</span>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                                        @switch($order->payment->status)
                                            @case('completed')
                                                bg-emerald-50 text-emerald-700 border border-emerald-200
                                                @break
                                            @case('failed')
                                                bg-red-50 text-red-700 border border-red-200
                                                @break
                                            @case('refunded')
                                                bg-blue-50 text-blue-700 border border-blue-200
                                                @break
                                            @default
                                                bg-gray-50 text-gray-700 border border-gray-200
                                        @endswitch">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>
                                
                                @if($order->payment->payment_processed_at)
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600">Processed At</span>
                                        <span class="text-gray-900 text-sm">{{ $order->payment->payment_processed_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                @endif
                                
                                @if($order->payment->gateway_transaction_id)
                                    <div class="pt-4 border-t border-gray-100">
                                        <span class="text-xs font-medium text-gray-700 uppercase tracking-wide block mb-2">Transaction ID</span>
                                        <span class="text-gray-900 font-mono text-xs bg-gray-50 px-3 py-2 rounded-lg break-all">{{ $order->payment->gateway_transaction_id }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection