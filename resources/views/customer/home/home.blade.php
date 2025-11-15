@extends('layout.customer')
@section('title', 'Home')
@section('content')

<style>
    /* Custom carousel styles */
    .featured-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .featured-carousel::-webkit-scrollbar {
        display: none;
    }

    .category-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .category-carousel::-webkit-scrollbar {
        display: none;
    }

    /* Custom animations */
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.6s ease-out forwards;
    }

    /* Enhanced hover effects */
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .product-card:hover {
        transform: translateY(-4px);
        text-decoration: none;
        color: inherit;
    }

    /* Improved focus styles for accessibility */
    .focus-ring:focus {
        outline: 2px solid #10b981;
        outline-offset: 2px;
    }

    /* Search input with icon styles */
    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper input {
        padding-right: 3.5rem;
    }

    .search-input-wrapper .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }
</style>

<div class="max-w-[90rem] mx-auto px-3 sm:px-6 lg:px-8">
    <!-- Hero Section with Background Image -->
<div class="relative rounded-xl shadow-md mb-8 p-6 sm:p-8 text-white animate-slide-in bg-cover bg-center" 
     style="background-image: url('{{ asset('images/bg-banner.png') }}');">
    <div class="absolute inset-0 bg-emerald-700/60 rounded-xl"></div> <!-- Optional overlay for better text readability -->

    <div class="relative max-w-4xl">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 leading-tight">
            Fresh from <span class="text-emerald-200">Local Vendors</span>
        </h1>
        <p class="text-md sm:text-lg mb-5 text-emerald-100 max-w-2xl">
            Discover premium quality products from trusted vendors in your community
        </p>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('products.search') }}" class="max-w-3xl">
            <div class="search-input-wrapper">
                <label for="product-search" class="sr-only">Search products</label>
                <input id="product-search" type="text" name="q" value="{{ request('search') }}" placeholder="Search products, vendors, or categories..." class="w-full px-5 py-4 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-emerald-300/50 shadow-lg placeholder-gray-500 text-lg" aria-label="Search products, vendors, or categories">
                <button type="submit" class="search-icon" aria-label="Search products">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>


    

<!--Vendors-->
@if($vendors->count() > 0)
    <section class="animate-slide-in mb-8" role="region" aria-label="Vendor listings">
        <!-- Section Header -->
        <header class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     width="24" height="24" viewBox="0 0 24 24" 
                     fill="none" stroke="currentColor" stroke-width="2" 
                     stroke-linecap="round" stroke-linejoin="round" 
                     class="w-6 h-6 text-emerald-600 mr-3 flex-shrink-0"
                     aria-hidden="true">
                    <path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5"/>
                    <path d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244"/>
                    <path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05"/>
                </svg>             
                Meet the Vendors
            </h2>
            <p class="text-sm text-gray-600">Discover trusted vendors in the San Fernando Market</p>
        </header>

        <!-- Carousel Container -->
        <div class="relative group">
            <!-- Scroll Hint Gradients -->
            <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Carousel -->
            <div id="vendor-carousel" 
                 class="overflow-x-auto scrollbar-hide pb-4 scroll-smooth"
                 role="list"
                 aria-label="Vendor cards carousel">
                <div class="flex gap-3 sm:gap-4 px-1">
                    @foreach($vendors as $vendor)
                      <a href="{{ route('products.vendor', $vendor->id) }}"> 
                        <article class="w-64 sm:w-72 bg-white rounded-2xl overflow-hidden flex-shrink-0 
                                       shadow-sm hover:shadow-xl border border-gray-100 
                                       transition-all duration-300 hover:scale-[1.02] hover:-translate-y-1
                                       focus-within:ring-2 focus-within:ring-emerald-500 focus-within:ring-offset-2"
                                 role="listitem"
                                 tabindex="0">
                            
                            <!-- Banner Section with Default Fallback and Vendor Name -->
<div class="relative h-20 sm:h-24 overflow-hidden">
    @if($vendor->shop_banner_url)
        <img src="{{ asset('storage/' . $vendor->shop_banner_url) }}" 
             alt="Banner for {{ $vendor->vendor_name }}" 
             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
             loading="lazy">
    @else
        <img src="{{ asset('images/bg-banner.png') }}" 
             alt="Default banner" 
             class="w-full h-full object-cover">
    @endif

    <!-- Vendor Name Overlay -->
    <div class="absolute bottom-2 left-3 text-white text-sm sm:text-base font-semibold drop-shadow-md">
        {{ $vendor->vendor_name }}
    </div>
    
    <!-- Status Badge Overlay -->
    <div class="absolute top-2 sm:top-3 right-2 sm:right-3 flex flex-col gap-1 sm:gap-2">

        <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium shadow-sm
                    {{ $vendor->is_accepting_orders 
                       ? 'bg-green-100 text-green-800 border border-green-200' 
                       : 'bg-red-100 text-red-800 border border-red-200' }}">
            <span class="w-1.5 h-1.5 rounded-full mr-1 sm:mr-1.5 
                        {{ $vendor->is_accepting_orders ? 'bg-green-400' : 'bg-red-400' }}"></span>
            {{ $vendor->is_accepting_orders ? 'Open' : 'Closed' }}
        </span>
    </div>
</div>

                            <!-- Content Section -->
                            <div class="p-4 sm:p-5">
                                <!-- Vendor Header -->
                                <div class="flex items-start gap-2 sm:gap-3 mb-3 sm:mb-4">
                                    <!-- Logo -->
                                    <div class="relative -mt-8 sm:-mt-10 flex-shrink-0">
                                        @if($vendor->shop_logo_url)
                                            <img src="{{ asset('storage/' . $vendor->shop_logo_url) }}" 
                                                 alt="{{ $vendor->vendor_name }} logo" 
                                                 class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover border-3 border-white shadow-lg"
                                                 loading="lazy">
                                        @else
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gray-100
                                                       flex items-center justify-center font-bold text-gray-500 text-base sm:text-lg 
                                                       border-3 border-white shadow-lg">
                                                {{ strtoupper(substr($vendor->vendor_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Name and Verification -->
                                    <div class="flex-1 min-w-0 pt-1 sm:pt-2">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-bold text-sm sm:text-base text-gray-900 truncate pr-1">
                                                {{ $vendor->vendor_name }}
                                            </h3>
                                        </div>
                                        
                                        <!-- Rating -->
                                        @if($vendor->average_rating)
                                            <div class="flex items-center">
                                                <div class="flex items-center mr-1 sm:mr-2" role="img" aria-label="Rating: {{ number_format($vendor->average_rating, 1) }} out of 5 stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $vendor->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-xs sm:text-sm font-medium text-gray-700">
                                                    {{ number_format($vendor->average_rating, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                              @if($vendor->description)
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 leading-relaxed">
                                    {{ Str::limit($vendor->description, 30) }}
                                </p>
                            @endif

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                    @if($vendor->accepts_cod)
                                        <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span class="hidden sm:inline">COD Available</span>
                                            <span class="sm:hidden">COD</span>
                                        </span>
                                    @else
                                        <div></div> <!-- Spacer -->
                                    @endif
                                    
                                </div>
                            </div>
                        </article>
                      </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
        <div class="animate-slide-in mb-7">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=" w-6 h-6 text-emerald-600 mr-2 lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>            
                    Featured Products
            </h3>

            <!-- Carousel Container with Scroll Hints -->
            <div class="relative group">
                <!-- Scroll Hint Gradients -->
                <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Carousel -->
                <div id="featured-carousel" class="featured-carousel overflow-x-auto pb-4 scroll-smooth">
                    <div class="flex gap-4 px-1">
                        @foreach($featuredProducts->take(8) as $product)
                            <a href="{{ route('products.show', $product->id) }}" class="product-card w-48 bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300 flex-shrink-0">
                                <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    @if($product->image_url)
                                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif

                                    @if($product->is_budget_based)
                                        <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">Budget</span>
                                    @endif
                                </div>

                                <div class="p-3 sm:p-4">
                                    @if($product->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full mb-2">{{ $product->category->category_name }}</span>
                                    @endif
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">{{ $product->product_name }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3">by <span class="text-emerald-600 font-medium">{{ $product->vendor->vendor_name }}</span></p>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                                        </div>
                                    </div>
                                  @if($product->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($product->vendor->average_rating, 1) }}
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


    <!-- Budget Products -->
    @if($budgetProducts->count() > 0)
        <div class="animate-slide-in mb-7">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-emerald-600 mr-2 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    Budget Based Products
            </h3>

            <!-- Carousel Container with Scroll Hints -->
            <div class="relative group">
                <!-- Scroll Hint Gradients -->
                <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Carousel -->
                <div id="featured-carousel" class="featured-carousel overflow-x-auto pb-4 scroll-smooth">
                    <div class="flex gap-4 px-1">
                        @foreach($budgetProducts->take(8) as $product)
                            <a href="{{ route('products.show', $product->id) }}" class="product-card w-48 bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300 flex-shrink-0">
                                <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    @if($product->image_url)
                                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif

                                    @if($product->is_budget_based)
                                        <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">Budget</span>
                                    @endif
                                </div>

                                <div class="p-3 sm:p-4">
                                    @if($product->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full mb-2">{{ $product->category->category_name }}</span>
                                    @endif
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">{{ $product->product_name }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3">by <span class="text-emerald-600 font-medium">{{ $product->vendor->vendor_name }}</span></p>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                                        </div>
                                    </div>
                                  @if($product->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($product->vendor->average_rating, 1) }}
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

    <!-- Products by Category -->
    @if($categoryProducts->count() > 0)
        @foreach($categoryProducts as $category)
            @if($category->products->count() > 0)
                <div class="animate-slide-in mb-7">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-emerald-600 mr-2 lucide lucide-grid-3x3-icon lucide-grid-3x3"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        {{ $category->category_name }}
                    </h3>

                    <!-- Carousel Container with Scroll Hints -->
                    <div class="relative group">
                        <!-- Scroll Hint Gradients -->
                        <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Carousel -->
                        <div id="category-{{ $category->id }}-carousel" class="featured-carousel overflow-x-auto pb-4 scroll-smooth">
                            <div class="flex gap-4 px-1">
                                @foreach($category->products->take(4) as $product)
                                    <a href="{{ route('products.show', $product->id) }}" class="product-card w-48 bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300 flex-shrink-0">
                                        <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                            @if($product->image_url)
                                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @endif

                                            @if($product->is_budget_based)
                                                <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">Budget</span>
                                            @endif
                                        </div>

                                        <div class="p-3 sm:p-4">
                                            @if($product->category)
                                                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full mb-2">{{ $product->category->category_name }}</span>
                                            @endif
                                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">{{ $product->product_name }}</h3>
                                            <p class="text-xs sm:text-sm text-gray-600 mb-3">by <span class="text-emerald-600 font-medium">{{ $product->vendor->vendor_name }}</span></p>
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <span class="text-lg font-bold text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                                    <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                                                </div>
                                            </div>
                                          @if($product->vendor->average_rating)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                {{ number_format($product->vendor->average_rating, 1) }}
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
        @endforeach
    @endif
</div>

</div>


 

<script>
    // Enhanced carousel functionality
    function scrollCarousel(direction, carouselId) {
        const carousel = document.getElementById(carouselId);
        let cardWidth;
        
        // Set card width based on carousel type
        switch(carouselId) {
            case 'featured-carousel':
                cardWidth = 288 + 16; // featured product card width + gap
                break;
            case 'vendor-carousel':
                cardWidth = window.innerWidth < 640 ? 192 + 16 : 256 + 16; // vendor card width + gap (responsive)
                break;
            case 'category-carousel':
                cardWidth = 120 + 12; // category card width + gap
                break;
            default:
                cardWidth = 200 + 16;
        }
        
        const scrollAmount = direction === 'left' ? -cardWidth * 2 : cardWidth * 2;
        
        carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
    
    // Auto-scroll carousel (optional)
    let autoScrollInterval;
    
    function startAutoScroll() {
        autoScrollInterval = setInterval(() => {
            const carousel = document.getElementById('featured-carousel');
            if (carousel && carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth) {
                carousel.scrollTo({ left: 0, behavior: 'smooth' });
            } else if (carousel) {
                scrollCarousel('right', 'featured-carousel');
            }
        }, 5000);
    }
    
    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }
    
    // Start auto-scroll when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const featuredCarousel = document.getElementById('featured-carousel');
        const vendorCarousel = document.getElementById('vendor-carousel');
        
        if (featuredCarousel) {
            startAutoScroll();
            
            // Pause auto-scroll on hover
            featuredCarousel.addEventListener('mouseenter', stopAutoScroll);
            featuredCarousel.addEventListener('mouseleave', startAutoScroll);
        }
        
        // Add touch scrolling support for vendor carousel on mobile
        if (vendorCarousel) {
            let isDown = false;
            let startX;
            let scrollLeft;
            
            vendorCarousel.addEventListener('mousedown', (e) => {
                isDown = true;
                vendorCarousel.classList.add('cursor-grabbing');
                startX = e.pageX - vendorCarousel.offsetLeft;
                scrollLeft = vendorCarousel.scrollLeft;
            });
            
            vendorCarousel.addEventListener('mouseleave', () => {
                isDown = false;
                vendorCarousel.classList.remove('cursor-grabbing');
            });
            
            vendorCarousel.addEventListener('mouseup', () => {
                isDown = false;
                vendorCarousel.classList.remove('cursor-grabbing');
            });
            
            vendorCarousel.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - vendorCarousel.offsetLeft;
                const walk = (x - startX) * 2;
                vendorCarousel.scrollLeft = scrollLeft - walk;
            });
        }
    });
    
    // Enhanced search functionality with debouncing
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Optional: implement live search here
            }, 300);
        });
        
        // Submit form when search icon is clicked
        const searchIcon = document.querySelector('.search-icon');
        if (searchIcon) {
            searchIcon.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.closest('form').submit();
            });
        }
    }
</script>

@endsection