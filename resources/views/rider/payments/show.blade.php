@extends('layout.rider')

@section('title', 'Verify Payment - Order #' . $payment->order->id)

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('rider.payments.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-green-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Payments
            </a>
        </div>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Payment Verification</h1>
                    <p class="text-sm text-gray-600 mt-1">Order #{{ $payment->order->id }}</p>
                </div>
                
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                    @if($payment->rider_verification_status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($payment->rider_verification_status === 'verified') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($payment->rider_verification_status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column: Payment Proof -->
            <div class="space-y-6">
                
                <!-- Payment Details Card -->
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-lg p-6 text-white">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Expected Payment
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <p class="text-sm text-green-100 mb-1">Amount Expected</p>
                            <p class="text-3xl font-bold">₱{{ number_format($payment->amount_paid, 2) }}</p>
                        </div>
                        
                        @if($payment->customer_reference_code)
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <p class="text-sm text-green-100 mb-1">Customer Reference</p>
                            <p class="text-lg font-semibold">{{ $payment->customer_reference_code }}</p>
                        </div>
                        @endif
                        
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <p class="text-sm text-green-100 mb-1">Submitted On</p>
                            <p class="text-lg font-semibold">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer's Payment Screenshot -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer's Payment Proof</h3>
                    
                    @if($payment->payment_proof_url)
                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $payment->payment_proof_url) }}" 
                                 alt="Payment Proof"
                                 class="w-full h-auto cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="openImageModal(this.src)">
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">Click image to view fullscreen</p>
                    @else
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">No payment proof uploaded</p>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column: Verification Instructions & Actions -->
            <div class="space-y-6">
                
                <!-- Verification Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        How to Verify
                    </h3>
                    
                    <ol class="space-y-3 text-sm text-blue-900">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-3 text-xs">1</span>
                            <span>Open your <strong>GCash app</strong> on your phone</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-3 text-xs">2</span>
                            <span>Check your <strong>recent transactions</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-3 text-xs">3</span>
                            <span>Verify the <strong>amount matches</strong> ₱{{ number_format($payment->amount_paid, 2) }}</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-3 text-xs">4</span>
                            <span>Check if <strong>reference number matches</strong> (if provided)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-3 text-xs">5</span>
                            <span>Confirm the <strong>transaction timestamp</strong> is recent</span>
                        </li>
                    </ol>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium text-gray-900">{{ $payment->order->customer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium text-gray-900">{{ $payment->order->customer->phone_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order Items:</span>
                            <span class="font-medium text-gray-900">{{ $payment->order->orderItems->count() }} items</span>
                        </div>
                    </div>
                </div>

                <!-- Verification Actions -->
                @if($payment->rider_verification_status === 'pending')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Decision</h3>
                        
                        <!-- Confirm Payment Form -->
                        <form id="verify-form" method="POST" action="{{ route('rider.payments.verify', $payment) }}" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea id="verification_notes" 
                                          name="verification_notes" 
                                          rows="3" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="e.g., Verified in GCash - Transaction ID matches"></textarea>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirm Payment Received
                            </button>
                        </form>
                        
                        <!-- Reject Payment Button -->
                        <button onclick="showRejectModal()" 
                                class="w-full bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Payment Not Received
                        </button>
                    </div>
                @elseif($payment->rider_verification_status === 'verified')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-green-800 mb-1">Payment Verified</h4>
                                <p class="text-sm text-green-700">Verified {{ $payment->rider_verified_at->diffForHumans() }}</p>
                                @if($payment->rider_verification_notes)
                                    <p class="text-sm text-green-600 mt-2">{{ $payment->rider_verification_notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-red-800 mb-1">Payment Not Received</h4>
                                @if($payment->rider_verification_notes)
                                    <p class="text-sm text-red-700">{{ $payment->rider_verification_notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

<!-- Reject Payment Modal -->
<div id="reject-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Payment Rejection</h3>
        
        <form id="reject-form" method="POST" action="{{ route('rider.payments.reject', $payment) }}">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason" 
                          name="rejection_reason" 
                          rows="4" 
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                          placeholder="e.g., No payment received in GCash, wrong amount, incorrect reference number..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="hideRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="hidden fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 p-4" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full">
        <img id="modal-image" src="" alt="Payment Proof" class="max-w-full max-h-screen object-contain">
    </div>
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showRejectModal() {
    document.getElementById('reject-modal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}

function openImageModal(src) {
    document.getElementById('modal-image').src = src;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Toast notification function
function createToast(message, type = 'success') {
    const toast = $(`
        <div class="pointer-events-auto bg-white rounded-lg shadow-lg border-l-4 ${type === 'success' ? 'border-green-500' : type === 'error' ? 'border-red-500' : 'border-blue-500'} p-4 transform transition-all duration-300 ease-in-out translate-x-full">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${type === 'success' ? 
                        '<svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                        '<svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
            </div>
        </div>
    `);
    
    $('#toast-container').append(toast);
    setTimeout(() => toast.removeClass('translate-x-full'), 100);
    setTimeout(() => {
        toast.addClass('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

$(document).ready(function() {
    @if(session('success'))
        createToast("{{ session('success') }}", 'success');
    @endif
    
    @if(session('error'))
        createToast("{{ session('error') }}", 'error');
    @endif
    
    @if(session('info'))
        createToast("{{ session('info') }}", 'info');
    @endif
});
</script>
@endsection
