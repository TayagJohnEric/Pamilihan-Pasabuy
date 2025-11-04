<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200 px-4 py-3">
    <div class="flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button 
            id="sidebar-toggle" 
            class="md:hidden p-2 rounded-lg text-gray-600 hover:text-green-500 hover:bg-green-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Layout purposes -->
       <span></span>

        <!-- Header Actions -->
        <div class="flex items-center space-x-3">
            <!-- Notifications -->
            @include('components.notification')
            
            <!-- Profile Section -->
            <div class="relative">
                <!-- Profile Button -->
                <button 
                    id="profile-menu-button" 
                    class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200"
                >
                    <!-- Profile Picture -->
                    @if(Auth::user()->profile_image_url)
                        <img 
                            src="{{ asset('storage/' . Auth::user()->profile_image_url) }}" 
                            alt="User Profile" 
                            class="h-10 w-10 rounded-full object-cover"
                        >
                    @else
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-white">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <!-- User Info -->
                    <div class="hidden sm:block text-left leading-tight">
                        <p class="text-sm font-semibold text-gray-800 m-0">
                            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </p>
                        <p class="text-xs font-medium text-green-500 truncate m-0">
                            {{ Auth::user()->role === 'customer' ? 'Customer' : ucfirst(Auth::user()->role) }}
                        </p>
                    </div>

                    <!-- Dropdown Icon -->
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Profile Dropdown Menu -->
                <div 
                    id="profile-dropdown" 
                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                >
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">
                            Settings
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">
                            Help
                        </a>
                        
                        <hr class="my-1 border-gray-200">
                        
                        <!-- Logout Form -->
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button 
                                type="submit" 
                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="w-5 h-5 text-gray-500 mr-3" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" x2="9" y1="12" y2="12" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>