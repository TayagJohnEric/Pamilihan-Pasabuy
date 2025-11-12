@extends('layout.customer')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-6 px-3 sm:px-4 lg:px-8">
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="mb-4 sm:mb-6 lg:mb-8">
            <div class="flex flex-col gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Profile</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-600">View and manage your personal information and settings</p>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
            <!-- Profile Summary Card - Left Sidebar -->
            <div class="lg:col-span-4">
                <div class="bg-white shadow rounded-xl overflow-hidden lg:sticky lg:top-6">
                    <!-- Profile Header -->
                    <div class="px-4 sm:px-6 py-6 sm:py-8 bg-gradient-to-br from-green-50 to-green-100 border-b border-green-200">
                        <div class="text-center">
                            <!-- Profile Image -->
                            <div class="mb-3 sm:mb-4">
                                @if($user->profile_image_url)
                                    <img src="{{ asset('storage/' . $user->profile_image_url) }}" 
                                         alt="{{ $user->first_name }} {{ $user->last_name }}" 
                                         class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full object-cover border-4 border-white shadow-lg">
                                @else
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-full bg-green-100 border-4 border-white shadow-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-green-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- User Name -->
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 px-2">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 px-2 break-all">{{ $user->email }}</p>
                            
                            <!-- Role Badge -->
                            <div class="mt-3">
                                <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <span class="ml-2.5 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700 truncate">Email Verified</span>
                                </div>
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            
                            <div class="flex items-center justify-between p-2.5 sm:p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="ml-2.5 sm:ml-3 text-xs sm:text-sm font-medium text-gray-700 truncate">Address Set</span>
                                </div>
                                @if($defaultAddress)
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-8 space-y-4 sm:space-y-6 lg:space-y-8">
                <!-- Personal Information Card -->
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                            <div class="flex items-start sm:items-center">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4 min-w-0">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Personal Information</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Your account details and contact information</p>
                                </div>
                            </div>

                            <div class="flex-shrink-0 sm:self-center">
                                <a href="{{ route('customer.profile.edit') }}"
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 sm:px-5 lg:px-6 py-2.5 sm:py-3 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 shadow-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 lg:py-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                            <div class="space-y-1.5">
                                <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">First Name</label>
                                <p class="text-base sm:text-lg font-medium text-gray-900 break-words">{{ $user->first_name }}</p>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Last Name</label> 
                                <p class="text-base sm:text-lg font-medium text-gray-900 break-words">{{ $user->last_name }}</p>
                            </div>
                            
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                <div class="flex items-start">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400 mr-2 mt-0.5 sm:mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <p class="text-base sm:text-lg font-medium text-gray-900 break-all">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Phone Number</label>
                                <div class="flex items-start">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400 mr-2 mt-0.5 sm:mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <p class="text-base sm:text-lg font-medium text-gray-900 break-words">
                                        {{ $user->phone_number ?? 'Not provided' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Default Address Card -->
                <div class="bg-white shadow rounded-xl overflow-hidden">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                            <div class="flex items-start sm:items-center">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 sm:ml-4 min-w-0">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Default Address</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">Your primary delivery address</p>
                                </div>
                            </div>

                            <div class="flex-shrink-0 sm:self-center">
                                <a href="{{ route('customer.saved_addresses.index') }}"
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 sm:px-5 lg:px-6 py-2.5 sm:py-3 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 shadow-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Address
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 lg:py-8">
                        @if ($defaultAddress)
                            <div class="space-y-4 sm:space-y-5 lg:space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                    <div class="space-y-1.5">
                                        <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Address Label</label>
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $defaultAddress->address_label }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-1.5">
                                        <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">District</label>
                                        <p class="text-base sm:text-lg font-medium text-gray-900 break-words">{{ $defaultAddress->district->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-1.5">
                                    <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Full Address</label>
                                    <div class="flex items-start">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400 mr-2 mt-0.5 sm:mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-base sm:text-lg font-medium text-gray-900 leading-relaxed break-words">{{ $defaultAddress->address_line1 }}</p>
                                    </div>
                                </div>
                                
                                @if($defaultAddress->delivery_notes)
                                    <div class="space-y-1.5">
                                        <label class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Delivery Notes</label>
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4">
                                            <p class="text-xs sm:text-sm text-green-800 break-words leading-relaxed">{{ $defaultAddress->delivery_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8 sm:py-10 lg:py-12 px-4">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No default address set</h4>
                                <p class="text-sm sm:text-base text-gray-600 mb-5 sm:mb-6 max-w-md mx-auto px-2">You haven't set up a default delivery address yet. Add one to make ordering faster and easier.</p>
                                <a href="{{ route('customer.profile.edit') }}" 
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 sm:px-5 py-2.5 sm:py-3 border border-green-300 text-xs sm:text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add Address
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection