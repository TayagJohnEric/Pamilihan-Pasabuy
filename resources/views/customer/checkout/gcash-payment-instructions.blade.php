<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment Instructions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full pointer-events-none"></div>

    <div class="min-h-screen bg-gray-50 py-6 lg:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-6 lg:mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">GCash Payment Instructions</h1>
                        <p class="text-sm lg:text-base text-gray-600 mt-1">
                            Complete your payment via GCash and upload proof
                        </p>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="hidden sm:flex items-center space-x-2 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">Secure Payment</span>
                    </div>
                </div>
            </div>

            <!-- Instructions Steps -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    How to Complete Payment
                </h2>
                
                <ol class="space-y-4">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full font-semibold mr-4">1</span>
                        <div class="flex-1 pt-1">
                            <p class="font-medium text-gray-900">Open your GCash app</p>
                            <p class="text-sm text-gray-600 mt-1">Launch the GCash mobile application on your phone</p>
                        </div>
                    </li>
                    
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full font-semibold mr-4">2</span>
                        <div class="flex-1 pt-1">
                            <p class="font-medium text-gray-900">Send payment to the rider's GCash account</p>
                            <p class="text-sm text-gray-600 mt-1">Use the details provided below (or scan the QR code)</p>
                        </div>
                    </li>
                    
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full font-semibold mr-4">3</span>
                        <div class="flex-1 pt-1">
                            <p class="font-medium text-gray-900">Take a screenshot of the successful transaction</p>
                            <p class="text-sm text-gray-600 mt-1">Make sure the reference number and amount are visible</p>
                        </div>
                    </li>
                    
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-full font-semibold mr-4">4</span>
                        <div class="flex-1 pt-1">
                            <p class="font-medium text-gray-900">Upload the screenshot below</p>
                            <p class="text-sm text-gray-600 mt-1">Submit your proof of payment for verification</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Payment Details Section -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Rider's GCash Details -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white">
                        <h2 class="text-xl font-semibold mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Payment Recipient Details
                        </h2>
                        
                        <div class="space-y-4">
                            <!-- Account Name -->
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <p class="text-sm text-blue-100 mb-1">Account Name</p>
                                <p class="text-xl font-bold">{{ $riderGCashDetails['gcash_name'] }}</p>
                            </div>
                            
                            <!-- GCash Number -->
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-blue-100 mb-1">GCash Number</p>
                                        <p class="text-2xl font-bold tracking-wider" id="gcash-number">{{ $riderGCashDetails['gcash_number'] }}</p>
                                    </div>
                                    <button type="button" 
                                            onclick="copyGCashNumber()"
                                            class="bg-white bg-opacity-30 hover:bg-opacity-40 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        Copy
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Amount -->
                            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                                <p class="text-sm text-blue-100 mb-1">Amount to Send</p>
                                <p class="text-3xl font-bold">₱{{ number_format($orderSummary['total_amount'], 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- QR Code (if available) -->
                        @if($riderGCashDetails['gcash_qr_path'])
                            <div class="mt-6 bg-white rounded-lg p-4">
                                <p class="text-sm text-gray-700 font-medium mb-3 text-center">Or scan this QR code</p>
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/' . $riderGCashDetails['gcash_qr_path']) }}" 
                                         alt="GCash QR Code" 
                                         class="w-48 h-48 object-contain border-2 border-gray-200 rounded-lg">
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Upload Payment Proof Form -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload Payment Proof
                        </h2>
                        
                        <!-- Display All Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <form id="payment-proof-form" 
                              method="POST" 
                              action="{{ route('payment.gcash-submit-proof') }}"
                              enctype="multipart/form-data"
                              class="space-y-6">
                            @csrf
                            
                            <!-- Hidden field for order ID if this is post-rider-acceptance payment -->
                            @if(isset($orderSummary['order_id']))
                                <input type="hidden" name="order_id" value="{{ $orderSummary['order_id'] }}">
                            @endif
                            
                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Payment Screenshot <span class="text-red-500">*</span>
                                </label>
                                
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        
                                        <div id="file-info" class="flex text-sm text-gray-600">
                                            <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span id="file-upload-text">Upload a file</span>
                                            </label>
                                            <p id="drag-drop-text" class="pl-1">or drag and drop</p>
                                        </div>
                                        
                                        <!-- File input - separate from text that changes -->
                                        <input id="payment_proof" 
                                               name="payment_proof" 
                                               type="file" 
                                               accept="image/jpeg,image/png,image/jpg"
                                               class="sr-only"
                                               required>
                                        
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                    </div>
                                </div>
                                
                                <!-- Image Preview -->
                                <div id="image-preview" class="hidden mt-4">
                                    <img id="preview-image" class="max-w-full h-auto rounded-lg border border-gray-300" alt="Preview">
                                    <button type="button" 
                                            onclick="removeImage()"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                                        Remove image
                                    </button>
                                </div>
                                
                                @error('payment_proof')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Reference Number (Optional) -->
                            <div>
                                <label for="customer_reference_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    GCash Reference Number (Optional)
                                </label>
                                <input type="text" 
                                       id="customer_reference_code" 
                                       name="customer_reference_code"
                                       maxlength="100"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., 1234567890123">
                                <p class="mt-1 text-sm text-gray-500">The 13-digit reference number from your GCash transaction</p>
                                @error('customer_reference_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Special Instructions -->
                            <div>
                                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Notes (Optional)
                                </label>
                                <textarea id="special_instructions" 
                                          name="special_instructions" 
                                          rows="3"
                                          maxlength="500"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                          placeholder="Any special instructions for your order or delivery..."></textarea>
                                @error('special_instructions')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Submit Button -->
                           <div class="flex flex-col sm:flex-row gap-3 w-full">
                                <button type="submit" 
                                        id="submit-btn"
                                        class="w-full sm:flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-3 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span id="submit-btn-text">Submit Payment Proof</span>
                                </button>

                                <a href="{{ route('payment.gcash-instructions') }}" 
                                class="w-full sm:flex-1 border-2 border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Refresh
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Order Summary
                            </h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Items ({{ $orderSummary['item_count'] }})</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($orderSummary['subtotal'], 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Delivery Fee</span>
                                    <span class="font-medium text-gray-900">₱{{ number_format($orderSummary['delivery_fee'], 2) }}</span>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900">Total</span>
                                        <span class="text-2xl font-bold text-blue-600">₱{{ number_format($orderSummary['total_amount'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Important Notice -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                                        <p class="text-sm text-yellow-700 mt-1">Make sure to send the exact amount. Your payment will be verified by our admin team before order processing begins.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            <button type="button" onclick="removeToast('${toastId}')" class="ml-4 flex-shrink-0 inline-flex rounded-md text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <span class="sr-only">Close</span>
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

    // Copy GCash Number
    function copyGCashNumber() {
        const gcashNumber = document.getElementById('gcash-number').textContent;
        navigator.clipboard.writeText(gcashNumber).then(() => {
            createToast('GCash number copied to clipboard!', 'success');
        }).catch(() => {
            createToast('Failed to copy. Please copy manually.', 'error');
        });
    }

    // Image Preview - without destroying the input
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        console.log('File input change event triggered');
        const file = e.target.files[0];
        console.log('File selected:', file ? file.name : 'none');
        
        if (file) {
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                createToast('File size must not exceed 5MB', 'error');
                this.value = '';
                return;
            }
            
            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                createToast('Please upload a JPG, JPEG, or PNG image', 'error');
                this.value = '';
                return;
            }
            
            console.log('File validated successfully:', file.name);
            
            // Update UI without destroying the input
            document.getElementById('file-upload-text').textContent = '✓ ' + file.name;
            document.getElementById('drag-drop-text').style.display = 'none';
            document.getElementById('file-info').classList.add('text-green-600', 'font-medium');
            document.getElementById('file-info').classList.remove('text-gray-600');
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('upload-icon').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    function removeImage() {
        console.log('Removing image');
        document.getElementById('payment_proof').value = '';
        document.getElementById('image-preview').classList.add('hidden');
        document.getElementById('upload-icon').classList.remove('hidden');
        
        // Reset UI without destroying input
        document.getElementById('file-upload-text').textContent = 'Upload a file';
        document.getElementById('drag-drop-text').style.display = '';
        document.getElementById('file-info').classList.remove('text-green-600', 'font-medium');
        document.getElementById('file-info').classList.add('text-gray-600');
    }

    // Form Submission
    $('#payment-proof-form').on('submit', function(e) {
        const submitBtn = $('#submit-btn');
        const btnText = $('#submit-btn-text');
        const form = this;
        const fileInput = form.elements['payment_proof'];
        
        // Client-side validation
        if (!fileInput || !fileInput.files.length) {
            e.preventDefault();
            createToast('Please upload a payment screenshot', 'error');
            return false;
        }
        
        // Disable button and show loading state
        submitBtn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        btnText.text('Submitting...');
        createToast('Submitting your payment proof...', 'info', 2000);
        
        // Form will submit normally (no e.preventDefault())
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
    </script>
</body>
</html>