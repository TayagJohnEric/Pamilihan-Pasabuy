@extends('layout.rider')

@section('title', 'Payment Verifications - Rider')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Payment Verifications</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        Verify customer payments received in your GCash account
                    </p>
                </div>
                
                <!-- Quick Stats -->
                <div class="hidden sm:flex items-center space-x-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-yellow-600 font-medium">Pending</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['pending_count'] }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-green-600 font-medium">Verified Today</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['verified_today'] }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-red-600 font-medium">Rejected Today</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['rejected_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Payments List -->
        @if($payments->count() > 0)
            <div class="space-y-4">
                @foreach($payments as $payment)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Order Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">Order #{{ $payment->order->id }}</h3>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($payment->rider_verification_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($payment->rider_verification_status === 'verified') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($payment->rider_verification_status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <p><span class="font-medium">Customer:</span> {{ $payment->order->customer->name }}</p>
                                        <p><span class="font-medium">Amount:</span> <span class="text-lg font-bold text-green-600">₱{{ number_format($payment->amount_paid, 2) }}</span></p>
                                        <p><span class="font-medium">Submitted:</span> {{ $payment->created_at->format('M d, Y h:i A') }}</p>
                                        @if($payment->customer_reference_code)
                                            <p><span class="font-medium">Reference:</span> {{ $payment->customer_reference_code }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('rider.payments.show', $payment) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View & Verify
                                    </a>
                                    
                                    @if($payment->rider_verification_status === 'verified')
                                        <span class="text-xs text-green-600 text-center">
                                            ✓ Verified {{ $payment->rider_verified_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @else
            <div class=" p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No payments found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($filterStatus === 'pending')
                        You don't have any pending payment verifications.
                    @else
                        No payments match the selected filter.
                    @endif
                </p>
            </div>
        @endif

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
    // Show flash messages as toasts
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
