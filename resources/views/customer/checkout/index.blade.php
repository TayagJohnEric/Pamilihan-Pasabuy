@extends('layout.customer')

@section('title', 'Checkout - Final Decisions')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-[90rem] mx-auto">
        <!-- Page Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Checkout & Final Decisions</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        Review your order and make your final choices
                    </p>
                </div>
                
                <!-- Breadcrumb Navigation -->
                <nav class="hidden sm:flex items-center space-x-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <a href="{{ route('products.index') }}" class="hover:text-gray-700 transition-colors">Products</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('cart.index') }}" class="hover:text-gray-700 transition-colors">Cart</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-900 font-medium">Checkout</span>
                </nav>
            </div>
        </div>

        <!-- Checkout Form -->
        <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" class="space-y-8">
            @csrf
            
            <!-- Hidden field to track payment method for dynamic form submission -->
            <input type="hidden" id="selected-payment-method" name="selected_payment_method" value="cod">
            
            <!-- Desktop Layout: Two Columns -->
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                
                <!-- Main Content Column (Desktop: Left, Mobile: Full Width) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Order Items Review Section -->
                    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Order Items ({{ $itemCount }})
                        </h2>
                        
                        <!-- Items List -->
                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                @php
                                    $isBudgetBased = !is_null($item->customer_budget);
                                @endphp
                                
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product->image_url)
                                            <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                                 alt="{{ $item->product->product_name }}" 
                                                 class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border border-gray-200">
                                        @else
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
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
                                                    Budget-Based Purchase
                                                </span>
                                                @if($item->customer_notes)
                                                    <p class="text-xs text-gray-600 mt-1">Notes: {{ $item->customer_notes }}</p>
                                                @endif
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
                        
                        @if($hasBudgetItems)
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Budget Items Notice</p>
                                        <p class="text-sm text-blue-700 mt-1">Some items are budget-based purchases. The vendor will confirm the final quantity and pricing based on your budget.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Delivery Address Section -->
                    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Delivery Address
                        </h2>
                        
                        @if($savedAddresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($savedAddresses as $address)
                                    <div class="relative">
                                        <input type="radio" 
                                               id="address_{{ $address->id }}" 
                                               name="delivery_address_id" 
                                               value="{{ $address->id }}" 
                                               class="peer sr-only"
                                               data-delivery-fee="{{ $address->district->delivery_fee }}"
                                               data-district-name="{{ $address->district->name }}"
                                               {{ $loop->first ? 'checked' : '' }}
                                               required>
                                        
                                        <label for="address_{{ $address->id }}" 
                                               class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                            <div class="flex-grow">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-medium text-gray-900">{{ $address->address_label }}</h4>
                                                    @if($address->is_default)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Default
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-600 mb-1">{{ $address->address_line1 }}</p>
                                                <p class="text-sm text-gray-500">{{ $address->district->name }}</p>
                                                @if($address->delivery_notes)
                                                    <p class="text-xs text-gray-500 mt-1">Notes: {{ $address->delivery_notes }}</p>
                                                @endif
                                                <p class="text-sm font-medium text-blue-600 mt-2">
                                                    Delivery Fee: ₱{{ number_format($address->district->delivery_fee, 2) }}
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <p class="text-gray-600 mb-4">No saved addresses found.</p>
                                <a href="{{ route('customer.saved_addresses.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Address
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Payment Method Section -->
                    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 013 3z"/>
                            </svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach($paymentMethods as $value => $label)
                                <div class="relative">
                                    <input type="radio" 
                                           id="payment_{{ $value }}" 
                                           name="payment_method" 
                                           value="{{ $value }}" 
                                           class="peer sr-only"
                                           {{ $loop->first ? 'checked' : '' }}
                                           required>
                                    
                                    <label for="payment_{{ $value }}" 
                                           class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="flex items-center">
                                            @if($value === 'cod')
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $label }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    @if($value === 'cod')
                                                        Pay when your order is delivered
                                                    @else
                                                        Pay securely online before delivery
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Rider Selection Section -->
                    <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Rider Selection
                        </h2>
                        
                        <!-- Rider Selection Type -->
                        <div class="mb-6">
                            <div class="space-y-3">
                                <div class="relative">
                                    <input type="radio" 
                                           id="system_assign" 
                                           name="rider_selection_type" 
                                           value="system_assign" 
                                           class="peer sr-only"
                                           checked
                                           required>
                                    
                                    <label for="system_assign" 
                                           class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Assign One For Me</h4>
                                                <p class="text-sm text-gray-600">Let the system find an available rider for you automatically</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" 
                                           id="choose_rider" 
                                           name="rider_selection_type" 
                                           value="choose_rider" 
                                           class="peer sr-only"
                                           required>
                                    
                                    <label for="choose_rider" 
                                           class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">Choose My Rider</h4>
                                                <p class="text-sm text-gray-600">Select from available riders based on ratings and preferences</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rider Selection List (shown when "Choose My Rider" is selected) -->
                        <div id="rider-selection-list" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Available Riders</h3>
                            
                            @if($availableRiders->count() > 0)
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach($availableRiders as $rider)
                                        <div class="relative">
                                            <input type="radio" 
                                                   id="rider_{{ $rider->id }}" 
                                                   name="selected_rider_id" 
                                                   value="{{ $rider->id }}" 
                                                   class="peer sr-only rider-radio">
                                            
                                            <label for="rider_{{ $rider->id }}" 
                                                   class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                                <div class="flex items-center w-full">
                                                    <!-- Rider Avatar -->
                                                    <div class="flex-shrink-0 mr-4">
                                                        @if($rider->profile_image_url)
                                                            <img src="{{ asset('storage/' . $rider->profile_image_url) }}"
                                                                 alt="{{ $rider->first_name }} {{ $rider->last_name }}"
                                                                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-200">
                                                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Rider Info -->
                                                    <div class="flex-grow">
                                                        <h4 class="font-medium text-gray-900">
                                                            {{ $rider->first_name }} {{ $rider->last_name }}
                                                        </h4>
                                                        
                                                        <div class="flex items-center mt-1">
                                                            @if($rider->rider->average_rating)
                                                                <div class="flex items-center mr-3">
                                                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                    </svg>
                                                                    <span class="text-sm text-gray-600">{{ number_format($rider->rider->average_rating, 1) }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            <span class="text-sm text-gray-600">
                                                                {{ $rider->rider->total_deliveries }} deliveries
                                                            </span>
                                                            
                                                            @if($rider->rider->vehicle_type)
                                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                                    {{ ucfirst($rider->rider->vehicle_type) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-gray-600">No riders are currently available.</p>
                                    <p class="text-sm text-gray-500 mt-1">Please try "Assign One For Me" or try again later.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary Column (Desktop: Right Sidebar, Mobile: Full Width) -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="lg:sticky lg:top-6">
                        <div class="bg-white rounded-lg lg:rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Order Summary
                            </h2>
                            
                            <!-- Summary Items -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Items ({{ $itemCount }})</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between text-sm" id="delivery-fee-row">
                                    <span class="text-gray-600">Delivery Fee</span>
                                    <span class="font-medium text-gray-900" id="delivery-fee-amount">
                                        @if($savedAddresses->count() > 0)
                                            ₱{{ number_format($savedAddresses->first()->district->delivery_fee, 2) }}
                                        @else
                                            ₱0.00
                                        @endif
                                    </span>
                                </div>
                                
                                @if($hasBudgetItems)
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
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-gray-900" id="total-amount">
                                        @if($savedAddresses->count() > 0)
                                            ₱{{ number_format($subtotal + $savedAddresses->first()->district->delivery_fee, 2) }}
                                        @else
                                            ₱{{ number_format($subtotal, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <!-- Main Submit Button -->
                                <button type="submit" 
                                        id="checkout-submit-btn"
                                        class="w-full bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white px-6 py-4 rounded-lg font-semibold text-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span id="submit-btn-text">Place Order</span>
                                </button>
                                
                                <!-- Back to Cart -->
                                <a href="{{ route('cart.index') }}" 
                                   class="w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 inline-flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced JavaScript with Toast Notifications -->
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
            warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                       <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                     </svg>`
        };

        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-amber-50 border-amber-200 text-amber-800'
        };

        const iconColors = {
            success: 'text-green-500',
            error: 'text-red-500',
            warning: 'text-amber-500'
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
                                        class="inline-flex rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
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

    @if(session('warning'))
        createToast("{{ session('warning') }}", 'warning');
    @endif

    @if(session('error'))
        createToast("{{ session('error') }}", 'error');
    @endif

    // Handle rider selection type change
    $('input[name="rider_selection_type"]').on('change', function() {
        const selectedType = $(this).val();
        const riderList = $('#rider-selection-list');
        const riderRadios = $('.rider-radio');
        
        if (selectedType === 'choose_rider') {
            riderList.removeClass('hidden');
            riderRadios.prop('required', true);
        } else {
            riderList.addClass('hidden');
            riderRadios.prop('checked', false).prop('required', false);
        }
        
        updateSubmitButtonText();
    });

    // Handle address selection change to update delivery fee
    $('input[name="delivery_address_id"]').on('change', function() {
        const selectedAddress = $(this);
        const deliveryFee = parseFloat(selectedAddress.data('delivery-fee'));
        const districtName = selectedAddress.data('district-name');
        
        updateDeliveryFee(deliveryFee);
    });

    // Update delivery fee and total
    function updateDeliveryFee(deliveryFee) {
        const subtotal = {{ $subtotal }};
        const total = subtotal + deliveryFee;
        
        $('#delivery-fee-amount').text('₱' + deliveryFee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#total-amount').text('₱' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    // Update submit button text based on payment method
    $('input[name="payment_method"]').on('change', function() {
        updateSubmitButtonText();
        updatePaymentMethodField();
    });

    function updateSubmitButtonText() {
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        const submitBtnText = $('#submit-btn-text');
        
        if (paymentMethod === 'online_payment') {
            submitBtnText.text('Proceed to Payment');
        } else {
            submitBtnText.text('Place Order');
        }
    }

    function updatePaymentMethodField() {
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        $('#selected-payment-method').val(paymentMethod);
    }

    // Form validation and submission
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        const hasAddresses = $('input[name="delivery_address_id"]').length > 0;
        const deliveryAddress = $('input[name="delivery_address_id"]:checked').val();
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        const riderSelectionType = $('input[name="rider_selection_type"]:checked').val();
        const selectedRiderId = $('input[name="selected_rider_id"]:checked').val();

        if (!hasAddresses) { createToast('Please add a delivery address first.', 'warning'); return; }
        if (!deliveryAddress) { createToast('Please select a delivery address.', 'warning'); return; }
        if (!paymentMethod) { createToast('Please choose a payment method.', 'warning'); return; }
        if (riderSelectionType === 'choose_rider' && !selectedRiderId) { createToast('Please select a rider.', 'warning'); return; }

        // Disable submit to prevent duplicate submissions
        const submitBtn = $('#checkout-submit-btn');
        submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        $('#submit-btn-text').text(paymentMethod === 'online_payment' ? 'Redirecting...' : 'Processing...');

        // Determine the action URL and method based on payment method
        let actionUrl = '{{ route('checkout.process') }}';
        let formMethod = 'POST';
        
        if (paymentMethod === 'online_payment') {
            actionUrl = '{{ route('payment.confirmation') }}';
            formMethod = 'POST'; // Both routes use POST
        }

        // Update the form's action attribute and method
        $('#checkout-form').attr('action', actionUrl);
        $('#checkout-form').attr('method', formMethod);

        // Submit the form
        e.currentTarget.submit();
    });

    // Initialize button text and payment method field on page load
    updateSubmitButtonText();
    updatePaymentMethodField();
});
</script>
@endsection