@extends('layout.admin')

@section('title', 'Review Payment - Admin')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.payments.pending') }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Payment List
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Payment Review</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        Order #{{ $payment->order_id }} - {{ $payment->order->customer->name }}
                    </p>
                </div>
                
                <!-- Status Badge -->
                @php
                    $statusColors = [
                        'pending_review' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'approved' => 'bg-green-100 text-green-800 border-green-300',
                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $statusLabels = [
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ];
                @endphp
                <div class="px-4 py-2 rounded-lg border-2 {{ $statusColors[$payment->admin_verification_status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                    <span class="text-sm font-semibold">{{ $statusLabels[$payment->admin_verification_status] ?? ucfirst($payment->admin_verification_status) }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Payment Proof Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Payment Proof
                    </h2>
                    
                    @if($payment->payment_proof_url)
                        <div class="space-y-4">
                            <!-- Image Preview -->
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $payment->payment_proof_url) }}" 
                                     alt="Payment Proof" 
                                     class="w-full h-auto rounded-lg border-2 border-gray-300 cursor-pointer hover:border-blue-500 transition-colors"
                                     onclick="openImageModal()">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity rounded-lg flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Download Button -->
                            <a href="{{ asset('storage/' . $payment->payment_proof_url) }}" 
                               download
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download Image
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No payment proof uploaded</p>
                        </div>
                    @endif
                </div>
                
                <!-- Reference Number & Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Transaction Details
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">GCash Reference Number</p>
                                @if($payment->customer_reference_code)
                                    <code class="text-base font-mono bg-gray-100 px-3 py-2 rounded block">{{ $payment->customer_reference_code }}</code>
                                @else
                                    <p class="text-sm text-gray-400 italic">Not provided</p>
                                @endif
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Amount Paid</p>
                                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payment->amount_paid, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                                <p class="text-base font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method_used)) }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Submitted</p>
                                <p class="text-base font-medium text-gray-900">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($payment->verifiedBy)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Verified By</p>
                                <p class="text-base font-medium text-gray-900">{{ $payment->verifiedBy->name }}</p>
                                @if($payment->payment_processed_at)
                                    <p class="text-sm text-gray-500">{{ $payment->payment_processed_at->format('M d, Y h:i A') }}</p>
                                @endif
                            </div>
                        @endif
                        
                        @if($payment->admin_notes)
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Admin Notes</p>
                                <p class="text-base text-gray-900">{{ $payment->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Order Items
                    </h2>
                    
                    <div class="space-y-3">
                        @foreach($payment->order->orderItems as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->product_name_snapshot }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($item->customer_budget_requested)
                                            Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}
                                        @else
                                            Qty: {{ $item->quantity_requested }} × ₱{{ number_format($item->unit_price_snapshot, 2) }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $item->product->vendor->vendor_name ?? 'Vendor' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">
                                        ₱{{ number_format($item->customer_budget_requested ?? ($item->quantity_requested * $item->unit_price_snapshot), 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Sidebar - Order Summary & Actions -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-6 space-y-6">
                    
                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Order ID</span>
                                <span class="font-medium text-gray-900">#{{ $payment->order_id }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Items</span>
                                <span class="font-medium text-gray-900">{{ $payment->order->orderItems->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($payment->order->final_total_amount - $payment->order->delivery_fee, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($payment->order->delivery_fee, 2) }}</span>
                            </div>
                            
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-blue-600">₱{{ number_format($payment->order->final_total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Details</h2>
                        
                        <div class="flex items-center mb-4">
                            @if($payment->order->customer->profile_image_url)
                                <img src="{{ asset('storage/' . $payment->order->customer->profile_image_url) }}" 
                                     alt="{{ $payment->order->customer->name }}"
                                     class="w-12 h-12 rounded-full object-cover mr-3">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-lg font-medium text-gray-600">
                                        {{ substr($payment->order->customer->first_name, 0, 1) }}{{ substr($payment->order->customer->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $payment->order->customer->name }}</p>
                                <p class="text-sm text-gray-600">{{ $payment->order->customer->email }}</p>
                            </div>
                        </div>
                        
                        @if($payment->order->customer->phone_number)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $payment->order->customer->phone_number }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Rider Info -->
                    @if($payment->order->rider)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Rider Details</h2>
                            
                            <div class="flex items-center mb-4">
                                @if($payment->order->rider->profile_image_url)
                                    <img src="{{ asset('storage/' . $payment->order->rider->profile_image_url) }}" 
                                         alt="{{ $payment->order->rider->name }}"
                                         class="w-12 h-12 rounded-full object-cover mr-3">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-lg font-medium text-gray-600">
                                            {{ substr($payment->order->rider->first_name, 0, 1) }}{{ substr($payment->order->rider->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->order->rider->name }}</p>
                                    @if($payment->order->rider->rider)
                                        <p class="text-sm text-gray-600">{{ $payment->order->rider->rider->vehicle_type ?? 'Rider' }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($payment->order->rider->rider && $payment->order->rider->rider->gcash_number)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-xs text-blue-600 font-medium mb-1">GCash Number</p>
                                    <p class="text-base font-mono text-blue-900">{{ $payment->order->rider->rider->gcash_number }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    @if($payment->admin_verification_status === 'pending_review')
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Verification Actions</h2>
                            
                            <!-- Approve Form -->
                            <form id="approve-form" method="POST" action="{{ route('admin.payments.approve', $payment->id) }}" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Approval Notes (Optional)
                                    </label>
                                    <textarea id="approve_notes" 
                                              name="admin_notes" 
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none text-sm"
                                              placeholder="Add any notes about this approval..."></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full bg-green-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Approve Payment
                                </button>
                            </form>
                            
                            <!-- Reject Button -->
                            <button type="button" 
                                    onclick="openRejectModal()"
                                    class="w-full bg-red-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Reject Payment
                            </button>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 text-center">
                                This payment has already been 
                                <span class="font-semibold">{{ $statusLabels[$payment->admin_verification_status] ?? ucfirst($payment->admin_verification_status) }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-5xl max-h-full">
        <button type="button" 
                onclick="closeImageModal()"
                class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img src="{{ asset('storage/' . $payment->payment_proof_url) }}" 
             alt="Payment Proof" 
             class="max-w-full max-h-screen rounded-lg">
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Reject Payment</h3>
        
        <form id="reject-form" method="POST" action="{{ route('admin.payments.reject', $payment->id) }}">
            @csrf
            <div class="mb-4">
                <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea id="reject_notes" 
                          name="admin_notes" 
                          rows="4"
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                          placeholder="Please provide a clear reason for rejecting this payment..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Reject Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Toast Notification System
function createToast(message, type = 'success', duration = 4000) {
    const toastContainer = $('#toast-container');
    const toastId = 'toast-' + Date.now();
    
    const icons = {
        success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`,
        error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`,
        info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>`
    };

    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };

    const iconColors = {
        success: 'text-green-500',
        error: 'text-red-500',
        info: 'text-blue-500'
    };

    const toast = $(`
        <div id="${toastId}" class="transform transition-all duration-300 ease-out translate-x-full opacity-0 pointer-events-auto">
            <div class="w-full max-w-sm ${colors[type]} border rounded-lg shadow-lg">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 ${iconColors[type]}">${icons[type]}</div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium leading-5">${message}</p>
                        </div>
                        <button type="button" onclick="removeToast('${toastId}')" class="ml-4 flex-shrink-0 inline-flex rounded-md text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);

    toastContainer.append(toast);
    setTimeout(() => $(`#${toastId}`).removeClass('translate-x-full opacity-0').addClass('translate-x-0 opacity-100'), 100);
    setTimeout(() => removeToast(toastId), duration);
}

window.removeToast = function(toastId) {
    const toast = $(`#${toastId}`);
    if (toast.length) {
        toast.removeClass('translate-x-0 opacity-100').addClass('translate-x-full opacity-0');
        setTimeout(() => toast.remove(), 300);
    }
};

// Image Modal Functions
function openImageModal() {
    document.getElementById('image-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Reject Modal Functions
function openRejectModal() {
    document.getElementById('reject-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
    document.getElementById('reject_notes').value = '';
    document.body.style.overflow = 'auto';
}

// Form Submission Handling
$('#approve-form').on('submit', function(e) {
    if (!confirm('Are you sure you want to APPROVE this payment? This will trigger order fulfillment.')) {
        e.preventDefault();
        return false;
    }
    $(this).find('button[type="submit"]').prop('disabled', true).text('Approving...');
});

$('#reject-form').on('submit', function(e) {
    const reason = $('#reject_notes').val().trim();
    if (!reason) {
        e.preventDefault();
        createToast('Please provide a reason for rejection', 'error');
        return false;
    }
    if (!confirm('Are you sure you want to REJECT this payment? The customer will be notified.')) {
        e.preventDefault();
        return false;
    }
    $(this).find('button[type="submit"]').prop('disabled', true).text('Rejecting...');
});

// Session messages
@if(session('success'))
    createToast("{{ session('success') }}", 'success');
@endif
@if(session('error'))
    createToast("{{ session('error') }}", 'error');
@endif
@if(session('info'))
    createToast("{{ session('info') }}", 'info');
@endif

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeRejectModal();
    }
});
</script>
@endsection