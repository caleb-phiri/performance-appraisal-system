<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Leave Balance - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* MOIC Brand Colors - Consistent with dashboard */
        :root {
            --moic-navy: #110484;
            --moic-navy-light: #3328a5;
            --moic-accent: #e7581c;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-blue-light: #2d1fd1;
            --moic-gradient: linear-gradient(135deg, var(--moic-navy), var(--moic-blue));
            --moic-gradient-accent: linear-gradient(135deg, var(--moic-accent), #ff7c45);
            --moic-gradient-mixed: linear-gradient(135deg, var(--moic-navy), var(--moic-accent));
            
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
        }
        
        /* Base styles */
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.6;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: #f0f3f8;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(17, 4, 132, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(231, 88, 28, 0.03) 0%, transparent 50%),
                repeating-linear-gradient(45deg, rgba(0,0,0,0.01) 0px, rgba(0,0,0,0.01) 1px, transparent 1px, transparent 10px);
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            position: relative;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
        }
        
        body.mobile-menu-open {
            overflow: hidden;
        }
        
        /* Ensure content is above backgrounds */
        main, header, footer, .alert-fixed {
            position: relative;
            z-index: 1;
        }
        
        /* Custom Color Classes */
        .moic-navy { color: var(--moic-navy) !important; }
        .moic-navy-bg { background-color: var(--moic-navy) !important; }
        .moic-accent { color: var(--moic-accent) !important; }
        .moic-accent-bg { background-color: var(--moic-accent) !important; }
        
        /* MOIC Buttons */
        .btn-moic {
            background: var(--moic-gradient);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }
        
        .btn-moic:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-moic:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }
        
        .btn-accent {
            background: var(--moic-gradient-accent);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--moic-accent-light), #ff8d5c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 1px solid var(--moic-navy);
            color: var(--moic-navy) !important;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* Animated Gradient Header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
            box-shadow: 0 2px 10px rgba(17, 4, 132, 0.15);
            position: relative;
            z-index: 1020;
        }
        
        /* Card Styling with depth */
        .card-moic {
            border: none;
            box-shadow: 
                0 5px 10px rgba(0, 0, 0, 0.05),
                0 15px 25px rgba(0, 0, 0, 0.02),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            background-color: white;
        }
        
        .card-moic:hover {
            box-shadow: 
                0 10px 20px rgba(17, 4, 132, 0.08),
                0 20px 30px rgba(0, 0, 0, 0.05),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
        }
        
        /* Balance Card - Custom for this page */
        .balance-card {
            border: none;
            box-shadow: 
                0 5px 10px rgba(0, 0, 0, 0.05),
                0 15px 25px rgba(0, 0, 0, 0.02),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            background-color: white;
            padding: 1.5rem;
            border-left: 4px solid var(--moic-navy);
            height: 100%;
        }
        
        .balance-card:hover {
            box-shadow: 
                0 10px 20px rgba(17, 4, 132, 0.08),
                0 20px 30px rgba(0, 0, 0, 0.05),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
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
        
        /* Desktop Navigation Links */
        .nav-link-moic {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .nav-link-moic:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link-moic.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            font-weight: 600;
        }
        
        .nav-link-moic i {
            margin-right: 0.375rem;
            font-size: 0.875rem;
        }
        
        /* Footer Navigation Styles */
        .nav-link-footer {
            color: var(--moic-navy);
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-link-footer:hover {
            background: linear-gradient(135deg, rgba(17, 4, 132, 0.05), rgba(231, 88, 28, 0.05));
            transform: translateY(-1px);
        }
        
        .nav-link-footer.active {
            background: linear-gradient(135deg, rgba(17, 4, 132, 0.1), rgba(231, 88, 28, 0.1));
            color: var(--moic-navy);
            font-weight: 600;
        }
        
        /* Footer top border gradient */
        footer.border-top {
            border-image: linear-gradient(90deg, var(--moic-navy), var(--moic-accent), var(--moic-navy)) 1;
            border-top-width: 2px !important;
        }
        
        /* Footer responsive adjustments */
        @media (max-width: 768px) {
            .nav-link-footer {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
            
            footer .btn {
                padding: 0.25rem 0.75rem !important;
                font-size: 0.7rem !important;
            }
        }
        
        /* Progress Bar */
        .progress-bar-custom {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--moic-navy), var(--moic-blue-light));
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        /* Avatar Styles */
        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .avatar-large {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        
        /* Mobile Menu */
        .mobile-menu {
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 1050;
            min-width: 16rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .mobile-menu .dropdown-item {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
        
        .mobile-menu .dropdown-item i {
            width: 1.25rem;
            text-align: center;
        }
        
        /* Notification Dropdown - FIXED POSITIONING FOR MOBILE */
        .notification-wrapper {
            position: static !important;
        }
        
        @media (min-width: 769px) {
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
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            max-height: calc(100vh - 6rem);
            overflow-y: auto;
        }
        
        @media (min-width: 769px) {
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
        
        /* Alert Styling */
        .alert-fixed {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1060;
            min-width: 20rem;
            max-width: calc(100vw - 2rem);
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 576px) {
            .alert-fixed {
                left: 1rem;
                right: 1rem;
                min-width: auto;
            }
        }
        
        /* Animation for alerts */
        @keyframes slideIn {
            from {
                transform: translateY(20px);
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
        
        /* Notification Badge */
        .notification-badge {
            transition: transform 0.2s ease;
        }
        
        .notification-badge:hover {
            transform: scale(1.1);
        }
        
        /* Rotate animation for dropdown arrows */
        .rotate-180 {
            transform: rotate(180deg);
        }
        
        /* Background utilities */
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-indigo-50 { background-color: #eef2ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
        .bg-amber-50 { background-color: #fffbeb !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-pink-50 { background-color: #fdf2f8 !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-cyan-50 { background-color: #ecfeff !important; }
        .bg-teal-50 { background-color: #f0fdfa !important; }
        
        /* Text utilities */
        .text-green-800 { color: #166534 !important; }
        .text-purple-800 { color: #6b21a8 !important; }
        .text-blue-800 { color: #1e40af !important; }
        .text-yellow-800 { color: #92400e !important; }
        .text-red-800 { color: #991b1b !important; }
        .text-teal-800 { color: #115e59 !important; }
        .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; }
        .text-purple-600 { color: #7c3aed !important; }
        
        /* Responsive container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Responsive breakpoints */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .balance-card {
                padding: 1.25rem;
            }
            
            .desktop-only {
                display: none !important;
            }
            
            .mobile-only {
                display: block !important;
            }
            
            .btn-moic, .btn-accent, .btn-outline-moic {
                padding: 0.5rem 1rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
            
            .desktop-only {
                display: block !important;
            }
        }
        
        @media (max-width: 576px) {
            .logo-inner {
                gap: 0.5rem;
                padding: 0.25rem;
            }
            
            .user-badge {
                font-size: 0.7rem;
                padding: 0.125rem 0.375rem;
            }
            
            .mobile-menu {
                min-width: 14rem;
            }
        }
        
        /* Touch device optimizations */
        .touch-device button,
        .touch-device a {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
            
            .balance-card:hover,
            .card-moic:hover {
                transform: none;
            }
            
            html {
                scroll-behavior: auto;
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
        
        /* Grid system replacement */
        .grid {
            display: grid;
        }
        
        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        
        /* Border colors */
        .border-blue-200 {
            border-color: #bfdbfe !important;
        }
        
        .border-purple-200 {
            border-color: #e9d5ff !important;
        }
        
        .border-green-200 {
            border-color: #a7f3d0 !important;
        }
        
        .border-pink-200 {
            border-color: #fbcfe8 !important;
        }
        
        .border-indigo-200 {
            border-color: #c7d2fe !important;
        }
        
        .border-teal-200 {
            border-color: #99f6e4 !important;
        }
    </style>
</head>
<body>
    <!-- Header with Animated Gradient (Matching Dashboard) -->
<div class="gradient-header text-white">
    <div class="container-custom px-3 py-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <!-- Logo Section (Left side) -->
            <div class="d-flex align-items-center">
                <!-- Dual Logo Container -->
                <div class="logo-container me-3">
                    <div class="logo-inner">
                        <div class="d-flex align-items-center gap-2">
                            <!-- MOIC Logo -->
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-white rounded p-1 mb-1">
                                    <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                                </div>
                                <span class="status-badge moic-navy-bg text-white">MOIC</span>
                            </div>
                            
                            <!-- Partnership Badge -->
                            <div class="position-relative">
                                <div class="rounded-circle bg-gradient-to-br from-[#110484] to-[#e7581c]" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-handshake text-white" style="font-size: 0.75rem;"></i>
                                </div>
                                <div class="position-absolute top-100 start-50 translate-middle mt-1">
                                    <span class="status-badge bg-white moic-navy">PARTNERS</span>
                                </div>
                            </div>
                            
                            <!-- TKC Logo -->
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-white rounded p-1 mb-1">
                                    <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo" onerror="this.style.display='none'">
                                </div>
                                <span class="status-badge moic-accent-bg text-white">TKC</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Title (only visible on mobile) -->
                <div class="mobile-only ms-2">
                    <h1 class="h6 mb-0 fw-bold">Leave Balance</h1>
                    <p class="mb-0 text-white-50 small">Welcome, {{ explode(' ', auth()->user()->name ?? 'User')[0] }}</p>
                </div>
            </div>

            <!-- Desktop Navigation Links - MOVED OUTSIDE Logo Section -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="{{ route('dashboard') }}" class="nav-link-moic {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('leave.index') }}" class="nav-link-moic {{ request()->routeIs('leave.*') && !request()->routeIs('leave.balance') && !request()->routeIs('calendar.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Leave
                </a>
                <a href="{{ route('leave.balance') }}" class="nav-link-moic {{ request()->routeIs('leave.balance') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i> Balance
                </a>
                <a href="{{ route('calendar.index') }}" class="nav-link-moic {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i> Calendar
                </a>
            </div>

            <!-- User Section (Right side) -->
            <div class="d-flex align-items-center gap-2">
                @auth
                    <!-- Notification Dropdown with Alpine.js - FIXED FOR MOBILE -->
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
                                    this.open = false;
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
                    }" @keydown.escape.window="closeDropdown()" class="position-relative">
                        <!-- Notification Bell -->
                        <button @click="toggleDropdown()" class="btn btn-outline-light btn-sm position-relative notification-badge">
                            <i class="fas fa-bell"></i>
                            
                            <!-- Notification Badge -->
                            <template x-if="unreadCount > 0">
                                <span x-show="unreadCount > 0"
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform scale-50"
                                      x-transition:enter-end="opacity-100 transform scale-100"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                      style="font-size: 0.6rem; padding: 0.25rem 0.4rem;"
                                      x-text="unreadCount > 9 ? '9+' : unreadCount">
                                </span>
                            </template>
                        </button>

                        <!-- Overlay for mobile -->
                        <div x-show="open" @click="closeDropdown()" class="dropdown-overlay" :class="{ 'active': open }" style="display: none;"></div>

                        <!-- Notifications Dropdown Panel - FIXED for mobile -->
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
                            
                            <!-- Header -->
                            <div class="px-3 py-2 bg-gradient text-white" style="background: var(--moic-gradient); border-radius: 0.5rem 0.5rem 0 0;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="h6 mb-0 fw-bold">
                                        Notifications 
                                        <span x-show="unreadCount > 0" 
                                              class="badge bg-white text-moic-navy ms-2"
                                              x-text="unreadCount + ' new'">
                                        </span>
                                    </h3>
                                    <button x-show="unreadCount > 0" 
                                            @click="markAllAsRead()"
                                            class="btn btn-link btn-sm text-white p-0 text-decoration-none">
                                        Mark all as read
                                    </button>
                                </div>
                            </div>

                            <!-- Loading State -->
                            <div x-show="loading" class="px-3 py-4 text-center">
                                <div class="spinner-border spinner-border-sm text-moic-navy mb-2" role="status"></div>
                                <p class="small text-muted mb-0">Loading notifications...</p>
                            </div>

                            <!-- Notifications List -->
                            <div x-show="!loading" class="notification-list">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a href="#" @click.prevent="markAsRead(notification.id); closeDropdown()" 
                                       class="list-group-item list-group-item-action px-3 py-2 d-block text-decoration-none"
                                       :class="{ 'bg-blue-50': !notification.read_at }">
                                        <p class="small mb-1" 
                                           :class="notification.read_at ? 'text-muted' : 'fw-semibold'"
                                           x-text="notification.data.message || 'Notification'">
                                        </p>
                                        <p class="small text-muted mb-0" 
                                           x-text="notification.created_at ? new Date(notification.created_at).toLocaleDateString() + ' ' + new Date(notification.created_at).toLocaleTimeString() : 'Just now'">
                                        </p>
                                    </a>
                                </template>
                                
                                <!-- Empty State -->
                                <template x-if="notifications.length === 0">
                                    <div class="px-3 py-4 text-center">
                                        <div class="d-inline-block p-2 bg-gray-50 rounded-circle mb-2">
                                            <i class="far fa-bell-slash text-muted"></i>
                                        </div>
                                        <p class="small text-muted mb-0">No notifications yet</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer -->
                            <div x-show="notifications.length > 0" class="px-3 py-2 bg-gray-50 border-top small text-center">
                                <a href="/notifications" @click="closeDropdown()" class="text-decoration-none moic-accent">
                                    View all notifications <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile (Desktop) -->
                    <div class="desktop-only d-flex flex-column align-items-end">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-success rounded-circle me-1" style="width: 0.5rem; height: 0.5rem;"></div>
                            <span class="fw-medium">{{ auth()->user()->name ?? 'User' }}</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.75rem;">{{ auth()->user()->job_title ?? 'Employee' }}</span>
                    </div>

                    <!-- User Menu Dropdown - Desktop -->
                    <div class="dropdown desktop-only">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 moic-navy"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2 moic-navy"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.index') }}"><i class="fas fa-calendar-alt me-2 moic-accent"></i> Leave</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.balance') }}"><i class="fas fa-wallet me-2 moic-navy"></i> Balance</a></li>
                            <li><a class="dropdown-item" href="{{ route('calendar.index') }}"><i class="fas fa-calendar me-2 moic-accent"></i> Calendar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="dropdown mobile-only">
                        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mobile-menu">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-gradient me-3">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->name ?? 'User' }}</div>
                                        <div class="small text-muted">{{ auth()->user()->employee_number ?? '' }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2 moic-navy"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.index') }}"><i class="fas fa-calendar-alt me-2 moic-accent"></i> My Leaves</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.balance') }}"><i class="fas fa-wallet me-2 moic-navy"></i> Leave Balance</a></li>
                            <li><a class="dropdown-item" href="{{ route('calendar.index') }}"><i class="fas fa-calendar me-2 moic-accent"></i> Calendar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 moic-navy"></i> Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-custom px-3">
            <!-- Header with Year Selector -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 mb-4">
                <div>
                    <h2 class="h4 fw-bold moic-navy mb-1">Leave Balance</h2>
                    <p class="text-muted small mb-0">Track your leave days for {{ $year ?? date('Y') }}</p>
                </div>
                
                <div class="d-flex gap-2">
                    <!-- Year Selector -->
                    <form method="GET" action="{{ route('leave.balance') }}" class="d-flex">
                        <select name="year" onchange="this.form.submit()" 
                                class="form-select form-select-sm" style="width: auto; min-width: 100px;">
                            @foreach($availableYears ?? [date('Y'), date('Y')-1, date('Y')+1] as $availableYear)
                                <option value="{{ $availableYear }}" {{ ($availableYear == ($year ?? date('Y'))) ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    
                    <!-- New Leave Button -->
                    <a href="{{ route('leave.create') }}" class="btn btn-moic btn-sm">
                        <i class="fas fa-plus me-2"></i> New Leave
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <!-- Total Entitled -->
                <div class="col-md-4">
                    <div class="balance-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Entitled</p>
                                <p class="h2 fw-bold moic-navy mb-0">{{ $totalEntitled ?? 0 }}</p>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-circle">
                                <i class="fas fa-calendar-alt moic-navy"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">Total days for {{ $year ?? date('Y') }}</p>
                    </div>
                </div>

                <!-- Total Taken -->
                <div class="col-md-4">
                    <div class="balance-card" style="border-left-color: #f59e0b;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Total Taken</p>
                                <p class="h2 fw-bold text-warning mb-0">{{ $totalTaken ?? 0 }}</p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-circle">
                                <i class="fas fa-check-circle text-warning"></i>
                            </div>
                        </div>
                        <div class="progress-bar-custom mt-3">
                            <div class="progress-fill" style="width: {{ ($totalEntitled ?? 0) > 0 ? (($totalTaken ?? 0) / ($totalEntitled ?? 1)) * 100 : 0 }}%"></div>
                        </div>
                        <p class="text-muted small mt-2 mb-0">{{ ($totalEntitled ?? 0) > 0 ? round((($totalTaken ?? 0) / ($totalEntitled ?? 1)) * 100) : 0 }}% used</p>
                    </div>
                </div>

                <!-- Remaining -->
                <div class="col-md-4">
                    <div class="balance-card" style="border-left-color: #10b981;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-1">Remaining</p>
                                <p class="h2 fw-bold text-success mb-0">{{ $totalRemaining ?? 0 }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded-circle">
                                <i class="fas fa-wallet text-success"></i>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">Available to use</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card-moic p-3">
                        <p class="text-muted small mb-1">Pending</p>
                        <p class="h5 fw-bold text-warning mb-0">{{ $pendingLeaves ?? 0 }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-moic p-3">
                        <p class="text-muted small mb-1">Approved</p>
                        <p class="h5 fw-bold text-success mb-0">{{ $approvedCount ?? 0 }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-moic p-3">
                        <p class="text-muted small mb-1">Average</p>
                        <p class="h5 fw-bold text-info mb-0">{{ ($totalTaken ?? 0) > 0 && ($approvedCount ?? 0) > 0 ? round(($totalTaken ?? 0) / (($approvedCount ?? 1) ?: 1), 1) : 0 }} days</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card-moic p-3">
                        <p class="text-muted small mb-1">Upcoming</p>
                        <p class="h5 fw-bold text-purple-600 mb-0">{{ $upcomingLeaves->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Two Column Section -->
            <div class="row g-4 mb-4">
                <!-- Monthly Leave Usage Chart -->
                <div class="col-lg-6">
                    <div class="card card-moic">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h5 class="h6 fw-bold moic-navy mb-0">
                                <i class="fas fa-chart-line me-2 moic-accent"></i>
                                Monthly Leave Usage
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="leaveChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Leaves -->
                <div class="col-lg-6">
                    <div class="card card-moic">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h5 class="h6 fw-bold moic-navy mb-0">
                                <i class="fas fa-calendar-check me-2 text-success"></i>
                                Upcoming Leaves
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($upcomingLeaves) && $upcomingLeaves->count() > 0)
                                <div class="d-flex flex-column gap-2">
                                    @foreach($upcomingLeaves as $leave)
                                        <div class="d-flex justify-content-between align-items-center p-3 bg-gray-50 rounded">
                                            <div>
                                                <p class="fw-medium small mb-1">{{ $leave->leave_type_name ?? 'Leave' }}</p>
                                                <p class="small text-muted mb-0">
                                                    <i class="far fa-calendar me-1"></i>
                                                    <span class="desktop-only">{{ isset($leave->start_date) ? $leave->start_date->format('M d') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : '' }}</span>
                                                    <span class="mobile-only">{{ isset($leave->start_date) ? $leave->start_date->format('d/m') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('d/m/Y') : '' }}</span>
                                                </p>
                                            </div>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                {{ $leave->total_days ?? 0 }} days
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center mt-3">
                                    <a href="{{ route('leave.index') }}" class="btn btn-link moic-navy text-decoration-none p-0">
                                        View all leaves <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @else
                                <p class="text-center text-muted py-4 mb-0">No upcoming leaves</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Types Breakdown -->
            <div class="card card-moic mb-4">
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <h5 class="h6 fw-bold moic-navy mb-0">Leave Types</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($leaveEntitlements ?? ['annual' => 30, 'sick' => 15, 'emergency' => 5, 'other' => 0] as $type => $entitled)
                            @php
                                $taken = $takenDays[$type] ?? 0;
                                $remaining = $remainingDays[$type] ?? 0;
                                $percentage = $entitled > 0 ? round(($taken / $entitled) * 100) : 0;
                                
                                $colors = [
                                    'annual' => 'bg-blue-50 border-blue-200',
                                    'sick' => 'bg-green-50 border-green-200',
                                    'maternity' => 'bg-pink-50 border-pink-200',
                                    'paternity' => 'bg-purple-50 border-purple-200',
                                    'study' => 'bg-indigo-50 border-indigo-200',
                                    'unpaid' => 'bg-gray-50 border-gray-200',
                                    'emergency' => 'bg-red-50 border-red-200',
                                    'compassionate' => 'bg-yellow-50 border-yellow-200',
                                    'other' => 'bg-teal-50 border-teal-200',
                                ];
                                
                                $bgColor = $colors[$type] ?? 'bg-gray-50 border-gray-200';
                            @endphp
                            
                            <div class="col-sm-6 col-lg-4">
                                <div class="border rounded p-3 {{ $bgColor }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-semibold mb-0 text-capitalize">{{ $type }}</h6>
                                        <span class="badge bg-white text-dark rounded-pill">
                                            {{ $remaining }} left
                                        </span>
                                    </div>
                                    
                                    <div class="small text-muted mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span>Entitled:</span>
                                            <span class="fw-medium">{{ $entitled }} days</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Taken:</span>
                                            <span class="fw-medium">{{ $taken }} days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-muted">Used</span>
                                            <span class="fw-medium">{{ $percentage }}%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdown -->
            @if(!empty($monthlyBreakdown))
            <div class="card card-moic mb-4">
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <h5 class="h6 fw-bold moic-navy mb-0">Monthly Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($monthlyBreakdown as $month => $days)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="bg-gray-50 rounded p-3 text-center">
                                    <p class="small text-muted mb-1">{{ $month }}</p>
                                    <p class="h5 fw-bold moic-navy mb-0">{{ $days }}</p>
                                    <p class="small text-muted mb-0">days</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Tips -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-white p-3 rounded-circle">
                                    <i class="fas fa-lightbulb moic-navy"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold moic-navy mb-1">Plan Your Leaves</h6>
                                <p class="small text-muted mb-0">You have <span class="fw-bold text-success">{{ $totalRemaining ?? 0 }}</span> days remaining. Plan ahead!</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="bg-purple-50 border border-purple-200 rounded p-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-white p-3 rounded-circle">
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-semibold text-purple-800 mb-1">Leave Expiry</h6>
                                <p class="small text-muted mb-0">Use your leaves before {{ $year ?? date('Y') }} ends.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer - Updated to match navigation style -->
            <footer class="mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded me-3">
                                <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                            </div>
                            <div>
                                <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                                <p class="text-muted small">Version 1.0.0 powered by SmartWave Solutions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-3">
                            <!-- Navigation Links in Footer - Matching Header Style -->
                            <div class="d-flex flex-wrap gap-2 p-2 bg-gray-50 rounded-3">
                                <a href="{{ route('dashboard') }}" 
                                   class="nav-link-footer {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-home me-1"></i> Dashboard
                                </a>
                                
                                <span class="text-muted opacity-25">|</span>
                                
                                <a href="{{ route('leave.index') }}" 
                                   class="nav-link-footer {{ request()->routeIs('leave.index') ? 'active' : '' }}">
                                    <i class="fas fa-calendar-alt me-1"></i> Leave
                                </a>
                                
                                <span class="text-muted opacity-25">|</span>
                                
                                <a href="{{ route('leave.balance') }}" 
                                   class="nav-link-footer {{ request()->routeIs('leave.balance') ? 'active' : '' }}">
                                    <i class="fas fa-wallet me-1"></i> Balance
                                </a>
                                
                                <span class="text-muted opacity-25">|</span>
                                
                                <a href="{{ route('calendar.index') }}" 
                                   class="nav-link-footer {{ request()->routeIs('calendar.index') ? 'active' : '' }}">
                                    <i class="fas fa-calendar me-1"></i> Calendar
                                </a>
                                
                                <span class="text-muted opacity-25">|</span>
                                
                                <a href="{{ route('profile.show') }}" 
                                   class="nav-link-footer {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                                    <i class="fas fa-user-circle me-1"></i> Profile
                                </a>
                                
                                <span class="text-muted opacity-25">|</span>
                                
                                <a href="#" class="nav-link-footer">
                                    <i class="fas fa-question-circle me-1"></i> Help
                                </a>
                            </div>
                            
                            <!-- Quick Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('leave.create') }}" 
                                   class="btn btn-sm btn-accent" 
                                   style="padding: 0.25rem 1rem; font-size: 0.75rem;">
                                    <i class="fas fa-plus me-1"></i> New Leave
                                </a>
                                
                                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                                        class="btn btn-sm btn-outline-moic"
                                        style="padding: 0.25rem 1rem; font-size: 0.75rem;">
                                    <i class="fas fa-arrow-up me-1"></i> Top
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Footer Navigation (Visible only on mobile) -->
                <div class="row mt-3 d-md-none">
                    <div class="col-12">
                        <div class="d-flex flex-wrap justify-content-center gap-2 p-2 bg-gray-50 rounded-3">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('dashboard') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-home"></i>
                            </a>
                            <a href="{{ route('leave.index') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('leave.index') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                            <a href="{{ route('leave.balance') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('leave.balance') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-wallet"></i>
                            </a>
                            <a href="{{ route('calendar.index') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('calendar.index') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-calendar"></i>
                            </a>
                            <a href="{{ route('profile.show') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('profile.show') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-user-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright Line -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-copyright me-1"></i> {{ date('Y') }} Ministry of Investment and International Cooperation. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-fixed alert-slide d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-1">Success!</h6>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-fixed alert-slide d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-1">Please fix the following errors:</h6>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide messages
            setTimeout(() => {
                document.querySelectorAll('.alert-fixed').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Touch device detection
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle image loading errors
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
            });

            // Initialize Chart
            const ctx = document.getElementById('leaveChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! isset($months) ? json_encode($months) : json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!},
                        datasets: [{
                            label: 'Leave Days',
                            data: {!! isset($leaveHistory) ? json_encode(array_values($leaveHistory)) : json_encode([0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                            backgroundColor: '#110484',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>