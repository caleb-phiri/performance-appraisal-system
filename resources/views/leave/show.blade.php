<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Leave Application #{{ $leave->id }} - MOIC Performance Appraisal System</title>
    
    <!-- Tailwind CSS via CDN (Production-friendly version) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* MOIC Brand Colors */
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-navy-light: #3328a5;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-gradient: linear-gradient(135deg, #110484, #1a0c9e);
            --moic-gradient-accent: linear-gradient(135deg, #e7581c, #ff7c45);
        }
        
        /* Password setup modal z-index fix */
        .z-modal {
            z-index: 1000;
        }
        
        /* New Logo container with gradient border */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 8px;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 6px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 12px;
        }
        
        /* Animated gradient for header */
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
        
        /* Mobile menu animations */
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
        
        /* Status Pill Styles */
        .status-pill {
            display: inline-block;
            padding: 0.375rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fcd34d;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        .status-approved {
            background-color: #7cecb2;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        
        .status-rejected {
            background-color: #fca5a5;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .status-cancelled {
            background-color: #fdba74;
            color: #9a3412;
            border: 1px solid #fdba74;
        }
        
        /* Info card hover effect */
        .info-card-hover {
            transition: all 0.2s ease;
        }
        
        .info-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(17, 4, 132, 0.1);
            border-color: var(--moic-navy);
        }
        
        /* Reason box styling */
        .reason-box {
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 1rem;
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
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
        }
        
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
            /* Stat Card Styles - Matching your example */
.stat-card {
    transition: all 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Background color utilities if not already present */
.bg-blue-50 { background-color: #eff6ff; }
.bg-purple-50 { background-color: #faf5ff; }
.bg-green-50 { background-color: #f0fdf4; }
.bg-amber-50 { background-color: #fffbeb; }

/* Text color utilities */
.text-purple-600 { color: #7c3aed; }
.text-green-600 { color: #059669; }
.moic-accent { color: #e7581c; }
.moic-navy { color: #110484; }

/* Responsive text */
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
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" :class="{ 'overflow-hidden': mobileMenuOpen }" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation with Gradient Header - Full MOIC Design -->
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
                                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23110484%22/><text x=%2250%22 y=%2265%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2236%22 fill=%22white%22>M</text></svg>';">
                                    </div>
                                    <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                </div>
                                
                                <!-- Partnership Badge -->
                                <div class="relative">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#110484] to-[#e7581c] flex items-center justify-center">
                                        <i class="fas fa-handshake text-white text-xs"></i>
                                    </div>
                                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                        <span class="status-badge bg-white text-[#110484] text-[0.6rem]">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <!-- TKC Logo -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="h-6 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23e7581c%22/><text x=%2250%22 y=%2265%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2236%22 fill=%22white%22>T</text></svg>';">
                                    </div>
                                    <span class="status-badge moic-accent-bg text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Page Title (Desktop) -->
                    <div class="hidden md:flex items-center">
                        <div class="h-8 w-px bg-white/30 mx-4"></div>
                        <div>
                            <h1 class="text-sm font-bold">Leave Application</h1>
                            <p class="text-xs text-white/75">#{{ $leave->id }}</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="md:hidden ml-2">
                        <h1 class="text-sm font-bold">App #{{ $leave->id }}</h1>
                        <p class="text-xs text-white/75">{{ explode(' ', Auth::user()->name ?? 'User')[0] }}</p>
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

                        <!-- User Menu (Desktop) -->
                        <div class="relative hidden md:block" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 p-2 hover:bg-white/10 rounded-lg transition-colors">
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                    <span class="font-medium text-sm" style="color: #110484;">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <span class="hidden lg:block text-sm font-medium text-white">{{ Auth::user()->name ?? 'User' }}</span>
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
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <!-- User Info -->
                                    <div class="px-4 py-3 border-b">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'User' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->job_title ?? 'Employee' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->employee_number ?? '' }}</p>
                                    </div>
                                    
                                    <!-- Menu Items -->
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
                        <span class="font-medium text-lg" style="color: #110484;">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-xs text-white/70">{{ Auth::user()->employee_number ?? '' }}</p>
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
                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-3 text-base text-white hover:bg-white/10 rounded-lg transition-colors no-underline">
                        <i class="fas fa-user-circle mr-3 w-5 text-center"></i> Profile
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div class="mb-4 md:mb-0">
                <div class="flex items-center flex-wrap gap-3">
                    <h1 class="text-2xl md:text-3xl font-bold" style="color: #110484;">Leave Application</h1>
                    <span class="status-pill status-{{ strtolower($leave->status) }}">
                        {{ strtoupper($leave->status) }}
                    </span>
                </div>
                <div class="flex items-center mt-2 flex-wrap gap-3">
                    <span class="text-gray-500 text-sm">
                        <i class="fas fa-hashtag mr-1"></i> Application ID: {{ $leave->id }}
                    </span>
                    <span class="text-gray-500 text-sm">
                        <i class="fas fa-clock mr-1"></i> Applied {{ $leave->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <a href="{{ route('leave.index') }}" class="inline-flex items-center bg-white text-[#110484] px-4 py-2 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition duration-200 font-medium no-underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Leave List
            </a>
        </div>

        <!-- Main Application Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
            <!-- Header Summary -->
            <div class="gradient-header px-6 py-5 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold flex items-center">
                            <i class="fas fa-calendar-alt mr-3"></i> 
                            {{ $leave->leave_type_name }}
                        </h2>
                        <div class="flex items-center mt-2 text-blue-100 flex-wrap gap-2">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ $leave->start_date->format('l, M d, Y') }} — {{ $leave->end_date->format('l, M d, Y') }}
                            <span class="mx-3 hidden sm:inline">|</span>
                            <span class="sm:ml-0">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $leave->total_days }} {{ Str::plural('day', $leave->total_days) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 md:mt-0">
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                            <i class="fas fa-calendar-check mr-1"></i> Applied: {{ $leave->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

           <!-- Employee Information -->
<div class="p-6 border-b border-gray-200">
    <h3 class="text-lg font-bold mb-4 flex items-center" style="color: #110484;">
        <i class="fas fa-user mr-2"></i> Employee Information
    </h3>
    
    <!-- Employee Stats Cards - Mobile optimized with 2 per row -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Employee Name Card -->
        <div class="stat-card bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md transition-all duration-200">
            <div class="flex items-center">
                <div class="rounded p-2 mr-3 bg-blue-50 flex-shrink-0">
                    <i class="fas fa-user moic-navy"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Employee Name</p>
                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ $leave->employee_name }}</p>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                Personal details
            </div>
        </div>
        
        <!-- Employee Number Card -->
        <div class="stat-card bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md transition-all duration-200">
            <div class="flex items-center">
                <div class="rounded p-2 mr-3 bg-purple-50 flex-shrink-0">
                    <i class="fas fa-id-card text-purple-600"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Employee Number</p>
                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ $leave->employee_number }}</p>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                ID verification
            </div>
        </div>
        
        <!-- Department Card -->
        <div class="stat-card bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md transition-all duration-200">
            <div class="flex items-center">
                <div class="rounded p-2 mr-3 bg-green-50 flex-shrink-0">
                    <i class="fas fa-building text-green-600"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Department</p>
                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ $leave->department ?? 'Not specified' }}</p>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                Work unit
            </div>
        </div>
        
        <!-- Job Title Card -->
        <div class="stat-card bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md transition-all duration-200">
            <div class="flex items-center">
                <div class="rounded p-2 mr-3 bg-amber-50 flex-shrink-0">
                    <i class="fas fa-briefcase moic-accent"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-muted text-xs text-gray-500 mb-0.5 truncate">Job Title</p>
                    <p class="font-bold text-gray-800 text-sm sm:text-base truncate">{{ $leave->job_title ?? 'Not specified' }}</p>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-100 text-xs text-gray-400">
                Position
            </div>
        </div>
    </div>
</div>

            <!-- Leave Details -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold mb-4 flex items-center" style="color: #110484;">
                    <i class="fas fa-info-circle mr-2"></i> Leave Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                        <div class="flex items-start">
                            <div class="p-2 bg-white rounded-lg mr-3">
                                <i class="fas fa-tag" style="color: #110484;"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Leave Type</p>
                                <p class="font-bold text-gray-800">{{ $leave->leave_type_name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                        <div class="flex items-start">
                            <div class="p-2 bg-white rounded-lg mr-3">
                                <i class="fas fa-calculator" style="color: #e7581c;"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Total Days</p>
                                <p class="font-bold text-gray-800">{{ $leave->total_days }} Days</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                        <div class="flex items-start">
                            <div class="p-2 bg-white rounded-lg mr-3">
                                <i class="fas fa-flag-checkered text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Current Status</p>
                                <p class="font-bold text-gray-800 capitalize">{{ $leave->status }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Last updated: {{ $leave->updated_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reason for Leave -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold mb-4 flex items-center" style="color: #110484;">
                    <i class="fas fa-comment-dots mr-2"></i> Reason for Leave
                </h3>
                
                <div class="bg-gradient-to-r from-gray-50 to-indigo-50/30 rounded-xl p-5 border border-gray-200">
                    <p class="text-gray-700 leading-relaxed">
                        {{ $leave->reason }}
                    </p>
                </div>
            </div>

            <!-- Contact During Leave -->
            @if($leave->contact_address || $leave->contact_phone)
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold mb-4 flex items-center" style="color: #110484;">
                    <i class="fas fa-phone-alt mr-2"></i> Contact During Leave
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($leave->contact_address)
                    <div class="flex items-start bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="p-2 bg-white rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-map-marker-alt" style="color: #110484;"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Contact Address</p>
                            <p class="font-medium text-gray-800">{{ $leave->contact_address }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($leave->contact_phone)
                    <div class="flex items-start bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="p-2 bg-white rounded-lg mr-3 shadow-sm">
                            <i class="fas fa-mobile-alt" style="color: #e7581c;"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Contact Phone</p>
                            <p class="font-medium text-gray-800">{{ $leave->contact_phone }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Official Remarks / Action Taken -->
            @if(in_array($leave->status, ['approved', 'rejected', 'cancelled']))
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold mb-4 flex items-center" style="color: #110484;">
                    <i class="fas fa-clipboard-check mr-2"></i> Official Remarks
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase">Action By</p>
                        <p class="font-semibold text-gray-800 mt-1 flex items-center">
                            <i class="fas fa-user-tie mr-2" style="color: #110484;"></i>
                            {{ $leave->approved_by ?? 'System Administrator' }}
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase">Action Date</p>
                        <p class="font-semibold text-gray-800 mt-1 flex items-center">
                            <i class="fas fa-calendar mr-2" style="color: #e7581c;"></i>
                            {{ optional($leave->approved_at)->format('M d, Y h:i A') ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase">Final Status</p>
                        <div class="mt-1">
                            <span class="status-pill status-{{ strtolower($leave->status) }}">
                                {{ strtoupper($leave->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($leave->remarks)
                <div class="reason-box 
                    @if($leave->status == 'approved') bg-green-50 border border-green-200
                    @elseif($leave->status == 'rejected') bg-red-50 border border-red-200
                    @else bg-orange-50 border border-orange-200
                    @endif">
                    <div class="flex items-start">
                        <div class="mr-3">
                            @if($leave->status == 'approved')
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            @elseif($leave->status == 'rejected')
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            @else
                                <i class="fas fa-ban text-orange-600 text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 mb-1">
                                Reason for {{ ucfirst($leave->status) }}:
                            </p>
                            <p class="text-gray-700">{{ $leave->remarks }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row justify-end gap-3 no-print">
                <a href="{{ route('leave.index') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200 no-underline">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
                
                @if($leave->isPending())
                <a href="{{ route('leave.edit', $leave->id) }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 transition duration-200 shadow-md no-underline">
                    <i class="fas fa-edit mr-2"></i> Edit Application
                </a>
                
                <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" class="inline-block" id="cancelForm">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            onclick="confirmCancel()"
                            class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 transition duration-200 shadow-md">
                        <i class="fas fa-ban mr-2"></i> Cancel Request
                    </button>
                </form>
                @endif
                
                @if($leave->status == 'approved')
                <button onclick="window.print()" 
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#110484] to-[#1a0c9e] hover:shadow-lg transition duration-200 no-underline">
                    <i class="fas fa-print mr-2"></i> Print Form
                </button>
                @endif
            </div>
        </div>
        
        <!-- Additional Information Card -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 no-print">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="p-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg mr-4">
                        <i class="fas fa-info-circle text-xl" style="color: #110484;"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">Leave Application Process</h4>
                        <p class="text-sm text-gray-600">Track your leave application status and receive updates via email</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-check-circle mr-1"></i> Pending Review
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Approved
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i> Rejected
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-8 pt-6 border-t border-gray-200 no-print">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- MOIC Logo in footer -->
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">MOIC Leave Management System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Version 1.0.0 powered by SmartWave Solutions</p>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('profile.show') }}" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-user-circle mr-1"></i> Profile
                    </a>
                    <a href="{{ route('leave.index') }}" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-calendar mr-1"></i> My Leaves
                    </a>
                    <a href="{{ route('leave.balance') }}" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-wallet mr-1"></i> Balance
                    </a>
                    <a href="/calendar" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-calendar-check mr-1"></i> Calendar
                    </a>
                    <a href="#" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-question-circle mr-1"></i> Help
                    </a>
                    <a href="#" class="text-sm font-medium no-underline" style="color: #110484;" onmouseover="this.style.color='#e7581c'" onmouseout="this.style.color='#110484'">
                        <i class="fas fa-book mr-1"></i> Leave Policy
                    </a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div id="successMessage" class="alert-fixed bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center alert-slide">
        <i class="fas fa-check-circle mr-3"></i> 
        <div>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
    <div id="errorMessage" class="alert-fixed bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center alert-slide">
        <i class="fas fa-exclamation-triangle mr-3"></i> 
        <div>
            <p class="font-medium">Please fix the following errors:</p>
            <ul class="text-xs opacity-90 mt-1">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide messages
            setTimeout(() => {
                const successMsg = document.getElementById('successMessage');
                if (successMsg) successMsg.style.display = 'none';
                
                const errorMsg = document.getElementById('errorMessage');
                if (errorMsg) errorMsg.style.display = 'none';
            }, 5000);
            
            // Touch device detection
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            }
        });

        // Confirm cancel function
        function confirmCancel() {
            if (confirm('Are you sure you want to cancel this leave request? This action cannot be undone.')) {
                document.getElementById('cancelForm').submit();
            }
        }
    </script>
</body>
</html>