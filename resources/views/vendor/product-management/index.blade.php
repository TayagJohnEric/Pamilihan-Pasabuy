@extends('layout.vendor')
@section('title', 'My Products')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-gray-50 overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 p-4 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">My Products</h2>
                    <p class="text-gray-600 text-sm sm:text-md">Manage your product catalog</p>
                </div>
               <a href="{{ route('vendor.products.create') }}"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 text-white text-sm sm:text-base font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-200">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Product
                    </a>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="px-4 sm:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl shadow">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm sm:text-base">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm sm:text-base">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Products Content -->
        <div class="p-3 sm:p-8">
            @if($products->count() > 0)
                <!-- Products Grid - 2 columns on mobile, 3 on tablet, 4 on desktop -->
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($products as $product)
                        <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group overflow-hidden">
                            <!-- Product Image -->
                            <div class="relative w-full overflow-hidden rounded-t-lg sm:rounded-t-xl bg-gray-50">
                                @if($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" 
                                         alt="{{ $product->product_name }}" 
                                         class="w-full h-32 sm:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-32 sm:h-48 bg-gray-50 flex items-center justify-center group-hover:bg-gray-100 transition-colors">
                                        <svg class="w-10 h-10 sm:w-16 sm:h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <!-- Status Badge Overlay -->
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold shadow-sm {{ $product->is_available ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full mr-1 sm:mr-2 {{ $product->is_available ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="p-3 sm:p-5">
                                <div class="mb-2 sm:mb-3">
                                    <h3 class="font-bold text-gray-900 text-sm sm:text-lg mb-1 truncate group-hover:text-green-700 transition-colors">{{ $product->product_name }}</h3>
                                    <p class="text-[10px] sm:text-sm text-gray-500 bg-gray-50 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md inline-block truncate max-w-full">{{ $product->category->category_name ?? 'No Category' }}</p>
                                </div>

                                <!-- Pricing Info -->
                                <div class="mb-3 sm:mb-4 bg-green-50 rounded-lg p-2 sm:p-3 border border-green-100">
                                    @if($product->is_budget_based)
                                        <div class="text-center">
                                            <p class="text-lg sm:text-2xl font-bold text-green-700 mb-0.5 sm:mb-1">₱{{ number_format($product->price, 2) }}</p>
                                            <div class="text-[10px] sm:text-sm text-gray-600">
                                                <span class="font-medium">Budget-based</span><br>
                                                <span class="text-green-600 font-semibold">₱{{ number_format($product->indicative_price_per_unit, 2) }}/pc</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <p class="text-lg sm:text-2xl font-bold text-green-700 mb-0.5 sm:mb-1">₱{{ number_format($product->price, 2) }}</p>
                                            <p class="text-[10px] sm:text-sm text-gray-600">
                                                <span class="font-medium">Stock:</span> 
                                                <span class="text-green-600 font-semibold">{{ $product->quantity_in_stock }} {{ $product->unit }}</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between border-t border-gray-100 pt-3 sm:pt-4">
                                    <div class="flex space-x-1 sm:space-x-3">
                                       <a href="{{ route('vendor.products.show', $product) }}" 
                                            class="text-gray-500 hover:text-gray-600 p-1.5 sm:p-2 rounded-md transition-all hover:bg-green-50 inline-flex items-center justify-center"
                                            title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 sm:w-4 sm:h-4">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </a>
                                        <a href="{{ route('vendor.products.edit', $product) }}" 
                                            class="text-gray-500 hover:text-gray-600 p-1.5 sm:p-2 rounded-md transition-all hover:bg-gray-50 inline-flex items-center justify-center"
                                            title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 sm:w-4 sm:h-4">
                                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                                                </svg>
                                            </a>
                                        <form action="{{ route('vendor.products.destroy', $product) }}" 
                                                method="POST" 
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="text-gray-500 hover:text-red-600 p-1.5 sm:p-2 rounded-md transition-all hover:bg-red-50"
                                                    title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 sm:w-4 sm:h-4">
                                                        <path d="M10 11v6"/>
                                                        <path d="M14 11v6"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                    </div>
                                    
                                    <!-- Availability Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                        <input type="checkbox" 
                                               class="sr-only peer availability-toggle" 
                                               data-product-id="{{ $product->id }}"
                                               {{ $product->is_available ? 'checked' : '' }}>
                                        <div class="w-9 h-5 sm:w-12 sm:h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 sm:peer-focus:ring-4 peer-focus:ring-emerald-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 sm:after:h-5 sm:after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-sm group-hover/toggle:shadow-md transition-shadow"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6 sm:mt-8 flex justify-center">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-2">
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12 sm:py-16 px-4">
                    <div class="max-w-md mx-auto">
                        <div class="bg-green-50 rounded-full w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">No products yet</h3>
                        <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-lg leading-relaxed">Start building your product catalog by adding your first product and reach more customers.</p>
                        <a href="{{ route('vendor.products.create') }}" 
                           class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-green-600 hover:bg-green-700 text-white text-sm sm:text-base font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-green-200">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Your First Product
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for availability toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.availability-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const productId = this.getAttribute('data-product-id');
            const isChecked = this.checked;
            
            fetch(`/vendor/products/${productId}/toggle-availability`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_available: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status badge
                    const productCard = this.closest('.bg-white');
                    const statusBadge = productCard ? productCard.querySelector('.absolute.top-2.right-2 span') : null;

                    // Reflect server-confirmed state on the checkbox
                    this.checked = !!data.is_available;

                    if (statusBadge) {
                        if (data.is_available) {
                            statusBadge.className = 'inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold shadow-sm bg-green-100 text-green-800 border border-green-200';
                            statusBadge.innerHTML = '<div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full mr-1 sm:mr-2 bg-green-500"></div>Available';
                        } else {
                            statusBadge.className = 'inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold shadow-sm bg-red-100 text-red-800 border border-red-200';
                            statusBadge.innerHTML = '<div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full mr-1 sm:mr-2 bg-red-500"></div>Unavailable';
                        }
                    }
                    
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    // Revert the toggle
                    this.checked = !isChecked;
                    showNotification('Failed to update product availability', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !isChecked;
                showNotification('An error occurred while updating product availability', 'error');
            });
        });
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 sm:top-6 right-4 sm:right-6 z-50 p-3 sm:p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full max-w-xs sm:max-w-sm ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'}`;
    
    const icon = type === 'success' 
        ? '<svg class="w-5 h-5 mr-3 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
        : '<svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
    
    notification.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="font-medium text-sm sm:text-base">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection