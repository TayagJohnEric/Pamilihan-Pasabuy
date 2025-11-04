@extends('layout.customer')

@section('title', 'Payment Confirmation')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Page Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Payment Confirmation</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        Review your order before proceeding to payment
                    </p>
                </div>
                
                <!-- Security Badge -->
                <div class="hidden sm:flex items-center space-x-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800">Secure Payment</span>
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
                                @if($orderSummary['rider_selection_type'] === 'choose_rider')
                                    <div class="flex items-center">
                                        @if($orderSummary['selected_rider']->profile_image_url)
                                            <img src="{{ asset('storage/' . $orderSummary['selected_rider']->profile_image_url) }}"
                                                 alt="{{ $orderSummary['selected_rider']->first_name }}"
                                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 mr-3">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-200 mr-3">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">
                                                {{ $orderSummary['selected_rider']->first_name }} {{ $orderSummary['selected_rider']->last_name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">Selected Rider</p>
                                        </div>
                                    </div>
                                @else
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Payment Method
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">GCash Payment</h4>
                                <p class="text-sm text-gray-600">Manual payment via GCash transfer</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Process Notice -->
                    <div class="mt-4 flex items-start gap-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Payment Instructions</p>
                            <p class="text-sm text-yellow-700">You will receive GCash payment details on the next page. After sending payment, upload your proof for verification.</p>
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
                                <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                                <span class="text-2xl font-bold text-gray-900">₱{{ number_format($orderSummary['total_amount'], 2) }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Form -->
                        <form id="payment-form" method="GET" action="{{ route('payment.gcash-instructions') }}" class="space-y-3">
                            <!-- Proceed to Payment Button -->
                            <button type="submit" 
                                    id="payment-submit-btn"
                                    class="w-full bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 text-white px-6 py-4 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span id="payment-btn-text">Proceed to Payment</span>
                            </button>
                            
                            <!-- Back to Checkout -->
                            <a href="{{ route('checkout.index') }}" 
                               class="w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back to Checkout
                            </a>
                        </form>
                        
                        <!-- Payment Method Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Payment via GCash</h3>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-lg font-bold text-white">G</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">GCash Transfer</p>
                                    <p class="text-xs text-gray-600">Secure and instant payment</p>
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

    // Simple form submission with loading state
    $('#payment-form').on('submit', function(e) {
        const submitBtn = $('#payment-submit-btn');
        const btnText = $('#payment-btn-text');
        
        // Show loading state
        submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        btnText.text('Loading Payment Details...');
        
        // Form will submit normally (GET request)
        return true;
    });
});
</script>
@endsection