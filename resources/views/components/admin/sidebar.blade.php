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
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center font-semibold p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/>
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    </svg>
                    <span class="ml-3 text-sm">Dashboard</span>
                </a>
            </li>

            <!-- User Management -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.rider-applications.*') || request()->routeIs('admin.vendor-applications.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.rider-applications.*') || request()->routeIs('admin.vendor-applications.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-users-icon lucide-users">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <path d="M16 3.128a4 4 0 0 1 0 7.744"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            <span class="ml-3 text-sm">User Management</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.rider-applications.*') || request()->routeIs('admin.vendor-applications.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="/admin/users" class="block p-2 text-sm {{ request()->routeIs('admin.users.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">All Users</a></li>
                            <li><a href="/admin/rider-applications" class="block p-2 text-sm {{ request()->routeIs('admin.rider-applications.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Rider Application</a></li>
                            <li><a href="/admin/vendor-applications" class="block p-2 text-sm {{ request()->routeIs('admin.vendor-applications.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Vendor Application</a></li>
                        </ul>
                    </div>
                </div>
            </li>

            <!-- Platform Operations -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.vendors.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.districts.*') || request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.vendors.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.districts.*') || request()->routeIs('admin.orders.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-store-icon lucide-store">
                                <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
                                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                                <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
                                <path d="M2 7h20"/>
                                <path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>
                            </svg>
                            <span class="ml-3 text-sm">Platform Operations</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.vendors.*') || request()->routeIs('admin.products.*') || request()->routeIs('admin.districts.*') || request()->routeIs('admin.orders.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="{{ route('admin.categories.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.categories.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Categories</a></li>
                            <li><a href="{{ route('admin.vendors.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.vendors.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Vendors</a></li>
                            <li><a href="{{ route('admin.products.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.products.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Products</a></li>
                            <li><a href="{{ route('admin.districts.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.districts.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Delivery Zones</a></li>
                            <li><a href="{{ route('admin.orders.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.orders.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Orders</a></li>
                        </ul>
                    </div>
                </div>
            </li>

            <!-- Financial Management -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.payouts.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.payouts.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-wallet-icon lucide-wallet">
                                <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/>
                                <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/>
                            </svg>
                            <span class="ml-3 text-sm">Financial</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.payouts.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="{{ route('admin.payments.pending') }}" class="block p-2 text-sm {{ request()->routeIs('admin.payments.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Payment Transaction</a></li>
                          
                        </ul>
                    </div>
                </div>
            </li>

            <!-- System and Monitoring -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('system-settings.*') || request()->routeIs('admin.ratings.*') || request()->routeIs('admin.notifications.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('system-settings.*') || request()->routeIs('admin.ratings.*') || request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-monitor-smartphone-icon lucide-monitor-smartphone">
                                <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/>
                                <path d="M10 19v-3.96 3.15"/>
                                <path d="M7 19h5"/>
                                <rect width="6" height="10" x="16" y="12" rx="2"/>
                            </svg>
                            <span class="ml-3 text-sm">System & Monitoring</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('system-settings.*') || request()->routeIs('admin.ratings.*') || request()->routeIs('admin.notifications.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="{{ route('admin.ratings.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.ratings.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Ratings & Feedback</a></li>
                            <li><a href="{{ route('admin.notifications.index') }}" class="block p-2 text-sm {{ request()->routeIs('admin.notifications.*') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Notification Log</a></li>
                        </ul>
                    </div>
                </div>
            </li>

            <!-- My Account -->
            <li>
                <div class="dropdown-item">
                    <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg {{ request()->routeIs('admin.profile.*') || request()->routeIs('admin.password.*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white' : 'hover:bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:text-white' }} transition-all duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('admin.profile.*') || request()->routeIs('admin.password.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors lucide lucide-square-user-icon lucide-square-user">
                                <rect width="18" height="18" x="3" y="3" rx="2"/>
                                <circle cx="12" cy="10" r="3"/>
                                <path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <span class="ml-3 text-sm">My Account</span>
                        </div>
                        <svg class="dropdown-arrow w-4 h-4 {{ request()->routeIs('admin.profile.*') || request()->routeIs('admin.password.*') ? 'text-white' : 'text-green-500 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="dropdown-content ml-8 mt-1">
                        <ul class="space-y-1">
                            <li><a href="{{ route('admin.profile.edit') }}" class="block p-2 text-sm {{ request()->routeIs('admin.profile.edit') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Profile</a></li>
                            <li><a href="{{ route('admin.password.edit') }}" class="block p-2 text-sm {{ request()->routeIs('admin.password.edit') ? 'text-green-600 bg-green-50 font-semibold' : 'text-gray-600 hover:text-green-500 hover:bg-green-50' }} rounded-md transition-colors">Change Password</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</div>

