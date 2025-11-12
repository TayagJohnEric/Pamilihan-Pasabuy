@extends('layout.customer')

@section('title', 'Rate Order #' . $order->id)

@section('content')
    <div class="min-h-screen bg-gray-50 ">
        <div class="max-w-[90rem] mx-auto">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                <div class="flex items-center gap-3 mb-4">
                   
                    <div>
                        <h2 class="text-2xl sm:text-2xl font-bold text-gray-900">Rate Your Experience</h2>
                        <p class="text-gray-600 mt-1">Help us improve by sharing your feedback about Order #{{ $order->id }}</p>
                    </div>
                </div>
                
                <!-- Back Button -->
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('customer.orders.show', $order) }}" 
                       class="inline-flex items-center text-emerald-600 hover:text-emerald-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Order Details
                    </a>
                </div>
            </div>

            @if($hasRated)
                <!-- Already Rated Message -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-emerald-900">Thank You!</h3>
                        </div>
                        <p class="text-emerald-700">You have already submitted your rating for this order. Your feedback is valuable to us!</p>
                    </div>
                    
                    <!-- Show existing ratings -->
                    <div class="space-y-6">
                        @if($existingRiderRating && $order->rider)
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                    <h4 class="text-lg font-bold text-gray-900">Your Rating for Rider</h4>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-lg p-4 border border-gray-100">
                                    <div class="flex items-center gap-4">
                                        @if($order->rider->profile_image_url)
                                            <img src="{{ asset('storage/' . $order->rider->profile_image_url ) }}" 
                                                 alt="{{ $order->rider->first_name }}"
                                                 class="w-12 h-12 rounded-full object-cover shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200">
                                                <span class="text-emerald-700 font-bold text-sm">
                                                    {{ substr($order->rider->first_name, 0, 1) }}{{ substr($order->rider->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $order->rider->first_name }} {{ $order->rider->last_name }}</p>
                                            <p class="text-sm text-gray-600">Delivery Rider</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-start sm:items-end gap-2">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $existingRiderRating->rating_value ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm font-semibold text-gray-700">({{ $existingRiderRating->rating_value }}/5)</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($existingRiderRating->comment)
                                    <div class="mt-4 bg-white rounded-lg p-4 border border-gray-100">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Your Comment:</p>
                                        <p class="text-gray-600 leading-relaxed">{{ $existingRiderRating->comment }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @foreach($vendors as $vendor)
                            @if(isset($existingVendorRatings[$vendor->id]))
                                @php $vendorRating = $existingVendorRatings[$vendor->id]; @endphp
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                                        <h4 class="text-lg font-bold text-gray-900">Your Rating for Vendor</h4>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-lg p-4 border border-gray-100">
                                        <div class="flex items-center gap-4">
                                            @if($vendor->shop_logo_url)
                                                <img src="{{ asset('storage/' . $vendor->shop_logo_url) }}" 
                                                     alt="{{ $vendor->vendor_name }}"
                                                     class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $vendor->vendor_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $vendor->market_section ?? 'Vendor' }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-start sm:items-end gap-2">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $vendorRating->rating_value ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 text-sm font-semibold text-gray-700">({{ $vendorRating->rating_value }}/5)</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($vendorRating->comment)
                                        <div class="mt-4 bg-white rounded-lg p-4 border border-gray-100">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Your Comment:</p>
                                            <p class="text-gray-600 leading-relaxed">{{ $vendorRating->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Rating Form -->
                <form id="ratingForm" action="{{ route('customer.orders.rate.submit', $order) }}" method="POST">
                    @csrf
                    
                    <!-- Rider Rating -->
                    @if($order->rider)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Rate Your Rider</h3>
                            </div>
                            
                            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                <div class="flex flex-col sm:flex-row items-center gap-4 mb-6">
                                    @if($order->rider->profile_image_url)
                                        <img src="{{ asset('storage/' . $order->rider->profile_image_url ) }}" 
                                             alt="{{ $order->rider->first_name }}"
                                             class="w-20 h-20 rounded-full object-cover shadow-sm">
                                    @else
                                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200">
                                            <span class="text-emerald-700 font-bold text-lg">
                                                {{ substr($order->rider->first_name, 0, 1) }}{{ substr($order->rider->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="text-center sm:text-left">
                                        <h4 class="font-bold text-gray-900 text-xl">
                                            {{ $order->rider->first_name }} {{ $order->rider->last_name }}
                                        </h4>
                                        <p class="text-gray-600 font-medium">Delivery Rider</p>
                                    </div>
                                </div>
                                
                                <!-- Star Rating -->
                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Rating *</label>
                                    <div class="flex items-center justify-center sm:justify-start gap-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" 
                                                    class="star-button w-10 h-10 text-gray-300 hover:text-amber-400 transition-all duration-200 hover:scale-110"
                                                    data-target="rider_rating" 
                                                    data-value="{{ $i }}">
                                                <svg fill="currentColor" viewBox="0 0 20 20" class="w-full h-full">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rider_rating" id="rider_rating" required>
                                    @error('rider_rating')
                                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Comment -->
                                <div>
                                    <label for="rider_comment" class="block text-sm font-semibold text-gray-700 mb-3">
                                        Comment (Optional)
                                    </label>
                                    <textarea name="rider_comment" 
                                              id="rider_comment" 
                                              rows="4" 
                                              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                              placeholder="Share your experience with the delivery service...">{{ old('rider_comment') }}</textarea>
                                    @error('rider_comment')
                                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Vendor Ratings -->
                    @if($vendors->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Rate the Vendors</h3>
                            </div>
                            
                            <div class="space-y-8">
                                @foreach($vendors as $vendor)
                                    <div class="bg-gray-50 rounded-xl p-6">
                                        <div class="flex flex-col sm:flex-row items-center gap-4 mb-6">
                                            @if($vendor->shop_logo_url)
                                                <img src="{{asset('storage/' . $vendor->shop_logo_url)  }}" 
                                                     alt="{{ $vendor->vendor_name }}"
                                                     class="w-20 h-20 rounded-xl object-cover shadow-sm">
                                            @else
                                                <div class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center border border-gray-300">
                                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="text-center sm:text-left">
                                                <h4 class="font-bold text-gray-900 text-xl">{{ $vendor->vendor_name }}</h4>
                                                <p class="text-gray-600 font-medium">{{ $vendor->market_section ?? 'Vendor' }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Star Rating -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-semibold text-gray-700 mb-3">Rating *</label>
                                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" 
                                                            class="star-button w-10 h-10 text-gray-300 hover:text-amber-400 transition-all duration-200 hover:scale-110"
                                                            data-target="vendor_rating_{{ $vendor->id }}" 
                                                            data-value="{{ $i }}">
                                                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-full h-full">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="vendor_ratings[{{ $vendor->id }}]" id="vendor_rating_{{ $vendor->id }}" required>
                                            @error("vendor_ratings.{$vendor->id}")
                                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Comment -->
                                        <div>
                                            <label for="vendor_comment_{{ $vendor->id }}" class="block text-sm font-semibold text-gray-700 mb-3">
                                                Comment (Optional)
                                            </label>
                                            <textarea name="vendor_comments[{{ $vendor->id }}]" 
                                                      id="vendor_comment_{{ $vendor->id }}" 
                                                      rows="4" 
                                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                                      placeholder="Share your experience with {{ $vendor->vendor_name }}...">{{ old("vendor_comments.{$vendor->id}") }}</textarea>
                                            @error("vendor_comments.{$vendor->id}")
                                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Submit Button -->
                    <div class="flex justify-center sm:justify-end">
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full sm:w-auto px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 disabled:bg-emerald-400 disabled:cursor-not-allowed shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <span class="btn-text">Submit Ratings</span>
                            <span class="btn-loading hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Star rating functionality
            $('.star-button').on('click', function() {
                const target = $(this).data('target');
                const value = $(this).data('value');
                const container = $(this).parent();
                
                // Set the hidden input value
                $('#' + target).val(value);
                
                // Update star colors
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (starValue <= value) {
                        $(this).removeClass('text-gray-300').addClass('text-amber-400');
                    } else {
                        $(this).removeClass('text-amber-400').addClass('text-gray-300');
                    }
                });
            });
            
            // Star rating hover effects
            $('.star-button').on('mouseenter', function() {
                const value = $(this).data('value');
                const container = $(this).parent();
                
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (starValue <= value) {
                        $(this).addClass('text-amber-400');
                    }
                });
            });
            
            $('.star-button').parent().on('mouseleave', function() {
                const container = $(this);
                const target = container.find('.star-button:first').data('target');
                const selectedValue = $('#' + target).val();
                
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (selectedValue && starValue <= selectedValue) {
                        $(this).removeClass('text-gray-300').addClass('text-amber-400');
                    } else {
                        $(this).removeClass('text-amber-400').addClass('text-gray-300');
                    }
                });
            });
            
            // Form submission with AJAX
            $('#ratingForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate that all required ratings are selected
                let isValid = true;
                const requiredRatings = ['rider_rating'];
                
                @foreach($vendors as $vendor)
                    requiredRatings.push('vendor_rating_{{ $vendor->id }}');
                @endforeach
                
                requiredRatings.forEach(function(ratingName) {
                    const ratingValue = $('#' + ratingName).val();
                    if (!ratingValue) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    // Show error message
                    if ($('.rating-error').length === 0) {
                        $('#ratingForm').prepend('<div class="rating-error bg-red-50 border border-red-200 rounded-xl p-4 mb-6"><p class="text-red-800 font-medium">Please provide ratings for all items before submitting.</p></div>');
                    }
                    return;
                } else {
                    $('.rating-error').remove();
                }
                
                // Disable submit button and show loading state
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.find('.btn-text').hide();
                submitBtn.find('.btn-loading').show();
                
                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('#ratingForm').hide();
                            $('.max-w-4xl.mx-auto').prepend(`
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 mb-8">
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                                        <div class="flex items-center mb-3">
                                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-emerald-900">${response.message}</h3>
                                        </div>
                                        <p class="text-emerald-700">Your feedback helps improve our service quality.</p>
                                    </div>
                                </div>
                            `);
                            
                            // Redirect after a short delay
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.btn-text').show();
                        submitBtn.find('.btn-loading').hide();
                        
                        let errorMessage = 'Failed to submit ratings. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        // Show error message
                        $('.rating-error').remove();
                        $('#ratingForm').prepend(`
                            <div class="rating-error bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                                <p class="text-red-800 font-medium">${errorMessage}</p>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
@endsection