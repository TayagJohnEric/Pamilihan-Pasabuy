<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
     <!-- Lucide CDN for icons --> 
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Sidebar Transitions */
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .backdrop-transition {
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Dropdown Animations */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                       opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
        }
        
        .dropdown-content.active {
            max-height: 200px;
            opacity: 1;
        }
        
        .dropdown-arrow {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }
        
        /* Mobile sidebar positioning */
        @media (max-width: 768px) {
            .sidebar-mobile-closed {
                transform: translateX(-100%);
            }
            
            .sidebar-mobile-open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden relative">
        <!--Sidebar-->
        @include('components.admin.sidebar')

        <!-- Backdrop overlay -->
        <div id="sidebar-backdrop" class="backdrop-transition fixed inset-0 bg-black bg-opacity-50 z-40 opacity-0 pointer-events-none md:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
        <!--Header-->
          @include('components.admin.header')

            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">

                 @yield('content')

            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar elements
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const sidebarClose = document.getElementById('sidebar-close');

            // Header dropdown elements
            const profileMenuButton = document.getElementById('profile-menu-button');
            const profileDropdown = document.getElementById('profile-dropdown');

            // Sidebar functionality
            function openSidebar() {
                sidebar.classList.remove('sidebar-mobile-closed');
                sidebar.classList.add('sidebar-mobile-open');
                sidebarBackdrop.classList.remove('opacity-0', 'pointer-events-none');
                sidebarBackdrop.classList.add('opacity-100', 'pointer-events-auto');
                document.body.style.overflow = 'hidden'; // Prevent body scrolling
            }

            function closeSidebar() {
                sidebar.classList.remove('sidebar-mobile-open');
                sidebar.classList.add('sidebar-mobile-closed');
                sidebarBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
                sidebarBackdrop.classList.add('opacity-0', 'pointer-events-none');
                document.body.style.overflow = ''; // Restore body scrolling
            }

            // Event listeners for sidebar
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 768 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target) &&
                    !sidebar.classList.contains('sidebar-mobile-closed')) {
                    closeSidebar();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    // Desktop view - reset mobile states
                    sidebar.classList.remove('sidebar-mobile-closed', 'sidebar-mobile-open');
                    sidebarBackdrop.classList.add('opacity-0', 'pointer-events-none');
                    document.body.style.overflow = '';
                } else {
                    // Mobile view - ensure sidebar is closed by default
                    if (!sidebar.classList.contains('sidebar-mobile-closed')) {
                        sidebar.classList.add('sidebar-mobile-closed');
                    }
                }
            });

            // Header dropdown functionality - ONLY for profile menu
            if (profileMenuButton && profileDropdown) {
                profileMenuButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                    
                    // Close notification dropdown if open (let the notification component handle its own state)
                    const notificationDropdown = document.getElementById('notification-dropdown');
                    if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) {
                        notificationDropdown.classList.add('hidden', 'scale-95', 'opacity-0');
                        notificationDropdown.classList.remove('scale-100', 'opacity-100');
                    }
                });
            }

            // Close profile dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (profileMenuButton && profileDropdown && 
                    !profileMenuButton.contains(event.target) && 
                    !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            }); 
            
            // Dropdown toggle functionality for navigation items
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    const dropdownContent = this.nextElementSibling;
                    const arrow = this.querySelector('.dropdown-arrow');

                    // Close other dropdowns
                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== this) {
                            const otherContent = otherToggle.nextElementSibling;
                            const otherArrow = otherToggle.querySelector('.dropdown-arrow');
                            if (otherContent) otherContent.classList.remove('active');
                            if (otherArrow) otherArrow.classList.remove('rotated');
                        }
                    });

                    // Toggle current dropdown
                    if (dropdownContent) dropdownContent.classList.toggle('active');
                    if (arrow) arrow.classList.toggle('rotated');
                });
            });
        });
    </script>
</body>
</html>