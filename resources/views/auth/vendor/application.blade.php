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
    <title>Vendor Application</title>

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
        }

        .file-upload:hover {
            border-color: #50C878;
            background-color: #F1F5F9;
        }

        .progress-bar {
            background: linear-gradient(90deg,  #5cff9d,  #50C878);
            height: 4px;
            border-radius: 2px;
        }

        .checkbox-custom {
            position: relative;
        }
        
        .checkbox-custom input[type="checkbox"] {
            opacity: 0;
            position: absolute;
        }
        
        .checkbox-custom label {
            position: relative;
            cursor: pointer;
            padding-left: 2rem;
        }
        
        .checkbox-custom label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #D1D5DB;
            border-radius: 0.25rem;
            background: white;
            transition: all 0.3s ease;
        }
        
        .checkbox-custom input[type="checkbox"]:checked + label:before {
            background: #50C878;
            border-color: ##50C878;
        }
        
        .checkbox-custom input[type="checkbox"]:checked + label:after {
            content: '✓';
            position: absolute;
            left: 4px;
            top: 1px;
            color: white;
            font-size: 0.875rem;
            font-weight: bold;
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
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Join Our Vendor Network</h1>
            <p class="text-gray-600 text-lg">Apply to become a verified vendor in our marketplace</p>

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
        @if(session('success'))
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

        <!-- Main Form -->
        <form action="{{ route('vendor-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Applicant Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Applicant Information
                    </h2>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" name="applicant_first_name" value="{{ old('applicant_first_name') }}" placeholder="Enter your first name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" name="applicant_last_name" value="{{ old('applicant_last_name') }}" placeholder="Enter your last name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="applicant_email" value="{{ old('applicant_email') }}" placeholder="your@email.com" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="text" name="applicant_phone_number" value="{{ old('applicant_phone_number') }}" placeholder="+63 xxx xxx xxxx" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h4a1 1 0 011 1v5m-6 0V9a1 1 0 011-1h4a1 1 0 011 1v13"></path>
                        </svg>
                        Business Information
                    </h2>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proposed Vendor Name *</label>
                        <input type="text" name="proposed_vendor_name" value="{{ old('proposed_vendor_name') }}" placeholder="Enter your business/vendor name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stall Number Preference</label>
                            <input type="text" name="stall_number_preference" value="{{ old('stall_number_preference') }}" placeholder="e.g., A-01, B-15 (Optional)" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200">
                            <p class="text-sm text-gray-500 mt-1">Preferred stall number if you have one in mind</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Market Section Preference</label>
                            <input type="text" name="market_section_preference" value="{{ old('market_section_preference') }}" placeholder="e.g., Food Court, Electronics (Optional)" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200">
                            <p class="text-sm text-gray-500 mt-1">Which section of the market you prefer</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Description *</label>
                        <textarea name="business_description" rows="4" placeholder="Describe your business, what you plan to sell, your experience, target customers, etc." class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200 resize-none" required>{{ old('business_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Product Categories *</label>
                        <textarea name="primary_product_categories_description" rows="3" placeholder="List the main categories of products or services you will offer (e.g., Fresh vegetables, Cooked meals, Electronics, Clothing, etc.)" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200 resize-none" required>{{ old('primary_product_categories_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Document Upload Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Business Documents
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Upload your business registration documents</p>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Permit</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="business_permit_document_url" accept="application/pdf,image/*" class="hidden" id="business_permit">
                                <label for="business_permit" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DTI Registration</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="dti_registration_url" accept="application/pdf,image/*" class="hidden" id="dti_registration">
                                <label for="dti_registration" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">BIR Registration</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="bir_registration_url" accept="application/pdf,image/*" class="hidden" id="bir_registration">
                                <label for="bir_registration" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Documents</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="other_documents[]" multiple accept="application/pdf,image/*" class="hidden" id="other_documents">
                                <label for="other_documents" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload multiple files</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Images (Max 2MB each)</p>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Any other supporting documents (certificates, licenses, etc.)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-2xl shadow p-8">
                <div class="space-y-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Terms and Conditions</h3>
                        <div class="text-sm text-gray-600 space-y-2 max-h-32 overflow-y-auto">
                            <p>By submitting this application, you agree to:</p>
                            <ul class="list-disc list-inside space-y-1 ml-4">
                                <li>Comply with all marketplace policies and regulations</li>
                                <li>Maintain quality standards for all products and services</li>
                                <li>Provide accurate and up-to-date business information</li>
                                <li>Pay applicable fees and commissions as outlined in our vendor agreement</li>
                                <li>Respect other vendors and maintain professional conduct</li>
                            </ul>
                        </div>

                        <div class="checkbox-custom mt-6">
                            <input type="checkbox" name="agreed_to_terms" id="agreed_to_terms" {{ old('agreed_to_terms') ? 'checked' : '' }} required>
                            <label for="agreed_to_terms" class="text-sm font-medium text-gray-700">
                                I have read and agree to the terms and conditions *
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ready to Submit?</h3>
                        <p class="text-gray-600 mb-6">Please review your information before submitting your vendor application.</p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                                Review Application
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition duration-200 font-medium shadow-lg transform hover:scale-105">
                                Submit Application
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-4">
                            Your application will be reviewed within 3-5 business days.
                        </p>
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">Questions about the application process? Contact our vendor support team.</p>
        </div>
    </div>
</div>

<script>
    // Form progress tracking
    const form = document.querySelector('form');
    const progressBar = document.querySelector('.progress-bar');
    const requiredInputs = form.querySelectorAll('input[required], textarea[required]');
    const checkbox = document.querySelector('input[name="agreed_to_terms"]');
    
    function updateProgress() {
        let filledInputs = 0;
        const totalRequired = requiredInputs.length + 1; // +1 for checkbox
        
        requiredInputs.forEach(input => {
            if (input.value.trim() !== '') {
                filledInputs++;
            }
        });
        
        if (checkbox.checked) {
            filledInputs++;
        }
        
        const progress = (filledInputs / totalRequired) * 100;
        progressBar.style.width = progress + '%';
    }
    
    requiredInputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    checkbox.addEventListener('change', updateProgress);
    
    // File upload feedback
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileContainer = this.closest('.file-upload');
            const label = fileContainer.querySelector('label');
            
            if (this.files && this.files.length > 0) {
                if (this.files.length === 1) {
                    const fileName = this.files[0].name;
                    const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                    label.innerHTML = `
                        <span class="text-sm text-green-600 font-medium">${fileName}</span>
                        <p class="text-xs text-gray-500 mt-1">File size: ${fileSize} MB</p>
                    `;
                } else {
                    label.innerHTML = `
                        <span class="text-sm text-green-600 font-medium">${this.files.length} files selected</span>
                        <p class="text-xs text-gray-500 mt-1">Multiple files uploaded</p>
                    `;
                }
                fileContainer.classList.add('border-green-400', 'bg-green-50');
            }
        });
    });
    
    // Form validation enhancement
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        const termsCheckbox = form.querySelector('input[name="agreed_to_terms"]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!termsCheckbox.checked) {
            alert('Please agree to the terms and conditions to proceed.');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields and agree to the terms.');
        }
    });
</script>

</body>
</html>