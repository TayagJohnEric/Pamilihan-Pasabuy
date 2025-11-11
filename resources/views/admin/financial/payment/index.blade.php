@extends('layout.admin')

@section('title', 'Payments - Admin')

@section('content')
<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Payments</h1>
                    <p class="text-sm lg:text-base text-gray-600 mt-1">
                        View customer payment records and transaction history
                    </p>
                </div>
                
                <!-- Quick Stats -->
                <div class="hidden sm:flex items-center space-x-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-blue-600 font-medium">Total Payments</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $payments->total() }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-green-600 font-medium">Approved</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['approved_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('admin.payments.pending') }}" class="flex flex-col sm:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Search by order ID or customer name...">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="w-full sm:w-48">
                    <select name="filter_status" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="this.form.submit()">
                        <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>All Payments</option>
                        <option value="approved" {{ $filterStatus === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $filterStatus === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <!-- Search Button -->
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Search
                </button>
                
                <!-- Clear Button -->
                @if(request('search') || request('filter_status'))
                    <a href="{{ route('admin.payments.pending') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Payments List -->
        @if($payments->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Payments Found</h3>
                <p class="text-gray-600">
                    @if(request('search'))
                        No payments match your search criteria.
                    @else
                        There are no payment records at the moment.
                    @endif
                </p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">#{{ $payment->order_id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($payment->order->customer->profile_image_url)
                                                <img src="{{ asset('storage/' . $payment->order->customer->profile_image_url) }}" 
                                                     alt="{{ $payment->order->customer->name }}"
                                                     class="w-8 h-8 rounded-full object-cover mr-3">
                                            @else
                                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ substr($payment->order->customer->first_name, 0, 1) }}{{ substr($payment->order->customer->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $payment->order->customer->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $payment->order->customer->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold text-gray-900">₱{{ number_format($payment->amount_paid, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->order->rider)
                                            <p class="text-sm text-gray-900">{{ $payment->order->rider->name }}</p>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->customer_reference_code)
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->customer_reference_code }}</code>
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending_review' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'pending_review' => 'Pending',
                                                'approved' => 'Approved',
                                                'rejected' => 'Rejected',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$payment->admin_verification_status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$payment->admin_verification_status] ?? ucfirst($payment->admin_verification_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.payments.review', $payment->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        @endif
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
</script>
@endsection