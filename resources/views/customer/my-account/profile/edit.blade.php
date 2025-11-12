@extends('layout.customer')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-6 px-3 sm:px-4 lg:px-8">
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="mb-4 sm:mb-6 lg:mb-8">
            <div class="flex flex-col gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-600">Update your personal information and preferences</p>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 sm:mb-6 rounded-lg bg-green-50 border border-green-200 p-3 sm:p-4" role="alert">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Form Layout -->
        <form action="{{ route('customer.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 lg:gap-8">
                <!-- Left Sidebar - Profile Image & Help -->
                <div class="lg:col-span-4 space-y-4 sm:space-y-6">
                    <!-- Profile Image Card -->
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <div class="px-4 sm:px-6 py-6 sm:py-8">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 text-center">Profile Picture</h3>
                            
                            <!-- Profile Image Display with Preview -->
                            <div class="mb-4 sm:mb-6">
                                <div id="imagePreviewContainer" class="relative inline-block mx-auto flex justify-center w-full">
                                    @if($user->profile_image_url)
                                        <img id="profileImagePreview" 
                                             src="{{ asset('storage/' . $user->profile_image_url) }}" 
                                             alt="Current profile picture" 
                                             class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg transition-all duration-300">
                                    @else
                                        <div id="profileImagePreview" class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-200 border-4 border-gray-300 flex items-center justify-center shadow-lg transition-all duration-300">
                                            <svg class="w-14 h-14 sm:w-16 sm:h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            id="removeImageBtn" 
                                            class="absolute top-0 right-1/4 w-7 h-7 sm:w-8 sm:h-8 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 hidden"
                                            onclick="removeImage()">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="flex items-center justify-center w-full">
                                <label for="profile_image" class="flex flex-col items-center justify-center w-full h-48 sm:h-56 lg:h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex flex-col items-center justify-center pt-4 pb-5 px-3">
                                        <svg class="w-7 h-7 sm:w-8 sm:h-8 mb-3 sm:mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-1.5 sm:mb-2 text-xs sm:text-sm text-gray-500 text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 text-center">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                    <input id="profile_image" name="profile_image" type="file" accept="image/*" onchange="previewImage(this)" class="hidden" />
                                </label>
                            </div>
                            @error('profile_image') 
                                <p class="mt-2 text-xs sm:text-sm text-red-600 text-center" role="alert">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 sm:p-6 border border-green-200">
                        <div class="text-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-green-600 mx-auto mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h4 class="text-xs sm:text-sm font-semibold text-green-900 mb-1.5 sm:mb-2">Need Help?</h4>
                            <p class="text-xs text-green-700 mb-2 sm:mb-3">Our support team is here to assist you</p>
                            <a href="#" class="inline-flex items-center text-xs font-medium text-green-600 hover:text-green-500 transition-colors duration-200">
                                Contact Support
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Form Fields -->
                <div class="lg:col-span-8">
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <!-- Personal Information Section -->
                        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 lg:py-8 border-b border-gray-200">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="ml-3 sm:ml-4 text-base sm:text-lg font-semibold text-gray-900">Personal Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        First Name <span class="text-red-500" aria-label="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', $user->first_name) }}" 
                                           required
                                           class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('first_name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                           placeholder="Enter your first name">
                                    @error('first_name') 
                                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        Last Name <span class="text-red-500" aria-label="required">*</span>
                                    </label>
                                    <input type="text" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', $user->last_name) }}" 
                                           required
                                           class="block w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('last_name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                           placeholder="Enter your last name">
                                    @error('last_name') 
                                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-6 lg:py-8">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <h3 class="ml-3 sm:ml-4 text-base sm:text-lg font-semibold text-gray-900">Contact Information</h3>
                            </div>
                            
                            <div class="space-y-4 sm:space-y-5 lg:space-y-6">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        Email Address <span class="text-red-500" aria-label="required">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                            </svg>
                                        </div>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required
                                               class="block w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                               placeholder="Enter your email address">
                                    </div>
                                    @error('email') 
                                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">
                                        Phone Number
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                            </svg>
                                        </div>
                                        <input type="tel" 
                                               id="phone_number" 
                                               name="phone_number" 
                                               value="{{ old('phone_number', $user->phone_number) }}"
                                               class="block w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('phone_number') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                               placeholder="Enter your phone number">
                                    </div>
                                    @error('phone_number') 
                                        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600" role="alert">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 bg-gray-50 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:justify-end gap-2.5 sm:gap-3">
                                <button type="button" 
                                        onclick="window.history.back()" 
                                        class="inline-flex justify-center items-center w-full sm:w-auto px-4 sm:px-5 lg:px-6 py-2.5 sm:py-3 border border-gray-300 text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="inline-flex justify-center items-center w-full sm:w-auto px-6 sm:px-7 lg:px-8 py-2.5 sm:py-3 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 shadow-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 active:scale-95">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview functionality
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('profileImagePreview');
        const removeBtn = document.getElementById('removeImageBtn');
        
        if (file) {
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, or GIF)');
                input.value = '';
                return;
            }
            
            // Validate file size (10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert('File size must be less than 10MB');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create new image element or update existing one
                if (preview.tagName === 'DIV') {
                    // Replace the placeholder div with an img element
                    const img = document.createElement('img');
                    img.id = 'profileImagePreview';
                    img.src = e.target.result;
                    img.alt = 'Profile picture preview';
                    img.className = 'w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-green-300 shadow-lg transition-all duration-300';
                    preview.parentNode.replaceChild(img, preview);
                } else {
                    // Update existing image
                    preview.src = e.target.result;
                    preview.className = 'w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-green-300 shadow-lg transition-all duration-300';
                }
                
                // Show remove button
                removeBtn.classList.remove('hidden');
                
                // Add a subtle animation
                const newPreview = document.getElementById('profileImagePreview');
                newPreview.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    newPreview.style.transform = 'scale(1)';
                }, 100);
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Remove image functionality
    function removeImage() {
        const input = document.getElementById('profile_image');
        const preview = document.getElementById('profileImagePreview');
        const removeBtn = document.getElementById('removeImageBtn');
        
        // Clear the file input
        input.value = '';
        
        // Replace image with placeholder
        const placeholder = document.createElement('div');
        placeholder.id = 'profileImagePreview';
        placeholder.className = 'w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gray-200 border-4 border-gray-300 flex items-center justify-center shadow-lg transition-all duration-300';
        placeholder.innerHTML = `
            <svg class="w-14 h-14 sm:w-16 sm:h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        `;
        
        preview.parentNode.replaceChild(placeholder, preview);
        
        // Hide remove button
        removeBtn.classList.add('hidden');
    }
    
    
    // Form validation on submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!firstName || !lastName || !email) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
        
        // Show loading state on submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Updating...
        `;
        submitBtn.disabled = true;
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
    
    // Drag and drop functionality for image upload
    const imageContainer = document.getElementById('imagePreviewContainer');
    const fileInput = document.getElementById('profile_image');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageContainer.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        imageContainer.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        imageContainer.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    imageContainer.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        imageContainer.classList.add('border-green-500', 'border-2', 'border-dashed');
    }
    
    function unhighlight(e) {
        imageContainer.classList.remove('border-green-500', 'border-2', 'border-dashed');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    }
</script>
@endsection