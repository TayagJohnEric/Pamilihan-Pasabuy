<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PasaBuy - Your San Fernando Market, Delivered Fresh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'market-primary': '#059669',
                        'market-secondary': '#10b981',
                        'market-accent': '#f59e0b',
                        'market-dark': '#065f46'
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-down': 'fadeInDown 0.8s ease-out',
                        'fade-in-left': 'fadeInLeft 0.8s ease-out',
                        'fade-in-right': 'fadeInRight 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        fadeInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        .gradient-text {
            background: linear-gradient(135deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stats-counter {
            font-variant-numeric: tabular-nums;
        }
        
        body {
            overflow-x: hidden;
            padding-top: 0;
        }
        
        section {
            position: relative;
            z-index: 1;
        }
        
        header {
            z-index: 1000;
        }
        
        #home {
            padding-top: 80px;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        @media (max-width: 768px) {
            #home {
                padding-top: 60px;
            }
        }

        .header-white .nav-link,
        .header-white .fa-shopping-basket,
        .header-white .text-gray-800,
        .header-white .text-gray-700,
        .header-white .text-gray-500,
        .header-white .font-medium,
        .header-white .font-bold,
        .header-white .fa-bars,
        .header-white .fa-shopping-cart,
        .header-white .fa-play {
            color: #fff !important;
        }
        .header-white .bg-green-600 {
            background-color: #fff !important;
            color: #059669 !important;
        }
        .header-white .border-white {
            border-color: #fff !important;
        }
        .header-white .bg-white {
            background-color: transparent !important;
            color: #fff !important;
        }
        
        .hero-title.animate-in {
            animation: heroTitleIn 1.2s ease-out forwards;
        }
        
        .hero-subtitle.animate-in {
            animation: heroSubtitleIn 1.2s ease-out 0.3s forwards;
        }
        
        .hero-buttons.animate-in {
            animation: heroButtonsIn 1.2s ease-out 0.6s forwards;
        }
        
        .hero-trust.animate-in {
            animation: heroTrustIn 1.2s ease-out 0.9s forwards;
        }
        
        .hero-scroll.animate-in {
            animation: heroScrollIn 1.2s ease-out 1.2s forwards;
        }
        
        @keyframes heroTitleIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            50% {
                opacity: 0.8;
                transform: translateY(-5px) scale(1.02);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes heroSubtitleIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes heroButtonsIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            50% {
                opacity: 0.8;
                transform: translateY(-3px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes heroTrustIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes heroScrollIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        #home::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(16, 185, 129, 0.1));
            opacity: 0;
            animation: backgroundFadeIn 2s ease-out 0.5s forwards;
            z-index: 1;
        }
        
        @keyframes backgroundFadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Feature Card Styles */
        .feature-card {
            position: relative;
            height: 300px;
            border-radius: 1rem;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: all 0.3s ease-in-out;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease-in-out;
        }

        .feature-card:hover::before {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2));
        }

        .feature-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            color: white;
        }

        /* Carousel Styles */
        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 400px;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .carousel-slides {
            display: flex;
            width: 400%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-slide {
            width: 25%;
            height: 100%;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
        }

        .carousel-nav {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .carousel-arrow:hover {
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .carousel-arrow.prev {
            left: 1rem;
        }

        .carousel-arrow.next {
            right: 1rem;
        }

        .carousel-arrow i {
            color: #059669;
            font-size: 1.125rem;
        }

        @media (max-width: 768px) {
            .feature-card {
                height: 250px;
            }
            
            .feature-content {
                padding: 1.5rem;
            }
            
            .carousel-container {
                height: 300px;
            }
            
            .carousel-arrow {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body class="font-sans bg-white">
    
   <!-- Header for top of hero (white content) -->
<header id="header-top" class="fixed top-0 w-full z-50 transition-all duration-300 animate-fade-in-down">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
           <a href="{{ route('admin.login') }}">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transform hover:scale-110 transition-transform overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
                </div>
            </a>
            <div>
                <span class="text-2xl font-bold text-white">PasaBuy</span>
                <div class="text-xs text-white leading-none">San Fernando Market</div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Home</a>
            <a href="#how-it-works" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">How It Works</a>
            <a href="#features" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Features</a>
            <a href="#testimonials" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Reviews</a>
            <a href="#about" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">About</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden lg:flex items-center space-x-3">
            <a href="{{ route('customer.login') }}" class="px-3 py-2.5 text-white hover:bg-white hover:text-green-600 border border-white rounded-xl transition-colors font-medium">
                Log In
            </a>
            <a href="{{ route('customer.register') }}" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 theme text-white rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Sign Up Free
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-white p-2" onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </nav>

   <!-- Mobile Menu -->
<div id="mobile-menu" class="hidden lg:hidden glass-effect border-t">
    <div class="container mx-auto px-4 py-6 space-y-4">
        <!-- Navigation Links -->
        <a href="#home" class="block text-gray-700 hover:text-green-600 py-2 font-medium transition-colors">Home</a>
        <a href="#how-it-works" class="block text-gray-700 hover:text-green-600 py-2 font-medium transition-colors">How It Works</a>
        <a href="#features" class="block text-gray-700 hover:text-green-600 py-2 font-medium transition-colors">Features</a>
        <a href="#testimonials" class="block text-gray-700 hover:text-green-600 py-2 font-medium transition-colors">Reviews</a>
        <a href="#about" class="block text-gray-700 hover:text-green-600 py-2 font-medium transition-colors">About</a>
        
        <!-- Login/Signup Buttons -->
        <div class="pt-4 border-t border-gray-200 space-y-3">
            <a href="{{ route('customer.login') }}" 
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition-colors font-medium shadow-sm">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Log In as Customer
            </a>
            
            <a href="{{ route('rider.login') }}" 
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition-colors font-medium shadow-sm">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-motorbike-icon lucide-motorbike"><path d="m18 14-1-3"/><path d="m3 9 6 2a2 2 0 0 1 2-2h2a2 2 0 0 1 1.99 1.81"/><path d="M8 17h3a1 1 0 0 0 1-1 6 6 0 0 1 6-6 1 1 0 0 0 1-1v-.75A5 5 0 0 0 17 5"/><circle cx="19" cy="17" r="3"/><circle cx="5" cy="17" r="3"/></svg>
                Log In as Rider
            </a>
            
            <a href="{{ route('vendor.login') }}" 
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition-colors font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide lucide-store-icon lucide-store"><path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5"/><path d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244"/><path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05"/></svg>
                Log In as Vendor
            </a>
            
            <a href="{{ route('customer.register') }}" 
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Sign Up Free
            </a>
        </div>
    </div>
</div>
</header>

<!-- Header for scrolling (original style) -->
<header id="header-scroll" class="fixed top-0 w-full z-50 transition-all duration-300 animate-fade-in-down glass-effect" style="display:none;">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
    <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform overflow-hidden">
            <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
        </div>
        <div>
            <span class="text-2xl font-bold text-gray-800">PasaBuy</span>
            <div class="text-xs text-gray-500 leading-none">San Fernando Market</div>
    </div>
</div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Home</a>
            <a href="#how-it-works" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">How It Works</a>
            <a href="#features" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Features</a>
            <a href="#testimonials" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Reviews</a>
            <a href="#about" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">About</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden lg:flex items-center space-x-3">
            <a href="{{ route('customer.login') }}" 
                class="px-3 py-2.5 text-gray-700 hover:bg-white hover:text-green-600 border border-gray-300 rounded-xl transition-colors font-medium">
                    Log In
            </a>           
            
            <a href="{{ route('customer.register') }}" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 theme text-white rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Sign Up Free
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-gray-700 p-2" onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </nav>
</header>

<!-- Hero Section -->
<section id="home" 
         class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/hero-bg.png') }}');">
             <div class="absolute inset-0 bg-black/60 z-0"></div>

                         <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <div class="relative z-10 w-full px-4 py-20">
        <div class="max-w-5xl mx-auto text-center text-white">
           <h1 class="text-4xl lg:text-7xl font-bold mb-6 leading-tight drop-shadow-lg hero-title opacity-0 transform translate-y-8">
                Your
                <span class="text-white">San Fernando Market</span>,
                <br>Delivered
                <span class="text-emerald-500 font-serif italic" style="font-family: 'Pacifico', cursive;">Fresh</span>
                to Your Door
            </h1>


            <p class="text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed drop-shadow hero-subtitle opacity-0 transform translate-y-8">
                Experience the convenience of online grocery shopping while supporting 
                local businesses. Fresh produce, authentic flavors, delivered with care.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 hero-buttons opacity-0 transform translate-y-8">
              <a href="{{ route('customer.register') }}" class="bg-emerald-600 text-white-600 hover:bg-emerald-500 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Start Shopping Now
                </a>
                <button class="border-2 border-white text-white hover:bg-white hover:text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Learn More
                </button>
            </div>
            <!-- Trust Indicators -->
            <div class="flex items-center justify-center space-x-6 text-sm text-white/80 hero-trust opacity-0 transform translate-y-8">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-green-300 mr-2"></i>
                    100% Secure
                </div>
                <div class="flex items-center">
                    <i class="fas fa-truck text-green-300 mr-2"></i>
                    Same Day Delivery
                </div>
                <div class="flex items-center">
                    <i class="fas fa-leaf text-green-300 mr-2"></i>
                    Fresh Guarantee
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10 hero-scroll opacity-0">
        <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</section>

 <!-- Apply Section -->
<section class="py-20 bg-gray-50 animate-on-scroll">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Choose Your 
                                <span class="text-emerald-500 font-serif italic" style="font-family: 'Pacifico', cursive;">Journey</span>

            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Whether you're shopping for fresh groceries, growing your business, or earning flexible income - we have the perfect path for you.</p>
        </div>

        <div class="max-w-5xl mx-auto space-y-8">

            <!-- Customer Card -->
            <div class="group bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Image Section -->
                    <div class="lg:w-1/3 h-64 lg:h-auto relative overflow-hidden">
                        <img src="{{ asset('images/apply-section1.png') }}"
                             alt="Fresh produce and market stall" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    <!-- Content Section -->
                    <div class="lg:w-2/3 p-8 lg:p-10">
                        <div class="flex gap-1 items-center mb-2">
                            <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lg text-emerald-600 lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900">Shop as a Customer</h3>
                        </div>
                        
                        <p class="text-gray-600 text-md mb-6 leading-relaxed">
                                Browse thousands of fresh products from trusted local vendors. Get everything delivered fast and fresh to your doorstep. 
                        </p>
                        
                        <!-- Key Features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">  500+ Fresh products daily</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Same-day delivery available</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">100% freshness guarantee</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm"> Secure payment options</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('customer.register') }}" 
                           class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            Become a Customer
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Vendor Card - Image moved to right -->
            <div class="group bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Content Section - Now first on desktop -->
                    <div class="lg:w-2/3 p-8 lg:p-10">
                        <div class="flex gap-1 items-center mb-2">
                            <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lg text-emerald-600 lucide lucide-store-icon lucide-store"><path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5"/><path d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244"/><path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05"/></svg>                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900">Sell as a Vendor</h3>
                        </div>
                        
                        <p class="text-gray-600 text-md mb-6 leading-relaxed">
                           Transform your market stall into a digital storefront. Reach thousands of customers and grow your business exponentially.
                        </p>
                        
                        <!-- Key Features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Easy online store setup</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Reach 1,000+ customers</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Real-time order management</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Weekly payouts</span>
                            </div>
                        </div>
                        
                            <a href="{{ route('vendor-applications.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            Become a Vendor
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                    
                    <!-- Image Section - Now on the right -->
                    <div class="lg:w-1/3 h-64 lg:h-auto relative overflow-hidden order-first lg:order-last">
                        <img src="{{ asset('images/apply-section2.png') }}"
                             alt="Fresh produce and market stall" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <!-- Rider Card -->
            <div class="group bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Image Section -->
                    <div class="lg:w-1/3 h-64 lg:h-auto relative overflow-hidden">
                        <img src="{{ asset('images/apply-section3.png') }}" 
                             alt="Delivery rider on motorcycle" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    
                    <!-- Content Section -->
                    <div class="lg:w-2/3 p-8 lg:p-10">
                        <div class="flex gap-1 items-center mb-2">
                            <div class="w-12 h-12 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lg text-emerald-600 lucide lucide-truck-icon lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900">Deliver as a Rider</h3>
                        </div>
                        
                        <p class="text-gray-600 text-md mb-6 leading-relaxed">
                            Earn flexible income by connecting your community with fresh groceries. Be your own boss with competitive rates.
                        </p>
                        
                        <!-- Key Features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Flexible working hours</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Competitive delivery rates</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Weekly earnings</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3"></div>
                                <span class="text-sm">Fuel bonus incentives</span>
                            </div>
                        </div>
                        
                             <a href="{{ route('rider-applications.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            Become a Rider
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- How It Works Section -->
<section id="how-it-works" class="py-16 md:py-20 bg-gray-50 animate-on-scroll">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 md:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 md:mb-6 leading-tight">
                Fresh Groceries in 3 Easy 
                                <span class="text-emerald-500 font-serif italic" style="font-family: 'Pacifico', cursive;">Steps</span>

            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed px-4">
                From browsing to delivery, we've made grocery shopping as simple as 1-2-3. Experience the easiest way to get fresh market products.
            </p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <!-- Step 1 -->
                <div class="text-center group relative">       
                    <div class="bg-white rounded-2xl shadow p-6 md:p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-emerald-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-search text-emerald-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">Browse & Select</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Explore our extensive catalog of fresh produce, meats, seafood, and local specialties from verified San Fernando Market vendors.
                        </p>
                    </div>
                </div>

                <!-- Mobile Connection Line -->
                <div class="flex justify-center md:hidden">
                    <div class="w-1 h-8 bg-gradient-to-b from-emerald-400 to-emerald-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="text-center group relative">           
                    <div class="bg-white rounded-2xl shadow p-6 md:p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-emerald-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-hands text-emerald-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">We Pick & Pack</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Our experienced vendors personally select and prepare your order, ensuring you get the freshest, highest-quality products available.
                        </p>
                    </div>
                </div>

                <!-- Mobile Connection Line -->
                <div class="flex justify-center md:hidden">
                    <div class="w-1 h-8 bg-gradient-to-b from-emerald-400 to-emerald-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="text-center group relative">      
                    <div class="bg-white rounded-2xl shadow p-6 md:p-8 hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-emerald-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-truck-fast text-emerald-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>                          
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">Fast, Local Delivery</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Our local riders deliver your fresh groceries straight to your doorstep, maintaining the cold chain for optimal freshness.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Desktop Process Flow -->
            <div class="hidden md:flex justify-center mt-12 lg:mt-16">
                <div class="flex items-center space-x-4 lg:space-x-8">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white text-xl rounded-full font-bold shadow-lg">
                        1
                    </div>
                    <div class="h-1 w-12 lg:w-20 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white text-xl rounded-full font-bold shadow-lg">
                        2
                    </div>
                    <div class="h-1 w-12 lg:w-20 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white text-xl rounded-full font-bold shadow-lg">
                        3
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Features Section - Redesigned -->
    <section id="features" class="py-20 bg-gray-50 animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Experience the 
                    <span class="text-emerald-500 font-serif italic" style="font-family: 'Pacifico', cursive;">Difference</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We're more than just a delivery app - we're your bridge to authentic, fresh, and affordable market goods from the heart of San Fernando.</p>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    <!-- Feature Card 1 - Support Local Businesses -->
                    <div class="feature-card" style="background-image: url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=300&fit=crop&crop=center');">
                        <div class="feature-content">
                            <h3 class="text-2xl font-bold mb-3">Support Local</h3>
                            <p class="text-white/90 mb-4 leading-relaxed">Every purchase directly supports San Fernando Market vendors and their families.</p>
                            <a href="#" class="inline-flex items-center text-white hover:text-emerald-300 font-semibold transition-colors">
                                Learn More <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Feature Card 2 - Unbeatable Freshness -->
                    <div class="feature-card" style="background-image: url('https://images.unsplash.com/photo-1610348725531-843dff563e2c?w=500&h=300&fit=crop&crop=center');">
                        <div class="feature-content">
                            <h3 class="text-2xl font-bold mb-3">Unbeatable Freshness</h3>
                            <p class="text-white/90 mb-4 leading-relaxed">Get the same quality you'd hand-pick yourself from our trusted vendors.</p>
                            <a href="#" class="inline-flex items-center text-white hover:text-emerald-300 font-semibold transition-colors">
                                Learn More <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Feature Card 3 - Ultimate Convenience -->
                    <div class="feature-card" style="background-image: url('https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=500&h=300&fit=crop&crop=center');">
                        <div class="feature-content">
                            <h3 class="text-2xl font-bold mb-3">Ultimate Convenience</h3>
                            <p class="text-white/90 mb-4 leading-relaxed">Skip the crowds and parking hassles. Shop from home with ease.</p>
                            <a href="#" class="inline-flex items-center text-white hover:text-emerald-300 font-semibold transition-colors">
                                Learn More <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Feature Card 4 - Trusted & Secure -->
                    <div class="feature-card" style="background-image: url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&h=300&fit=crop&crop=center');">
                        <div class="feature-content">
                            <h3 class="text-2xl font-bold mb-3">Trusted & Secure</h3>
                            <p class="text-white/90 mb-4 leading-relaxed">Shop with confidence using our secure payment system and reliable delivery.</p>
                            <a href="#" class="inline-flex items-center text-white hover:text-emerald-300 font-semibold transition-colors">
                                Learn More <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section - Redesigned with Carousel -->
    <section id="about" class="py-20 bg-gradient-to-br from-gray-50 to-emerald-50 animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Content -->
                    <div>
                        <div class="flex justify-center lg:justify-start">
                            <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-semibold mb-6">
                                <i class="fas fa-info-circle mr-2"></i>
                                Our Story
                            </div>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">Connecting Community Through Fresh Food</h2>
                        <p class="text-md text-gray-600 mb-8 leading-relaxed">
                            Born from a deep love for San Fernando's rich market tradition, PasaBuy bridges the gap between time-honored vendors and modern convenience. We believe that fresh, quality food should be accessible to everyone, while preserving the livelihoods of our local market heroes.
                        </p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Supporting 100+ local vendors and their families</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Serving 1,000+ happy customers daily</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Committed to sustainable, eco-friendly practices</span>
                            </div>
                        </div>
                      <div class="flex justify-center lg:justify-start">
                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-users mr-2"></i>
                                Join Our Mission
                            </button>
                        </div>

                    </div>

                    <!-- Image Carousel -->
                    <div class="flex justify-center lg:justify-end">
                        <div class="carousel-container" id="aboutCarousel">
                            <div class="carousel-slides" id="carouselSlides">
                                <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&h=400&fit=crop&crop=center');"></div>
                                <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop&crop=center');"></div>
                                <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1610348725531-843dff563e2c?w=600&h=400&fit=crop&crop=center');"></div>
                                <div class="carousel-slide" style="background-image: url('https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=600&h=400&fit=crop&crop=center');"></div>
                            </div>
                            
                            <!-- Navigation Arrows -->
                            <button class="carousel-arrow prev" id="prevBtn">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="carousel-arrow next" id="nextBtn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Navigation Dots -->
                            <div class="carousel-nav" id="carouselNav">
                                <div class="carousel-dot active" data-slide="0"></div>
                                <div class="carousel-dot" data-slide="1"></div>
                                <div class="carousel-dot" data-slide="2"></div>
                                <div class="carousel-dot" data-slide="3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Final CTA Section with Mobile Mockups Left, Content Right -->
<section class="py-20 relative overflow-hidden animate-on-scroll">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/hero-bg.png') }}');">
    </div>
    
    <!-- Emerald Gradient Overlay with adjustable opacity -->
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/80 to-teal-600/75"></div>
    
    <!-- Optional: Additional overlay for better text contrast (remove if not needed) -->
    <div class="absolute inset-0 bg-black/10"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-12 max-w-7xl mx-auto">
            
            <!-- Mobile Mockups - Left Side  -->
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-start">
                <div class="relative z-10 transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/mobile-mockup.png') }}" 
                         alt="Mobile App Mockup" 
                         class="w-full max-w-xl lg:max-w-2xl xl:max-w-3xl h-auto object-contain drop-shadow-2xl"
                         style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
                </div>
            </div>
            
            <!-- CTA Content - Right Side -->
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <div class="max-w-2xl mx-auto lg:mx-0">
                    <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight drop-shadow-lg">
                        Ready to Get Started?
                    </h2>
                    <p class="text-xl lg:text-2xl text-emerald-100 mb-8 leading-relaxed drop-shadow-md">
                        Join thousands of satisfied customers who've made the switch to convenient, fresh grocery delivery.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start items-center mb-8">
                        <button class="w-full sm:w-auto bg-white text-emerald-600 hover:bg-gray-100 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-2xl backdrop-blur-sm">
                            <i class="fas fa-user-plus mr-3"></i>
                            Create Your Free Account
                        </button>
                        <button class="w-full sm:w-auto border-2 border-white text-white hover:bg-white hover:text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 backdrop-blur-sm">
                            <i class="fas fa-phone mr-3"></i>
                            Contact Us
                        </button>
                    </div>
                    
                    <!-- Features -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-8 text-emerald-100">
                        <div class="flex items-center drop-shadow-md">
                            <i class="fas fa-mobile-alt mr-2 text-lg"></i>
                            <span class="text-base font-medium">Mobile Friendly</span>
                        </div>
                        <div class="flex items-center drop-shadow-md">
                            <i class="fas fa-lock mr-2 text-lg"></i>
                            <span class="text-base font-medium">100% Secure</span>
                        </div>
                        <div class="flex items-center drop-shadow-md">
                            <i class="fas fa-clock mr-2 text-lg"></i>
                            <span class="text-base font-medium">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Optional: Custom CSS for additional fine-tuning -->
<style>
    /* Custom background blend mode for even better integration */
    .cta-background-blend {
        background-blend-mode: multiply;
    }
    
    /* Ensure text remains readable on various background colors */
    @media (max-width: 768px) {
        .cta-mobile-overlay {
            background: linear-gradient(to right, rgba(5, 150, 105, 0.9), rgba(20, 184, 166, 0.85));
        }
    }
</style>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                         <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform overflow-hidden">
                                <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
                            </div>
                        <div>
                            <span class="text-2xl font-bold">PasaBuy</span>
                            <div class="text-xs text-gray-400">San Fernando Market</div>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">Connecting San Fernando's rich market tradition with modern convenience. Fresh groceries delivered with care.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-emerald-400 transition-colors">Home</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-emerald-400 transition-colors">How It Works</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-emerald-400 transition-colors">Features</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-emerald-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Help Center</a></li>
                    </ul>
                </div>

                <!-- For Partners -->
                <div>
                    <h3 class="text-xl font-bold mb-6">For Partners</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Become a Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Become a Rider</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Vendor Portal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Rider Portal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Partner Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Commission Structure</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Contact Info</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-emerald-400 mt-1"></i>
                            <div class="text-gray-400">
                                <div>New San Fernando Public Market</div>
                                <div>San Fernando, Pampanga</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <span class="text-gray-400">+63 917 123 4567</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <span class="text-gray-400">hello@pamilihan.ph</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-emerald-400"></i>
                            <span class="text-gray-400">24/7 Customer Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="text-gray-400 text-center lg:text-left">
                        <p>&copy; 2024 PasaBuy. All rights reserved.</p>
                    </div>
                    <div class="flex flex-wrap justify-center lg:justify-end space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Legal</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Header Background Change on Scroll
        const headerTop = document.getElementById('header-top');
        const headerScroll = document.getElementById('header-scroll');
        const hero = document.getElementById('home');

        function toggleHeaders() {
            if (window.scrollY < hero.offsetHeight - headerTop.offsetHeight) {
                headerTop.style.display = '';
                headerScroll.style.display = 'none';
            } else {
                headerTop.style.display = 'none';
                headerScroll.style.display = '';
            }
        }

        window.addEventListener('scroll', toggleHeaders);
        window.addEventListener('resize', toggleHeaders);
        toggleHeaders();

        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.add('hidden');
            });
        });

        // Animation on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Carousel Functionality
        let currentSlide = 0;
        const totalSlides = 4;

        function updateCarousel() {
            const slides = document.getElementById('carouselSlides');
            const dots = document.querySelectorAll('.carousel-dot');
            
            slides.style.transform = `translateX(-${currentSlide * 25}%)`;
            
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateCarousel();
        }

        // Event Listeners for Carousel
        document.getElementById('nextBtn').addEventListener('click', nextSlide);
        document.getElementById('prevBtn').addEventListener('click', prevSlide);

        document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
            dot.addEventListener('click', () => goToSlide(index));
        });

        // Auto-play carousel
        setInterval(nextSlide, 5000);

        // Initialize animations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            function triggerHeroAnimations() {
                const heroTitle = document.querySelector('.hero-title');
                const heroSubtitle = document.querySelector('.hero-subtitle');
                const heroButtons = document.querySelector('.hero-buttons');
                const heroTrust = document.querySelector('.hero-trust');
                const heroScroll = document.querySelector('.hero-scroll');
                
                setTimeout(() => heroTitle.classList.add('animate-in'), 100);
                setTimeout(() => heroSubtitle.classList.add('animate-in'), 400);
                setTimeout(() => heroButtons.classList.add('animate-in'), 700);
                setTimeout(() => heroTrust.classList.add('animate-in'), 1000);
                setTimeout(() => heroScroll.classList.add('animate-in'), 1300);
            }
            
            setTimeout(triggerHeroAnimations, 200);

            // Add active state to navigation links
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section[id]');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-emerald-600');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('text-emerald-600');
                    }
                });
            });
        });

        function revealOnScroll() {
            const reveals = document.querySelectorAll('.animate-on-scroll');
            reveals.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('animated');
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);
        revealOnScroll();

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.absolute.top-10.left-10');
            const parallax2 = document.querySelector('.absolute.bottom-10.right-10');
            
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
            if (parallax2) {
                parallax2.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // Add form validation for any future forms
        function validateForm(form) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            return isValid;
        }

        // Add lazy loading for images (if any are added later)
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Touch/swipe support for carousel on mobile
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        const carousel = document.getElementById('aboutCarousel');

        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });

        carousel.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            isDragging = false;
            
            const diffX = startX - currentX;
            const threshold = 50;
            
            if (Math.abs(diffX) > threshold) {
                if (diffX > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        });

        // Pause auto-play on hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        carousel.addEventListener('mouseleave', () => {
            autoPlayInterval = setInterval(nextSlide, 5000);
        });

        let autoPlayInterval = setInterval(nextSlide, 5000);
    </script>
</body>
</html>