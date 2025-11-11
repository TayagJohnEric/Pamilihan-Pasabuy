@extends('layout.customer')

@section('title', 'Top Rated Riders - Merit System')

@section('content')
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Top Rated Riders</h2>
                    <p class="text-sm sm:text-base text-gray-600">Choose from our highest performing delivery partners based on ratings, experience, and customer feedback.</p>
                </div>
                
                <!-- Refresh Rankings Button -->
                <button id="refreshRankings" 
                    class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Refresh Rankings</span>
                </button>
            </div>

            <!-- Merit Score Legend -->
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">Merit Score Calculation</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full flex-shrink-0"></div>
                        <span>Average Rating (60%)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full flex-shrink-0"></div>
                        <span>Total Deliveries (30%)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full flex-shrink-0"></div>
                        <span>Customer Comments (10%)</span>
                    </div>
                </div>
            </div>

            <!-- Suggested Riders Section -->
            @if($riders->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                        <span>🏅</span>
                        <span>Suggested Riders</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                        @foreach($riders->take(3) as $index => $rider)
                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-lg p-4 sm:p-6 hover:shadow-lg transition duration-200 cursor-pointer rider-card"
                                 data-rider-id="{{ $rider->id }}">
                                
                                <!-- Suggested Badge -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="bg-yellow-500 text-white px-2 sm:px-3 py-1 rounded-full text-xs font-semibold flex items-center space-x-1">
                                        @if($index === 0)
                                            <span>⭐</span>
                                            <span>#1 Top Rated</span>
                                        @elseif($index === 1)
                                            <span>🥈</span>
                                            <span>#2 Recommended</span>
                                        @else
                                            <span>🥉</span>
                                            <span>#3 Suggested</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Availability Status -->
                                    <div class="flex items-center space-x-1 text-xs">
                                        <div class="w-2 h-2 rounded-full {{ $rider->is_available ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="{{ $rider->is_available ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $rider->is_available ? 'Available' : 'Busy' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Rider Info -->
                                <div class="flex items-center space-x-3 sm:space-x-4 mb-4">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                                        @if($rider->profile_image_url)
                                            <img src="{{ asset('storage/' . $rider->profile_image_url) }}" alt="{{ $rider->first_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-lg sm:text-xl font-semibold">
                                                {{ strtoupper(substr($rider->first_name, 0, 1)) }}{{ strtoupper(substr($rider->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $rider->first_name }} {{ $rider->last_name }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600 capitalize truncate">{{ $rider->vehicle_type ?? 'Delivery Partner' }}</p>
                                    </div>
                                </div>

                                <!-- Statistics -->
                                <div class="grid grid-cols-2 gap-2 sm:gap-4 text-center">
                                    <div class="bg-white rounded-lg p-2 sm:p-3">
                                        <div class="text-base sm:text-lg font-bold text-green-600">{{ number_format($rider->merit_score, 1) }}</div>
                                        <div class="text-xs text-gray-500">Merit Score</div>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-2 sm:p-3">
                                        <div class="text-base sm:text-lg font-bold text-blue-600 flex items-center justify-center space-x-1">
                                            <span>{{ number_format($rider->average_rating, 1) }}</span>
                                            <span class="text-yellow-400">★</span>
                                        </div>
                                        <div class="text-xs text-gray-500">Rating</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 sm:gap-4 text-center mt-2">
                                    <div class="bg-white rounded-lg p-2 sm:p-3">
                                        <div class="text-base sm:text-lg font-bold text-purple-600">{{ number_format($rider->total_deliveries) }}</div>
                                        <div class="text-xs text-gray-500">Deliveries</div>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-2 sm:p-3">
                                        <div class="text-base sm:text-lg font-bold text-orange-600">{{ $rider->comment_count }}</div>
                                        <div class="text-xs text-gray-500">Reviews</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- All Riders Section -->
            <div class="border-t pt-6 sm:pt-8">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4 sm:mb-6">All Available Riders</h3>
                
                @if($riders->count() > 0)
                    <div id="ridersContainer" class="space-y-3 sm:space-y-4">
                        @foreach($riders as $index => $rider)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition duration-200 cursor-pointer rider-card"
                                 data-rider-id="{{ $rider->id }}">
                                
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <!-- Left Section - Rider Info -->
                                    <div class="flex items-center space-x-3 sm:space-x-6 flex-grow min-w-0">
                                        <!-- Rank Number -->
                                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                            <span class="font-bold text-gray-600 text-sm sm:text-base">#{{ $index + 1 }}</span>
                                        </div>

                                        <!-- Profile Image -->
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                                            @if($rider->profile_image_url)
                                                <img src="{{ asset('storage/' . $rider->profile_image_url) }}" alt="{{ $rider->first_name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-base sm:text-xl font-semibold">
                                                    {{ strtoupper(substr($rider->first_name, 0, 1)) }}{{ strtoupper(substr($rider->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Rider Details -->
                                        <div class="flex-grow min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-1 sm:mb-2">
                                                <h4 class="text-base sm:text-lg font-semibold text-gray-800 truncate">{{ $rider->first_name }} {{ $rider->last_name }}</h4>
                                                
                                                <div class="flex items-center space-x-2 mt-1 sm:mt-0">
                                                    <!-- Suggested Badge -->
                                                    @if(in_array($rider->id, $suggestedRiders))
                                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                                            🏅 Suggested
                                                        </span>
                                                    @endif
                                                    
                                                    <!-- Availability -->
                                                    <div class="flex items-center space-x-1">
                                                        <div class="w-2 h-2 rounded-full {{ $rider->is_available ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                                        <span class="text-xs {{ $rider->is_available ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $rider->is_available ? 'Available' : 'Busy' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <p class="text-xs sm:text-sm text-gray-600 capitalize truncate">
                                                {{ $rider->vehicle_type ? ucfirst($rider->vehicle_type) . ' Delivery' : 'Delivery Partner' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Right Section - Statistics -->
                                    <div class="flex items-center justify-between sm:justify-end sm:space-x-4 lg:space-x-8">
                                        <!-- Merit Score -->
                                        <div class="text-center">
                                            <div class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($rider->merit_score, 1) }}</div>
                                            <div class="text-xs text-gray-500 font-medium">Merit</div>
                                        </div>
                                        
                                        <!-- Rating -->
                                        <div class="text-center">
                                            <div class="text-base sm:text-xl font-bold text-blue-600 flex items-center space-x-1">
                                                <span>{{ number_format($rider->average_rating ?: 0, 1) }}</span>
                                                <span class="text-yellow-400">★</span>
                                            </div>
                                            <div class="text-xs text-gray-500 font-medium">Rating</div>
                                        </div>
                                        
                                        <!-- Deliveries - Hidden on smallest screens -->
                                        <div class="text-center hidden xs:block">
                                            <div class="text-base sm:text-xl font-bold text-purple-600">{{ number_format($rider->total_deliveries) }}</div>
                                            <div class="text-xs text-gray-500 font-medium hidden sm:block">Deliveries</div>
                                            <div class="text-xs text-gray-500 font-medium sm:hidden">Trips</div>
                                        </div>
                                        
                                        <!-- Comments - Hidden on mobile -->
                                        <div class="text-center hidden sm:block">
                                            <div class="text-xl font-bold text-orange-600">{{ $rider->comment_count }}</div>
                                            <div class="text-xs text-gray-500 font-medium">Reviews</div>
                                        </div>
                                        
                                        <!-- View Profile Arrow -->
                                        <div class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- No Riders Available -->
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-4xl sm:text-6xl mb-4">🏍️</div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-600 mb-2">No Riders Available</h3>
                        <p class="text-sm sm:text-base text-gray-500">There are currently no verified riders in the system.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle rider card clicks - navigate to rider profile
            $('.rider-card').on('click', function() {
                const riderId = $(this).data('rider-id');
                // Build the URL using a placeholder to satisfy Laravel's required parameter
                const profileTemplate = @json(route('merit-system.rider.profile', ['riderId' => '__RID__']));
                window.location.href = profileTemplate.replace('__RID__', riderId);
            });

            // Handle refresh rankings button
            $('#refreshRankings').on('click', function() {
                const $button = $(this);
                const $buttonText = $button.find('span');
                const originalText = $buttonText.text();
                
                // Show loading state
                $button.prop('disabled', true);
                $buttonText.text('Refreshing...');
                
                $.ajax({
                    url: '{{ route("merit-system.refresh.rankings") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message briefly
                            $buttonText.text('Updated!');
                            
                            // Reload the page to show updated rankings
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function() {
                        // Show error message briefly
                        $buttonText.text('Error - Try Again');
                        
                        setTimeout(function() {
                            $button.prop('disabled', false);
                            $buttonText.text(originalText);
                        }, 2000);
                    }
                });
            });

            // Add hover effects (disabled on touch devices)w
            if (!('ontouchstart' in window)) {
                $('.rider-card').hover(
                    function() {
                        $(this).addClass('transform scale-[1.02]');
                    },
                    function() {
                        $(this).removeClass('transform scale-[1.02]');
                    }
                );
            }
        });
    </script>
@endsection