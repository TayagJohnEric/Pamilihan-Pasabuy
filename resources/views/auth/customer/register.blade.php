<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #059669;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            transition: color 0.2s ease;
        }
        
        .input-with-icon input {
            padding-left: 40px;
        }
        
        .input-with-icon input:focus + .input-icon {
            color: #059669;
        }
        
        .register-button {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .register-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            transition: left 0.3s ease;
        }
        
        .register-button:hover::before {
            left: 0;
        }
        
        .register-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }
        
        .register-button:active {
            transform: translateY(0);
        }
        
        .register-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .register-button:disabled:hover::before {
            left: -100%;
        }
        
        .welcome-animation {
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-animation {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        /* Custom responsive utilities */
        .container-responsive {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container-responsive {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        @media (min-width: 768px) {
            .container-responsive {
                padding-left: 0;
                padding-right: 0;
            }
        }

        /* Mobile-first touch targets */
        @media (max-width: 767px) {
            .register-button {
                min-height: 48px;
            }
            
            .password-toggle {
                min-width: 32px;
                min-height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 4px;
            }
            
            .password-toggle svg {
                width: 18px !important;
                height: 18px !important;
            }
        }

        /* Landscape orientation adjustments for mobile */
        @media screen and (max-height: 600px) and (orientation: landscape) {
            .min-h-screen {
                min-height: 100vh;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }

        /* Custom checkbox styling */
        .custom-checkbox {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        
        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
        }
        
        .custom-checkbox .checkmark {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: white;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .custom-checkbox input[type="checkbox"]:checked + .checkmark {
            background-color: #059669;
            border-color: #059669;
        }
        
        .custom-checkbox input[type="checkbox"]:checked + .checkmark:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .custom-checkbox input[type="checkbox"]:focus + .checkmark {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
        }

        /* Label styling */
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.875rem;
        }
        
        @media (min-width: 640px) {
            .form-label {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <!-- Main Container with responsive padding and sizing -->
    <div class="bg-white w-full max-w-md mx-auto container-responsive py-8 px-6 sm:px-8 md:p-8">
        <!-- Logo + Title -->
        <div class="flex items-center justify-center gap-3 mb-4 sm:mb-6 welcome-animation">
            <!-- Logo -->
            <a href="{{ route('landing.page') }}">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center transform hover:scale-110 transition-transform overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
                </div>
            </a>

            <!-- Text -->
            <div class="text-left">
                <span class="text-xl sm:text-2xl font-bold text-gray-900 block">PasaBuy</span>
                <div class="text-xs text-gray-700 leading-none">San Fernando Market</div>
            </div>
        </div>
        
        <!-- Welcome Header -->
        <div class="text-center mb-6 sm:mb-8 welcome-animation">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 ">
                Let's create your
            </h2>
              <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                 account today!
            </h2>
            <p class="text-gray-600 font-medium text-sm sm:text-md">Join our community today and enjoy a seamless experience as a valued customer</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg form-animation">
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Registration Form -->
        <form id="registerForm" class="form-animation">
            @csrf

            <!-- Name Fields Grid -->
            <div class="grid grid-cols-2 gap-4 mb-4 sm:mb-6">
                <div>
                    <div class="relative">
                        <input type="text" id="first_name" name="first_name" 
                          class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-4 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base" 
                          required placeholder="First name">

                        <!-- User Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                          class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                          <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="relative">
                        <input type="text" id="last_name" name="last_name" 
                          class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-4 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base" 
                          required placeholder="Last name">

                        <!-- User Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                          class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                          <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Email Input -->
            <div class="mb-4 sm:mb-6">
                <div class="relative">
                    <input type="email" id="email" name="email" 
                      class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-4 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base" 
                      required placeholder="Enter your email">

                    <!-- Email Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                      class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                      <circle cx="12" cy="12" r="4"/>
                      <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>
                    </svg>
                </div>
            </div>
            
            <!-- Password Input -->
            <div class="mb-4 sm:mb-6">
                <div class="relative">
                    <input type="password" id="password-input" name="password"
                      class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-12 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base"
                      required placeholder="Create a password">

                    <!-- Lock Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                      <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>

                    <!-- Eye toggle icon -->
                    <div class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 password-toggle" id="toggle-password">
                        <i data-lucide="eye" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Confirm Password Input -->
            <div class="mb-6 sm:mb-6">
                <div class="relative">
                    <input type="password" id="password-confirmation-input" name="password_confirmation"
                      class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-12 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base"
                      required placeholder="Confirm your password">

                    <!-- Lock Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                      <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>

                    <!-- Eye toggle icon -->
                    <div class="absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 password-toggle" id="toggle-password-confirmation">
                        <i data-lucide="eye" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Agreement Checkbox -->
            <div class="mb-6 sm:mb-8">
                <label class="custom-checkbox flex items-start gap-3 text-sm sm:text-base text-gray-600 cursor-pointer">
                    <input type="checkbox" id="agreement" name="agreement" required>
                    <span class="checkmark flex-shrink-0 mt-1"></span>
                    <span class="leading-relaxed">
                        I agree to the 
                        <a href="#" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors duration-200 hover:underline">Terms & Conditions</a> 
                        and 
                        <a href="#" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors duration-200 hover:underline">Privacy Policy</a>.
                    </span>
                </label>
            </div>

            <!-- Error Container -->
            <div id="errorContainer" class="mb-4 p-3 text-red-600 bg-red-50 border border-red-200 rounded-lg hidden text-sm">
                <ul id="errorList" class="text-sm space-y-1"></ul>
            </div>
            
            <!-- Create Account Button -->
            <div class="mb-4 sm:mb-6">
                <button type="submit" id="register-btn" disabled
                        class="register-button w-full text-white font-semibold py-3 sm:py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center relative z-10 text-base">
                    <span id="btn-text" class="relative z-20">Create Account</span>
                    <svg id="loading-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden relative z-20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>
        </form>
        
        <!-- Sign In Link -->
        <div class="text-center form-animation">
            <p class="text-gray-600 font-semibold text-sm">
                Already have an account? 
                <a href="{{ route('customer.login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors duration-200 hover:underline">
                    Sign in
                </a>
            </p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-xl p-8 w-96 text-center border border-green-100">
            <div class="mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold mb-2 text-gray-800">Welcome!</h3>
            <p class="mb-6 text-gray-600" id="successMessage">Your account has been successfully created.</p>
            <button onclick="closeSuccessModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-300">
                Get Started
            </button>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        $(document).ready(function () {
            // Password toggle functionality for both password fields
            $('#toggle-password').on('click', function () {
                const passwordInput = $('#password-input');
                const eyeIcon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.attr('data-lucide', 'eye-off');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.attr('data-lucide', 'eye');
                }
                
                lucide.createIcons();
            });

            $('#toggle-password-confirmation').on('click', function () {
                const passwordInput = $('#password-confirmation-input');
                const eyeIcon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.attr('data-lucide', 'eye-off');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.attr('data-lucide', 'eye');
                }
                
                lucide.createIcons();
            });

            // Agreement checkbox functionality
            $('#agreement').on('change', function () {
                const isChecked = $(this).is(':checked');
                const registerBtn = $('#register-btn');
                
                if (isChecked) {
                    registerBtn.prop('disabled', false);
                } else {
                    registerBtn.prop('disabled', true);
                }
            });
        });

        function closeSuccessModal() {
            $('#successModal').addClass('hidden');
        }

        $('#registerForm').submit(function (e) {
            e.preventDefault();

            // Check if agreement is checked
            if (!$('#agreement').is(':checked')) {
                const errorContainer = $('#errorContainer');
                const errorList = $('#errorList');
                errorList.html('<li>You must agree to the Terms & Conditions and Privacy Policy to continue.</li>');
                errorContainer.removeClass('hidden');
                return;
            }

            let form = $(this);
            let url = "{{ route('customer.register') }}";
            let errorContainer = $('#errorContainer');
            let errorList = $('#errorList');
            
            // Button loading state elements
            const $registerBtn = $('#register-btn');
            const $btnText = $('#btn-text');
            const $spinner = $('#loading-spinner');

            // Clear previous errors and hide error container
            errorList.html('');
            errorContainer.addClass('hidden');
            
            // Set button to loading state
            $registerBtn.prop('disabled', true);
            $btnText.text('Creating Account...');
            $spinner.removeClass('hidden');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Reset button state
                    $registerBtn.prop('disabled', false);
                    $btnText.text('Create Account');
                    $spinner.addClass('hidden');

                    // Show success modal
                    $('#successModal').removeClass('hidden');

                    // Optional: Redirect after 3 seconds
                    setTimeout(function () {
                        window.location.href = "{{ route('customer.dashboard') }}";
                    }, 3000);
                },
                error: function (xhr) {
                    // Reset button state based on checkbox
                    const isAgreed = $('#agreement').is(':checked');
                    $registerBtn.prop('disabled', !isAgreed);
                    $btnText.text('Create Account');
                    $spinner.addClass('hidden');

                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, messages) {
                            messages.forEach(function (message) {
                                errorList.append(`<li>${message}</li>`);
                            });
                        });
                        errorContainer.removeClass('hidden');
                    } else {
                        errorList.append('<li>An unexpected error occurred. Please try again.</li>');
                        errorContainer.removeClass('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>