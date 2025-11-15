<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <title>Rider Application</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            min-height: 100vh;
        }

        .form-section {
            border-left: 4px solid #50C878;
            background: #F8FAFC;
        }

        .file-upload {
            border: 2px dashed #CBD5E1;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: #50C878;
            background-color: #F1F5F9;
        }

        .file-upload.dragover {
            border-color: #50C878;
            background-color: #F0FDF4;
        }

        .file-upload.has-file {
            border-color: #50C878;
            background-color: #F0FDF4;
        }

        .progress-bar {
            background: linear-gradient(90deg, #50C878, #50C878);
            height: 4px;
            border-radius: 2px;
        }

        .file-name {
            font-size: 0.875rem;
            color: #50C878;
            font-weight: 500;
        }

        .file-size {
            font-size: 0.75rem;
            color: #6B7280;
        }

        .remove-file {
            background-color: #EF4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .remove-file:hover {
            background-color: #DC2626;
        }
    </style>
</head>
<body>

<div class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Header Section -->
            <div class="text-center mb-10">
            <!-- Logo + Title -->
            <div class="flex items-center justify-center gap-3 mb-4">
                <!-- Logo -->
            <a href="{{ route('landing.page') }}">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transform hover:scale-110 transition-transform overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
                </div>
            </a>

                <!-- Text -->
                <div class="text-left">
                <span class="text-2xl font-bold text-gray-900 block">PasaBuy</span>
                <div class="text-xs text-gray-700 leading-none">San Fernando Market</div>
                </div>
            </div>

            <!-- Heading -->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Join Our Rider Network</h1>
            <p class="text-gray-600 text-lg">Fill out the application form below to become a verified rider</p>

            <!-- Progress Bar -->
            <div class="mt-6 bg-gray-200 rounded-full h-2 max-w-md mx-auto">
                <div class="progress-bar w-0 h-full rounded-full bg-emerald-600 transition-all duration-500"></div>
            </div>
            </div>


        <!-- Laravel Error Messages -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-red-800">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Laravel Success Message -->
        @if (session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- JavaScript Error Messages -->
        <div id="error-messages" class="hidden mb-8 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800" id="error-text"></p>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form id="rider-application-form" action="{{ route('rider-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <!-- Personal Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Personal Information
                    </h2>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Enter your full name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number *</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="+63 xxx xxx xxxx" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date *</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Complete Address *</label>
                        <textarea name="address" rows="3" placeholder="Enter your complete address including street, barangay, city, and province" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200 resize-none" required>{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        Vehicle Information
                    </h2>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                            <input type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" placeholder="e.g., Motorcycle, Bicycle, Car" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model *</label>
                            <input type="text" name="vehicle_model" value="{{ old('vehicle_model') }}" placeholder="e.g., Honda Beat, Yamaha Mio" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Plate Number *</label>
                        <input type="text" name="license_plate_number" value="{{ old('license_plate_number') }}" placeholder="ABC 1234" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                    </div>
                </div>
            </div>

            <!-- License Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        License Information
                    </h2>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Driver License Number *</label>
                            <input type="text" name="driver_license_number" value="{{ old('driver_license_number') }}" placeholder="Enter license number" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                            <input type="date" name="license_expiry_date" value="{{ old('license_expiry_date') }}" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">TIN Number</label>
                        <input type="text" name="tin_number" value="{{ old('tin_number') }}" placeholder="xxx-xxx-xxx-xxx (Optional)" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200">
                        <p class="text-sm text-gray-500 mt-1">Tax Identification Number (if available)</p>
                    </div>
                </div>
            </div>

            <!-- Document Upload Section - FIXED -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Required Documents
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Upload the following documents for verification</p>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NBI Clearance Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NBI Clearance</label>
                            <div class="file-upload-container">
                                <input type="file" name="nbi_clearance_url" accept="application/pdf,image/*" class="hidden" id="nbi_clearance">
                                <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center transition duration-200" onclick="document.getElementById('nbi_clearance').click()">
                                    <div class="upload-content">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                        <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                    </div>
                                    <div class="file-info hidden">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <div class="file-name"></div>
                                                    <div class="file-size"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-file" onclick="event.stopPropagation(); removeFile('nbi_clearance')">×</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Valid ID Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid ID</label>
                            <div class="file-upload-container">
                                <input type="file" name="valid_id_url" accept="application/pdf,image/*" class="hidden" id="valid_id">
                                <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center transition duration-200" onclick="document.getElementById('valid_id').click()">
                                    <div class="upload-content">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                        <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                    </div>
                                    <div class="file-info hidden">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <div class="file-name"></div>
                                                    <div class="file-size"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="remove-file" onclick="event.stopPropagation(); removeFile('valid_id')">×</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selfie with ID Upload -->
                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selfie with ID</label>
                        <div class="file-upload-container">
                            <input type="file" name="selfie_with_id_url" accept="image/*" class="hidden" id="selfie_with_id">
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center transition duration-200" onclick="document.getElementById('selfie_with_id').click()">
                                <div class="upload-content">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">Image only (Max 2MB)</p>
                                </div>
                                <div class="file-info hidden">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <div class="file-name"></div>
                                                <div class="file-size"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="remove-file" onclick="event.stopPropagation(); removeFile('selfie_with_id')">×</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Take a clear photo of yourself holding your valid ID next to your face</p>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-2xl shadow p-8">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ready to Submit?</h3>
                    <p class="text-gray-600 mb-6">Please review your information before submitting your application.</p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="button" id="review-btn" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                            Review Application
                        </button>
                        <button type="submit" id="submit-btn" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition duration-200 font-medium shadow-lg transform hover:scale-105">
                            Submit Application
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 mt-4">
                        By submitting this application, you agree to our terms and conditions and privacy policy.
                    </p>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">Need help? Contact our support team for assistance.</p>
        </div>
    </div>
</div>

<script>
    const form = document.querySelector('#rider-application-form');
    const progressBar = document.querySelector('.progress-bar');
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    const fileInputs = document.querySelectorAll('input[type="file"]');

    // Progress bar functionality
    function updateProgress() {
        let filledInputs = 0;
        inputs.forEach(input => {
            if (input.value.trim() !== '') {
                filledInputs++;
            }
        });

        const progress = (filledInputs / inputs.length) * 100;
        progressBar.style.width = progress + '%';
    }

    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });

    // File upload functionality
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            handleFileSelect(e.target);
        });
    });

    function handleFileSelect(input) {
        const file = input.files[0];
        const container = input.closest('.file-upload-container');
        const uploadDiv = container.querySelector('.file-upload');
        const uploadContent = container.querySelector('.upload-content');
        const fileInfo = container.querySelector('.file-info');
        const fileName = container.querySelector('.file-name');
        const fileSize = container.querySelector('.file-size');

        if (file) {
            // Validate file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                showError('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = input.accept.split(',').map(type => type.trim());
            const fileType = file.type;
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            const isValidType = allowedTypes.some(type => {
                if (type.includes('/*')) {
                    return fileType.startsWith(type.replace('/*', ''));
                }
                return type === fileType || type === fileExtension;
            });

            if (!isValidType) {
                showError('Invalid file type. Please upload a valid file.');
                input.value = '';
                return;
            }

            // Show file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            uploadContent.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            uploadDiv.classList.add('has-file');

            updateProgress();
        }
    }

    function removeFile(inputId) {
        const input = document.getElementById(inputId);
        const container = input.closest('.file-upload-container');
        const uploadDiv = container.querySelector('.file-upload');
        const uploadContent = container.querySelector('.upload-content');
        const fileInfo = container.querySelector('.file-info');

        input.value = '';
        uploadContent.classList.remove('hidden');
        fileInfo.classList.add('hidden');
        uploadDiv.classList.remove('has-file');

        updateProgress();
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Drag and drop functionality
    document.querySelectorAll('.file-upload').forEach(uploadDiv => {
        uploadDiv.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
        });

        uploadDiv.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
        });

        uploadDiv.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = this.parentElement.querySelector('input[type="file"]');
                input.files = files;
                handleFileSelect(input);
            }
        });
    });

    // Error handling
    function showError(message) {
        const errorDiv = document.getElementById('error-messages');
        const errorText = document.getElementById('error-text');
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    }

    function showSuccess() {
        const successDiv = document.getElementById('success-message');
        successDiv.classList.remove('hidden');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        submitBtn.classList.add('opacity-75');
        
        // Let the form submit normally to the server
        // The server will handle the response and redirect
    });

    // Review button functionality
    document.getElementById('review-btn').addEventListener('click', function() {
        const formData = new FormData(form);
        let reviewContent = 'Application Summary:\n\n';
        
        // Personal Information
        reviewContent += 'PERSONAL INFORMATION:\n';
        reviewContent += `Name: ${formData.get('full_name') || 'Not provided'}\n`;
        reviewContent += `Email: ${formData.get('email') || 'Not provided'}\n`;
        reviewContent += `Contact: ${formData.get('contact_number') || 'Not provided'}\n`;
        reviewContent += `Birth Date: ${formData.get('birth_date') || 'Not provided'}\n`;
        reviewContent += `Address: ${formData.get('address') || 'Not provided'}\n\n`;
        
        // Vehicle Information
        reviewContent += 'VEHICLE INFORMATION:\n';
        reviewContent += `Type: ${formData.get('vehicle_type') || 'Not provided'}\n`;
        reviewContent += `Model: ${formData.get('vehicle_model') || 'Not provided'}\n`;
        reviewContent += `License Plate: ${formData.get('license_plate_number') || 'Not provided'}\n\n`;
        
        // License Information
        reviewContent += 'LICENSE INFORMATION:\n';
        reviewContent += `License Number: ${formData.get('driver_license_number') || 'Not provided'}\n`;
        reviewContent += `Expiry Date: ${formData.get('license_expiry_date') || 'Not provided'}\n`;
        reviewContent += `TIN: ${formData.get('tin_number') || 'Not provided'}\n\n`;
        
        // Documents
        reviewContent += 'DOCUMENTS:\n';
        const nbiFile = formData.get('nbi_clearance_url');
        const idFile = formData.get('valid_id_url');
        const selfieFile = formData.get('selfie_with_id_url');
        
        reviewContent += `NBI Clearance: ${nbiFile && nbiFile.name ? nbiFile.name : 'Not uploaded'}\n`;
        reviewContent += `Valid ID: ${idFile && idFile.name ? idFile.name : 'Not uploaded'}\n`;
        reviewContent += `Selfie with ID: ${selfieFile && selfieFile.name ? selfieFile.name : 'Not uploaded'}\n`;
        
        alert(reviewContent);
    });

    // Initial progress update
    updateProgress();
</script>

</body>
</html>