<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Apply for Leave - MOIC Performance Appraisal System</title>
    
    <!-- Tailwind CSS via CDN (Production-friendly version) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* MOIC Brand Colors - Consistent with dashboard */
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-navy-light: #3328a5;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-gradient: linear-gradient(135deg, #110484, #1a0c9e);
            --moic-gradient-accent: linear-gradient(135deg, #e7581c, #ff7c45);
        }
        
        /* File upload animations and styles */
        .file-upload-progress {
            transition: width 0.3s ease;
        }
        
        .upload-area {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover, .upload-area.drag-over {
            border-color: #110484;
            background-color: rgba(17, 4, 132, 0.05);
        }
        
        .upload-area.has-file {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.05);
        }
        
        /* Form input focus styles */
        input:focus, select:focus, textarea:focus {
            outline: none;
            ring: 2px solid #110484;
            border-color: transparent;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #110484;
        }
        
        /* Loading spinner */
        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid #110484;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Alert animations */
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .alert-slide {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Notification badge pulse */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .notification-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            input, select, textarea, button {
                font-size: 16px !important;
            }
        }
        
        /* Logo Container */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 0.375rem;
            padding: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.125rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .moic-navy-bg {
            background-color: #110484;
        }
        
        .moic-accent-bg {
            background-color: #e7581c;
        }
        
        /* Navigation link styles */
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Gradient Header Animation */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
        }
        
        /* Custom utility classes */
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }
        
        .from-\[\#110484\] {
            --tw-gradient-from: #110484;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(17, 4, 132, 0));
        }
        
        .to-\[\#1a0c9e\] {
            --tw-gradient-to: #1a0c9e;
        }
        
        .text-\[\#110484\] {
            color: #110484;
        }
        
        .bg-\[\#110484\] {
            background-color: #110484;
        }
        
        .border-\[\#110484\] {
            border-color: #110484;
        }
        
        .ring-\[\#110484\] {
            --tw-ring-color: #110484;
        }
        
        .hover\:text-\[\#e7581c\]:hover {
            color: #e7581c;
        }
        
        /* Mobile menu */
        .mobile-menu-enter {
            animation: slideDown 0.3s ease forwards;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Stat Card Styles - Matching your example */
        .stat-card {
            transition: all 0.2s ease;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        /* Background utilities */
        .bg-blue-50 { background-color: #eff6ff; }
        .bg-purple-50 { background-color: #faf5ff; }
        .bg-green-50 { background-color: #f0fdf4; }
        .bg-amber-50 { background-color: #fffbeb; }
        .bg-yellow-50 { background-color: #fef3c7; }
        .bg-red-50 { background-color: #fee2e2; }
        
        /* Text utilities */
        .text-purple-600 { color: #7c3aed; }
        .text-green-600 { color: #059669; }
        .text-warning { color: #f59e0b; }
        .text-danger { color: #ef4444; }
        .text-success { color: #10b981; }
        
        /* Notification Dropdown - FIXED POSITIONING FOR MOBILE */
        .notification-wrapper {
            position: static !important;
        }
        
        @media (min-width: 768px) {
            .notification-wrapper {
                position: relative !important;
            }
        }
        
        .notification-dropdown {
            position: fixed;
            left: 1rem;
            right: 1rem;
            top: 4rem;
            z-index: 1060;
            width: auto;
            max-width: none;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
            max-height: calc(100vh - 6rem);
            overflow-y: auto;
        }
        
        @media (min-width: 768px) {
            .notification-dropdown {
                position: absolute;
                left: auto;
                right: 0;
                top: 100%;
                width: 24rem;
                max-width: 24rem;
                max-height: 80vh;
            }
        }
        
        /* Overlay for mobile when dropdown is open */
        .dropdown-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: none;
        }
        
        .dropdown-overlay.active {
            display: block;
        }
        
        /* Notification list scroll */
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 4px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 2px;
        }
        
        /* Alert positioning */
        .alert-fixed {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1070;
            max-width: calc(100vw - 2rem);
        }
        
        @media (max-width: 640px) {
            .alert-fixed {
                left: 1rem;
                right: 1rem;
                width: auto;
            }
        }
        
        /* Mobile responsive for stat cards */
        @media (max-width: 640px) {
            .stat-card {
                padding: 0.75rem !important;
            }
            
            .stat-card .rounded {
                padding: 0.5rem !important;
                margin-right: 0.5rem !important;
            }
            
            .stat-card .rounded i {
                font-size: 0.875rem !important;
            }
            
            .stat-card .font-bold {
                font-size: 0.875rem !important;
            }
            
            .stat-card .text-xs {
                font-size: 0.65rem !important;
            }
        }
        
        /* Truncate long text */
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* Min width utility */
        .min-w-0 {
            min-width: 0;
        }
        
        /* Flex shrink utility */
        .flex-shrink-0 {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" :class="{ 'overflow-hidden': mobileMenuOpen }" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation with Gradient Header -->
    <nav class="gradient-header text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Section with Dual Logos -->
                <div class="flex items-center">
                    <!-- Dual Logo Container -->
                    <div class="logo-container mr-3">
                        <div class="logo-inner">
                            <div class="flex items-center gap-2">
                                <!-- MOIC Logo -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="w-6 h-6 bg-[#110484] rounded flex items-center justify-center text-white text-xs font-bold hidden">MOIC</div>
                                    </div>
                                    <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                </div>
                                
                                <!-- Partnership Badge -->
                                <div class="relative">
                                    <div class="rounded-full bg-gradient-to-br from-[#110484] to-[#e7581c]" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-handshake text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="absolute top-100 start-50 translate-middle mt-1" style="transform: translateX(-50%);">
                                        <span class="status-badge bg-white text-[#110484] whitespace-nowrap">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <!-- TKC Logo -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="w-6 h-6 bg-[#e7581c] rounded flex items-center justify-center text-white text-xs font-bold hidden">TKC</div>
                                    </div>
                                    <span class="status-badge moic-accent-bg text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Page Title (Desktop) -->
                    <div class="hidden md:flex items-center">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem; width: 1px;"></div>
                        <div>
                            <h1 class="text-sm font-bold mb-0">Leave Application</h1>
                            <p class="mb-0 text-white text-opacity-75 text-xs">Apply for leave</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="md:hidden ml-2">
                        <h1 class="text-sm font-bold mb-0">Apply Leave</h1>
                        <p class="mb-0 text-white text-opacity-75 text-xs">{{ explode(' ', auth()->user()->name)[0] }}</p>
                    </div>
                    
                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex items-center ml-6 space-x-1">
                        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('leave.index') }}" class="nav-link {{ request()->routeIs('leave.index') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Leave</span>
                        </a>
                        <a href="{{ route('leave.balance') }}" class="nav-link {{ request()->routeIs('leave.balance') ? 'active' : '' }}">
                            <i class="fas fa-wallet"></i>
                            <span>Leave Balance</span>
                        </a>
                        <a href="/calendar" class="nav-link {{ request()->is('calendar') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            <span>Calendar</span>
                        </a>
                    </div>
                </div>

                <!-- Right side navigation -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Notification Dropdown - FIXED FOR MOBILE -->
                        <div x-data="{ 
                            open: false,
                            notifications: [],
                            unreadCount: 0,
                            loading: false,
                            
                            init() {
                                this.loadNotifications();
                                setInterval(() => this.loadNotifications(), 30000);
                                window.addEventListener('new-notification', () => this.loadNotifications());
                                
                                // Close on escape key
                                document.addEventListener('keydown', (e) => {
                                    if (e.key === 'Escape' && this.open) {
                                        this.closeDropdown();
                                    }
                                });
                            },
                            
                            loadNotifications() {
                                this.loading = true;
                                fetch('/notifications/latest')
                                    .then(response => response.json())
                                    .then(data => {
                                        this.notifications = data.notifications;
                                        this.unreadCount = data.unread_count;
                                        this.loading = false;
                                    })
                                    .catch(error => {
                                        console.error('Error loading notifications:', error);
                                        this.loading = false;
                                    });
                            },
                            
                            markAsRead(notificationId) {
                                fetch(`/notifications/${notificationId}/mark-as-read`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(() => {
                                    this.loadNotifications();
                                });
                            },
                            
                            markAllAsRead() {
                                fetch('/notifications/mark-all-as-read', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                }).then(() => {
                                    this.loadNotifications();
                                });
                            },
                            
                            toggleDropdown() {
                                this.open = !this.open;
                                if (this.open) {
                                    // Prevent body scroll on mobile
                                    document.body.style.overflow = 'hidden';
                                } else {
                                    document.body.style.overflow = '';
                                }
                            },
                            
                            closeDropdown() {
                                this.open = false;
                                document.body.style.overflow = '';
                            }
                        }" @keydown.escape.window="closeDropdown()" class="relative">
                            <!-- Notification Bell -->
                            <button @click="toggleDropdown()" class="relative p-2 hover:bg-white/10 rounded-lg transition-colors">
                                <i class="fas fa-bell text-xl"></i>
                                <template x-if="unreadCount > 0">
                                    <span x-show="unreadCount > 0"
                                          x-transition:enter="transition ease-out duration-300"
                                          x-transition:enter-start="opacity-0 transform scale-50"
                                          x-transition:enter-end="opacity-100 transform scale-100"
                                          class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[1.25rem] h-5 notification-pulse"
                                          x-text="unreadCount > 9 ? '9+' : unreadCount">
                                    </span>
                                </template>
                            </button>

                            <!-- Overlay for mobile -->
                            <div x-show="open" @click="closeDropdown()" class="dropdown-overlay" :class="{ 'active': open }" style="display: none;"></div>

                            <!-- Notifications Dropdown - FIXED for mobile -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="notification-dropdown"
                                 @click.away="closeDropdown()"
                                 style="display: none;">
                                
                                <div class="px-4 py-3" style="background: linear-gradient(135deg, #110484, #1a0c9e); border-radius: 0.75rem 0.75rem 0 0;">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-white">
                                            Notifications 
                                            <span x-show="unreadCount > 0" 
                                                  class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white" style="color: #110484;"
                                                  x-text="unreadCount + ' new'">
                                            </span>
                                        </h3>
                                        <button x-show="unreadCount > 0" 
                                                @click="markAllAsRead()"
                                                class="text-xs text-white/80 hover:text-white transition-colors">
                                            Mark all as read
                                        </button>
                                    </div>
                                </div>

                                <div x-show="loading" class="px-4 py-8 text-center">
                                    <div class="spinner mx-auto"></div>
                                    <p class="text-sm text-gray-500 mt-2">Loading notifications...</p>
                                </div>

                                <div x-show="!loading" class="notification-list">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-100 last:border-b-0" 
                                             :class="{ 'bg-blue-50': !notification.read_at }"
                                             @click="markAsRead(notification.id); closeDropdown()">
                                            <p class="text-sm" 
                                               :class="notification.read_at ? 'text-gray-600' : 'text-gray-900 font-medium'"
                                               x-text="notification.data.message || 'Notification'">
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1" 
                                               x-text="notification.created_at ? new Date(notification.created_at).toLocaleDateString() + ' ' + new Date(notification.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'Just now'">
                                            </p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center">
                                            <div class="inline-block p-3 bg-gray-100 rounded-full mb-3">
                                                <i class="far fa-bell-slash text-gray-400 text-xl"></i>
                                            </div>
                                            <p class="text-sm text-gray-500">No notifications yet</p>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="notifications.length > 0" class="px-4 py-2 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                                    <a href="/notifications" @click="closeDropdown()" class="text-xs font-medium flex items-center justify-center no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                                        View all notifications
                                        <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 hover:bg-white/10 rounded-lg transition-colors">
                            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- User Menu -->
                        <div class="relative hidden sm:block" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 p-2 hover:bg-white/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                    <span class="font-medium text-sm" style="color: #110484;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:block text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4 text-white transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-95"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-95"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                        <i class="fas fa-user mr-2 text-gray-500"></i> Profile
                                    </a>
                                    <hr class="my-1 border-gray-200">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                        <i class="fas fa-chart-pie mr-2 text-gray-500"></i> Dashboard
                                    </a>
                                    <a href="{{ route('leave.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                        <i class="fas fa-calendar-alt mr-2 text-gray-500"></i> Leave
                                    </a>
                                    <a href="{{ route('leave.balance') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                        <i class="fas fa-wallet mr-2 text-gray-500"></i> Leave Balance
                                    </a>
                                    <a href="/calendar" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
                                        <i class="fas fa-calendar-check mr-2 text-gray-500"></i> Calendar
                                    </a>
                                    <hr class="my-1 border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-t border-white/20 py-4 px-2"
                 style="display: none;">
                
                @auth
                <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-white/20">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="font-medium text-lg" style="color: #110484;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-white/70">{{ auth()->user()->employee_number ?? '' }}</p>
                    </div>
                </div>
                @endauth

                <div class="space-y-1">
                    <a href="/dashboard" class="flex items-center px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors no-underline">
                        <i class="fas fa-chart-pie mr-3 w-5 text-center"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('leave.index') }}" class="flex items-center px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors no-underline">
                        <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
                        Leave
                    </a>
                    <a href="{{ route('leave.balance') }}" class="flex items-center px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors no-underline">
                        <i class="fas fa-wallet mr-3 w-5 text-center"></i> Leave Balance
                    </a>
                    <a href="/calendar" class="flex items-center px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors no-underline">
                        <i class="fas fa-calendar-check mr-3 w-5 text-center"></i> Calendar
                    </a>
                </div>

                @auth
                <div class="mt-4 pt-3 border-t border-white/20">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>
    
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Display Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative alert-slide" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-700 mr-2"></i>
                <strong class="font-bold">Success!</strong>
                <span class="ml-2">{{ session('success') }}</span>
            </div>
            <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <!-- Display Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert-slide" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-700 mr-2 mt-1"></i>
                <div>
                    <strong class="font-bold">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="absolute top-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold" style="color: #110484;">Apply for Leave</h1>
            <p class="text-gray-600 mt-2">Fill out the form below to submit your leave request</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold" style="color: #110484;">
                    <i class="fas fa-pen-alt mr-2" style="color: #e7581c;"></i> Leave Application Form
                </h3>
            </div>
            
            <form action="{{ route('leave.store') }}" method="POST" class="p-6" enctype="multipart/form-data" id="leaveForm">
                @csrf
                
                <!-- Employee Information - Redesigned with stat cards (2 per row on mobile) -->
                <div class="mb-8">
                    <h4 class="font-bold mb-4 flex items-center" style="color: #110484;">
                        <i class="fas fa-user-circle mr-2 text-xl"></i> Employee Information
                    </h4>
                    
                    <!-- Employee Stats Cards - 2 per row on mobile, 4 on desktop -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Employee Name Card -->
                        <div class="stat-card">
                            <div class="flex items-center">
                                <div class="rounded p-2 mr-3 bg-blue-50 flex-shrink-0">
                                    <i class="fas fa-user moic-navy"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Employee Name</p>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                                Personal details
                            </div>
                        </div>
                        
                        <!-- Employee ID Card -->
                        <div class="stat-card">
                            <div class="flex items-center">
                                <div class="rounded p-2 mr-3 bg-purple-50 flex-shrink-0">
                                    <i class="fas fa-id-card text-purple-600"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Employee ID</p>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ auth()->user()->employee_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                                ID verification
                            </div>
                        </div>
                        
                        <!-- Department Card -->
                        <div class="stat-card">
                            <div class="flex items-center">
                                <div class="rounded p-2 mr-3 bg-green-50 flex-shrink-0">
                                    <i class="fas fa-building text-green-600"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Department</p>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ auth()->user()->department ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                                Work unit
                            </div>
                        </div>
                        
                        <!-- Job Title Card (if available) -->
                        <div class="stat-card">
                            <div class="flex items-center">
                                <div class="rounded p-2 mr-3 bg-amber-50 flex-shrink-0">
                                    <i class="fas fa-briefcase moic-accent"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Job Title</p>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ auth()->user()->job_title ?? 'Employee' }}</p>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                                Position
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Details -->
                <div class="space-y-6">
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Type of Leave
                        </label>
                        <select name="leave_type" id="leave_type" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('leave_type') border-red-500 @enderror">
                            <option value="">Select leave type</option>
                            <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                            <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                            <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                            <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                            <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                            <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('leave_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Start Date
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="start_date" id="start_date" required
                                       value="{{ old('start_date') }}"
                                       class="w-full border border-gray-300 rounded-lg pl-10 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('start_date') border-red-500 @enderror"
                                       min="{{ date('Y-m-d') }}">
                            </div>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> End Date
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="end_date" id="end_date" required
                                       value="{{ old('end_date') }}"
                                       class="w-full border border-gray-300 rounded-lg pl-10 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('end_date') border-red-500 @enderror">
                            </div>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Days Count Display - Redesigned -->
                    <div id="daysCount" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border-l-4 border-green-500 {{ old('start_date') && old('end_date') ? '' : 'hidden' }}">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-2 mr-3">
                                <i class="fas fa-calendar-day text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-800">
                                    Total Leave Days: <span id="totalDays" class="font-bold text-lg">{{ old('start_date') && old('end_date') ? \Carbon\Carbon::parse(old('start_date'))->diffInDays(\Carbon\Carbon::parse(old('end_date'))) + 1 : 0 }}</span> days
                                </p>
                                <p class="text-xs text-green-600 mt-1">Includes weekends and holidays</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Reason for Leave
                        </label>
                        <textarea name="reason" id="reason" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('reason') border-red-500 @enderror"
                                  placeholder="Please provide details about why you need leave...">{{ old('reason') }}</textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">Minimum 10 characters</p>
                            <p id="reasonCounter" class="text-xs text-gray-500">0/500</p>
                        </div>
                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachment Section - Redesigned -->
                    <div id="attachmentSection" class="p-5 bg-gray-50 rounded-lg border-2 {{ in_array(old('leave_type'), ['sick', 'maternity', 'paternity', 'other']) ? '' : 'hidden' }}" style="border-color: #11048420;">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#110484] rounded-full p-2 mr-3">
                                <i class="fas fa-paperclip text-white text-sm"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Supporting Documents</h4>
                            <span id="requiredBadge" class="ml-3 px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full {{ in_array(old('leave_type'), ['sick', 'other']) ? '' : 'hidden' }}">Required</span>
                            <span id="optionalBadge" class="ml-3 px-3 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded-full {{ in_array(old('leave_type'), ['sick', 'other']) ? 'hidden' : '' }}">Optional</span>
                        </div>
                        
                        <p id="attachmentHelpText" class="text-sm text-gray-600 mb-4 bg-white p-3 rounded-lg border">
                            <i class="fas fa-info-circle mr-2 text-[#110484]"></i>
                            @if(old('leave_type') == 'sick')
                                Please upload a medical certificate or doctor's note.
                            @elseif(old('leave_type') == 'maternity' || old('leave_type') == 'paternity')
                                Please upload supporting documents (medical certificate, birth certificate, etc.)
                            @else
                                Upload any relevant supporting documents (optional).
                            @endif
                        </p>

                        <!-- File Upload Area - Redesigned -->
                        <div id="uploadArea" class="upload-area relative p-6 rounded-lg bg-white cursor-pointer text-center">
                            <input type="file" name="attachment" id="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            
                            <div id="uploadPlaceholder" class="{{ old('attachment') ? 'hidden' : '' }}">
                                <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl" style="color: #110484;"></i>
                                </div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold" style="color: #110484;">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-file-pdf mr-1"></i> PDF, DOC, DOCX, JPG, PNG (Max: 10MB)
                                </p>
                            </div>

                            <!-- File Preview - Redesigned -->
                            <div id="filePreview" class="{{ old('attachment') ? '' : 'hidden' }}">
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border-2 border-green-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                        </div>
                                        <div class="text-left">
                                            <p id="fileName" class="text-sm font-medium text-gray-800">{{ old('attachment') ? basename(old('attachment')) : 'document.pdf' }}</p>
                                            <p id="fileSize" class="text-xs text-gray-500 mt-1">245 KB</p>
                                        </div>
                                    </div>
                                    <button type="button" id="removeFile" class="w-8 h-8 bg-red-50 rounded-full flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Progress -->
                            <div id="uploadProgress" class="mt-4 hidden">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Uploading...</span>
                                    <span id="progressPercent">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div id="progressBar" class="file-upload-progress h-2.5 rounded-full" style="background: linear-gradient(90deg, #110484, #e7581c); width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        @error('attachment')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-xs text-gray-500 mt-3 flex items-center">
                            <i class="fas fa-shield-alt mr-1 text-green-500"></i>
                            Your documents are securely uploaded and encrypted
                        </p>
                    </div>

                    <!-- Contact Information - Redesigned -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i> Contact Address During Leave
                            </label>
                            <input type="text" name="contact_address" id="contact_address"
                                   value="{{ old('contact_address') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   placeholder="Where can you be reached?">
                        </div>
                        
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone-alt mr-1 text-gray-400"></i> Contact Phone Number
                            </label>
                            <input type="tel" name="contact_phone" id="contact_phone"
                                   value="{{ old('contact_phone') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   placeholder="Phone number for emergencies">
                        </div>
                    </div>
                </div>

                <!-- Form Actions - Redesigned -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('leave.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 text-center no-underline font-medium flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                            class="text-white px-8 py-3 rounded-lg hover:shadow-lg transition duration-200 font-medium flex items-center justify-center" style="background: linear-gradient(135deg, #110484, #1a0c9e);">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Application
                        <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Leave Policy Information - Redesigned -->
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-100">
            <h4 class="font-bold mb-4 flex items-center text-lg" style="color: #110484;">
                <div class="w-8 h-8 rounded-full bg-[#110484] flex items-center justify-center mr-2">
                    <i class="fas fa-info-circle text-white text-sm"></i>
                </div>
                Important Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <ul class="text-sm text-gray-600 space-y-3">
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>Leave applications require supervisor approval</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>Submit your application at least 3 working days in advance for annual leave</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>Medical certificates are required for sick leave exceeding 3 days</span>
                    </li>
                </ul>
                <ul class="text-sm text-gray-600 space-y-3">
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>You can cancel pending applications anytime</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>You will receive notifications about your application status</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center mt-0.5 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        </div>
                        <span>Leave balance will be updated upon approval</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-xl p-8 text-center max-w-sm mx-4">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-r from-[#110484] to-[#e7581c] flex items-center justify-center">
                    <i class="fas fa-paper-plane text-white text-2xl"></i>
                </div>
                <div class="spinner mx-auto mb-4"></div>
                <p class="text-gray-700 font-medium">Submitting your application...</p>
                <p class="text-xs text-gray-500 mt-2">Please don't close this window</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const daysCount = document.getElementById('daysCount');
            const totalDays = document.getElementById('totalDays');
            const leaveType = document.getElementById('leave_type');
            const attachmentSection = document.getElementById('attachmentSection');
            const requiredBadge = document.getElementById('requiredBadge');
            const optionalBadge = document.getElementById('optionalBadge');
            const attachmentHelpText = document.getElementById('attachmentHelpText');
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('attachment');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFileBtn = document.getElementById('removeFile');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const leaveForm = document.getElementById('leaveForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const reason = document.getElementById('reason');
            const reasonCounter = document.getElementById('reasonCounter');
            
            // Touch device detection
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            }
            
            // Character counter
            if (reason) {
                reason.addEventListener('input', function() {
                    reasonCounter.textContent = this.value.length + '/500';
                });
            }
            
            // Date calculation
            function calculateDays() {
                if (startDate.value && endDate.value) {
                    const start = new Date(startDate.value);
                    const end = new Date(endDate.value);
                    
                    if (end >= start) {
                        const timeDiff = end.getTime() - start.getTime();
                        const dayDiff = Math.floor(timeDiff / (1000 * 3600 * 24)) + 1;
                        
                        if (dayDiff > 0) {
                            totalDays.textContent = dayDiff;
                            daysCount.classList.remove('hidden');
                        } else {
                            daysCount.classList.add('hidden');
                        }
                    } else {
                        daysCount.classList.add('hidden');
                    }
                }
            }
            
            // Leave type change handler
            function handleLeaveTypeChange() {
                const type = leaveType.value;
                
                if (type === 'sick' || type === 'maternity' || type === 'paternity' || type === 'other') {
                    attachmentSection.classList.remove('hidden');
                    
                    if (type === 'sick') {
                        requiredBadge.classList.remove('hidden');
                        optionalBadge.classList.add('hidden');
                        attachmentHelpText.innerHTML = '<i class="fas fa-info-circle mr-2 text-[#110484]"></i>Please upload a medical certificate or doctor\'s note.';
                        fileInput.setAttribute('required', 'required');
                    } else if (type === 'other') {
                        requiredBadge.classList.remove('hidden');
                        optionalBadge.classList.add('hidden');
                        attachmentHelpText.innerHTML = '<i class="fas fa-info-circle mr-2 text-[#110484]"></i>Please upload relevant supporting documents for your leave request.';
                        fileInput.setAttribute('required', 'required');
                    } else {
                        requiredBadge.classList.add('hidden');
                        optionalBadge.classList.remove('hidden');
                        attachmentHelpText.innerHTML = '<i class="fas fa-info-circle mr-2 text-[#110484]"></i>Please upload supporting documents (medical certificate, birth certificate, etc.) - Optional but recommended.';
                        fileInput.removeAttribute('required');
                    }
                } else {
                    attachmentSection.classList.add('hidden');
                    fileInput.removeAttribute('required');
                }
            }
            
            // File upload handling
            function handleFileSelect(file) {
                if (file) {
                    // Validate file size (10MB limit)
                    if (file.size > 10 * 1024 * 1024) {
                        showNotification('File size exceeds 10MB limit', 'error');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    
                    if (!allowedExtensions.includes(fileExtension)) {
                        showNotification('Invalid file type. Please upload PDF, DOC, DOCX, JPG, or PNG files only.', 'error');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Show file preview
                    fileName.textContent = file.name;
                    fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                    
                    uploadPlaceholder.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                    uploadArea.classList.add('has-file');
                    
                    // Simulate upload progress
                    simulateUpload();
                }
            }
            
            function simulateUpload() {
                uploadProgress.classList.remove('hidden');
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 10;
                    progressBar.style.width = progress + '%';
                    progressPercent.textContent = progress + '%';
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            uploadProgress.classList.add('hidden');
                        }, 500);
                    }
                }, 100);
            }
            
            // Show notification
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 alert-slide ${
                    type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 'bg-red-100 text-red-700 border border-red-400'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
            
            // Form submission handler
            leaveForm.addEventListener('submit', function(e) {
                const reasonValue = document.getElementById('reason').value;
                
                if (reasonValue.length < 10) {
                    e.preventDefault();
                    showNotification('Reason must be at least 10 characters long', 'error');
                    return;
                }
                
                // Show loading overlay
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                loadingOverlay.classList.remove('hidden');
            });
            
            // Event Listeners
            startDate.addEventListener('change', function() {
                if (this.value) {
                    endDate.min = this.value;
                    calculateDays();
                }
            });
            
            endDate.addEventListener('change', calculateDays);
            leaveType.addEventListener('change', handleLeaveTypeChange);
            
            // File input change
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    handleFileSelect(this.files[0]);
                }
            });
            
            // Drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelect(e.dataTransfer.files[0]);
                }
            });
            
            // Remove file
            removeFileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.value = '';
                uploadPlaceholder.classList.remove('hidden');
                filePreview.classList.add('hidden');
                uploadArea.classList.remove('has-file');
            });
            
            // Run on page load
            if (startDate.value && endDate.value) {
                calculateDays();
            }
            
            if (leaveType.value) {
                handleLeaveTypeChange();
            }
            
            if (reason) {
                reasonCounter.textContent = reason.value.length + '/500';
            }
            
            // Prevent double submission on back button
            if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Submit Application';
                loadingOverlay.classList.add('hidden');
            }
        });

        // Debug function to clear cache (temporary)
        function clearValidationCache() {
            fetch('/clear-validation-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }
    </script>
</body>
</html>