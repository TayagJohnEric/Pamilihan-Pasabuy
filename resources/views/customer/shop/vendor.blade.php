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