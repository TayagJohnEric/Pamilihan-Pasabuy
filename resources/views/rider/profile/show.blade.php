@extends('layout.rider')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 px-6 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full overflow-hidden bg-white shadow-lg">
                            @if($user->profile_image_url)
                                <img src="{{ Storage::url($user->profile_image_url) }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            <p class="text-green-100">
                                {{ $rider->verification_status === 'verified' ? '✓ Verified Rider' : ucfirst($rider->verification_status) . ' Status' }}
                            </p>
                            <div class="flex items-center mt-1">
                                @if($rider->is_available)
                                    <span class="bg-green-300 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">Available</span>
                                @else
                                    <span class="bg-gray-300 text-gray-700 text-xs px-2 py-1 rounded-full font-semibold">Offline</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('rider.profile.edit') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-md">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Personal Information</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Full Name</label>
                                <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Email Address</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                                @if($user->email_verified_at)
                                    <span class="text-green-600 text-xs">✓ Verified</span>
                                @else
                                    <span class="text-orange-600 text-xs">⚠ Not verified</span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Phone Number</label>
                                <p class="text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Member Since</label>
                                <p class="text-gray-900">{{ $user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rider Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Rider Information</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">License Number</label>
                                <p class="text-gray-900">{{ $rider->license_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Vehicle Type</label>
                                <p class="text-gray-900">{{ $rider->vehicle_type ? ucfirst($rider->vehicle_type) : 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Verification Status</label>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($rider->verification_status === 'verified') bg-green-100 text-green-800
                                        @elseif($rider->verification_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($rider->verification_status) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">Last Location Update</label>
                                <p class="text-gray-900">
                                    {{ $rider->location_last_updated_at ? $rider->location_last_updated_at->diffForHumans() : 'Never' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GCash Payment Information -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">GCash Payment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-600">GCash Number</label>
                                <p class="text-gray-900">{{ $rider->gcash_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-600">GCash Account Name</label>
                                <p class="text-gray-900">{{ $rider->gcash_name ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-600 block mb-2">GCash QR Code</label>
                            @if($rider->gcash_qr_path)
                                <div class="bg-gray-50 rounded-lg p-4 inline-block">
                                    <img src="{{ Storage::url($rider->gcash_qr_path) }}" alt="GCash QR Code" class="w-48 h-48 object-contain">
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No QR code uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistics</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ number_format($rider->total_deliveries) }}</div>
                            <div class="text-sm text-green-700 font-medium">Total Deliveries</div>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $rider->daily_deliveries }}</div>
                            <div class="text-sm text-green-700 font-medium">Today's Deliveries</div>
                        </div>
                        
                        <div class="bg-green-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">
                                @if($rider->average_rating)
                                    {{ number_format($rider->average_rating, 1) }}
                                    <span class="text-base">⭐</span>
                                @else
                                    --
                                @endif
                            </div>
                            <div class="text-sm text-green-700 font-medium">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection