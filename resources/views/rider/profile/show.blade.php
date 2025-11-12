@extends('layout.rider')

@section('title', 'My Profile')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 rounded-lg p-4 mb-6 shadow-sm animate-fade-in">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-hidden">
            <!-- Enhanced Header with Gradient -->
            <div class="relative px-6 sm:px-8 py-10 sm:py-12">
                <div class="relative flex flex-col sm:flex-row items-center sm:items-start justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                        <!-- Enhanced Profile Image -->
                        <div class="relative group">
                            <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-full overflow-hidden bg-white shadow-xl ring-4 ring-white/50 transition-transform group-hover:scale-105">
                                @if($user->profile_image_url)
                                    <img src="{{ Storage::url($user->profile_image_url) }}" alt="Profile" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 sm:h-14 sm:w-14 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- User Info -->
                        <div class="text-center sm:text-left">
                            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mb-2 text-gray-900">{{ $user->name }}</h1>
                            <p class="text-emerald-600 text-base sm:text-lg font-medium mb-3">
                                {{ $rider->verification_status === 'verified' ? '✓ Verified Rider' : ucfirst($rider->verification_status) . ' Status' }}
                            </p>
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                @if($rider->is_available)
                                    <span class="inline-flex items-center bg-emerald-100 text-green-700 text-sm px-3 py-1.5 rounded-full font-semibold shadow-sm">
                                        <span class="h-2 w-2 bg-green-900 rounded-full mr-2 animate-pulse"></span>
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-gray-300 text-gray-800 text-sm px-3 py-1.5 rounded-full font-semibold shadow-lg">
                                        <span class="h-2 w-2 bg-gray-600 rounded-full mr-2"></span>
                                        Offline
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Edit Button -->
                    <a href="{{ route('rider.profile.edit') }}" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl font-semibold shadow-md hover:shadow-lg hover:from-emerald-700 hover:to-green-700 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="">
                <!-- Statistics Cards - 3 in a row on mobile -->
                <div class="mb-10">
                    <div class="grid grid-cols-3 gap-3 sm:gap-6">
                        <!-- Total Deliveries -->
                        <div class=" p-4 sm:p-6 text-center">
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-100 text-emerald-600 mb-2 sm:mb-3 mx-auto">
                                <i class="fas fa-box text-sm sm:text-base"></i>
                            </div>
                            <div class="text-xl sm:text-4xl font-bold text-gray-800 mb-1 sm:mb-2">
                                {{ number_format($rider->total_deliveries) }}
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 font-semibold uppercase tracking-wide">Total Deliveries</div>
                        </div>

                        <!-- Today's Deliveries -->
                        <div class=" p-4 sm:p-6 text-center ">
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-100 text-emerald-600 mb-2 sm:mb-3 mx-auto">
                                <i class="fas fa-truck text-sm sm:text-base"></i>
                            </div>
                            <div class="text-xl sm:text-4xl font-bold text-gray-800 mb-1 sm:mb-2">
                                {{ $rider->daily_deliveries }}
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 font-semibold uppercase tracking-wide">Today's Deliveries</div>
                        </div>

                        <!-- Average Rating -->
                        <div class=" p-4 sm:p-6 text-center">
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-100 text-emerald-600 mb-2 sm:mb-3 mx-auto">
                                <i class="fas fa-star text-sm sm:text-base"></i>
                            </div>
                            <div class="text-xl sm:text-4xl font-bold text-gray-800 mb-1 sm:mb-2">
                                @if($rider->average_rating)
                                    {{ number_format($rider->average_rating, 1) }}
                                @else
                                    <span class="text-gray-400">--</span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 font-semibold uppercase tracking-wide">Average Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Personal Information Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Personal Information
                        </h3>
                        
                        <div class="space-y-5">
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Full Name</label>
                                <p class="text-gray-900 font-semibold text-lg">{{ $user->name }}</p>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Email Address</label>
                                <p class="text-gray-900 font-medium break-all">{{ $user->email }}</p>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Phone Number</label>
                                <p class="text-gray-900 font-medium">{{ $user->phone_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Member Since</label>
                                <p class="text-gray-900 font-medium">{{ $user->created_at->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rider Information Card -->
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Rider Information
                        </h3>
                        
                        <div class="space-y-5">
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">License Number</label>
                                <p class="text-gray-900 font-medium">{{ $rider->license_number ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Vehicle Type</label>
                                <p class="text-gray-900 font-medium">{{ $rider->vehicle_type ? ucfirst($rider->vehicle_type) : 'Not specified' }}</p>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Verification Status</label>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                                    @if($rider->verification_status === 'verified') bg-green-100 text-green-800 ring-1 ring-green-200
                                    @elseif($rider->verification_status === 'pending') bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200
                                    @else bg-red-100 text-red-800 ring-1 ring-red-200 @endif">
                                    {{ ucfirst($rider->verification_status) }}
                                </span>
                            </div>
                            
                            <div class="group">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Last Location Update</label>
                                <p class="text-gray-900 font-medium">
                                    {{ $rider->location_last_updated_at ? $rider->location_last_updated_at->diffForHumans() : 'Never' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GCash Payment Information -->
                <div class="mt-8">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 sm:p-8 shadow-sm border border-blue-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            GCash Payment Information
                        </h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-5">
                                <div class="group">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">GCash Number</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ $rider->gcash_number ?? 'Not provided' }}</p>
                                </div>
                                
                                <div class="group">
                                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1 block">GCash Account Name</label>
                                    <p class="text-gray-900 font-semibold text-lg">{{ $rider->gcash_name ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3 block">GCash QR Code</label>
                                @if($rider->gcash_qr_path)
                                    <div class="bg-white rounded-2xl p-6 inline-block shadow-md hover:shadow-lg transition-shadow border border-gray-200">
                                        <img src="{{ Storage::url($rider->gcash_qr_path) }}" alt="GCash QR Code" class="w-48 h-48 object-contain">
                                    </div>
                                @else
                                    <div class="bg-white rounded-2xl p-8 text-center border-2 border-dashed border-gray-300">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-500 text-sm font-medium">No QR code uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection