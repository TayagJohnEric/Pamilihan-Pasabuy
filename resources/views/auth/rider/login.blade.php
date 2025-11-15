<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        
        .remember-checkbox {
            accent-color: #059669;
        }
        
        .login-button {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            transition: left 0.3s ease;
        }
        
        .login-button:hover::before {
            left: 0;
        }
        
        .login-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(5, 150, 105, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
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
            .login-button {
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
            
            input[type="checkbox"] {
                min-width: 20px;
                min-height: 20px;
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
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
               Welcome Back, Rider!
            </h2>
            <p class="text-gray-600 font-medium text-sm sm:text-md">Log in to your dashboard and start managing your deliveries today.</p>
        </div>
        
        <!-- Error Message -->
        <div id="error-msg" class="mb-4 p-3 text-red-600 bg-red-50 border border-red-200 rounded-lg hidden form-animation text-sm"></div>
        
        <!-- Login Form -->
        <form id="login-form" class="form-animation">
            <!-- Email Input -->
            <div class="mb-4 sm:mb-6">
                <div class="relative">
                    <!-- Input Field -->
                    <input type="email" name="email" 
                      class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-4 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base" 
                      required placeholder="Enter your email address">

                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                      class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400">
                      <circle cx="12" cy="12" r="4"/>
                      <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>
                    </svg>
                </div>
            </div>
            
            <!-- Password Input -->
            <div class="mb-6 sm:mb-8">
                <div class="relative">
                    <!-- Input Field -->
                    <input type="password" id="password-input" name="password"
                      class="w-full bg-white border border-gray-300 rounded-lg pl-12 pr-12 py-3 sm:py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 focus:outline-none transition-all duration-200 text-base"
                      required placeholder="Enter your password">

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
            
            <!-- Sign In Button -->
            <div class="mb-4 sm:mb-6">
                <button type="submit" id="login-btn" 
                        class="login-button w-full text-white font-semibold py-3 sm:py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-emerald-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center relative z-10 text-base">
                    <span id="btn-text" class="relative z-20">Sign In</span>
                    <svg id="loading-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden relative z-20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>
        </form>
        
        <!-- Sign Up Link -->
        <div class="text-center form-animation">
            <p class="text-gray-600 font-semibold text-sm">
                Don't have an account? 
                <a href="{{ route('rider-applications.create') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors duration-200 hover:underline">
                    Apply Here
                </a>
            </p>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        $(document).ready(function () {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Password toggle functionality
            $('#toggle-password').on('click', function () {
                const passwordInput = $('#password-input');
                const eyeIcon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    // Show password
                    passwordInput.attr('type', 'text');
                    eyeIcon.attr('data-lucide', 'eye-off');
                } else {
                    // Hide password
                    passwordInput.attr('type', 'password');
                    eyeIcon.attr('data-lucide', 'eye');
                }
                
                // Reinitialize icons after changing the data-lucide attribute
                lucide.createIcons();
            });
            
            $('#login-form').on('submit', function (e) {
                e.preventDefault();
                
                // Set button to loading state
                const $loginBtn = $('#login-btn');
                const $btnText = $('#btn-text');
                const $spinner = $('#loading-spinner');
                
                $loginBtn.prop('disabled', true);
                $btnText.text('Logging in');
                $spinner.removeClass('hidden');
                
                // Hide any previous error messages
                $('#error-msg').addClass('hidden');
                
                $.ajax({
                    url: "{{ route('rider.login') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        // Show success state briefly
                        $btnText.text('Welcome back!');
                        $spinner.addClass('hidden');
                        
                        // Redirect to dashboard after brief delay
                        setTimeout(function() {
                            window.location.href = "{{ route('rider.dashboard') }}";
                        }, 800);
                    },
                    error: function (xhr) {
                        // Reset button state
                        $loginBtn.prop('disabled', false);
                        $btnText.text('Sign In');
                        $spinner.addClass('hidden');
                        
                        const res = xhr.responseJSON;
                        const errorMsg = res?.errors?.email ? res.errors.email[0] : 'Login failed.';
                        $('#error-msg').text(errorMsg).removeClass('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>