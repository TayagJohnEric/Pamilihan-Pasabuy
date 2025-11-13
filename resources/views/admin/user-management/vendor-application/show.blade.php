@extends('layout.admin')

@section('title', 'Vendor Application Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                <h2 class="text-3xl font-bold mb-2">Vendor Application Details</h2>
                <p class="text-emerald-100 opacity-90">Review and manage vendor application information</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Applicant Information Section -->
            <div class="border-b border-gray-100">
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-3"></div>
                        Applicant Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Full Name</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->applicant_first_name }} {{ $application->applicant_last_name }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email Address</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->applicant_email }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Phone Number</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->applicant_phone_number }}</p>
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

            <!-- Business Information Section -->
            <div class="border-b border-gray-100">
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-2 h-6 bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full mr-3"></div>
                        Business Information
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Proposed Vendor Name</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $application->proposed_vendor_name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Business Description</label>
                            <p class="mt-1 text-gray-900 leading-relaxed">{{ $application->business_description }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Product Categories</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->primary_product_categories_description }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Market Preference</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->market_section_preference }}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Stall Preference</label>
                                <p class="mt-1 text-gray-900 font-medium">{{ $application->stall_number_preference }}</p>
                            </div>
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
                    @if($application->business_permit_document_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Business Permit</p>
                                    <p class="text-xs text-gray-500 mt-1">Required document</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getBusinessPermitFullUrl() }}" 
                                         alt="Business Permit" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getBusinessPermitFullUrl() }}" 
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
                    
                    @if($application->dti_registration_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">DTI Registration</p>
                                    <p class="text-xs text-gray-500 mt-1">Required document</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getDtiRegistrationFullUrl() }}" 
                                         alt="DTI Registration" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getDtiRegistrationFullUrl() }}" 
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
                    
                    @if($application->bir_registration_url)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">BIR Registration</p>
                                    <p class="text-xs text-gray-500 mt-1">Required document</p>
                                </div>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <img src="{{ $application->getBirRegistrationFullUrl() }}" 
                                         alt="BIR Registration" 
                                         class="w-full h-48 object-contain rounded"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                </div>
                                <a href="{{ $application->getBirRegistrationFullUrl() }}" 
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
                    
                    @if($application->other_documents && count($application->getOtherDocumentsFullUrls()) > 0)
                        @foreach($application->getOtherDocumentsFullUrls() as $index => $docUrl)
                            <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Other Document {{ $index + 1 }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Additional document</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-2 border border-gray-200">
                                        <img src="{{ $docUrl }}" 
                                             alt="Other Document {{ $index + 1 }}" 
                                             class="w-full h-48 object-contain rounded"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2214%22 dy=%22100%22 text-anchor=%22middle%22 x=%22100%22%3EImage not found%3C/text%3E%3C/svg%3E';">
                                    </div>
                                    <a href="{{ $docUrl }}" 
                                       target="_blank" 
                                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Open Full Size
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                @if(!$application->business_permit_document_url && !$application->dti_registration_url && !$application->bir_registration_url && !$application->other_documents)
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
                    <a href="{{ route('admin.vendor_applications.createVendor', $application->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 shadow-md hover:shadow-lg">
                        Create Vendor Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection