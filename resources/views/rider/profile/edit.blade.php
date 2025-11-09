@extends('layout.rider')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Edit Profile</h1>
                        <p class="text-green-100 mt-1">Update your personal and rider information</p>
                    </div>
                    <a href="{{ route('rider.profile.show') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-200 shadow-md">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('rider.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Personal Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Personal Information</h3>
                        
                        <!-- Profile Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                                    @if($user->profile_image_url)
                                        <img src="{{ Storage::url($user->profile_image_url) }}" alt="Current Profile" class="h-full w-full object-cover" id="current-image">
                                    @else
                                        <div class="h-full w-full bg-gray-100 flex items-center justify-center" id="placeholder-image">
                                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name*</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name*</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address*</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rider Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Rider Information</h3>
                        
                        <!-- License Number -->
                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                            <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $rider->license_number) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('license_number') border-red-500 @enderror">
                            @error('license_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vehicle Type -->
                        <div>
                            <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                            <select name="vehicle_type" id="vehicle_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('vehicle_type') border-red-500 @enderror">
                                <option value="">Select vehicle type</option>
                                <option value="bike" {{ old('vehicle_type', $rider->vehicle_type) === 'bike' ? 'selected' : '' }}>Bike</option>
                                <option value="motorcycle" {{ old('vehicle_type', $rider->vehicle_type) === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                <option value="car" {{ old('vehicle_type', $rider->vehicle_type) === 'car' ? 'selected' : '' }}>Car</option>
                                <option value="scooter" {{ old('vehicle_type', $rider->vehicle_type) === 'scooter' ? 'selected' : '' }}>Scooter</option>
                            </select>
                            @error('vehicle_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Availability Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Availability Status</label>
                            <div class="flex items-center">
                                <input type="hidden" name="is_available" value="0">
                                <input type="checkbox" name="is_available" id="is_available" value="1" 
                                    {{ old('is_available', $rider->is_available) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="is_available" class="ml-2 text-sm text-gray-700">I'm available for deliveries</label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Check this box if you're currently available to accept delivery orders</p>
                        </div>

                        <!-- GCash Number -->
                        <div>
                            <label for="gcash_number" class="block text-sm font-medium text-gray-700 mb-1">GCash Number</label>
                            <input type="text" name="gcash_number" id="gcash_number" value="{{ old('gcash_number', $rider->gcash_number) }}"
                                placeholder="09XXXXXXXXX"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('gcash_number') border-red-500 @enderror">
                            @error('gcash_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GCash Name -->
                        <div>
                            <label for="gcash_name" class="block text-sm font-medium text-gray-700 mb-1">GCash Account Name</label>
                            <input type="text" name="gcash_name" id="gcash_name" value="{{ old('gcash_name', $rider->gcash_name) }}"
                                placeholder="Full name as registered in GCash"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('gcash_name') border-red-500 @enderror">
                            @error('gcash_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GCash QR Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">GCash QR Code</label>
                            @if($rider->gcash_qr_path)
                                <div class="mb-3">
                                    <div class="bg-gray-50 rounded-lg p-3 inline-block">
                                        <img src="{{ Storage::url($rider->gcash_qr_path) }}" alt="Current GCash QR" class="w-32 h-32 object-contain" id="gcash-qr-preview">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Current QR code</p>
                                </div>
                            @endif
                            <input type="file" name="gcash_qr_path" id="gcash_qr_path" accept="image/*" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="text-xs text-gray-500 mt-1">Upload your GCash QR code (PNG, JPG, GIF up to 2MB)</p>
                            @error('gcash_qr_path')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Read-only Information -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <h4 class="font-medium text-gray-800">Account Information</h4>
                            
                            <div>
                                <span class="text-sm text-gray-600">Verification Status:</span>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($rider->verification_status === 'verified') bg-green-100 text-green-800
                                    @elseif($rider->verification_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($rider->verification_status) }}
                                </span>
                            </div>
                            
                            <div>
                                <span class="text-sm text-gray-600">Total Deliveries:</span>
                                <span class="ml-2 font-medium">{{ number_format($rider->total_deliveries) }}</span>
                            </div>
                            
                            <div>
                                <span class="text-sm text-gray-600">Average Rating:</span>
                                <span class="ml-2 font-medium">
                                    @if($rider->average_rating)
                                        {{ number_format($rider->average_rating, 1) }} ⭐
                                    @else
                                        Not rated yet
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('rider.profile.show') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        // Profile image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentImage = document.getElementById('current-image');
                    const placeholderImage = document.getElementById('placeholder-image');
                    
                    if (currentImage) {
                        currentImage.src = e.target.result;
                    } else if (placeholderImage) {
                        placeholderImage.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-full w-full object-cover">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // GCash QR code preview
        document.getElementById('gcash_qr_path').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const qrPreview = document.getElementById('gcash-qr-preview');
                    
                    if (qrPreview) {
                        qrPreview.src = e.target.result;
                    } else {
                        // Create preview if it doesn't exist
                        const previewContainer = document.createElement('div');
                        previewContainer.className = 'mb-3';
                        previewContainer.innerHTML = `
                            <div class="bg-gray-50 rounded-lg p-3 inline-block">
                                <img src="${e.target.result}" alt="QR Preview" class="w-32 h-32 object-contain" id="gcash-qr-preview">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">New QR code preview</p>
                        `;
                        document.getElementById('gcash_qr_path').parentElement.insertBefore(previewContainer, document.getElementById('gcash_qr_path'));
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection