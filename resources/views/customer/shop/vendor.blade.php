@extends('layout.customer')
@section('title', $vendor->vendor_name . ' - Vendor Shop')
@section('content')

<style>
/* Custom animations */
    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes fadeInScale {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .animate-slide-in {
        animation: slideIn 0.6s ease-out forwards;
    }
    
    .animate-fade-scale {
        animation: fadeInScale 0.5s ease-out forwards;
    }
    
    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
        z-index: 1;
    }
    
    .product-card:hover::before {
        left: 100%;
    }
    
    .glass-effect {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .shadow-luxury {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.05);
    }
</style>

<div class="max-w-[90rem] mx-auto animate-slide-in">
   <!-- Enhanced Breadcrumb -->
        <nav class="hidden sm:flex mb-8 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/80 backdrop-blur-sm rounded-full px-4 py-2 shadow-sm border border-gray-100">
                <li class="inline-flex items-center">
                    <a href="{{ route('products.index') }}" 
                    class="text-gray-600 hover:text-emerald-600 transition-colors duration-200 font-medium flex items-center">
                        Shop
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 font-semibold">{{ $vendor->vendor_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>


    <!-- Enhanced Vendor Header -->
    <div class="glass-effect rounded-2xl shadow-luxury mb-10 overflow-hidden border border-white/20 animate-fade-scale">
       <!-- Enhanced Banner with Gradient Overlay and Default Fallback -->
            @if($vendor->shop_banner_url)
                <div class="h-52 sm:h-72 relative overflow-hidden">
                    <img 
                        src="{{asset('storage/' . $vendor->shop_banner_url )  }}" 
                        alt="{{ $vendor->vendor_name }} banner"
                        class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/30 via-emerald-600/20 to-teal-600/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                </div>
            @else
                <div class="h-52 sm:h-72 relative overflow-hidden">
                    <img 
                        src="{{ asset('images/bg-banner.png') }}" 
                        alt="Default banner"
                        class="w-full h-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/30 via-emerald-600/20 to-teal-600/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                </div>
            @endif

        <!-- Enhanced Vendor Info -->
        <div class="relative px-6 pb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-end space-y-6 sm:space-y-0 sm:space-x-8 -mt-20">
                <!-- Enhanced Logo with Hover Effect -->
                <div class="relative group">
                    @if($vendor->shop_logo_url)
                        <div class="w-36 h-36 rounded-2xl overflow-hidden border-4 border-white shadow-xl bg-white transition-all duration-300 group-hover:shadow-2xl group-hover:scale-105">
                            <img 
                                src="{{ asset('storage/' . $vendor->shop_logo_url)  }}" 
                                alt="{{ $vendor->vendor_name }} logo"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    @else
                        <div class="w-36 h-36 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border-4 border-white shadow-xl flex items-center justify-center transition-all duration-300 group-hover:shadow-2xl group-hover:scale-105">
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                    @if($vendor->verification_status === 'verified')
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center border-3 border-white shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Vendor Details -->
                <div class="flex-1 pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
                        <div class="flex-1">
                            <h1 class="text-3xl sm:text-4xl font-bold text-black sm:text-white mb-3">
                                {{ $vendor->vendor_name }}
                            </h1>
                            
                            <!-- Enhanced Info Tags -->
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-4">
                                @if($vendor->stall_number)
                                    <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">Stall {{ $vendor->stall_number }}</span>
                                    </div>
                                @endif
                                @if($vendor->market_section)
                                    <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">{{ $vendor->market_section }}</span>
                                    </div>
                                @endif
                                @if($vendor->business_hours)
                                    <div class="flex items-center bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200">
                                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium">{{ $vendor->business_hours }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Enhanced Rating Display -->
                            @if($vendor->average_rating)
                                <div class="flex items-center mb-4">
                                    <div class="flex items-center bg-yellow-50 px-3 py-1.5 rounded-full border border-yellow-200">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $vendor->average_rating ? 'text-yellow-400' : 'text-yellow-200' }} transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm font-semibold text-gray-700">{{ number_format($vendor->average_rating, 1) }} rating</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Enhanced Status Badges -->
                        <div class="flex flex-wrap gap-3 sm:flex-col sm:items-end">
                            @if($vendor->verification_status === 'verified')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 border border-emerald-300 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Verified Vendor
                                </span>
                            @endif
                            @if($vendor->accepts_cod)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h10z"/>
                                    </svg>
                                    Cash on Delivery
                                </span>
                            @endif
                            @if($vendor->is_accepting_orders)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-800 border border-emerald-300 shadow-sm">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>
                                    Accepting Orders
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 shadow-sm">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                    Not Accepting Orders
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($vendor->description)
                        <div class="bg-gray-50 rounded-xl p-4 mt-6 border border-gray-100">
                            <p class="text-gray-700 leading-relaxed">{{ $vendor->description }}</p>
                        </div>
                    @endif

                    @if($vendor->public_contact_number || $vendor->public_email)
                        <div class="flex flex-wrap gap-4 mt-6">
                            @if($vendor->public_contact_number)
                                <a href="tel:{{ $vendor->public_contact_number }}" 
                                   class="flex items-center px-4 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-300 border border-emerald-200 shadow-sm hover:shadow-md font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    {{ $vendor->public_contact_number }}
                                </a>
                            @endif
                            @if($vendor->public_email)
                                <a href="mailto:{{ $vendor->public_email }}" 
                                   class="flex items-center px-4 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-300 border border-emerald-200 shadow-sm hover:shadow-md font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                    {{ $vendor->public_email }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Products Section -->
    <div class="animate-slide-in">
        <div class="sm:max-w-[90rem] sm:mx-auto">
           <!-- Enhanced Section Header with Background Image -->
<div class="relative rounded-2xl p-6 mb-8 shadow-lg bg-cover bg-center" 
     style="background-image: url('{{ asset('images/bg-banner.png') }}');">
    <div class="absolute inset-0 bg-emerald-800/50 rounded-2xl"></div> <!-- Overlay for readability -->

    <div class="relative">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <div class="bg-white/20 rounded-xl p-2 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white">
                    <circle cx="8" cy="21" r="1"/>
                    <circle cx="19" cy="21" r="1"/>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                </svg>
            </div>
            @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                Filtered Products
            @else
                All Products
            @endif
        </h2>
        <p class="text-emerald-100 mt-2">Discover quality products from {{ $vendor->vendor_name }}</p>
    </div>
</div>

            @if($products->count() > 0)
                <!-- Enhanced Product Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-12">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" 
                           class="product-card group relative bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:border-emerald-200 hover:-translate-y-2 transform">
                            
                            <!-- Enhanced Product Image -->
                            <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                @if($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" 
                                         alt="{{ $product->product_name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:text-gray-500 transition-colors duration-300">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Enhanced Badge -->
                                @if($product->is_budget_based)
                                    <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg border border-blue-400">
                                        Budget
                                    </span>
                                @endif

                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <!-- Enhanced Product Info -->
                            <div class="p-4 sm:p-5">
                                <!-- Category Tag -->
                                <div class="mb-3">
                                    @if($product->category)
                                        <span class="inline-block bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-300">
                                            {{ $product->category->category_name }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Product Name -->
                                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-sm sm:text-base group-hover:text-emerald-700 transition-colors duration-300">
                                    {{ $product->product_name }}
                                </h3>
                                
                                <!-- Vendor Name -->
                                <p class="text-xs sm:text-sm text-gray-600 mb-4">
                                    by <span class="text-emerald-600 font-semibold">{{ $product->vendor->vendor_name }}</span>
                                </p>
                                
                                <!-- Price -->
                                <div class="flex items-center">
                                        <span class="text-lg font-bold text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">/ {{ $product->unit }}</span>
                                </div>
                                
                
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                <div class="flex justify-center">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-2xl shadow-sm border border-gray-200">
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-20">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl w-40 h-40 flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-700 mb-4">No products found</h3>
                    <p class="text-gray-500 mb-8 max-w-lg mx-auto text-lg leading-relaxed">We couldn't find any products matching your search criteria. Try adjusting your filters or search terms to discover more items.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L10 4.414l6.293 6.293a1 1 0 001.414-1.414l-7-7z"/>
                                <path d="M13 17v-6h-2v6h2z"/>
                            </svg>
                            View All Products
                        </a>
                        <button onclick="document.querySelector('form').reset()" 
                                class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 font-bold border-2 border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection