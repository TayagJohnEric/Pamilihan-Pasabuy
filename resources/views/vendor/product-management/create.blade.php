@extends('layout.vendor')
@section('title', 'Add New Product')
@section('content')
<div class="max-w-[90rem] mx-auto ">
    <div class="">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Add New Product</h2>
                <p class="text-gray-600 text-sm sm:text-base">Create a new product for your catalog</p>
            </div>
            <a href="{{ route('vendor.products.index') }}" 
               class="bg-white border border-gray-300 text-gray-800 inline-flex items-center justify-center px-3 sm:px-4 py-2 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 sm:p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-xs sm:text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" 
                           id="product_name" 
                           name="product_name" 
                           value="{{ old('product_name') }}"
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('product_name') border-red-500 @enderror"
                           placeholder="Enter product name">
                    @error('product_name')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                    <input type="text" 
                           id="unit" 
                           name="unit" 
                           value="{{ old('unit') }}"
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('unit') border-red-500 @enderror"
                           placeholder="e.g., kg, piece, liter">
                    @error('unit')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Max: 2MB. Formats: JPEG, PNG, JPG, GIF</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Enter product description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing Model -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Pricing Model *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <!-- Standard Pricing -->
                    <div class="relative">
                        <input type="radio" 
                               id="standard_pricing" 
                               name="pricing_model" 
                               value="standard" 
                               class="sr-only peer"
                               {{ old('pricing_model', 'standard') == 'standard' ? 'checked' : '' }}>
                        <label for="standard_pricing" 
                               class="flex p-3 sm:p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                            <div class="flex items-start sm:items-center w-full">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex-shrink-0 mt-0.5 sm:mt-0 peer-checked:border-green-600 peer-checked:bg-green-600 relative">
                                    <div class="absolute inset-0 m-auto w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Standard Pricing</div>
                                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Fixed price with stock quantity</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Budget-Based Pricing -->
                    <div class="relative">
                        <input type="radio" 
                               id="budget_pricing" 
                               name="pricing_model" 
                               value="budget_based" 
                               class="sr-only peer"
                               {{ old('pricing_model') == 'budget_based' ? 'checked' : '' }}>
                        <label for="budget_pricing" 
                               class="flex p-3 sm:p-4 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                            <div class="flex items-start sm:items-center w-full">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex-shrink-0 mt-0.5 sm:mt-0 peer-checked:border-green-600 peer-checked:bg-green-600 relative">
                                    <div class="absolute inset-0 m-auto w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Budget-Based Pricing</div>
                                    <div class="text-xs sm:text-sm text-gray-500 mt-0.5">Price per unit, quantity varies</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @error('pricing_model')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Stock Fields (Always Visible) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) *</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           value="{{ old('price') }}"
                           step="0.01" 
                           min="0"
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('price')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity_in_stock" class="block text-sm font-medium text-gray-700 mb-2">Quantity in Stock *</label>
                    <input type="number" 
                           id="quantity_in_stock" 
                           name="quantity_in_stock" 
                           value="{{ old('quantity_in_stock') }}"
                           min="0"
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('quantity_in_stock') border-red-500 @enderror"
                           placeholder="0">
                    @error('quantity_in_stock')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Budget-Based Pricing Fields (Shown when Budget-Based is selected) -->
            <div id="budget_fields" class="hidden">
                <div class="max-w-full sm:max-w-md">
                    <label for="indicative_price_per_unit" class="block text-sm font-medium text-gray-700 mb-2">Indicative Price per Unit (₱) *</label>
                    <input type="number" 
                           id="indicative_price_per_unit" 
                           name="indicative_price_per_unit" 
                           value="{{ old('indicative_price_per_unit') }}"
                           step="0.01" 
                           min="0"
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('indicative_price_per_unit') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('indicative_price_per_unit')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs sm:text-sm text-gray-500">Estimated price. Actual quantity depends on customer's budget.</p>
                </div>
            </div>

            <!-- Availability -->
            <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                <label class="flex items-start sm:items-center">
                    <input type="checkbox" 
                           name="is_available" 
                           value="1" 
                           {{ old('is_available', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 mt-0.5 sm:mt-0 flex-shrink-0">
                    <span class="ml-2 text-sm font-medium text-gray-700">Product is available for sale</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="w-full sm:w-auto sm:flex-1 sm:max-w-xs px-6 py-3 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 text-white text-sm sm:text-base font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Create Product
                </button>
                <a href="{{ route('vendor.products.index') }}" 
                   class="w-full sm:w-auto px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm sm:text-base font-medium rounded-lg text-center transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const standardRadio = document.getElementById('standard_pricing');
    const budgetRadio = document.getElementById('budget_pricing');
    const budgetFields = document.getElementById('budget_fields');
    
    function togglePricingFields() {
        if (budgetRadio.checked) {
            // Show budget-based fields when budget pricing is selected
            budgetFields.classList.remove('hidden');
            document.getElementById('indicative_price_per_unit').disabled = false;
        } else {
            // Hide budget-based fields when standard pricing is selected
            budgetFields.classList.add('hidden');
            document.getElementById('indicative_price_per_unit').disabled = true;
        }
        
        // Price and stock fields remain enabled and visible for both pricing models
        document.getElementById('price').disabled = false;
        document.getElementById('quantity_in_stock').disabled = false;
    }
    
    standardRadio.addEventListener('change', togglePricingFields);
    budgetRadio.addEventListener('change', togglePricingFields);
    
    // Initialize on page load
    togglePricingFields();
});
</script>
@endsection