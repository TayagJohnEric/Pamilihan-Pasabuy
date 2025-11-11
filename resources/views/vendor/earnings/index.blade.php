@extends('layout.vendor')
@section('title', 'Earnings Dashboard')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Earnings Dashboard</h2>
                <p class="text-gray-600">Track your sales performance and earnings</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Earnings -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_earned ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-green-600 lucide lucide-chart-line-icon lucide-chart-line"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Sales</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_sales ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Commission Paid -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Commission Paid</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_commission ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStats->total_orders ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Period Sales -->
        @if($currentPeriodSales && $currentPeriodSales->total_sales > 0)
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Period Sales</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $currentPeriodSales->order_count }}</p>
                    <p class="text-sm text-gray-600">Orders</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentPeriodSales->total_sales, 2) }}</p>
                    <p class="text-sm text-gray-600">Gross Sales</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentPeriodSales->estimated_commission, 2) }}</p>
                    <p class="text-sm text-gray-600">Est. Commission</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">
                Period: {{ $currentPeriodStart->format('M j, Y') }} - Present
            </p>
        </div>
        @endif
    </div>

</div>
@endsection