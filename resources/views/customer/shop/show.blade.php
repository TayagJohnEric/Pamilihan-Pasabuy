{{-- 
    Product Detail Page
    ====================
    This page displays detailed information about a product including:
    - Product image and details
    - Vendor information and ratings
    - Add to cart functionality (regular and budget-based)
    - Related and similar products
    - Customer reviews
--}}

@extends('layout.customer')
@section('title', $product->product_name . ' - ' . $product->vendor->vendor_name)
@section('content')

<div class="max-w-[90rem] mx-auto ">
    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

   {{-- Breadcrumb Navigation --}}
<nav class="hidden sm:flex mb-6 text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('products.index') }}" 
               class="text-gray-600 hover:text-emerald-600 transition-colors">
                Home
            </a>
        </li>
        
        {{-- Category Breadcrumb --}}
        @if($product->category)
        <li>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <a href="{{ route('products.category', $product->category->id) }}" 
                   class="text-gray-600 hover:text-emerald-600 transition-colors">
                    {{ $product->category->category_name }}
                </a>
            </div>
        </li>
        @endif
        
        {{-- Current Product --}}
        <li>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-500" aria-current="page">{{ $product->product_name }}</span>
            </div>
        </li>
    </ol>
</nav>


    {{-- Main Product Card --}}
<div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100 mb-10 max-w-[90rem] mx-auto">
    <div class="flex flex-col lg:flex-row">
        {{-- Product Image Section --}}
        <div class="w-full lg:w-1/2 relative">
            <div class="aspect-square relative bg-gradient-to-br from-gray-50 to-gray-100">
                @if($product->image_url)
                    <img 
                        src="{{ asset('storage/' . $product->image_url) }}"
                        alt="{{ $product->product_name }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                @else
                    {{-- Placeholder for products without images --}}
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-24 h-24 lg:w-32 lg:h-32" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @endif
                
                {{-- Budget Option Badge --}}
                @if($product->is_budget_based)
                    <span class="absolute top-3 left-3 lg:top-4 lg:left-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-2.5 py-1 lg:px-3 lg:py-1 rounded-full text-xs lg:text-sm font-medium shadow-lg">
                        Budget Option Available
                    </span>
                @endif
            </div>
        </div>

        {{-- Product Details Section --}}
        <div class="w-full lg:w-1/2 p-4 sm:p-6 lg:p-8">
            {{-- Category Badge --}}
            <div class="mb-3 lg:mb-4">
                @if($product->category)
                    <span class="inline-block bg-emerald-50 text-emerald-700 text-xs sm:text-sm px-2.5 py-1 lg:px-3 lg:py-1 rounded-full font-medium">
                        {{ $product->category->category_name }}
                    </span>
                @endif
            </div>

            {{-- Product Title --}}
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-3 lg:mb-4 leading-tight">
                {{ $product->product_name }}
            </h1>
            
            {{-- Price Information --}}
            <div class="mb-4 lg:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-baseline mb-2">
                    <span class="text-lg sm:text-xl lg:text-2xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                        ₱{{ number_format($product->price, 2) }}
                    </span>
                    <span class="text-sm sm:text-base lg:text-lg text-gray-500 sm:ml-2">per {{ $product->unit }}</span>
                </div>
                
                {{-- Indicative Price for Budget-Based Products --}}
                @if($product->is_budget_based && $product->indicative_price_per_unit)
                    <p class="text-xs sm:text-sm text-gray-600">
                        Indicative price: ₱{{ number_format($product->indicative_price_per_unit, 2) }} per {{ $product->unit }}
                    </p>
                @endif
            </div>

            {{-- Vendor Information Section --}}
            <div class="border-t border-b border-gray-200 py-3 lg:py-4 mb-4 lg:mb-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-grow min-w-0">
                        
                     
                      <div class="flex items-center gap-3">
                            <!-- Shop Profile Picture -->
                            @if($product->vendor->shop_logo_url)
                                <img 
                                    src="{{ asset('storage/' . $product->vendor->shop_logo_url) }}" 
                                    alt="{{ $product->vendor->vendor_name }} Logo" 
                                    class="h-12 w-12 rounded-full object-cover"
                                >
                            @else
                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-white">
                                    {{ strtoupper(substr($product->vendor->vendor_name, 0, 1)) }}
                                </div>
                            @endif

                            <!-- Vendor Info -->
                            <div class="flex flex-col">
                                <p class="text-xs sm:text-sm text-gray-600 leading-none">Sold by</p>
                                <a href="{{ route('products.vendor', $product->vendor->id) }}" 
                                class="text-sm sm:text-base lg:text-lg font-semibold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent hover:from-emerald-700 hover:to-teal-700 transition-all break-words">
                                    {{ $product->vendor->vendor_name }}
                                </a>
                                 {{-- Vendor Rating --}}
                                @if($product->vendor->average_rating)
                                    <div class="flex items-center mt-1">
                                        <div class="flex items-center" aria-label="Rating: {{ number_format($product->vendor->average_rating, 1) }} out of 5 stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $product->vendor->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                    fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-xs sm:text-sm text-gray-600">{{ number_format($product->vendor->average_rating, 1) }} rating</span>
                                    </div>
                                @endif
                            </div>    
                        </div>
                    </div>
                    
                    {{-- Vendor Logo --}}
                    @if($product->vendor->shop_logo_url)
                        <div class="flex-shrink-0">
                            <img src="{{ $product->vendor->shop_logo_url }}" 
                                 alt="{{ $product->vendor->vendor_name }} logo" 
                                 class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stock Information --}}
            <div class="mb-4 lg:mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Availability:</span>
                    @if($product->is_budget_based)
                        <span class="text-xs sm:text-sm {{ $product->is_available ? 'text-emerald-600' : 'text-red-600' }} font-medium">
                            {{ $product->is_available ? 'Available' : 'Currently Unavailable' }}
                        </span>
                    @else
                        <span class="text-xs sm:text-sm {{ $product->quantity_in_stock <= 5 ? 'text-red-600' : 'text-emerald-600' }} font-medium">
                            {{ $product->quantity_in_stock }} {{ $product->unit }} available
                        </span>
                    @endif
                </div>
                
                {{-- Low Stock Warning --}}
                @if(!$product->is_budget_based && $product->quantity_in_stock <= 5 && $product->quantity_in_stock > 0)
                    <div class="flex items-center text-xs sm:text-sm text-red-600 bg-red-50 px-2.5 py-2 lg:px-3 rounded-lg">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Low stock - order soon!
                    </div>
                @endif
            </div>

            {{-- Product Description --}}
            @if($product->description)
                <div class="mb-4 lg:mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Description</h3>
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            {{-- Add to Cart Section --}}
            <div class="space-y-4">
                @php
                    $isInStock = $product->is_budget_based ? $product->is_available : ($product->quantity_in_stock > 0);
                    $maxQuantity = $product->is_budget_based ? 999 : $product->quantity_in_stock;
                @endphp
                
                @if($isInStock)
                    {{-- Regular Purchase Form (Default) --}}
                    <div class="regular-form-{{ $product->id }}">
                        <form id="regular-cart-form-{{ $product->id }}" 
                              class="space-y-4" 
                              data-product-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            {{-- Quantity Selector --}}
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                <label for="quantity-{{ $product->id }}" class="text-sm font-medium text-gray-700">
                                    Quantity:
                                </label>
                                <div class="flex items-center">
                                    <div class="flex items-center border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-transparent">
                                        <button type="button" 
                                                onclick="decreaseQuantity({{ $product->id }})" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-l-lg transition-colors"
                                                aria-label="Decrease quantity">
                                            <span class="text-lg font-medium">-</span>
                                        </button>
                                        <input type="number" 
                                               id="quantity-{{ $product->id }}"
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $maxQuantity }}"
                                               data-price="{{ $product->price }}"
                                               class="w-14 sm:w-16 px-2 py-2 text-center border-0 focus:ring-0 focus:outline-none text-sm sm:text-base"
                                               aria-label="Quantity">
                                        <button type="button" 
                                                onclick="increaseQuantity({{ $product->id }})" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-r-lg transition-colors"
                                                aria-label="Increase quantity">
                                            <span class="text-lg font-medium">+</span>
                                        </button>
                                    </div>
                                    <span class="text-xs sm:text-sm text-gray-500 ml-2">
                                        ({{ $product->is_budget_based ? 'available' : $product->quantity_in_stock . ' available' }})
                                    </span>
                                </div>
                            </div>

                            {{-- Price Summary --}}
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Unit Price:</span>
                                    <span class="font-semibold text-gray-800">₱{{ number_format($product->price, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center text-base sm:text-lg border-t pt-2">
                                    <span class="text-gray-800 font-medium">Total:</span>
                                    <span id="total-price-{{ $product->id }}" class="font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                        ₱{{ number_format($product->price, 2) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Add to Cart Button --}}
                            <button type="submit" 
                                    id="regular-cart-btn-{{ $product->id }}"
                                    class="w-full bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white px-4 py-3 sm:px-6 rounded-lg font-semibold hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 transition-all duration-200 focus:ring-4 focus:ring-emerald-200 flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                                </svg>
                                <span class="btn-text">Add to Cart</span>
                            </button>
                        </form>
                    </div>

                    {{-- Budget-Based Option (Only for budget-based products) --}}
                    @if($product->is_budget_based)
                        <div class="pt-4 border-t border-gray-200">
                            {{-- Budget Toggle Button --}}
                            <div class="budget-toggle-{{ $product->id }}">
                                <button type="button" 
                                        onclick="toggleBudgetForm({{ $product->id }})"
                                        class="w-full px-4 py-3 sm:px-6 bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-lg font-semibold hover:from-blue-100 hover:to-blue-200 transition-all border border-blue-200 text-sm sm:text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 sm:w-5 inline mr-2 flex-shrink-0 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                                    Set Custom Budget Instead
                                </button>
                                <p class="text-xs text-gray-600 text-center mt-2 px-2">
                                    Get a custom quote based on your specific budget and requirements
                                </p>
                            </div>

                            {{-- Budget-Based Form (Hidden by default) --}}
                            <div class="budget-form-{{ $product->id }} hidden">
                                <form id="budget-cart-form-{{ $product->id }}" 
                                      class="space-y-4 mt-4" 
                                      data-product-id="{{ $product->id }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="space-y-3">
                                        {{-- Budget Amount Input --}}
                                        <div>
                                            <label for="budget-{{ $product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                Budget Amount <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                                <input type="number" 
                                                       id="budget-{{ $product->id }}"
                                                       name="customer_budget" 
                                                       value="{{ old('customer_budget') }}"
                                                       step="0.01" 
                                                       min="0.01"
                                                       max="999999.99"
                                                       required
                                                       class="pl-8 pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full text-sm sm:text-base"
                                                       placeholder="0.00"
                                                       aria-describedby="budget-help-{{ $product->id }}">
                                            </div>
                                            @if($product->indicative_price_per_unit)
                                                <p id="budget-help-{{ $product->id }}" class="text-xs text-gray-500 mt-1">
                                                    Indicative price: ~₱{{ number_format($product->indicative_price_per_unit, 2) }}/{{ $product->unit }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        {{-- Customer Notes --}}
                                        <div>
                                            <label for="notes-{{ $product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                Notes (Optional)
                                            </label>
                                            <textarea id="notes-{{ $product->id }}"
                                                      name="customer_notes" 
                                                      rows="3"
                                                      placeholder="Special requests or preferences..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none text-sm">{{ old('customer_notes') }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Budget Form Actions --}}
                                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                                        <button type="button" 
                                                onclick="toggleBudgetForm({{ $product->id }})"
                                                class="flex-1 px-4 py-2.5 sm:px-6 sm:py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm sm:text-base">
                                            Back to Standard
                                        </button>
                                        <button type="submit" 
                                                id="budget-cart-btn-{{ $product->id }}"
                                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 focus:ring-4 focus:ring-blue-200 flex items-center justify-center text-sm sm:text-base">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="btn-text">Add with Budget</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                @else
                    {{-- Out of Stock State --}}
                    <div class="text-center py-4">
                        <button class="w-full bg-gray-300 text-gray-500 px-4 py-3 sm:px-6 rounded-lg font-semibold cursor-not-allowed text-sm sm:text-base" disabled>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                            </svg>
                            @if($product->is_budget_based)
                                Currently Unavailable
                            @else
                                Out of Stock
                            @endif
                        </button>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">
                            @if($product->is_budget_based)
                                This service is temporarily unavailable
                            @else
                                This item is currently unavailable
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            {{-- Additional Vendor Information --}}
            <div class="mt-4 lg:mt-6 pt-4 lg:pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                    @if($product->vendor->stall_number)
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0 sm:mr-2">Stall Number:</span>
                            <span class="font-medium">{{ $product->vendor->stall_number }}</span>
                        </div>
                    @endif
                    @if($product->vendor->market_section)
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0 sm:mr-2">Market Section:</span>
                            <span class="font-medium">{{ $product->vendor->market_section }}</span>
                        </div>
                    @endif
                    @if($product->vendor->accepts_cod)
                        <div class="col-span-1 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                ✓ Cash on Delivery Available
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Customer Reviews Section --}}
@if($product->vendor && $product->vendor->ratingsReceived && $product->vendor->ratingsReceived->count() > 0)
    <div class="bg-white rounded-xl shadow mt-6 p-4 border border-gray-100">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">
            Customer Reviews for {{ $product->vendor->vendor_name }}
        </h3>
        <div class="space-y-3">
            @foreach($product->vendor->ratingsReceived as $rating)
                <div class="border-b border-gray-200 pb-3 last:border-b-0">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            {{-- Star Rating Display --}}
                            <div class="flex items-center" aria-label="Rating: {{ $rating->rating_value }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm sm:text-base font-medium">
                                {{ $rating->user ? $rating->user->first_name . ' ' . $rating->user->last_name : 'Anonymous' }}
                            </span>
                        </div>
                        <span class="text-xs sm:text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                    </div>
                    @if($rating->comment)
                        <p class="text-gray-700 text-sm sm:text-base leading-snug">{{ $rating->comment }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif


    {{-- Related Products from Same Vendor --}}
    @if($relatedProducts->count() > 0)
        <div class="animate-slide-in mb-7 mt-7">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                More from {{ $product->vendor->vendor_name }}
            </h3>
            <div class="relative">
                <div id="featured-carousel" class="featured-carousel overflow-x-auto pb-4">
                    <div class="flex gap-4">
                        @foreach($relatedProducts->take(8) as $relatedProduct)
                            <a href="{{ route('products.show', $relatedProduct->id) }}" 
                               class="product-card w-48 bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300 flex-shrink-0">
                                {{-- Related Product Image --}}
                                <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    @if($relatedProduct->image_url)
                                        <img src="{{ asset('storage/' . $relatedProduct->image_url) }}" 
                                             alt="{{ $relatedProduct->product_name }}" 
                                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" 
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Budget Badge for Related Products --}}
                                    @if($relatedProduct->is_budget_based)
                                        <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">
                                            Budget
                                        </span>
                                    @endif
                                </div>

                                {{-- Related Product Details --}}
                                <div class="p-3 sm:p-4">
                                    @if($relatedProduct->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full mb-2">
                                            {{ $relatedProduct->category->category_name }}
                                        </span>
                                    @endif
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">
                                        {{ $relatedProduct->product_name }}
                                    </h3>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-emerald-600">₱{{ number_format($relatedProduct->price, 2) }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $relatedProduct->unit }}</span>
                                        </div>
                                    </div>
                                     @if($relatedProduct->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($relatedProduct->vendor->average_rating, 1) }}
                                    </div>
                                @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Similar Products Section --}}
    <div class="animate-slide-in">
        <div class="sm:max-w-[90rem] sm:mx-auto">
            <div class="p-1">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Similar Products</h3>
            </div>

            @if($similarProducts->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-10">
                    @foreach($similarProducts as $similarProduct)
                        <a href="{{ route('products.show', $similarProduct->id) }}" 
                           class="product-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300">
                            
                            {{-- Similar Product Image --}}
                            <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                @if($similarProduct->image_url)
                                    <img src="{{ asset('storage/' . $similarProduct->image_url) }}" 
                                         alt="{{ $similarProduct->product_name }}" 
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" 
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Budget Badge for Similar Products --}}
                                @if($similarProduct->is_budget_based)
                                    <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">
                                        Budget
                                    </span>
                                @endif
                            </div>

                            {{-- Similar Product Details --}}
                            <div class="p-3 sm:p-4">
                                <div class="mb-2">
                                    @if($similarProduct->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full">
                                            {{ $similarProduct->category->category_name }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">
                                    {{ $similarProduct->product_name }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 mb-3">
                                    by <span class="text-emerald-600 font-medium">{{ $similarProduct->vendor->vendor_name }}</span>
                                </p>
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <span class="text-lg font-bold text-emerald-600">₱{{ number_format($similarProduct->price, 2) }}</span>
                                        <span class="text-xs text-gray-500">/ {{ $similarProduct->unit }}</span>
                                    </div>
                                </div>
                                 @if($similarProduct->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($similarProduct->vendor->average_rating, 1) }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- No Similar Products State --}}
                <div class="text-center py-16">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">No products found</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        We couldn't find any products that are related.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('products.index') }}" 
                           class="inline-block px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">
                            View All Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- JavaScript Dependencies --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
/**
 * Toast Notification System
 * =========================
 * Handles showing and removing toast notifications for user feedback
 */

/**
 * Show toast notification
 * @param {string} message - The message to display
 * @param {string} type - Type of toast ('success' or 'error')
 */
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const toastHtml = `
        <div id="${toastId}" 
             class="max-w-md mx-auto rounded-md border ${type === 'success' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'} p-4 flex items-center justify-between shadow-lg transform transition-all duration-300 translate-x-full opacity-0"
             role="alert"
             aria-live="polite">
            <div class="flex items-center space-x-2 ${type === 'success' ? 'text-green-700' : 'text-red-700'} text-sm font-semibold leading-tight">
                <i class="fas ${type === 'success' ? 'fa-check' : 'fa-exclamation-triangle'}" aria-hidden="true"></i>
                <span>${message}</span>
            </div>
            <button onclick="removeToast('${toastId}')" 
                    aria-label="Close notification" 
                    class="${type === 'success' ? 'text-green-400 hover:text-green-600' : 'text-red-400 hover:text-red-600'} focus:outline-none transition-colors">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Animate toast in
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => removeToast(toastId), 5000);
}

/**
 * Remove toast notification with animation
 * @param {string} toastId - ID of the toast to remove
 */
function removeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }
}

/**
 * Cart Management System
 * ======================
 * Handles adding products to cart with loading states and validation
 */

/**
 * Submit cart form via AJAX
 * @param {HTMLFormElement} form - The form element to submit
 * @param {boolean} isRegular - Whether this is a regular or budget-based form
 */
function submitCartForm(form, isRegular = true) {
    const productId = form.dataset.productId;
    const button = form.querySelector('button[type="submit"]');
    const buttonText = button.querySelector('.btn-text');
    const buttonIcon = button.querySelector('svg');
    const originalText = buttonText.textContent;
    
    // Show loading state with inline spinner
    button.disabled = true;
    button.classList.add('opacity-75', 'cursor-not-allowed');
    
    // Create loading spinner that appears inline with text
    const loadingSpinner = document.createElement('svg');
    loadingSpinner.className = 'animate-spin w-5 h-5 mr-2';
    loadingSpinner.setAttribute('fill', 'none');
    loadingSpinner.setAttribute('viewBox', '0 0 24 24');
    loadingSpinner.innerHTML = `
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    `;
    
    // Replace the original icon with loading spinner
    if (buttonIcon) {
        buttonIcon.replaceWith(loadingSpinner);
    }
    
    buttonText.textContent = 'Adding...';
    
    // Prepare form data
    const formData = new FormData(form);
    
    // Submit via AJAX
    $.ajax({
        url: '{{ route("cart.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                
                // Update cart badge
                if (response.cart_count !== undefined && window.updateCartBadge) {
                    window.updateCartBadge(response.cart_count);
                }
                
                // Reset form based on type
                if (isRegular) {
                    const quantityInput = form.querySelector('input[name="quantity"]');
                    if (quantityInput) {
                        quantityInput.value = 1;
                        updateTotalPrice(productId);
                    }
                } else {
                    // Reset budget form
                    form.reset();
                }
            } else {
                showToast(response.message || 'An error occurred. Please try again.', 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred. Please try again.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors[0] || errorMessage;
                }
            }
            
            showToast(errorMessage, 'error');
        },
        complete: function() {
            // Reset button state - restore original icon and text
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
            
            // Restore original icon
            const currentSpinner = button.querySelector('.animate-spin');
            if (currentSpinner && buttonIcon) {
                currentSpinner.replaceWith(buttonIcon);
            }
            
            buttonText.textContent = originalText;
        }
    });
}

/**
 * Form Toggle System
 * ==================
 * Handles switching between regular and budget-based forms
 */

/**
 * Toggle between regular and budget-based forms
 * @param {number} productId - The product ID
 */
function toggleBudgetForm(productId) {
    const regularForm = document.querySelector('.regular-form-' + productId);
    const budgetForm = document.querySelector('.budget-form-' + productId);
    const budgetToggle = document.querySelector('.budget-toggle-' + productId);

    if (budgetForm.classList.contains('hidden')) {
        // Show budget form, hide regular form and toggle button
        budgetForm.classList.remove('hidden');
        regularForm.classList.add('hidden');
        budgetToggle.classList.add('hidden');
    } else {
        // Show regular form and toggle button, hide budget form
        budgetForm.classList.add('hidden');
        regularForm.classList.remove('hidden');
        budgetToggle.classList.remove('hidden');
    }
}

/**
 * Quantity Management System
 * ==========================
 * Handles quantity input controls and price calculations
 */

/**
 * Increase product quantity
 * @param {number} productId - The product ID
 */
function increaseQuantity(productId) {
    const input = document.getElementById('quantity-' + productId);
    if (!input) return;
    
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);

    if (current < max) {
        input.value = current + 1;
        updateTotalPrice(productId);
    }
}

/**
 * Decrease product quantity
 * @param {number} productId - The product ID
 */
function decreaseQuantity(productId) {
    const input = document.getElementById('quantity-' + productId);
    if (!input) return;
    
    const current = parseInt(input.value);

    if (current > 1) {
        input.value = current - 1;
        updateTotalPrice(productId);
    }
}

/**
 * Update total price display based on quantity
 * @param {number} productId - The product ID
 */
function updateTotalPrice(productId) {
    const quantityInput = document.getElementById('quantity-' + productId);
    const totalElement = document.getElementById('total-price-' + productId);

    if (quantityInput && totalElement) {
        const quantity = parseInt(quantityInput.value);
        const unitPrice = parseFloat(quantityInput.getAttribute('data-price')) || 0;
        const total = quantity * unitPrice;

        totalElement.textContent = '₱' + total.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

/**
 * Budget Input Validation
 * =======================
 * Validates budget input values within allowed ranges
 */

/**
 * Validate budget input value
 * @param {HTMLInputElement} input - The budget input element
 */
function validateBudgetInput(input) {
    const value = parseFloat(input.value);
    const min = parseFloat(input.getAttribute('min'));
    const max = parseFloat(input.getAttribute('max'));
    
    if (value < min) {
        input.value = min;
    } else if (value > max) {
        input.value = max;
    }
}

/**
 * Document Ready Event Handlers
 * =============================
 * Initialize all event listeners and form handlers
 */
$(document).ready(function() {
    // Handle regular form submission
    $('form[id^="regular-cart-form-"]').on('submit', function(e) {
        e.preventDefault();
        submitCartForm(this, true);
    });
    
    // Handle budget form submission
    $('form[id^="budget-cart-form-"]').on('submit', function(e) {
        e.preventDefault();
        submitCartForm(this, false);
    });

    // Handle quantity input changes for standard products
    const quantityInputs = document.querySelectorAll('input[name="quantity"]');
    quantityInputs.forEach(function(input) {
        const productId = input.id.split('-')[1];
        input.addEventListener('input', function() {
            updateTotalPrice(productId);
        });
    });

    // Handle budget input validation
    const budgetInputs = document.querySelectorAll('input[name="customer_budget"]');
    budgetInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            validateBudgetInput(this);
        });
        
        input.addEventListener('blur', function() {
            validateBudgetInput(this);
        });
    });

    // Enhanced budget form validation before submission
    $('form[id^="budget-cart-form-"]').on('submit', function(e) {
        const budgetInput = this.querySelector('input[name="customer_budget"]');
        if (budgetInput) {
            const value = parseFloat(budgetInput.value);
            const min = parseFloat(budgetInput.getAttribute('min'));
            const max = parseFloat(budgetInput.getAttribute('max'));
            
            if (isNaN(value) || value < min || value > max) {
                e.preventDefault();
                showToast(
                    'Please enter a valid budget amount between ₱' + 
                    min.toLocaleString() + ' and ₱' + max.toLocaleString(), 
                    'error'
                );
                budgetInput.focus();
                return false;
            }
        }
    });
});
</script>

@endsection