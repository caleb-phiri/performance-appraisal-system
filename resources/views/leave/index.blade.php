<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>My Leave Applications - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            background: #f0f3f8;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(17, 4, 132, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(231, 88, 28, 0.03) 0%, transparent 50%),
                repeating-linear-gradient(45deg, rgba(0,0,0,0.01) 0px, rgba(0,0,0,0.01) 1px, transparent 1px, transparent 10px);
            min-height: 100vh;
            font-size: 0.875rem;
            line-height: 1.6;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
            position: relative;
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
        
        /* Status Badges - Matching dashboard style */
        .badge-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .badge-approved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .badge-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .badge-cancelled {
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
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
        
        /* Stat Cards with depth */
        .stat-card {
            border: none;
            box-shadow: 
                0 5px 10px rgba(0, 0, 0, 0.05),
                0 15px 25px rgba(0, 0, 0, 0.02),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            background-color: white;
            padding: 1rem;
            height: 100%;
        }
        
        .stat-card:hover {
            box-shadow: 
                0 10px 20px rgba(17, 4, 132, 0.08),
                0 20px 30px rgba(0, 0, 0, 0.05),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
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
        
        /* Action Buttons */
        .action-btn {
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Table Styling - Matching dashboard */
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table-moic tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f3f4f6;
        }
        
        .table-moic tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table-moic tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Table responsive */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
        }
        
        .table-responsive table {
            min-width: 900px;
        }
        
        .table-responsive::-webkit-scrollbar {
            height: 4px;
            background-color: #f1f1f1;
            border-radius: 10px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 10px;
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
        
        /* Mobile menu animations */
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
        
        .mobile-menu-enter {
            animation: slideDown 0.3s ease forwards;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        .loading-spinner-dark {
            border: 3px solid rgba(0,0,0,.1);
            border-top-color: #110484;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
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
        .text-green-600 { color: #059669 !important; }
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
            
            .stat-card {
                padding: 0.75rem;
            }
            
            .action-btn {
                width: 1.75rem;
                height: 1.75rem;
                font-size: 0.75rem;
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
        
        /* Mobile menu improvements */
        @media (max-width: 768px) {
            .md\:hidden[x-show="mobileMenuOpen"] {
                position: relative;
                z-index: 40;
                max-height: calc(100vh - 4rem);
                overflow-y: auto;
            }
            
            .md\:hidden a, 
            .md\:hidden button {
                min-height: 48px;
            }
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
        
        /* Notification animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification-enter {
            animation: slideInRight 0.3s ease-out;
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
        
        /* Swipe indicator */
        .swipe-indicator {
            position: fixed;
            bottom: 1rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            pointer-events: none;
            z-index: 100;
        }
        
        .swipe-indicator-inner {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        
        /* Text colors */
        .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        /* Pagination styling */
        .pagination {
            gap: 0.25rem;
        }
        
        .page-item .page-link {
            border-radius: 0.375rem;
            color: var(--moic-navy);
            border: 1px solid #e5e7eb;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .page-item.active .page-link {
            background: var(--moic-gradient);
            border-color: var(--moic-navy);
            color: white;
        }
        
        .page-item.disabled .page-link {
            color: #9ca3af;
            pointer-events: none;
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
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">Leave Management</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">Welcome, {{ auth()->user()->name ?? 'User' }}</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="mobile-only ms-2">
                        <h1 class="h6 mb-0 fw-bold">Leave</h1>
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
            <!-- Page Header -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h2 class="h4 fw-bold moic-navy mb-1">My Leave Applications</h2>
                    <p class="text-muted small mb-0">View and manage your leave requests</p>
                </div>
                <a href="{{ route('leave.create') }}" class="btn btn-moic">
                    <i class="fas fa-plus me-2"></i> New Leave Application
                </a>
            </div>

            <!-- Stats Cards -->
            @php
                $totalApplications = isset($leaves) ? ($leaves instanceof \Illuminate\Pagination\LengthAwarePaginator ? $leaves->total() : $leaves->count()) : 0;
                $pendingCount = isset($leaves) ? $leaves->where('status', 'pending')->count() : 0;
                $approvedCount = isset($leaves) ? $leaves->where('status', 'approved')->count() : 0;
                $rejectedCount = isset($leaves) ? $leaves->where('status', 'rejected')->count() : 0;
            @endphp
            
            <div class="row g-3 mb-4">
                <!-- Total Applications -->
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-blue-50">
                                <i class="fas fa-calendar-alt moic-navy"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total</p>
                                <p class="h4 mb-0 fw-bold">{{ $totalApplications }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top small text-muted">
                            All time
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-yellow-50">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Pending</p>
                                <p class="h4 mb-0 fw-bold text-warning">{{ $pendingCount }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top small text-muted">
                            Awaiting approval
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-green-50">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Approved</p>
                                <p class="h4 mb-0 fw-bold text-success">{{ $approvedCount }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top small text-muted">
                            Approved requests
                        </div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="col-6 col-lg-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-red-50">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Rejected</p>
                                <p class="h4 mb-0 fw-bold text-danger">{{ $rejectedCount }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top small text-muted">
                            Rejected requests
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Applications Table -->
            <div class="card card-moic">
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="h5 fw-bold moic-navy mb-0">
                            <i class="fas fa-list-ul me-2 moic-accent"></i>
                            Recent Applications
                        </h3>
                    </div>
                </div>
                
                @if(isset($leaves) && $leaves->count() > 0)
                <div class="table-responsive">
                    <table class="table table-moic mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Period</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                            <tr>
                                <td class="fw-bold moic-navy">#{{ $leave->id }}</td>
                                <td>{{ $leave->leave_type_name ?? 'Leave' }}</td>
                                <td>
                                    <span class="desktop-only">{{ isset($leave->start_date) ? $leave->start_date->format('M d, Y') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : '' }}</span>
                                    <span class="mobile-only">{{ isset($leave->start_date) ? $leave->start_date->format('d/m/Y') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('d/m/Y') : '' }}</span>
                                </td>
                                <td>{{ $leave->total_days ?? 0 }}d</td>
                                <td>
                                    @if($leave->status == 'pending')
                                        <span class="badge badge-pending">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($leave->status == 'approved')
                                        <span class="badge badge-approved">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif($leave->status == 'rejected')
                                        <span class="badge badge-rejected">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @elseif($leave->status == 'cancelled')
                                        <span class="badge badge-cancelled">
                                            <i class="fas fa-ban"></i> Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white">
                                            {{ ucfirst($leave->status ?? 'Unknown') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="desktop-only">{{ isset($leave->created_at) ? $leave->created_at->format('M d, Y') : '' }}</span>
                                    <span class="mobile-only">{{ isset($leave->created_at) ? $leave->created_at->format('d/m/y') : '' }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('leave.show', $leave->id) }}" 
                                           class="action-btn bg-primary bg-opacity-10 text-primary"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(isset($leave->status) && $leave->status == 'pending')
                                        <a href="{{ route('leave.edit', $leave->id) }}" 
                                           class="action-btn bg-success bg-opacity-10 text-success"
                                           title="Edit Application">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn bg-danger bg-opacity-10 text-danger border-0"
                                                    title="Cancel Application"
                                                    onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($leaves, 'hasPages') && $leaves->hasPages())
                <div class="card-footer bg-white border-top">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                        <div class="small text-muted">
                            Showing <span class="fw-semibold">{{ $leaves->firstItem() }}</span> 
                            to <span class="fw-semibold">{{ $leaves->lastItem() }}</span> 
                            of <span class="fw-semibold">{{ $leaves->total() }}</span> results
                        </div>
                        <div>
                            {{ $leaves->links() }}
                        </div>
                    </div>
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 bg-blue-50 rounded-circle">
                            <i class="fas fa-calendar-alt moic-navy fa-3x"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold moic-navy mb-2">No leave applications yet</h4>
                    <p class="text-muted mb-4">You haven't submitted any leave applications. Create your first leave request to get started.</p>
                    <a href="{{ route('leave.create') }}" class="btn btn-moic">
                        <i class="fas fa-plus me-2"></i> Create First Leave Application
                    </a>
                </div>
                @endif
            </div>

            <!-- Upcoming Approved Leaves -->
            @php
                $upcomingLeaves = collect();
                if(isset($leaves) && $leaves->count() > 0) {
                    $upcomingLeaves = $leaves->where('status', 'approved')
                                            ->where('start_date', '>=', now())
                                            ->sortBy('start_date');
                }
            @endphp
            
            @if($upcomingLeaves->count() > 0)
            <div class="card card-moic mt-4">
                <div class="card-header bg-white border-bottom bg-green-50">
                    <h3 class="h5 fw-bold text-green-800 mb-0">
                        <i class="fas fa-calendar-check me-2 text-green-600"></i>
                        Upcoming Approved Leaves
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($upcomingLeaves->take(3) as $leave)
                        <div class="col-md-4">
                            <div class="border border-green-200 rounded p-3 bg-green-50 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-semibold text-green-800 small mb-0">{{ $leave->leave_type_name ?? 'Leave' }}</h5>
                                    <span class="badge bg-success text-white">{{ $leave->total_days ?? 0 }} days</span>
                                </div>
                                <p class="small text-muted mb-2">
                                    <i class="fas fa-calendar-alt me-1 text-green-600"></i>
                                    <span class="desktop-only">{{ isset($leave->start_date) ? $leave->start_date->format('M d, Y') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : '' }}</span>
                                    <span class="mobile-only">{{ isset($leave->start_date) ? $leave->start_date->format('d/m/Y') : '' }} - {{ isset($leave->end_date) ? $leave->end_date->format('d/m/Y') : '' }}</span>
                                </p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ isset($leave->start_date) ? $leave->start_date->diffForHumans() : '' }}
                                    </span>
                                    <a href="{{ route('leave.show', $leave->id) }}" class="small text-green-700 text-decoration-none">
                                        View <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($upcomingLeaves->count() > 3)
                    <div class="text-center mt-3">
                        <a href="{{ route('leave.index', ['status' => 'approved']) }}" class="btn btn-link moic-navy text-decoration-none">
                            View all {{ $upcomingLeaves->count() }} upcoming leaves <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Tips Section -->
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <div class="card card-moic bg-blue-50 border-blue-200">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-white p-2 rounded-circle">
                                        <i class="fas fa-wallet moic-navy"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-semibold moic-navy mb-1">Leave Balance</h5>
                                    <p class="small text-muted mb-2">Check your remaining leave days for the current year.</p>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        Coming Soon <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card card-moic bg-purple-50 border-purple-200">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-white p-2 rounded-circle">
                                        <i class="fas fa-clock text-purple-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-semibold text-purple-800 mb-1">Processing Time</h5>
                                    <p class="small text-muted mb-0">Leave applications are typically processed within 2-3 business days.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Swipe Indicator -->
            <div class="d-sm-none text-center mt-3">
                <div class="swipe-indicator" id="swipeIndicator">
                    <div class="swipe-indicator-inner">
                        <i class="fas fa-arrows-alt-h"></i>
                        Swipe horizontally to see more
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

    <!-- Success Message -->
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

    <!-- Error Message -->
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

    <script>
        // Auto-hide messages
        setTimeout(() => {
            document.querySelectorAll('.alert-fixed').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Hide swipe indicator after 3 seconds
        setTimeout(function() {
            const indicator = document.getElementById('swipeIndicator');
            if (indicator) {
                indicator.style.transition = 'opacity 0.5s';
                indicator.style.opacity = '0';
                setTimeout(() => {
                    if (indicator) indicator.remove();
                }, 500);
            }
        }, 3000);

        // Touch device detection
        document.addEventListener('DOMContentLoaded', function() {
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
        });

        // Function to trigger new notification
        window.dispatchNotification = function(type, message) {
            const event = new CustomEvent('new-notification', {
                detail: { type, message }
            });
            window.dispatchEvent(event);
        };
    </script>
</body>
</html>