@extends('layout.customer')

@section('title', 'Shopping Cart')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-[90rem] mx-auto ">
        <!-- Page Header -->
        <div class="mb-2">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Shopping Cart</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-600">  {{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }} in your cart</p>
                    </p>
                </div>
                
               <!-- Breadcrumb Navigation (hidden on mobile) -->
                <nav class="hidden sm:flex items-center space-x-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <a href="{{ route('products.index') }}" class="hover:text-green-600 transition-colors duration-200">Products</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-900 font-medium">Cart</span>
                </nav>
            </div>
        </div>

        @if($cartItems->count() > 0)
            <!-- Desktop Layout: Two Columns -->
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Cart Items Column -->
                <div class="lg:col-span-8">
                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            @php
                                $isBudgetBased = !is_null($item->customer_budget);
                            @endphp
                            
                            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                                <div class="p-6">
                                    <!-- Product Header -->
                                    <div class="flex gap-4 mb-1">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if($item->product->image_url)
                                                <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                                    alt="{{ $item->product->product_name }}" 
                                                    class="w-20 h-20 lg:w-24 lg:h-24 object-cover rounded-lg border border-gray-100">
                                            @else
                                                <div class="w-20 h-20 lg:w-24 lg:h-24 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100">
                                                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-grow min-w-0">
                                            <h3 class="text-sm sm:text-lg font-medium text-gray-900 line-clamp-2 mb-1">
                                                {{ $item->product->product_name }}
                                            </h3>
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1">
                                                by <span class="font-medium text-gray-700">{{ $item->product->vendor->vendor_name }}</span>
                                            </p>

                                            <!-- Subtotal and Price × Quantity under vendor -->
                                            @if($isBudgetBased)
                                                <div class="text-sm sm:text-base font-bold text-green-600">
                                                    ₱{{ number_format($item->customer_budget, 2) }}
                                                </div>
                                                <div class="text-[10px] sm:text-xs text-gray-500 mb-2">Your Budget</div>
                                            @else
                                                <div class="text-sm sm:text-base font-bold text-gray-900">
                                                    ₱{{ number_format($item->subtotal, 2) }}
                                                </div>
                                                <div class="text-[10px] sm:text-xs text-gray-500 mb-2">
                                                    ₱{{ number_format($item->product->price, 2) }} × {{ $item->quantity }} {{ $item->product->unit }}
                                                </div>
                                            @endif

                                            <!-- Status Badges -->
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($isBudgetBased)
                                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-1 sm:mr-2"></div>
                                                        Budget-Based
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-1 sm:mr-2"></div>
                                                        Fixed Price
                                                    </span>
                                                @endif
                                                
                                                @if($item->product->is_budget_based && !$isBudgetBased)
                                                   <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-xs font-medium 
                                                                bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Budget Option Available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Item Controls Section -->
                                <div class="border-t border-gray-50 pt-4 sm:pt-6">
                                    @if(!$isBudgetBased)
                                        <!-- Standard Item Controls -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-6">
                                            <!-- Quantity Control -->
                                            <div class="w-full sm:w-auto sm:flex-grow sm:max-w-xs">
                                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                                                    <button type="button" 
                                                            onclick="decreaseCartQuantity({{ $item->id }})"
                                                            class="px-3 sm:px-4 py-2.5 sm:py-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-inset"
                                                            aria-label="Decrease quantity">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                        </svg>
                                                    </button>
                                                    <input type="number" 
                                                        id="quantity-{{ $item->id }}"
                                                        name="quantity" 
                                                        value="{{ $item->quantity }}" 
                                                        min="1" 
                                                        max="{{ $item->product->is_budget_based ? 999 : $item->product->quantity_in_stock }}"
                                                        class="flex-1 sm:w-20 px-3 py-2.5 sm:py-3 text-center border-0 focus:ring-0 focus:outline-none bg-white text-gray-900"
                                                        data-price="{{ $item->product->price }}"
                                                        data-item-id="{{ $item->id }}"
                                                        aria-label="Quantity">
                                                    <button type="button" 
                                                            onclick="increaseCartQuantity({{ $item->id }})"
                                                            class="px-3 sm:px-4 py-2.5 sm:py-3 text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-inset"
                                                            aria-label="Increase quantity">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <button type="button" 
                                                    onclick="removeItem({{ $item->id }})"
                                                    class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 text-gray-600 bg-white rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Remove
                                            </button>
                                        </div>
                                    @else
                                        <!-- Budget-based Item Controls -->
                                        <div class="space-y-4 sm:space-y-6">
                                            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                                                <!-- Budget Input -->
                                                <div>
                                                    <label for="budget-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-2 sm:mb-3">
                                                        Budget Amount <span class="text-green-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                                            <span class="text-gray-500">₱</span>
                                                        </div>
                                                        <input type="number" 
                                                            id="budget-{{ $item->id }}"
                                                            name="customer_budget" 
                                                            value="{{ $item->customer_budget }}" 
                                                            step="0.01" 
                                                            min="0.01"
                                                            max="999999.99"
                                                            required
                                                            class="block w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white"
                                                            data-item-id="{{ $item->id }}"
                                                            aria-label="Budget amount">
                                                    </div>
                                                    @if($item->product->indicative_price_per_unit)
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            Indicative: ~₱{{ number_format($item->product->indicative_price_per_unit, 2) }}/{{ $item->product->unit }}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <!-- Special Notes -->
                                                <div>
                                                    <label for="notes-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-2 sm:mb-3">
                                                        Special Notes <span class="text-gray-400">(Optional)</span>
                                                    </label>
                                                    <textarea id="notes-{{ $item->id }}"
                                                            name="customer_notes" 
                                                            rows="3"
                                                            placeholder="Special requests or preferences..."
                                                            class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none bg-white"
                                                            data-item-id="{{ $item->id }}">{{ $item->customer_notes }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Remove Button for Budget Items -->
                                            <div class="flex justify-end">
                                                <button type="button" 
                                                        onclick="removeItem({{ $item->id }})"
                                                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 text-gray-600 bg-white rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full sm:w-auto">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hidden Remove Form -->
                                    <form id="remove-form-{{ $item->id }}" method="POST" action="{{ route('cart.destroy', $item) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>

                                    <!-- Budget Notes Display -->
                                    @if($isBudgetBased && $item->customer_notes)
                                        <div class="mt-6 p-4 bg-green-50 border border-green-100 rounded-xl">
                                            <div class="flex items-start">
                                                <div class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0">
                                                    <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-green-800">Special Notes:</p>
                                                    <p class="text-sm text-green-700 mt-1">{{ $item->customer_notes }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Budget Conversion Option -->
                                    @if($item->product->is_budget_based && !$isBudgetBased)
                                      <div class="mt-6 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-black">Want a custom quote instead?</p>
                                                <p class="text-xs text-emerald-700 mt-1">Switch to budget-based pricing for better flexibility</p>
                                            </div>
                                           <button type="button" 
                                                onclick="showBudgetConversion({{ $item->id }})"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium 
                                                    hover:bg-emerald-200 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 
                                                    whitespace-nowrap">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-philippine-peso-icon">
                                                    <path d="M20 11H4"/>
                                                    <path d="M20 7H4"/>
                                                    <path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/>
                                                </svg>

                                                <span>Switch to Budget</span>
                                            </button>

                                        </div>
                                    </div>


                                        <!-- Budget Conversion Form -->
                                        <div id="budget-conversion-{{ $item->id }}" class="hidden mt-6 p-6 bg-green-50 border border-green-100 rounded-xl" data-item-id="{{ $item->id }}">
                                            <form method="POST" action="{{ route('cart.update', $item) }}" class="space-y-6">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                                            Your Budget <span class="text-green-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                                <span class="text-gray-500">₱</span>
                                                            </div>
                                                            <input type="number" 
                                                                   name="customer_budget" 
                                                                   step="0.01" 
                                                                   min="0.01"
                                                                   max="999999.99"
                                                                   required
                                                                   placeholder="Enter your budget"
                                                                   class="block w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                                        </div>
                                                    </div>
                                                    
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                                            Special Notes <span class="text-gray-400">(Optional)</span>
                                                        </label>
                                                        <textarea name="customer_notes" 
                                                                  rows="3"
                                                                  placeholder="Any special requirements..."
                                                                  class="block w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none bg-white"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                   <button type="submit" 
                                                            class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 text-white rounded-lg text-sm font-medium 
                                                                hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                            
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                            </svg>

                                                            Convert to Budget
                                                        </button>

                                                    <button type="button" 
                                                            onclick="hideBudgetConversion({{ $item->id }})"
                                                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-gray-700 bg-white rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary Column -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <!-- Desktop Summary -->
                    <div class="hidden lg:block sticky top-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-light text-gray-900 mb-6">Order Summary</h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Items ({{ $cartItems->count() }})</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                
                                @if($cartItems->where('customer_budget', '!=', null)->count() > 0)
                                    <div class="flex items-start gap-3 p-4 bg-green-50 border border-green-100 rounded-xl">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-sm text-green-700">Budget-based items are subject to vendor confirmation</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="border-t border-gray-100 pt-6 mb-6">
                                <div class="flex justify-between">
                                    <span class="text-lg font-light text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                               <a href="{{ route('checkout.index') }}"
                                        class="w-full bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white px-6 py-4 rounded-xl font-medium text-center transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 inline-flex items-center justify-center hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 003 3z"/>
                                            </svg>
                                            Proceed to Checkout
                                        </a>                    
                                <button onclick="clearCart()" 
                                        class="w-full border border-gray-200 text-gray-700 px-6 py-4 rounded-xl font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Sticky Summary -->
            <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 shadow-xl z-20">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xl font-bold text-gray-900">₱{{ number_format($subtotal, 2) }}</p>
                        <p class="text-sm text-gray-600">{{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}</p>
                    </div>
                    
                    @if($cartItems->where('customer_budget', '!=', null)->count() > 0)
                        <div class="flex items-center text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs">Quote pending</span>
                        </div>
                    @endif
                </div>
                
                <div class="flex gap-3">
                    <button onclick="clearCart()" 
                            class="flex-1 border border-gray-200 text-gray-700 px-4 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Clear Cart
                    </button>
                    
                   <a href="{{ route('checkout.index') }}" 
                    class="flex-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium text-center 
                            hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 
                            focus:ring-offset-2 inline-flex items-center justify-center gap-2">

                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                            class="lucide lucide-shopping-cart-icon">
                            <circle cx="8" cy="21" r="1"/>
                            <circle cx="19" cy="21" r="1"/>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>

                        <span>Checkout</span>
                    </a>

                </div>
            </div>

            <!-- Mobile Bottom Padding -->
            <div class="lg:hidden h-32"></div>

            <!-- Hidden Clear Cart Form -->
            <form id="clear-cart-form" method="POST" action="{{ route('cart.clear') }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>

        @else
            <!-- Empty Cart State -->
            <div class=" p-12 text-center">
                <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center ">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" w-12 h-12 text-gray-300 lucide lucide-shopping-cart-icon lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-sm text-gray-600 mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet. Start shopping to fill it up!</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                 <a href="{{ route('products.index') }}" 
                    class="inline-flex items-center justify-center px-3 sm:px-8 py-2 sm:py-4 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600
                            text-white rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500
                            focus:ring-offset-2 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700">

                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>

                        <span class="text-xs sm:text-base">Start Shopping</span>
                 </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced JavaScript with Toast Notifications -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Prevent double submissions
    let isSubmitting = false;
    
    // Enhanced Toast Notification System - Fixed visibility
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

    // Clear existing session messages after showing toasts
    @if(session('success'))
        createToast("{{ session('success') }}", 'success');
    @endif

    @if(session('warning'))
        createToast("{{ session('warning') }}", 'warning');
    @endif

    @if(session('error'))
        createToast("{{ session('error') }}", 'error');
    @endif

    // For standard product (non-budget based) - auto-update on quantity change
    $('input[name="quantity"]').on('change', function (e) {
        if (isSubmitting) return;
        
        let input = $(this);
        let quantity = input.val();
        let cartItemId = input.data('item-id') || input.attr('id').split('-')[1];

        if (!cartItemId || !quantity) return;

        isSubmitting = true;
        input.prop('disabled', true).addClass('opacity-50');

        $.ajax({
            url: `/cart/${cartItemId}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity
            },
            success: function (response) {
                if (response.success && response.cart_summary) {
                    updateCartSummary(response.cart_summary);
                    
                    // Update cart badge
                    if (response.cart_summary.item_count !== undefined && window.updateCartBadge) {
                        window.updateCartBadge(response.cart_summary.item_count);
                    }
                    
                    // Show subtle success indication
                    input.addClass('border-green-400 focus:border-green-500 focus:ring-green-500').removeClass('border-red-400');
                    createToast('Cart updated successfully', 'success', 2000);
                    
                    setTimeout(() => {
                        input.removeClass('border-green-400 focus:border-green-500 focus:ring-green-500');
                    }, 2000);
                }
            },
            error: function (xhr) {
                handleAjaxError(xhr, input);
            },
            complete: function() {
                isSubmitting = false;
                input.prop('disabled', false).removeClass('opacity-50');
            }
        });
    });

    // For budget-based products - auto-update on budget or notes change
    $('input[name="customer_budget"], textarea[name="customer_notes"]').on('change', function (e) {
        if (isSubmitting) return;
        
        let input = $(this);
        let cartItemId = input.data('item-id');
        
        if (!cartItemId) return;
        
        let budgetInput = $(`input[name="customer_budget"][data-item-id="${cartItemId}"]`);
        let notesInput = $(`textarea[name="customer_notes"][data-item-id="${cartItemId}"]`);
        
        // Clear any pending timeout
        clearTimeout(window.budgetUpdateTimeout);
        
        // Validate budget input
        if (budgetInput.length > 0) {
            const value = parseFloat(budgetInput.val());
            const min = parseFloat(budgetInput.attr('min'));
            const max = parseFloat(budgetInput.attr('max'));
            
            if (isNaN(value) || value < min || value > max) {
                createToast('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString(), 'error');
                budgetInput.focus().addClass('border-red-400 focus:border-red-500 focus:ring-red-500');
                return;
            }
        }
        
        // Auto-update after a short delay
        window.budgetUpdateTimeout = setTimeout(function() {
            if (isSubmitting) return;
            
            isSubmitting = true;
            input.prop('disabled', true).addClass('opacity-50');
            
            $.ajax({
                url: `/cart/${cartItemId}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    customer_budget: budgetInput.val(),
                    customer_notes: notesInput.val()
                },
                success: function (response) {
                    if (response.success && response.cart_summary) {
                        updateCartSummary(response.cart_summary);
                        
                        // Update cart badge
                        if (response.cart_summary.item_count !== undefined && window.updateCartBadge) {
                            window.updateCartBadge(response.cart_summary.item_count);
                        }
                        
                        // Show subtle success indication
                        input.addClass('border-green-400 focus:border-green-500 focus:ring-green-500').removeClass('border-red-400');
                        createToast('Budget updated successfully', 'success', 2000);
                        
                        setTimeout(() => {
                            input.removeClass('border-green-400 focus:border-green-500 focus:ring-green-500');
                        }, 2000);
                    }
                },
                error: function (xhr) {
                    handleAjaxError(xhr, input);
                },
                complete: function() {
                    isSubmitting = false;
                    input.prop('disabled', false).removeClass('opacity-50');
                }
            });
        }, 1000);
    });

    // Handle budget conversion form submission with enhanced error handling
    $('form').on('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        const form = $(this);
        const budgetInput = form.find('input[name="customer_budget"]');
        const isBudgetConversion = form.closest('[id^="budget-conversion-"]').length > 0;
        
        // Validate budget input if present
        if (budgetInput.length > 0) {
            const value = parseFloat(budgetInput.val());
            const min = parseFloat(budgetInput.attr('min'));
            const max = parseFloat(budgetInput.attr('max'));
            
            if (isNaN(value) || value < min || value > max) {
                e.preventDefault();
                createToast('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString(), 'error');
                budgetInput.focus().addClass('border-red-400 focus:border-red-500 focus:ring-red-500');
                return false;
            }
        }
        
        // Handle budget conversion forms with AJAX
        if (isBudgetConversion) {
            e.preventDefault();
            
            isSubmitting = true;
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).addClass('opacity-75').html(`
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Converting...
            `);
            
            const formData = new FormData(form[0]);
            const cartItemId = form.closest('[id^="budget-conversion-"]').data('item-id');
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        createToast(response.message || 'Item converted successfully!', 'success');
                        
                        // Hide the conversion form
                        hideBudgetConversion(cartItemId);
                        
                        // Update cart summary if available
                        if (response.cart_summary) {
                            updateCartSummary(response.cart_summary);
                            
                            // Update cart badge
                            if (response.cart_summary.item_count !== undefined && window.updateCartBadge) {
                                window.updateCartBadge(response.cart_summary.item_count);
                            }
                        }
                        
                        // Reload the page to show the updated cart structure
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        createToast(response.message || 'Conversion failed. Please try again.', 'error');
                    }
                },
                error: function (xhr) {
                    handleAjaxError(xhr, budgetInput);
                },
                complete: function() {
                    isSubmitting = false;
                    submitBtn.prop('disabled', false).removeClass('opacity-75').html(originalText);
                }
            });
            
            return false;
        }
    });

    // Handle cart item removal with AJAX
    $('button[onclick*="remove-form"]').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const formId = button.attr('onclick').match(/document\.getElementById\('([^']+)'\)/)[1];
        const form = $(`#${formId}`);
        
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            // Show loading state
            button.prop('disabled', true).html(`
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `);
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    createToast('Item removed from cart', 'success');
                    
                    // Update cart badge by fetching current count
                    if (window.fetchCartCount) {
                        window.fetchCartCount();
                    }
                    
                    // Reload page to reflect changes
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    createToast('Failed to remove item. Please try again.', 'error');
                    button.prop('disabled', false).html(`
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    `);
                }
            });
        }
    });

    // Store previous values for error handling
    $('input[name="quantity"]').each(function() {
        $(this).data('previous-value', $(this).val());
    });

    $('input[name="quantity"]').on('focus', function() {
        $(this).data('previous-value', $(this).val());
    });

    // Enhanced error handling function
    function handleAjaxError(xhr, inputElement) {
        let errorMessage = 'An error occurred. Please try again.';
        
        if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                errorMessage = Object.values(errors).flat().join('\n');
            }
        } else if (xhr.status === 404) {
            errorMessage = 'Item not found. Please refresh the page.';
        } else if (xhr.responseJSON?.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        createToast(errorMessage, 'error');
        
        if (inputElement) {
            inputElement.addClass('border-red-400 focus:border-red-500 focus:ring-red-500');
            // Reset to previous value for quantity inputs
            if (inputElement.attr('name') === 'quantity') {
                const previousValue = inputElement.data('previous-value');
                if (previousValue) {
                    inputElement.val(previousValue);
                }
            }
        }
    }

    // Function to update cart summary without page reload
    function updateCartSummary(cartSummary) {
        // Update the cart total in both desktop and mobile summaries
        $('.text-xl.font-bold.text-gray-900, .text-lg.font-bold.text-gray-900').each(function() {
            if ($(this).text().includes('₱')) {
                $(this).text('₱' + parseFloat(cartSummary.subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            }
        });
        
        // Update item counts
        $('[class*="text-"][class*="gray-600"]').each(function() {
            const text = $(this).text();
            if (text.includes('item')) {
                $(this).text(cartSummary.item_count + (cartSummary.item_count === 1 ? ' item' : ' items'));
            }
        });
        
        // Update cart count in header if exists
        $('.cart-count').text(cartSummary.item_count);
    }
});

// UI interaction functions - Removed confirmation alerts
function removeItem(itemId) {
    // Direct form submission without confirmation
    document.getElementById('remove-form-' + itemId).submit();
}

function clearCart() {
    // Direct form submission without confirmation
    document.getElementById('clear-cart-form').submit();
}

function showBudgetConversion(itemId) {
    const element = document.getElementById('budget-conversion-' + itemId);
    element.classList.remove('hidden');
    
    // Smooth scroll to conversion form
    element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Focus on budget input
    setTimeout(() => {
        const budgetInput = element.querySelector('input[name="customer_budget"]');
        if (budgetInput) budgetInput.focus();
    }, 500);
}

function hideBudgetConversion(itemId) {
    document.getElementById('budget-conversion-' + itemId).classList.add('hidden');
}

function increaseCartQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);

    if (current < max) {
        input.value = current + 1;
        updateCartItemTotal(itemId);
    } else {
        createToast('Maximum quantity reached', 'warning', 2000);
    }
}

function decreaseCartQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const current = parseInt(input.value);

    if (current > 1) {
        input.value = current - 1;
        updateCartItemTotal(itemId);
    }
}

function updateCartItemTotal(itemId) {
    const quantityInput = document.getElementById('quantity-' + itemId);
    if (quantityInput) {
        // Store previous value before triggering change
        $(quantityInput).data('previous-value', quantityInput.value);
        // Trigger the change event to update via AJAX
        quantityInput.dispatchEvent(new Event('change'));
    }
}
</script>
@endsection