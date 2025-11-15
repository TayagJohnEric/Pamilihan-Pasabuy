<!-- Mobile Sidebar -->
<div id="sidebar" class="sidebar-transition sidebar-mobile-closed md:sidebar-mobile-open fixed md:static top-0 left-0 z-50 w-64 h-full bg-white text-gray-700 shadow-lg md:shadow-sm">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4">
        <div class="flex items-center space-x-3">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="San Fernando Market Logo" class="w-full h-full object-cover" />
            </div>
            <div>
                <span class="text-lg font-bold text-gray-800">PasaBuy</span>
                <div class="text-xs text-gray-500 leading-none">San Fernando Market</div>
            </div>
        </div>
        
        <!-- Close button (mobile only) -->
        <button id="sidebar-close" class="md:hidden p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-red-200">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-2 px-4">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('vendor.dashboard') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.dashboard') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.dashboard') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/>
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    <span class="ml-3 text-sm">Dashboard</span>
                </a>
            </li>
            
            <!-- Shop Profile -->
            <li>
                <a href="{{ route('vendor.profile.index') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.profile.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.profile.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-store-icon lucide-store">
                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                        <path d="M2 7h20"/>
                        <path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>
                    </svg>
                    <span class="ml-3 text-sm">Shop Profile</span>
                </a>
            </li>
            
            <!-- Product Management -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.products.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.products.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-apple-icon lucide-apple">
                                <path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/>
                                <path d="M10 2c1 .5 2 2 2 5"/>
                            </svg>
                            <span class="ml-3 text-sm">Product Management</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('vendor.products.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="{{ route('vendor.products.index') }}" class="block p-2 text-sm {{ request()->routeIs('vendor.products.index') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">My Products</a></li>
                            <li><a href="{{ route('vendor.products.create') }}" class="block p-2 text-sm {{ request()->routeIs('vendor.products.create') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Add New Product</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            
            <!-- Orders -->
            <li>
                <a href="{{ route('vendor.orders.index') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.orders.index') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">  
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.orders.index') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-list-ordered-icon lucide-list-ordered">
                        <path d="M10 12h11"/>
                        <path d="M10 18h11"/>
                        <path d="M10 6h11"/>
                        <path d="M4 10h2"/>
                        <path d="M4 6h1v4"/>
                        <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>
                    </svg>
                    <span class="ml-3 text-sm">Orders</span>
                </a>
            </li>

             <!-- Orders History-->
            <li>
                <a href="{{ route('vendor.orders.delivered') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.orders.delivered') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">  
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.orders.delivered') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors  lucide lucide-clipboard-clock-icon lucide-clipboard-clock"><path d="M16 14v2.2l1.6 1"/><path d="M16 4h2a2 2 0 0 1 2 2v.832"/><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2"/><circle cx="16" cy="16" r="6"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>                     
                        <path d="M10 12h11"/>
                        <path d="M10 18h11"/>
                        <path d="M10 6h11"/>
                        <path d="M4 10h2"/>
                        <path d="M4 6h1v4"/>
                        <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>
                    </svg>
                    <span class="ml-3 text-sm">Order History</span>
                </a>
            </li>
            
            <!-- Ratings and Review -->
            <li>
                <a href="{{ route('vendor.ratings.index') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.ratings.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.ratings.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-star-icon lucide-star">
                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                    </svg>               
                    <span class="ml-3 text-sm">Ratings & Review</span>
                </a>
            </li>
            
            <!-- Sales and Earnings -->
            <li>
                <a href="{{ route('vendor.earnings.index') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('vendor.earnings.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('vendor.earnings.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-chart-line-icon lucide-chart-line">
                        <path d="M3 3v16a2 2 0 0 0 2 2h16"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    <span class="ml-3 text-sm">Sales & Earnings</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

