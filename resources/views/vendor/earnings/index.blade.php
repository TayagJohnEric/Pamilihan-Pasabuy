@extends('layout.vendor')
@section('title', 'Earnings Dashboard')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4 sm:mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Earnings Dashboard</h2>
                <p class="text-sm sm:text-base text-gray-600">Track your sales performance and earnings</p>
            </div>
        </div>

        <!-- Summary Statistics - 2 per row on mobile -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6">
            <!-- Total Earnings -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 sm:p-4 md:p-6 rounded-lg border border-green-200">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div class="p-2 bg-green-100 rounded-lg mb-2 sm:mb-0 w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-6 sm:h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-green-600">Total Earnings</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 break-words">
                            ₱{{ number_format($totalStats->total_earned ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Weekly Earnings -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 sm:p-4 md:p-6 rounded-lg border border-green-200">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div class="p-2 bg-green-100 rounded-lg mb-2 sm:mb-0 w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-6 sm:h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-green-600">Weekly Earnings</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 break-words">
                            ₱{{ number_format($weeklyStats->net_earned ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-gray-600">Orders: {{ $weeklyStats->order_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Earnings -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 sm:p-4 md:p-6 rounded-lg border border-green-200">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div class="p-2 bg-green-100 rounded-lg mb-2 sm:mb-0 w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:w-6 sm:h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-green-600">Monthly Earnings</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 break-words">
                            ₱{{ number_format($monthlyStats->net_earned ?? 0, 2) }}
                        </p>
                        <p class="text-xs text-gray-600">Orders: {{ $monthlyStats->order_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-3 sm:p-4 md:p-6 rounded-lg border border-green-200">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div class="p-2 bg-green-100 rounded-lg mb-2 sm:mb-0 w-fit">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-green-600">Total Orders</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">{{ $totalStats->total_orders ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 sm:p-6 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Insights</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div class="text-center">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Avg. Earnings / Order</p>
                    <p class="text-base sm:text-xl md:text-2xl font-bold text-gray-900">₱{{ number_format($avgEarningsPerOrder ?? 0, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Weekly Orders</p>
                    <p class="text-base sm:text-xl md:text-2xl font-bold text-gray-900">{{ $weeklyStats->order_count ?? 0 }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Monthly Orders</p>
                    <p class="text-base sm:text-xl md:text-2xl font-bold text-gray-900">{{ $monthlyStats->order_count ?? 0 }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Suggested Monthly Savings (10%)</p>
                    <p class="text-base sm:text-xl md:text-2xl font-bold text-gray-900">₱{{ number_format(($monthlyStats->net_earned ?? 0) * 0.10, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Earnings -->
        <div class="mt-4 sm:mt-6">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recent Earnings</h3>
                </div>
                @if(isset($recentEarnings) && $recentEarnings->count())
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-3 sm:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Earned</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentEarnings as $row)
                                <tr>
                                    <td class="px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 whitespace-nowrap">
                                        <a class="text-blue-600 hover:underline" href="{{ route('vendor.orders.show', ['order' => $row->order_id]) }}">#{{ $row->order_id }}</a>
                                    </td>
                                    <td class="px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->ordered_at)->format('M j, Y') }}</td>
                                    <td class="px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700">{{ $row->items_count }}</td>
                                    <td class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-900 whitespace-nowrap">₱{{ number_format($row->net_earned, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-sm text-gray-600">No recent earnings yet.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection