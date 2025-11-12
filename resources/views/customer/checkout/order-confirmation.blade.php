@extends('layout.customer')

@section('title', 'Order Confirmation')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-[90rem] mx-auto">
        
        <!-- Page Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Order Confirmation</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        Review your order before final confirmation
                    </p>
                </div>
                
                <!-- COD Badge -->
                <div class="hidden sm:flex items-center space-x-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800">Cash on Delivery</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content - Order Review -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Order Items Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11h8"/>
                        </svg>
                        Order Items ({{ $orderSummary['item_count'] }})
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($orderSummary['cart_items'] as $item)
                            @php
                                $isBudgetBased = !is_null($item->customer_budget);
                            @endphp
                            
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                             alt="{{ $item->product->product_name }}" 
                                             class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Info -->
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-gray-900">{{ $item->product->product_name }}</h4>
                                    <p class="text-sm text-gray-600">by {{ $item->product->vendor->vendor_name }}</p>
                                    
                                    @if($isBudgetBased)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Budget: ₱{{ number_format($item->customer_budget, 2) }}
                                            </span>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-600 mt-1">
                                            Qty: {{ $item->quantity }} {{ $item->product->unit }} × ₱{{ number_format($item->product->price, 2) }}
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Price -->
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">
                                        ₱{{ number_format($item->subtotal, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Delivery Details Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Delivery Details
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Delivery Address -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Delivery Address</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900">{{ $orderSummary['delivery_address']->address_label }}</h4>
                                <p class="text-gray-600 mt-1">{{ $orderSummary['delivery_address']->address_line1 }}</p>
                                <p class="text-sm text-gray-500">{{ $orderSummary['delivery_address']->district->name }}</p>
                                @if($orderSummary['delivery_address']->delivery_notes)
                                    <p class="text-xs text-gray-500 mt-2">Notes: {{ $orderSummary['delivery_address']->delivery_notes }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Rider Assignment -->
                        <div>
                            <h3 class="font-medium text-gray-900 mb-3">Rider Assignment</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if($orderSummary['rider_selection_type'] === 'system_assign')
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">System Assignment</h4>
                                            <p class="text-sm text-gray-600">Rider will be assigned automatically</p>
                                        </div>
                                    </div>
                                @elseif($orderSummary['rider_selection_type'] === 'choose_rider' && !empty($orderSummary['selected_rider']))
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($orderSummary['selected_rider']->profile_image_url)
                                                <img src="{{ asset('storage/' . $orderSummary['selected_rider']->profile_image_url) }}"
                                                     alt="{{ $orderSummary['selected_rider']->first_name }} {{ $orderSummary['selected_rider']->last_name }}"
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                            @else
                                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-200">
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                {{ $orderSummary['selected_rider']->first_name }} {{ $orderSummary['selected_rider']->last_name }}
                                            </h4>
                                            <div class="flex items-center text-xs text-gray-600 mt-0.5">
                                                @if(optional($orderSummary['selected_rider']->rider)->average_rating)
                                                    <div class="flex items-center mr-3">
                                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                        <span>{{ number_format($orderSummary['selected_rider']->rider->average_rating, 1) }}</span>
                                                    </div>
                                                @endif
                                                @if(optional($orderSummary['selected_rider']->rider)->total_deliveries !== null)
                                                    <span>{{ $orderSummary['selected_rider']->rider->total_deliveries }} deliveries</span>
                                                @endif
                                                @if(optional($orderSummary['selected_rider']->rider)->vehicle_type)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($orderSummary['selected_rider']->rider->vehicle_type) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-600">Rider details will be available soon.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 013 3z"/>
                        </svg>
                        Payment Method
                    </h2>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Cash on Delivery (COD)</h4>
                                <p class="text-sm text-gray-600">Pay when your order is delivered to your doorstep</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- COD Notice -->
                    <div class="mt-4 flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-800">Payment Reminder</p>
                            <p class="text-sm text-amber-700">Please have the exact amount ready when your order arrives. Our rider will collect ₱{{ number_format($orderSummary['total_amount'], 2) }} upon delivery.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Order Summary
                        </h2>
                        
                        <!-- Summary Items -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Items ({{ $orderSummary['item_count'] }})</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($orderSummary['subtotal'], 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($orderSummary['delivery_fee'], 2) }}</span>
                            </div>
                            
                            @if($orderSummary['has_budget_items'])
                                <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-blue-700">Final total may vary based on vendor confirmation of budget items</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total to Pay on Delivery</span>
                                <span class="text-2xl font-bold text-green-600">₱{{ number_format($orderSummary['total_amount'], 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Special Instructions -->
                        <form id="cod-form" method="POST" action="{{ route('payment.process-cod') }}" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Special Instructions (Optional)
                                </label>
                                <textarea id="special_instructions" 
                                          name="special_instructions" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                          placeholder="Any special instructions for your order or delivery..."></textarea>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <!-- Confirm Order Button -->
                                <button type="submit" 
                                        id="cod-submit-btn"
                                        class="w-full bg-gradient-to-r from-green-600 via-green-600 to-emerald-600 text-white px-6 py-4 rounded-lg font-semibold text-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span id="cod-btn-text">Confirm Order</span>
                                </button>
                                
                                <!-- Back to Checkout -->
                                <a href="{{ route('checkout.index') }}" 
                                   class="w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back to Checkout
                                </a>
                            </div>
                        </form>
                        
                        <!-- COD Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Cash on Delivery Information</h3>
                            <div class="space-y-2">
                                <div class="flex items-start p-3 bg-green-50 rounded-lg">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-green-700">Pay only when your order arrives at your doorstep</p>
                                </div>
                                <div class="flex items-start p-3 bg-green-50 rounded-lg">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-green-700">Inspect your order before making payment</p>
                                </div>
                                <div class="flex items-start p-3 bg-green-50 rounded-lg">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-green-700">Have exact change ready for faster service</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Toast Notification System
    function createToast(message, type = 'success', duration = 4000) {
        const toastContainer = $('#toast-container');
        const toastId = 'toast-' + Date.now();
        
        const icons = {
            success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>`,
            error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                   </svg>`,
            info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                  </svg>`
        };

        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };

        const iconColors = {
            success: 'text-green-500',
            error: 'text-red-500',
            info: 'text-blue-500'
        };

        const toast = $(`
            <div id="${toastId}" class="transform transition-all duration-300 ease-out translate-x-full opacity-0 pointer-events-auto">
                <div class="w-full max-w-sm ${colors[type]} border rounded-lg shadow-lg">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 ${iconColors[type]}">
                                ${icons[type]}
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium leading-5">${message}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button type="button" onclick="removeToast('${toastId}')" 
                                        class="inline-flex rounded-md text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                    <span class="sr-only">Close</span>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        toastContainer.append(toast);

        // Animate in
        setTimeout(() => {
            $(`#${toastId}`).removeClass('translate-x-full opacity-0').addClass('translate-x-0 opacity-100');
        }, 100);

        // Auto remove
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    }

    window.removeToast = function(toastId) {
        const toast = $(`#${toastId}`);
        if (toast.length) {
            toast.removeClass('translate-x-0 opacity-100').addClass('translate-x-full opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    };

    // Show session messages
    @if(session('success'))
        createToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        createToast("{{ session('error') }}", 'error');
    @endif

    @if(session('info'))
        createToast("{{ session('info') }}", 'info');
    @endif

    // Form submission handling
    $('#cod-form').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#cod-submit-btn');
        const btnText = $('#cod-btn-text');
        
        // Disable submit button to prevent duplicate submissions
        submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        btnText.text('Processing Order...');
        
        // Show processing message
        createToast('Confirming your order...', 'info', 2000);
        
        // Submit the form after a brief delay for UX
        setTimeout(() => {
            e.currentTarget.submit();
        }, 1000);
    });
});
</script>
@endsection