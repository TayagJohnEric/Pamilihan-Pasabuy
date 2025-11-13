@extends('layout.admin')

@section('title', 'Rider Application Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                <h2 class="text-3xl font-bold mb-2">Rider Application Details</h2>
                <p class="text-emerald-100 opacity-90">Review and manage rider application information</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Personal Information Section -->
            <div class="border-b border-gray-100">
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-3"></div>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Full Name</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->full_name }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->email }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Contact Number</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->contact_number }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Address</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->address }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">TIN Number</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->tin_number }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Application Status</label>
                                <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full text-sm font-medium
                                    @if($application->status === 'approved') 
                                        bg-green-100 text-green-800
                                    @elseif($application->status === 'pending') 
                                        bg-yellow-100 text-yellow-800
                                    @else 
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle & License Information Section -->
            <div class="border-b border-gray-100">
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-3"></div>
                        Vehicle & License Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Vehicle Type</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $application->vehicle_type }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Vehicle Model</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $application->vehicle_model }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Driver's License</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $application->driver_license_number }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">License Expiry Date</label>
                            <p class="mt-1 text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($application->license_expiry_date)->format('F d, Y') }}
                                @if(\Carbon\Carbon::parse($application->license_expiry_date)->isPast())
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif(\Carbon\Carbon::parse($application->license_expiry_date)->diffInDays() <= 30)
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Expires Soon
                                    </span>
                                @else
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Valid
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-3"></div>
                    Supporting Documents
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @if($application->nbi_clearance_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">NBI Clearance</p>
                                    <p class="text-xs text-gray-500 mt-1">Background check document</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getNbiClearanceFullUrl() }}" 
                                         alt="NBI Clearance" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getNbiClearanceFullUrl() }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Full Size
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($application->valid_id_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Valid ID</p>
                                    <p class="text-xs text-gray-500 mt-1">Government issued ID</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getValidIdFullUrl() }}" 
                                         alt="Valid ID" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getValidIdFullUrl() }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Full Size
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($application->selfie_with_id_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Selfie with ID</p>
                                    <p class="text-xs text-gray-500 mt-1">Identity verification photo</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getSelfieWithIdFullUrl() }}" 
                                         alt="Selfie with ID" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getSelfieWithIdFullUrl() }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Full Size
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                @if(!$application->nbi_clearance_url && !$application->valid_id_url && !$application->selfie_with_id_url)
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-2">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">No documents uploaded</p>
                    </div>
                @endif
            </div>

            <!-- Action Section -->
            <div class="bg-gray-50 px-8 py-6 rounded-b-xl">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.rider_applications.createRider', $application->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-md hover:shadow-lg">
                        Create Rider Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection