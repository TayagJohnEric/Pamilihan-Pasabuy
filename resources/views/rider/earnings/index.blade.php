@extends('layout.rider')
@section('title', 'Earnings Dashboard')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Earnings Dashboard</h1>
                <p class="text-gray-600 mt-1">Track your delivery earnings and performance</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Current Month Earnings -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">This Month</p>
                    <p class="text-2xl font-bold">₱{{ number_format($currentMonthEarnings, 2) }}</p>
                    <p class="text-blue-100 text-sm">{{ $currentMonthDeliveries }} deliveries</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Incentives -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Incentives</p>
                    <p class="text-2xl font-bold">₱{{ number_format($currentMonthIncentives, 2) }}</p>
                    <p class="text-green-100 text-sm">This month</p>
                </div>
                <div class="bg-emerald-500 p-3 rounded-full">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 lucide lucide-hand-coins-icon lucide-hand-coins"><path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/></svg>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Earnings</p>
                    <p class="text-2xl font-bold">₱{{ number_format($totalEarnings, 2) }}</p>
                    <p class="text-purple-100 text-sm">All time</p>
                </div>
                <div class="bg-purple-500 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Rating</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold">{{ number_format($riderStats->average_rating ?? 0, 1) }}</p>
                        <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <p class="text-orange-100 text-sm">{{ $riderStats->total_deliveries ?? 0 }} total deliveries</p>
                </div>
                <div class="bg-yellow-400 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Earnings Chart -->
    <div class="mb-6">
        <!-- Daily Earnings (Last 7 Days) -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Earnings (Last 7 Days)</h3>
            <div class="h-64">
                <div class="flex items-end justify-between h-full space-x-2">
                    @foreach($dailyEarnings as $day)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="bg-blue-500 rounded-t w-full transition-all hover:bg-blue-600" 
                             style="height: {{ $day['earnings'] > 0 ? ($day['earnings'] / max(array_column($dailyEarnings, 'earnings'))) * 200 : 2 }}px;">
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs text-gray-600">{{ $day['date'] }}</p>
                            <p class="text-sm font-medium">₱{{ number_format($day['earnings'], 0) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection