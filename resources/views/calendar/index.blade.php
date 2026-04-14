<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>My Leave Calendar - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    
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
            background: linear-gradient(135deg, #f5f9ff 0%, #edf2f9 100%);
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }
        
        /* Dynamic mesh gradient overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 0% 30%, rgba(17, 4, 132, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 100% 70%, rgba(231, 88, 28, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.8) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
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
        }
        
        /* Card Styling - Enhanced with glassmorphism */
        .card-moic {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 2px 6px rgba(0, 0, 0, 0.05), inset 0 1px 2px rgba(255, 255, 255, 0.8);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-moic:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        
        /* Stat Cards - Enhanced with glassmorphism */
        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 0.5rem;
            padding: 1rem;
            height: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        /* User Badges */
        .user-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
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
        
        /* Mobile Menu */
        .mobile-menu {
            position: absolute;
            right: 0;
            top: 100%;
            z-index: 1000;
            min-width: 16rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        /* Alert Styling */
        .alert-fixed {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1050;
            min-width: 20rem;
            max-width: 90%;
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-cyan-50 { background-color: #ecfeff !important; }
        
        /* Text utilities */
        .text-green-800 { color: #166534 !important; }
        .text-purple-800 { color: #6b21a8 !important; }
        .text-blue-800 { color: #1e40af !important; }
        .text-yellow-800 { color: #92400e !important; }
        .text-red-800 { color: #991b1b !important; }
        
        /* Responsive container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* FullCalendar Customization */
        .fc .fc-button-primary {
            background-color: var(--moic-navy);
            border-color: var(--moic-navy);
        }
        
        .fc .fc-button-primary:hover {
            background-color: var(--moic-navy-light);
            border-color: var(--moic-navy-light);
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background-color: var(--moic-accent);
            border-color: var(--moic-accent);
        }
        
        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(231, 88, 28, 0.1) !important;
        }
        
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85rem;
            transition: transform 0.2s ease;
        }
        
        .fc-event:hover {
            transform: scale(1.02);
            filter: brightness(0.95);
        }
        
        .fc .fc-toolbar-title {
            color: var(--moic-navy);
            font-weight: 600;
        }
        
        /* Legend styles */
        .legend-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            background-color: #f9fafb;
            margin-bottom: 0.5rem;
        }
        
        .legend-color {
            width: 1rem;
            height: 1rem;
            border-radius: 4px;
            margin-right: 0.5rem;
        }
        
        .legend-color.today {
            background-color: #fef3c7;
            border: 2px solid #d97706;
        }
        
        /* Modal styles */
        .modal-moic {
            background: white;
            border-radius: 0.5rem;
            max-width: 500px;
            width: 90%;
            margin: 0 auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .modal-moic .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: var(--moic-gradient);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .modal-moic .modal-body {
            padding: 1.5rem;
        }
        
        .modal-moic .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0 0 0.5rem 0.5rem;
        }
        
        /* Quick actions card */
        .quick-actions-card {
            background: var(--moic-gradient-mixed);
            border-radius: 0.5rem;
            padding: 1.25rem;
            color: white;
        }
        
        .quick-actions-card a,
        .quick-actions-card button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            color: white;
            text-decoration: none;
            border: none;
            background: transparent;
            font-size: 0.875rem;
        }
        
        .quick-actions-card a:hover,
        .quick-actions-card button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        
        /* Upcoming leaves list */
        .upcoming-leave-item {
            display: block;
            padding: 0.75rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .upcoming-leave-item:hover {
            background-color: #f3f4f6;
            transform: translateX(4px);
        }
        
        /* Navigation link styles */
        .nav-link-custom {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .nav-link-custom:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link-custom.active {
            background-color: rgba(255, 255, 255, 0.2);
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
        
        /* Responsive breakpoints */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .stat-card {
                padding: 0.75rem;
            }
            
            .desktop-only {
                display: none !important;
            }
            
            .mobile-only {
                display: block !important;
            }
            
            .fc-toolbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-moic, .btn-accent, .btn-outline-moic {
                padding: 0.5rem 1rem;
            }
            
            .nav-link-footer {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
            
            footer .btn {
                padding: 0.25rem 0.75rem !important;
                font-size: 0.7rem !important;
            }
            
            .alert-fixed {
                min-width: auto;
                width: 90%;
                left: 5%;
                right: 5%;
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
            
            .stat-card:hover,
            .card-moic:hover {
                transform: none;
            }
            
            html {
                scroll-behavior: auto;
            }
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
        
        /* Form validation */
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #dc3545;
        }
        
        /* Text colors */
        .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .text-purple-600 {
            color: #7c3aed !important;
        }
        
        /* Opacity utilities */
        .opacity-25 {
            opacity: 0.25;
        }
    </style>
</head>
<body>
    <!-- Header with Animated Gradient (Matching Dashboard) -->
    <div class="gradient-header text-white">
        <div class="container-custom px-3 py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Logo Section -->
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
                    
                    <!-- Page Title -->
                    <div class="d-flex align-items-center desktop-only">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem;"></div>
                        <div>
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">Leave Calendar</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">Welcome, {{ auth()->user()->name ?? 'User' }}</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="mobile-only ms-2">
                        <h1 class="h6 mb-0 fw-bold">Calendar</h1>
                        <p class="mb-0 text-white-50 small">Welcome, {{ explode(' ', auth()->user()->name ?? 'User')[0] }}</p>
                    </div>
                </div>

                <!-- Navigation Links - Added Dashboard, Leave, Leave Balance, Calendar -->
                <div class="d-none d-md-flex align-items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('leave.index') }}" class="nav-link-custom {{ request()->routeIs('leave.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-1"></i> Leave
                    </a>
                    <a href="{{ route('leave.balance') }}" class="nav-link-custom {{ request()->routeIs('leave.balance') ? 'active' : '' }}">
                        <i class="fas fa-wallet me-1"></i> Leave Balance
                    </a>
                    <a href="{{ route('calendar.index') }}" class="nav-link-custom {{ request()->routeIs('calendar.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check me-1"></i> Calendar
                    </a>
                </div>

                <!-- User Section -->
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <!-- Notification Dropdown -->
                        <div class="position-relative" x-data="{ 
                            open: false,
                            notifications: [],
                            unreadCount: 0,
                            loading: false,
                            
                            init() {
                                this.loadNotifications();
                                setInterval(() => this.loadNotifications(), 30000);
                                window.addEventListener('new-notification', () => this.loadNotifications());
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
                            }
                        }" @click.outside="open = false">
                            <!-- Notification Bell -->
                            <button @click="open = !open" class="btn btn-outline-light btn-sm position-relative notification-badge">
                                <i class="fas fa-bell"></i>
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

                            <!-- Notifications Dropdown Panel -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-95"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-95"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="mobile-menu"
                                 style="display: none; width: 24rem; right: 0;">
                                
                                <!-- Header -->
                                <div class="px-3 py-2 bg-gradient text-white" style="background: var(--moic-gradient);">
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
                                <div x-show="!loading" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <a href="#" @click.prevent="markAsRead(notification.id)" 
                                           class="list-group-item list-group-item-action px-3 py-2"
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
                                    <a href="/notifications" class="text-decoration-none moic-accent">
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
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2 moic-navy"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-chart-pie me-2 moic-navy"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leave.index') }}">
                                        <i class="fas fa-calendar-alt me-2 moic-navy"></i> Leave
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leave.balance') }}">
                                        <i class="fas fa-wallet me-2 moic-accent"></i> Leave Balance
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('calendar.index') }}">
                                        <i class="fas fa-calendar-check me-2 moic-navy"></i> Calendar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Mobile User Menu -->
                        <div class="dropdown mobile-only">
                            <button class="btn btn-outline-light btn-sm" type="button" id="mobileMenu" data-bs-toggle="dropdown">
                                <i class="fas fa-bars"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mobile-menu" aria-labelledby="mobileMenu">
                                <li class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-gradient me-3">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->name ?? 'User' }}</div>
                                            <div class="small text-muted">{{ auth()->user()->employee_number ?? '' }}</div>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-chart-pie me-2 moic-navy"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leave.index') }}">
                                        <i class="fas fa-calendar-alt me-2 moic-navy"></i> Leave
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('leave.balance') }}">
                                        <i class="fas fa-wallet me-2 moic-accent"></i> Leave Balance
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('calendar.index') }}">
                                        <i class="fas fa-calendar-check me-2 moic-navy"></i> Calendar
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user-circle me-2 moic-navy"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
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
            <!-- Header -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h2 class="h4 fw-bold moic-navy mb-1">My Leave Calendar</h2>
                    <p class="text-muted small mb-0">
                        {{ $currentDate->format('F Y') ?? now()->format('F Y') }}
                        <span class="ms-2 badge bg-blue-50 moic-navy">
                            <i class="fas fa-user me-1"></i> Your leaves only
                        </span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('leave.create') }}" class="btn btn-moic">
                        <i class="fas fa-plus me-2"></i> New Leave
                    </a>
                    <a href="{{ route('calendar.export', ['month' => $currentDate->month ?? now()->month, 'year' => $currentDate->year ?? now()->year]) }}" 
                       class="btn btn-outline-moic">
                        <i class="fas fa-download me-2"></i> Export
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-blue-50">
                                <i class="fas fa-calendar-alt moic-navy"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">My Total</p>
                                <p class="h4 mb-0 fw-bold">{{ $statistics['total'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-green-50">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Approved</p>
                                <p class="h4 mb-0 fw-bold text-success">{{ $statistics['approved'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-yellow-50">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Pending</p>
                                <p class="h4 mb-0 fw-bold text-warning">{{ $statistics['pending'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-purple-50">
                                <i class="fas fa-calendar-day text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">This Month</p>
                                <p class="h4 mb-0 fw-bold moic-navy">{{ $leaves->count() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- Legend -->
                    <div class="card card-moic mb-4">
                        <div class="card-body">
                            <h5 class="h6 fw-bold moic-navy mb-3">
                                <i class="fas fa-info-circle me-2"></i>Legend
                            </h5>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #10b981;"></div>
                                <span>My Approved Leave</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #f59e0b;"></div>
                                <span>My Pending Leave</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color today"></div>
                                <span>Today</span>
                            </div>
                        </div>
                    </div>

                    <!-- My Upcoming Leaves -->
                    <div class="card card-moic mb-4">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h5 class="h6 fw-bold moic-navy mb-0">
                                <i class="fas fa-calendar-check me-2 text-success"></i>
                                My Upcoming Leaves
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                @forelse($upcomingLeaves ?? [] as $leave)
                                <a href="{{ route('leave.show', $leave->id) }}" class="upcoming-leave-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="fw-medium small mb-1">{{ $leave->leave_type_name ?? 'Leave' }}</p>
                                            <p class="small text-muted mb-0">
                                                <i class="far fa-calendar me-1"></i>
                                                <span class="desktop-only">{{ $leave->start_date->format('M d') ?? '' }} - {{ $leave->end_date->format('M d, Y') ?? '' }}</span>
                                                <span class="mobile-only">{{ $leave->start_date->format('d/m') ?? '' }} - {{ $leave->end_date->format('d/m/Y') ?? '' }}</span>
                                            </p>
                                        </div>
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $leave->start_date->format('d M') ?? '' }}
                                        </span>
                                    </div>
                                </a>
                                @empty
                                <p class="small text-muted text-center py-3 mb-0">No upcoming leaves</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions-card">
                        <h5 class="h6 fw-bold mb-3">Quick Actions</h5>
                        <div class="d-flex flex-column gap-1">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-pie me-2"></i> Dashboard
                            </a>
                            <a href="{{ route('leave.index') }}">
                                <i class="fas fa-list me-2"></i> View My Applications
                            </a>
                            <a href="{{ route('leave.balance') }}">
                                <i class="fas fa-wallet me-2"></i> Leave Balance
                            </a>
                            <a href="{{ route('leave.create') }}">
                                <i class="fas fa-plus me-2"></i> New Leave Request
                            </a>
                            <button onclick="calendar?.today()" class="w-100 text-start">
                                <i class="fas fa-calendar-check me-2"></i> Today
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Calendar -->
                <div class="col-lg-9">
                    <div class="card card-moic">
                        <div class="card-body">
                            <!-- Month Navigation -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <a href="{{ route('calendar.index', ['month' => $prevMonth->month ?? now()->subMonth()->month, 'year' => $prevMonth->year ?? now()->subMonth()->year]) }}" 
                                   class="btn btn-outline-moic btn-sm">
                                    <i class="fas fa-chevron-left me-1"></i> {{ $prevMonth->format('M Y') ?? now()->subMonth()->format('M Y') }}
                                </a>
                                <h5 class="h5 fw-bold moic-navy mb-0">
                                    {{ $currentDate->format('F Y') ?? now()->format('F Y') }}
                                </h5>
                                <a href="{{ route('calendar.index', ['month' => $nextMonth->month ?? now()->addMonth()->month, 'year' => $nextMonth->year ?? now()->addMonth()->year]) }}" 
                                   class="btn btn-outline-moic btn-sm">
                                    {{ $nextMonth->format('M Y') ?? now()->addMonth()->format('M Y') }} <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>

                            <!-- Calendar Container -->
                            <div id="calendar"></div>
                        </div>
                    </div>

                    <!-- Selected Date Events -->
                    <div id="selectedDateEvents" class="card card-moic mt-4 d-none">
                        <div class="card-header bg-white border-bottom bg-gray-50 d-flex justify-content-between align-items-center">
                            <h5 class="h6 fw-bold moic-navy mb-0">
                                Events for <span id="selectedDateDisplay" class="moic-accent"></span>
                            </h5>
                            <button onclick="closeSelectedEvents()" class="btn btn-sm btn-link text-muted">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div id="selectedDateEventsList" class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;"></div>
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
                                    <i class="fas fa-chart-pie me-1"></i> Dashboard
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
                                    <i class="fas fa-calendar-check me-1"></i> Calendar
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
                                <i class="fas fa-chart-pie"></i>
                            </a>
                            <a href="{{ route('leave.index') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('leave.index') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                            <a href="{{ route('leave.balance') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('leave.balance') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-wallet"></i>
                            </a>
                            <a href="{{ route('calendar.index') }}" class="text-decoration-none small px-2 py-1 {{ request()->routeIs('calendar.index') ? 'moic-navy fw-bold' : 'text-muted' }}">
                                <i class="fas fa-calendar-check"></i>
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

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--moic-gradient); color: white;">
                    <h5 class="modal-title" id="modalTitle">Leave Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="modalViewLink" class="btn btn-moic">View Details</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

    <script>
        let calendar;
        let eventModal;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap Modal
            eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Touch device detection
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            }

            // Initialize calendar
            const calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    initialDate: '{{ $currentDate->format("Y-m-d") ?? now()->format("Y-m-d") }}',
                    headerToolbar: false,
                    height: 'auto',
                    events: '{{ route("calendar.events") }}',
                    eventClick: function(info) {
                        showEventDetails(info.event);
                    },
                    dateClick: function(info) {
                        loadDateEvents(info.date);
                    },
                    eventDidMount: function(info) {
                        info.el.setAttribute('title', info.event.title);
                    },
                    eventBackgroundColor: function(event) {
                        return event.extendedProps.status === 'approved' ? '#10b981' : '#f59e0b';
                    },
                    eventBorderColor: function(event) {
                        return event.extendedProps.status === 'approved' ? '#059669' : '#d97706';
                    }
                });
                
                calendar.render();
            }
        });

        function loadDateEvents(date) {
            const formattedDate = date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            document.getElementById('selectedDateDisplay').textContent = formattedDate;
            
            const dateStr = date.toISOString().split('T')[0];
            
            fetch(`{{ route("calendar.events") }}?start=${dateStr}&end=${dateStr}`)
                .then(response => response.json())
                .then(events => {
                    const eventsList = document.getElementById('selectedDateEventsList');
                    eventsList.innerHTML = '';
                    
                    if (events.length === 0) {
                        eventsList.innerHTML = '<p class="text-sm text-muted text-center py-4 mb-0">No leaves on this date</p>';
                    } else {
                        events.forEach(event => {
                            const eventEl = document.createElement('a');
                            eventEl.href = event.url;
                            eventEl.className = 'upcoming-leave-item';
                            eventEl.innerHTML = `
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="fw-medium small mb-1">${event.title}</p>
                                        <p class="small text-muted mb-0">${event.extendedProps.days} days</p>
                                    </div>
                                    <span class="badge" style="background-color: ${event.color}20; color: ${event.color}">
                                        ${event.extendedProps.status}
                                    </span>
                                </div>
                            `;
                            eventsList.appendChild(eventEl);
                        });
                    }
                    
                    document.getElementById('selectedDateEvents').classList.remove('d-none');
                });
        }

        function closeSelectedEvents() {
            document.getElementById('selectedDateEvents').classList.add('d-none');
        }

        function showEventDetails(event) {
            document.getElementById('modalTitle').textContent = event.title;
            
            document.getElementById('modalContent').innerHTML = `
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status:</span>
                        <span class="badge" style="background-color: ${event.backgroundColor}20; color: ${event.backgroundColor}">
                            ${event.extendedProps.status}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Leave Type:</span>
                        <span class="fw-medium">${event.extendedProps.type}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Duration:</span>
                        <span class="fw-medium">${event.extendedProps.days} days</span>
                    </div>
                    <div class="pt-2 border-top">
                        <p class="text-muted mb-2">Period:</p>
                        <p class="fw-medium mb-0">
                            ${new Date(event.start).toLocaleDateString()} - ${new Date(event.end).toLocaleDateString()}
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('modalViewLink').href = event.url;
            eventModal.show();
        }

        // Auto-hide messages
        setTimeout(() => {
            document.querySelectorAll('.alert-fixed').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Escape key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSelectedEvents();
            }
        });

        // Handle image loading errors
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>