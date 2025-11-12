@extends('layout.customer')

@section('title', 'Top Rated Riders - Merit System')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Our Top Rated Riders</h2>
            <p class="text-sm sm:text-base text-gray-600">Choose from our highest performing delivery partners based on ratings, experience, and customer feedback.</p>
        </div>

        <!-- Top 3 Podium -->
        @if($riders->count() >= 3)
            <div class="mb-8">
                <div class="flex items-end justify-center gap-3 sm:gap-6 mb-8">
                    <!-- Second Place -->
                    <div class="flex flex-col items-center flex-1 max-w-[120px]">
                        <div class="relative mb-3">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-4 border-gray-300 bg-gray-200">
                                @if($riders[1]->profile_image_url)
                                    <img src="{{ asset('storage/' . $riders[1]->profile_image_url) }}" alt="{{ $riders[1]->first_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-xl font-semibold">
                                        {{ strtoupper(substr($riders[1]->first_name, 0, 1)) }}{{ strtoupper(substr($riders[1]->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-300 text-white w-8 h-8 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">
                                2
                            </div>
                        </div>
                        <h4 class="font-semibold text-gray-800 text-sm text-center truncate w-full">{{ $riders[1]->first_name }}</h4>
                        <p class="text-xs text-gray-500">{{ number_format($riders[1]->merit_score, 0) }} points</p>
                    </div>

                    <!-- First Place -->
                    <div class="flex flex-col items-center flex-1 max-w-[140px]">
                        <div class="text-4xl mb-2">👑</div>
                        <div class="relative mb-3">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-4 border-yellow-400 bg-gray-200">
                                @if($riders[0]->profile_image_url)
                                    <img src="{{ asset('storage/' . $riders[0]->profile_image_url) }}" alt="{{ $riders[0]->first_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-2xl font-semibold">
                                        {{ strtoupper(substr($riders[0]->first_name, 0, 1)) }}{{ strtoupper(substr($riders[0]->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-yellow-400 text-white w-9 h-9 rounded-full flex items-center justify-center text-xl font-bold shadow-lg">
                                1
                            </div>
                        </div>
                        <h4 class="font-semibold text-gray-800 text-base text-center truncate w-full">{{ $riders[0]->first_name }}</h4>
                        <p class="text-sm text-gray-500">{{ number_format($riders[0]->merit_score, 0) }} points</p>
                    </div>

                    <!-- Third Place -->
                    <div class="flex flex-col items-center flex-1 max-w-[120px]">
                        <div class="relative mb-3">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-4 border-orange-300 bg-gray-200">
                                @if($riders[2]->profile_image_url)
                                    <img src="{{ asset('storage/' . $riders[2]->profile_image_url) }}" alt="{{ $riders[2]->first_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-xl font-semibold">
                                        {{ strtoupper(substr($riders[2]->first_name, 0, 1)) }}{{ strtoupper(substr($riders[2]->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-orange-300 text-white w-8 h-8 rounded-full flex items-center justify-center text-lg font-bold shadow-lg">
                                3
                            </div>
                        </div>
                        <h4 class="font-semibold text-gray-800 text-sm text-center truncate w-full">{{ $riders[2]->first_name }}</h4>
                        <p class="text-xs text-gray-500">{{ number_format($riders[2]->merit_score, 0) }} points</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Leaderboard Rankings -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Table Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white px-4 sm:px-6 py-3 flex items-center justify-between text-xs sm:text-sm font-semibold">
        <div class="flex items-center space-x-3 sm:space-x-4 flex-1">
            <span class="w-8 sm:w-10">Rank</span>
            <span class="flex-1">Rider</span>
        </div>
        <span class="w-20 sm:w-24 text-right">Points</span>
    </div>

    <!-- Rankings List -->
    <div class="divide-y divide-gray-100">
        @if($riders->count() > 0)
            @foreach($riders as $index => $rider)
                <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-white transition-colors duration-200 cursor-pointer rider-card {{ $index < 3 ? 'bg-white' : '' }}"
                     data-rider-id="{{ $rider->id }}">
                    <div class="flex items-center justify-between">
                        <!-- Left Section -->
                        <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                            <!-- Rank Badge -->
                            <div class="flex-shrink-0 w-8 sm:w-10 h-8 sm:h-10 rounded-full flex items-center justify-center text-sm sm:text-base font-bold
                                {{ $index === 0 ? 'bg-yellow-400 text-white' : '' }}
                                {{ $index === 1 ? 'bg-gray-300 text-white' : '' }}
                                {{ $index === 2 ? 'bg-orange-300 text-white' : '' }}
                                {{ $index > 2 ? 'bg-emerald-100 text-emerald-600' : '' }}">
                                {{ $index + 1 }}
                            </div>

                            <!-- Profile Image -->
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                                @if($rider->profile_image_url)
                                    <img src="{{ asset('storage/' . $rider->profile_image_url) }}" alt="{{ $rider->first_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm sm:text-base font-semibold">
                                        {{ strtoupper(substr($rider->first_name, 0, 1)) }}{{ strtoupper(substr($rider->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Name & Rating -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm sm:text-base font-semibold text-gray-800 truncate">
                                    {{ $rider->first_name }} {{ $rider->last_name }}
                                </h4>
                                <div class="flex items-center space-x-1 text-xs text-gray-500">
                                    <span class="text-yellow-400">★</span>
                                    <span>{{ number_format($rider->average_rating ?: 0, 1) }}</span>
                                    <span class="hidden sm:inline">•</span>
                                    <span class="hidden sm:inline">{{ number_format($rider->total_deliveries) }} trips</span>
                                </div>
                            </div>
                        </div>

                        <!-- Points -->
                        <div class="flex-shrink-0 w-20 sm:w-24 text-right">
                            <div class="text-base sm:text-lg font-bold text-emerald-600">
                                {{ number_format($rider->merit_score, 0) }}
                            </div>
                            <div class="text-xs text-gray-500">points</div>
                        </div>
                    </div>
                </div>
            @endforeach
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


        <!-- Refresh Button -->
        <div class="mt-6 text-center">
           <button id="refreshRankings" 
                class="bg-gradient-to-r from-emerald-600 to-green-600 text-white px-6 py-3 rounded-full transition-all duration-300 
                    hover:from-emerald-700 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 
                    focus:ring-offset-2 inline-flex items-center space-x-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <span>Refresh Rankings</span>
            </button>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle rider card clicks - navigate to rider profile
            $('.rider-card').on('click', function() {
                const riderId = $(this).data('rider-id');
                const profileTemplate = @json(route('merit-system.rider.profile', ['riderId' => '__RID__']));
                window.location.href = profileTemplate.replace('__RID__', riderId);
            });

            // Handle refresh rankings button
            $('#refreshRankings').on('click', function() {
                const $button = $(this);
                const $buttonText = $button.find('span');
                const originalText = $buttonText.text();
                
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
                            $buttonText.text('Updated!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                    error: function() {
                        $buttonText.text('Error - Try Again');
                        setTimeout(function() {
                            $button.prop('disabled', false);
                            $buttonText.text(originalText);
                        }, 2000);
                    }
                });
            });
        });
    </script>
@endsection