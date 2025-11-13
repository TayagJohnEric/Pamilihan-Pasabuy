@extends('layout.vendor')
@section('title', 'Customer Ratings & Reviews')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Customer Ratings & Reviews</h2>
                <p class="text-gray-600">Track your customer satisfaction and feedback</p>
            </div>
            
           <!-- Filter Form -->
<div class="hidden lg:block mt-4 lg:mt-0">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <select name="rating_filter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="all" {{ request('rating_filter', 'all') == 'all' ? 'selected' : '' }}>All Ratings</option>
            <option value="5" {{ request('rating_filter') == '5' ? 'selected' : '' }}>5 Stars</option>
            <option value="4" {{ request('rating_filter') == '4' ? 'selected' : '' }}>4 Stars</option>
            <option value="3" {{ request('rating_filter') == '3' ? 'selected' : '' }}>3 Stars</option>
            <option value="2" {{ request('rating_filter') == '2' ? 'selected' : '' }}>2 Stars</option>
            <option value="1" {{ request('rating_filter') == '1' ? 'selected' : '' }}>1 Star</option>
        </select>
        <button type="submit" 
            class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white px-6 py-2 rounded-lg hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-sm hover:shadow-md">
            Filter
        </button>
    </form>
</div>

        </div>

        <!-- Rating Statistics -->
        @if($ratingStats && $ratingStats->total_ratings > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Overall Rating Card -->
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-50 p-6 rounded-lg border">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-800 mb-2">
                        {{ number_format($ratingStats->average_rating ?? 0, 1) }}
                    </div>
                    <div class="flex justify-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($ratingStats->average_rating))
                                <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @elseif($i - $ratingStats->average_rating < 1)
                                <svg class="w-6 h-6 text-yellow-400" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="half-fill">
                                            <stop offset="50%" stop-color="rgb(251, 191, 36)"/>
                                            <stop offset="50%" stop-color="rgb(229, 231, 235)"/>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#half-fill)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-300 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-600">Based on {{ $ratingStats->total_ratings }} reviews</p>
                </div>
            </div>

            <!-- Rating Breakdown -->
            <div class="bg-white p-6 rounded-lg border">
                <h4 class="font-semibold text-gray-800 mb-4">Rating Breakdown</h4>
                @foreach([5,4,3,2,1] as $star)
                    @php
                        $count = $ratingStats->{"${star}_star"} ?? 0;
                        $percentage = $ratingStats->total_ratings > 0 ? ($count / $ratingStats->total_ratings) * 100 : 0;
                    @endphp
                    <div class="flex items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 w-8">{{ $star }}</span>
                        <svg class="w-4 h-4 text-yellow-400 fill-current mr-2" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-10">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-gray-400 mb-2">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <p class="text-gray-600 text-lg">No ratings yet</p>
            <p class="text-gray-500">Your customer ratings will appear here once you receive them</p>
        </div>
        @endif
    </div>

    <!-- Reviews List -->
    @if($ratings->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Customer Reviews</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @foreach($ratings as $rating)
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    @php
                                        $initials = strtoupper(substr($rating->user->first_name ?? '', 0, 1) . substr($rating->user->last_name ?? '', 0, 1));
                                        @endphp
                                        {{ $initials }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <h4 class="font-medium text-gray-900">
                                    {{ $rating->user->first_name }} {{ $rating->user->last_name }}
                                </h4>
                                <div class="flex items-center mt-1 sm:mt-0">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating_value)
                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">{{ $rating->rating_value }}/5</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Order #{{ $rating->order->id }} • {{ $rating->created_at->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($rating->comment)
                <div class="ml-0 sm:ml-14">
                    <p class="text-gray-700 leading-relaxed">{{ $rating->comment }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($ratings->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $ratings->withQueryString()->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection