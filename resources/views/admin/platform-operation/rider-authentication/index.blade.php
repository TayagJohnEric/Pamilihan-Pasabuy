@extends('layout.admin')

@section('title', 'Rider Selfie Monitoring')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 py-8 space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl text-white p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-emerald-200">Platform Operations</p>
                    <h1 class="text-3xl md:text-4xl font-semibold mt-2">Rider Selfie Monitoring</h1>
                    <p class="text-emerald-100 mt-3 max-w-2xl">
                        Review the latest login selfies submitted by riders together with their current verification status
                        and profile activity.
                    </p>
                </div>
                <div class="bg-white/10 rounded-2xl border border-white/20 px-6 py-4 backdrop-blur-md">
                    <p class="text-sm text-emerald-100">Updated</p>
                    <p class="text-2xl font-bold">{{ now()->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-admin.stat-card label="Total Riders" value="{{ number_format($stats['total']) }}" icon="users" />
            <x-admin.stat-card label="With Recent Selfie" value="{{ number_format($stats['withSelfie']) }}" icon="camera" theme="emerald" />
            <x-admin.stat-card label="Missing Selfie" value="{{ number_format($stats['withoutSelfie']) }}" icon="exclamation" theme="amber" />
            <x-admin.stat-card label="Logged In (24h)" value="{{ number_format($stats['recentLogins']) }}" icon="clock" theme="teal" />
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow border border-gray-100">
            <form method="GET" action="{{ route('admin.rider-authentication.index') }}" class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Search Rider</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" /></svg>
                            </span>
                            <input type="text" name="search" value="{{ $filters['search'] }}"
                                   class="w-full rounded-xl border-gray-300 pl-10 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Name, email, phone number" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Verification Status</label>
                        <select name="verification_status" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="all" {{ $filters['verification_status'] === 'all' ? 'selected' : '' }}>All statuses</option>
                            @foreach($verificationStatuses as $status)
                                <option value="{{ $status }}" {{ $filters['verification_status'] === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Availability</label>
                        <select name="availability" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            @php
                                $availabilityOptions = [
                                    'all' => 'All riders',
                                    'available' => 'Available',
                                    'unavailable' => 'Unavailable',
                                ];
                            @endphp
                            @foreach($availabilityOptions as $value => $label)
                                <option value="{{ $value }}" {{ $filters['availability'] === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Selfie Status</label>
                        <select name="selfie_status" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="all" {{ $filters['selfie_status'] === 'all' ? 'selected' : '' }}>All riders</option>
                            <option value="with" {{ $filters['selfie_status'] === 'with' ? 'selected' : '' }}>With selfie</option>
                            <option value="without" {{ $filters['selfie_status'] === 'without' ? 'selected' : '' }}>Without selfie</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.rider-authentication.index') }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 text-center">Reset</a>
                    <button type="submit" class="px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow hover:shadow-lg">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Rider Cards -->
        <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($riders as $rider)
                    @php
                        $user = $rider->user;
                        $selfieUrl = $rider->selfie_verification_url ? Storage::url($rider->selfie_verification_url) : null;
                    @endphp
                    <div class="border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                        <div class="relative h-56 bg-gray-100">
                            @if($selfieUrl)
                                <img src="{{ $selfieUrl }}" alt="{{ $user?->name }} selfie"
                                     class="w-full h-full object-cover" loading="lazy">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h4l2-2h6l2 2h4v12H3V7z" />
                                        <circle cx="12" cy="11" r="3" stroke-width="1.5" />
                                    </svg>
                                    <p class="font-medium">No selfie uploaded</p>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-white/90 text-emerald-700">
                                {{ ucfirst(str_replace('_', ' ', $rider->verification_status ?? 'Pending')) }}
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Rider</p>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $user?->name ?? 'Unknown Rider' }}</h3>
                                <p class="text-sm text-gray-500">{{ $user?->email }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="space-y-1">
                                    <p class="text-gray-500">Phone</p>
                                    <p class="font-medium text-gray-900">{{ $user?->phone_number ?: '—' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-gray-500">Availability</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $rider->is_available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                                        {{ $rider->is_available ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-gray-500">Last Login</p>
                                    <p class="font-medium text-gray-900">{{ optional($user?->last_login_at)->diffForHumans() ?? 'No activity yet' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-gray-500">Total Deliveries</p>
                                    <p class="font-medium text-gray-900">{{ number_format($rider->total_deliveries) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 border-t border-gray-100 pt-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3" /></svg>
                                    <span>{{ $rider->updated_at?->diffForHumans() }}</span>
                                </div>
                                <span>{{ $rider->vehicle_type ?: 'Vehicle TBD' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800">No riders found</h3>
                        <p class="text-gray-500 mt-2 max-w-lg">No records match the selected filters. Try broadening your search to view rider selfies.</p>
                    </div>
                @endforelse
            </div>

            @if($riders->hasPages())
                <div class="mt-8 border-t border-gray-100 pt-6">
                    {{ $riders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
