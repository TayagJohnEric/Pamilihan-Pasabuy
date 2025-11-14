@extends('layout.rider')

@section('title', 'Order Details - #' . $order->id)

@section('content')
    <div class="min-h-screen bg-gray-50 pb-6">
        <div class="max-w-[90rem] mx-auto space-y-4 sm:space-y-6">
            
            <!-- Order Header Card -->
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                <div class="p-4 sm:px-6 sm:py-8">
                    <div class="space-y-4 lg:space-y-0 lg:flex lg:items-start lg:justify-between lg:gap-6">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 sm:space-x-3 mb-2">
                                <div class="p-1.5 sm:p-2 bg-green-50 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 truncate">Order #{{ $order->id }}</h1>
                            </div>
                            <p class="text-gray-600 text-xs sm:text-sm">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        
                        <!-- Order Status Badge -->
                        <div class="flex items-center justify-between lg:flex-col lg:items-end gap-3 sm:gap-4">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl font-semibold text-xs sm:text-sm border
                                    {{ $order->status === 'awaiting_rider_assignment' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $order->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $order->status === 'pickup_confirmed' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                    {{ $order->status === 'out_for_delivery' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-50 text-green-700 border-green-200' : '' }}">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full mr-1.5 sm:mr-2
                                        {{ $order->status === 'awaiting_rider_assignment' ? 'bg-amber-400' : '' }}
                                        {{ $order->status === 'assigned' ? 'bg-blue-400' : '' }}
                                        {{ $order->status === 'pickup_confirmed' ? 'bg-purple-400' : '' }}
                                        {{ $order->status === 'out_for_delivery' ? 'bg-indigo-400' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-400' : '' }}"></div>
                                    <span class="whitespace-nowrap">{{ strtoupper(str_replace('_', ' ', $order->status)) }}</span>
                                </span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2 sm:gap-3 w-auto lg:w-full">
                                @if($order->status === 'awaiting_rider_assignment')
                                    <button id="accept-order-btn" 
                                            class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-green-600 text-white rounded-lg sm:rounded-xl hover:bg-green-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1 sm:flex-none"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Accept</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <button id="decline-order-btn" 
                                            class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-white text-red-600 border-2 border-red-200 rounded-lg sm:rounded-xl hover:bg-red-50 hover:border-red-300 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-1 sm:flex-none"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Decline</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </button>
                                @elseif($order->status === 'assigned')
                                    @if($order->payment_method === 'online_payment' && $order->payment && $order->payment->rider_verification_status !== 'verified')
                                        <button disabled 
                                                class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-400 text-white rounded-lg sm:rounded-xl font-semibold text-sm cursor-not-allowed opacity-75 w-full lg:w-auto"
                                                title="Payment verification required before pickup">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <span>Payment Verification Required</span>
                                        </button>
                                    @else
                                        <button id="confirm-pickup-btn" 
                                                class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 text-white rounded-lg sm:rounded-xl hover:bg-blue-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full lg:w-auto"
                                                data-order-id="{{ $order->id }}">
                                            <span class="button-text">Confirm Pickup</span>
                                            <div class="button-loading hidden flex items-center justify-center">
                                                <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span>Processing...</span>
                                            </div>
                                        </button>
                                    @endif
                                @elseif($order->status === 'pickup_confirmed')
                                    <button id="start-delivery-btn" 
                                            class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-indigo-600 text-white rounded-lg sm:rounded-xl hover:bg-indigo-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full lg:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Start Delivery</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Proof Upload Section (Mobile Optimized) -->
                    @if($order->status === 'out_for_delivery')
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="space-y-3 sm:space-y-4">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg sm:rounded-xl p-3 sm:p-4">
                                    <div class="flex items-start space-x-2 sm:space-x-3">
                                        <div class="p-1 bg-amber-100 rounded flex-shrink-0">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-amber-800 text-xs sm:text-sm">Delivery Proof Required</p>
                                            <p class="text-amber-700 text-xs sm:text-sm mt-1">Please upload a photo as proof of delivery.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <form id="delivery-proof-form" enctype="multipart/form-data" class="space-y-3 sm:space-y-4">
                                    @csrf
                                    <div>
                                        <label for="delivery_proof_image" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                            Upload Delivery Proof
                                        </label>
                                        <input type="file" 
                                               id="delivery_proof_image" 
                                               name="delivery_proof_image" 
                                               accept="image/*" 
                                               capture="environment"
                                               required
                                               class="block w-full text-xs sm:text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF (Max: 5MB)</p>
                                    </div>
                                    
                                    <!-- Image Preview -->
                                    <div id="image-preview" class="hidden">
                                        <div class="relative inline-block">
                                            <img id="preview-img" class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg border border-gray-300" alt="Preview">
                                            <button type="button" id="remove-image" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 shadow-lg">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" 
                                            id="mark-delivered-btn" 
                                            disabled
                                            class="relative inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-400 text-white rounded-lg sm:rounded-xl font-semibold text-sm transition-all duration-200 shadow-lg w-full disabled:cursor-not-allowed"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Mark as Delivered</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
                <!-- Left Column -->
                <div class="xl:col-span-2 space-y-4 sm:space-y-6">
                    
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-1.5 sm:p-2 bg-green-50 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                Customer Information
                            </h3>
                        </div>
                        <div class="px-4 sm:px-6 py-4 sm:py-6">
                            <div class="space-y-6 sm:grid sm:grid-cols-2 sm:gap-8 sm:space-y-0">
                                <div class="space-y-4 sm:space-y-6">
                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($order->customer->profile_image_url)
                                                <img class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-green-100" 
                                                     src="{{asset('storage/' . $order->customer->profile_image_url)}}" 
                                                     alt="{{ $order->customer->first_name }} {{ $order->customer->last_name }}">
                                            @else
                                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-green-100 flex items-center justify-center border-2 border-green-200">
                                                    <span class="text-green-600 font-semibold text-base sm:text-xl">
                                                        {{ substr($order->customer->first_name, 0, 1) }}{{ substr($order->customer->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 text-base sm:text-lg truncate">
                                                {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                            </h4>
                                            <p class="text-gray-600 text-xs sm:text-sm truncate">{{ $order->customer->email }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($order->customer->phone_number)
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div class="p-1.5 sm:p-2 bg-gray-50 rounded-lg flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </div>
                                            <a href="tel:{{ $order->customer->phone_number }}" 
                                               class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors duration-200 truncate">
                                                {{ $order->customer->phone_number }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center text-sm sm:text-base">
                                        <div class="p-1 bg-green-50 rounded mr-2 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        Delivery Address
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="p-3 sm:p-4 bg-gray-50 rounded-lg sm:rounded-xl border border-gray-100">
                                            <p class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">{{ $order->deliveryAddress->address_label }}</p>
                                            <p class="text-gray-700 text-xs sm:text-sm">{{ $order->deliveryAddress->address_line1 }}</p>
                                            <p class="text-gray-600 text-xs sm:text-sm">{{ $order->deliveryAddress->district->name }}</p>
                                        </div>
                                        @if($order->deliveryAddress->delivery_notes)
                                            <div class="p-3 sm:p-4 bg-amber-50 border border-amber-200 rounded-lg sm:rounded-xl">
                                                <div class="flex items-start space-x-2">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-medium text-amber-800 text-xs sm:text-sm">Delivery Notes</p>
                                                        <p class="text-amber-700 text-xs sm:text-sm break-words">{{ $order->deliveryAddress->delivery_notes }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Locations -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-1.5 sm:p-2 bg-green-50 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                Pickup Locations
                            </h3>
                        </div>
                        <div class="px-4 sm:px-6 py-4 sm:py-6">
                            <div class="space-y-4 sm:space-y-6">
                                @foreach($vendorGroups as $vendorId => $items)
                                    @php $vendor = $items->first()->product->vendor; @endphp
                                    <div class="border border-gray-200 rounded-lg sm:rounded-xl overflow-hidden">
                                        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                                            <div class="flex items-start sm:items-center justify-between gap-3">
                                                <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                                                    <div class="flex-shrink-0">
                                                        @if($vendor->shop_logo_url)
                                                            <img class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl object-cover border border-gray-200" 
                                                                 src="{{asset('storage/' . $vendor->shop_logo_url)}}" 
                                                                 alt="{{ $vendor->vendor_name }}">
                                                        @else
                                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
                                                                <span class="text-green-600 font-semibold text-base sm:text-lg">
                                                                    {{ substr($vendor->vendor_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-gray-900 text-sm sm:text-lg truncate">{{ $vendor->vendor_name }}</h4>
                                                        <div class="flex flex-wrap gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 mt-1">
                                                            <span class="flex items-center whitespace-nowrap">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                <span class="truncate">Stall: {{ $vendor->stall_number ?? 'N/A' }}</span>
                                                            </span>
                                                            <span class="flex items-center whitespace-nowrap">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                <span class="truncate">{{ $vendor->market_section ?? 'N/A' }}</span>
                                                            </span>
                                                        </div>
                                                        @if($vendor->public_contact_number)
                                                            <a href="tel:{{ $vendor->public_contact_number }}" 
                                                               class="inline-flex items-center text-green-600 hover:text-green-700 text-xs sm:text-sm font-medium mt-1 sm:mt-2 transition-colors duration-200">
                                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                                </svg>
                                                                <span class="truncate">{{ $vendor->public_contact_number }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap">
                                                        {{ $items->count() }} item{{ $items->count() > 1 ? 's' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div class="space-y-2 sm:space-y-3">
                                                @foreach($items as $item)
                                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200 gap-2 sm:gap-4">
                                                        <div class="flex-1 min-w-0">
                                                            <h5 class="font-semibold text-gray-900 text-sm sm:text-base break-words">{{ $item->product_name_snapshot }}</h5>
                                                            <div class="flex flex-wrap gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600 mt-1">
                                                                <span class="flex items-center whitespace-nowrap">
                                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                                    </svg>
                                                                    Qty: {{ $item->quantity_requested }} {{ $item->product->unit }}
                                                                </span>
                                                                @if($item->customer_budget_requested)
                                                                    <span class="flex items-center whitespace-nowrap">
                                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                                        </svg>
                                                                        Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if($item->customerNotes_snapshot)
                                                                <p class="text-xs sm:text-sm text-gray-500 mt-1.5 sm:mt-2 italic break-words">Note: {{ $item->customerNotes_snapshot }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold whitespace-nowrap
                                                                {{ $item->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                                                {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-700' : '' }}
                                                                {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-700' : '' }}">
                                                                {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Payment Confirmation Reminder -->
                    @if($order->payment_method === 'online_payment' && $order->payment && $order->payment->rider_verification_status !== 'verified')
                        <div class="bg-amber-50 border border-amber-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="px-4 sm:px-6 py-4 sm:py-6">
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-amber-100 rounded-lg">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg sm:text-xl font-bold text-amber-900 mb-2">Payment Confirmation Required</h3>
                                        <p class="text-amber-800 text-sm sm:text-base mb-3">
                                            You must verify the customer's payment before proceeding with the delivery process.
                                        </p>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            @if($order->payment->rider_verification_status === 'pending')
                                                <a href="{{ route('rider.payments.show', $order->payment) }}" 
                                                   class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-amber-600 text-white rounded-lg sm:rounded-xl hover:bg-amber-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Verify Payment Now
                                                </a>
                                            @elseif($order->payment->rider_verification_status === 'rejected')
                                                <div class="flex items-center space-x-2 text-red-700 text-sm">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Payment verification was rejected. Please contact admin for assistance.</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-1.5 sm:p-2 bg-orange-50 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                Special Instructions
                            </h3>
                        </div>
                        <div class="px-4 sm:px-6 py-4 sm:py-6">
                            <div class="p-3 sm:p-4 bg-orange-50 border border-orange-200 rounded-lg sm:rounded-xl">
                                <div class="flex items-start space-x-2 sm:space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-orange-800 font-medium text-xs sm:text-sm break-words">{{ $order->special_instructions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-4 sm:space-y-6">
                    
                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-1.5 sm:p-2 bg-purple-50 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                    </svg>
                                </div>
                                Payment Details
                            </h3>
                        </div>
                        <div class="px-4 sm:px-6 py-4 sm:py-6">
                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium text-sm">Payment Method</span>
                                    <span class="font-bold text-gray-900 uppercase text-sm">{{ $order->payment_method }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium text-sm">Payment Status</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold whitespace-nowrap
                                            {{ $order->payment_status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                        @if($order->payment_method === 'online_payment' && $order->payment && $order->payment->rider_verification_status !== 'verified')
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold whitespace-nowrap bg-amber-100 text-amber-700" title="Rider verification required">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                {{ strtoupper($order->payment->rider_verification_status ?? 'pending') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-t border-gray-200 pt-3 sm:pt-4">
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium text-sm">Order Total</span>
                                        <span class="font-bold text-gray-900 text-base sm:text-lg">₱{{ number_format($order->final_total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium text-sm">Your Delivery Fee</span>
                                        <span class="font-bold text-green-600 text-base sm:text-lg">₱{{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                </div>
                                
                                @if($order->payment_method === 'cod')
                                    <div class="mt-3 sm:mt-4 p-3 sm:p-4 bg-orange-50 border border-orange-200 rounded-lg sm:rounded-xl">
                                        <div class="flex items-start space-x-2 sm:space-x-3">
                                            <div class="p-1 bg-orange-100 rounded flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-orange-800 text-xs sm:text-sm">Cash on Delivery</p>
                                                <p class="text-orange-700 text-xs sm:text-sm break-words">Collect ₱{{ number_format($order->final_total_amount, 2) }} from customer</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    @if($order->statusHistory->count() > 0)
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-1.5 sm:p-2 bg-gray-50 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Order Timeline
                            </h3>
                        </div>
                        <div class="px-4 sm:px-6 py-4 sm:py-6">
                            <div class="space-y-3 sm:space-y-4">
                                @foreach($order->statusHistory->sortByDesc('created_at') as $index => $history)
                                <div class="flex space-x-3 sm:space-x-4">
                                    <div class="flex flex-col items-center flex-shrink-0">
                                        <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 {{ $index === 0 ? 'bg-green-500' : 'bg-gray-400' }} rounded-full"></div>
                                        @if(!$loop->last)
                                            <div class="w-px h-6 sm:h-8 bg-gray-200 mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 pb-6 sm:pb-8">
                                        <div class="flex flex-col gap-1 sm:gap-0 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 text-sm sm:text-base break-words">{{ strtoupper(str_replace('_', ' ', $history->status)) }}</p>
                                                @if($history->notes)
                                                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">{{ $history->notes }}</p>
                                                @endif
                                                @if($history->updatedBy)
                                                    <p class="text-xs text-gray-500 mt-1 break-words">
                                                        Updated by: {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium whitespace-nowrap sm:ml-4">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-[calc(100vw-2rem)]"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF token for AJAX requests
            const csrfToken = '{{ csrf_token() }}';

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `flex items-center px-4 sm:px-6 py-3 sm:py-4 mb-2 text-xs sm:text-sm rounded-lg sm:rounded-xl shadow-lg transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
                    type === 'success' ? 'text-white bg-green-500' :
                    type === 'error' ? 'text-white bg-red-500' :
                    'text-white bg-blue-500'
                }`;
                
                toast.innerHTML = `
                    <svg class="flex-shrink-0 w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' ? 
                            '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>' :
                            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span class="font-medium flex-1 min-w-0 break-words">${message}</span>
                    <button type="button" class="ml-2 sm:ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-black hover:bg-opacity-10 inline-flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center flex-shrink-0" onclick="this.parentElement.remove()">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            // Generic button handler
            function handleButtonClick(button, endpoint) {
                const orderId = button.dataset.orderId;
                const buttonText = button.querySelector('.button-text');
                const buttonLoading = button.querySelector('.button-loading');
                
                // Show loading state
                button.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');
                button.classList.add('opacity-75', 'cursor-not-allowed');
                
                fetch(`/rider/orders/${orderId}/${endpoint}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        button.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        button.classList.remove('opacity-75', 'cursor-not-allowed');
                        
                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.redirect_url) {
                                setTimeout(() => window.location.href = data.redirect_url, 1500);
                            } else {
                                setTimeout(() => location.reload(), 1500);
                            }
                        } else {
                            showToast(data.message || `Failed to ${endpoint.replace('-', ' ')}.`, 'error');
                        }
                    })
                    .catch(error => {
                        // Reset button state on error
                        button.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        button.classList.remove('opacity-75', 'cursor-not-allowed');
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
            }

            // Handle order acceptance
            const acceptBtn = document.getElementById('accept-order-btn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'accept');
                });
            }

            // Handle order decline
            const declineBtn = document.getElementById('decline-order-btn');
            if (declineBtn) {
                declineBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'decline');
                });
            }

            // Handle pickup confirmation
            const pickupBtn = document.getElementById('confirm-pickup-btn');
            if (pickupBtn) {
                pickupBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'pickup');
                });
            }

            // Handle start delivery
            const startDeliveryBtn = document.getElementById('start-delivery-btn');
            if (startDeliveryBtn) {
                startDeliveryBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'start-delivery');
                });
            }

            // Handle delivery proof image upload and preview
            const deliveryProofInput = document.getElementById('delivery_proof_image');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const removeImageBtn = document.getElementById('remove-image');
            const deliveredBtn = document.getElementById('mark-delivered-btn');
            const deliveryProofForm = document.getElementById('delivery-proof-form');

            if (deliveryProofInput && deliveredBtn) {
                // Handle file input change
                deliveryProofInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (5MB max)
                        if (file.size > 5 * 1024 * 1024) {
                            showToast('File size must be less than 5MB', 'error');
                            e.target.value = '';
                            return;
                        }

                        // Validate file type
                        if (!file.type.startsWith('image/')) {
                            showToast('Please select a valid image file', 'error');
                            e.target.value = '';
                            return;
                        }

                        // Show preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);

                        // Enable the submit button
                        deliveredBtn.disabled = false;
                        deliveredBtn.classList.remove('bg-gray-400', 'disabled:cursor-not-allowed');
                        deliveredBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'transform', 'hover:-translate-y-0.5');
                    }
                });

                // Handle image removal
                if (removeImageBtn) {
                    removeImageBtn.addEventListener('click', function() {
                        deliveryProofInput.value = '';
                        imagePreview.classList.add('hidden');
                        deliveredBtn.disabled = true;
                        deliveredBtn.classList.add('bg-gray-400', 'disabled:cursor-not-allowed');
                        deliveredBtn.classList.remove('bg-green-600', 'hover:bg-green-700', 'transform', 'hover:-translate-y-0.5');
                    });
                }

                // Handle form submission with file upload
                if (deliveryProofForm) {
                    deliveryProofForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const orderId = deliveredBtn.dataset.orderId;
                        const buttonText = deliveredBtn.querySelector('.button-text');
                        const buttonLoading = deliveredBtn.querySelector('.button-loading');
                        const formData = new FormData();
                        
                        // Add the image file
                        const imageFile = deliveryProofInput.files[0];
                        if (!imageFile) {
                            showToast('Please select a delivery proof image', 'error');
                            return;
                        }
                        
                        formData.append('delivery_proof_image', imageFile);
                        formData.append('_token', csrfToken);
                        formData.append('_method', 'PATCH');
                        
                        // Show loading state
                        deliveredBtn.disabled = true;
                        buttonText.classList.add('hidden');
                        buttonLoading.classList.remove('hidden');
                        deliveredBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        
                        fetch(`/rider/orders/${orderId}/delivered`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            deliveredBtn.disabled = false;
                            buttonText.classList.remove('hidden');
                            buttonLoading.classList.add('hidden');
                            deliveredBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                            
                            if (data.success) {
                                showToast(data.message, 'success');
                                if (data.redirect_url) {
                                    setTimeout(() => window.location.href = data.redirect_url, 1500);
                                } else {
                                    setTimeout(() => location.reload(), 1500);
                                }
                            } else {
                                showToast(data.message || 'Failed to mark order as delivered.', 'error');
                            }
                        })
                        .catch(error => {
                            // Reset button state on error
                            deliveredBtn.disabled = false;
                            buttonText.classList.remove('hidden');
                            buttonLoading.classList.add('hidden');
                            deliveredBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                            console.error('Error:', error);
                            showToast('An error occurred. Please try again.', 'error');
                        });
                    });
                }
            } else {
                // Fallback for other order statuses (existing functionality)
                if (deliveredBtn) {
                    deliveredBtn.addEventListener('click', function() {
                        handleButtonClick(this, 'delivered');
                    });
                }
            }
        });
    </script>
@endsection