<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Dashboard - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Production CSS -->
    <style>
        /* MOIC Brand Colors */
        :root {
            --moic-navy: #110484;
            --moic-navy-light: #3328a5;
            --moic-accent: #e7581c;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-blue-light: #2d1fd1;
            --moic-gradient: linear-gradient(135deg, var(--moic-navy), var(--moic-blue));
            --moic-gradient-accent: linear-gradient(135deg, var(--moic-accent), #ff7c45);
            
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
        
        /* Status Badges */
        .badge-draft {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .badge-submitted {
            background-color: #dbeafe !important;
            color: #1e40af !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .badge-approved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .badge-rejected {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .badge-completed {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        .badge-pending {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 0.75rem;
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
        
        /* Action Cards */
        .action-card {
            border: none;
            border-radius: 0.5rem;
            padding: 1.5rem 1rem;
            color: white;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1) !important;
            text-decoration: none;
            color: white;
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
        
        /* Table Styling */
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
            max-width: 90%;
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .alert-fixed {
                min-width: auto;
                width: 90%;
                left: 5%;
                right: 5%;
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
        
        /* Background utilities */
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-indigo-50 { background-color: #eef2ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
        .bg-amber-50 { background-color: #fffbeb !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-pink-50 { background-color: #fdf2f8 !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-violet-50 { background-color: #f5f3ff !important; }
        .bg-orange-50 { background-color: #fff7ed !important; }
        .bg-cyan-50 { background-color: #ecfeff !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        
        /* Text utilities */
        .text-green-800 { color: #166534 !important; }
        .text-purple-800 { color: #6b21a8 !important; }
        .text-blue-800 { color: #1e40af !important; }
        .text-yellow-800 { color: #92400e !important; }
        .text-red-800 { color: #991b1b !important; }
        .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; }
        .text-purple { color: #8b5cf6 !important; }
        
        /* Responsive container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Table responsive */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-responsive table {
            min-width: 800px;
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
        
        /* Supervisor card */
        .supervisor-select-card {
            background: linear-gradient(135deg, rgba(17,4,132,0.03), rgba(231,88,28,0.03));
            border-left: 4px solid var(--moic-accent);
            transition: all 0.3s ease;
        }
        
        .supervisor-select-card:hover {
            background: linear-gradient(135deg, rgba(17,4,132,0.05), rgba(231,88,28,0.05));
        }
        
        .supervisor-select-card select {
            background-color: white;
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .supervisor-select-card select:focus {
            border-color: var(--moic-navy);
            box-shadow: 0 0 0 3px rgba(17,4,132,0.1);
            outline: none;
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
        
        /* Responsive breakpoints */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .action-card {
                padding: 1rem 0.75rem;
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
            
            .grid-cols-5 {
                grid-template-columns: repeat(2, 1fr) !important;
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
            
            .action-card {
                padding: 0.75rem 0.5rem;
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
            
            .action-card:hover,
            .stat-card:hover,
            .card-moic:hover {
                transform: none;
            }
            
            html {
                scroll-behavior: auto;
            }
        }
        
        /* Grid system replacement for Bootstrap */
        .grid {
            display: grid;
        }
        
        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-cols-5 { grid-template-columns: repeat(5, 1fr); }
        
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        
        /* Animation utilities */
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
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
        
        /* Opacity utilities */
        .opacity-90 {
            opacity: 0.9;
        }
        
        /* Border colors */
        .border-success {
            border-color: #10b981 !important;
        }
        
        .border-warning {
            border-color: #f59e0b !important;
        }
        
        /* Background opacity */
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        
        .bg-opacity-25 {
            --bs-bg-opacity: 0.25;
        }
        
        /* Gradient backgrounds */
        .bg-gradient {
            background: linear-gradient(135deg, rgba(17,4,132,0.02), rgba(231,88,28,0.02));
        }
        
        /* From/To utilities for gradients */
        .from-\[\#110484\] {
            --tw-gradient-from: #110484;
        }
        
        .to-\[\#e7581c\] {
            --tw-gradient-to: #e7581c;
        }
        
        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-from), var(--tw-gradient-to));
        }
        
        /* Delete Confirmation Modal */
        .modal-moic .modal-header {
            background: var(--moic-gradient);
            color: white;
        }
        
        .modal-moic .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
</head>
<body>
    @php
        // Get current supervisor selection (if any)
        $selectedSupervisorId = $user->manager_id ?? null;
        
        // Get supervisors list for the dropdown
        if ($user->user_type === 'supervisor') {
            // For supervisors, get other supervisors (excluding self)
            $supervisors = \App\Models\User::where('user_type', 'supervisor')
                ->where('employee_number', '!=', $user->employee_number)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        } else {
            // For regular employees, get all supervisors
            $supervisors = \App\Models\User::where('user_type', 'supervisor')
                ->where('status', 'active')
                ->orderBy('name')
                ->get();
        }
        
        // For supervisors: Get appraisals from their subordinates
        // For regular employees: Get only their own appraisals
        if ($user->user_type === 'supervisor') {
            // Get subordinate employee numbers
            $subordinateNumbers = $user->subordinates->pluck('employee_number')->toArray();
            // Include supervisor's own appraisals too
            $subordinateNumbers[] = $user->employee_number;
            
            // Get appraisals for supervisor and their subordinates
            $userAppraisals = \App\Models\Appraisal::whereIn('employee_number', $subordinateNumbers)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular employees only see their own appraisals
            $userAppraisals = \App\Models\Appraisal::where('employee_number', $user->employee_number)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $totalAppraisals = $userAppraisals->count() ?? 0;
        $draftCount = $userAppraisals->where('status', 'draft')->count() ?? 0;
        $submittedCount = $userAppraisals->where('status', 'submitted')->count() ?? 0;
        $approvedCount = $userAppraisals->where('status', 'approved')->count() ?? 0;
        $rejectedCount = $userAppraisals->where('status', 'rejected')->count() ?? 0;
        
        // Get only first 5 appraisals for dashboard
        $recentAppraisals = $userAppraisals->take(5);
        
        // Leave counts (if available)
        $pendingLeaves = $pendingLeaves ?? 0;
        $upcomingApprovedLeaves = $upcomingApprovedLeaves ?? 0;
    @endphp

    <!-- Header with Animated Gradient -->
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
                    
                    <!-- Dashboard Title -->
                    <div class="d-flex align-items-center desktop-only">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem;"></div>
                        <div>
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">Performance Appraisal System</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">Welcome, {{ $user->name }}</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="mobile-only ms-2">
                        <h1 class="h6 mb-0 fw-bold">Performance</h1>
                        <p class="mb-0 text-white-50 small">Welcome, {{ explode(' ', $user->name)[0] }}</p>
                    </div>
                </div>

                <!-- User Section -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Supervisor Dashboard Link -->
                    @if($user->user_type === 'supervisor')
                    <div class="desktop-only">
                        <a href="{{ route('supervisor.dashboard') }}" class="btn btn-accent btn-sm">
                            <i class="fas fa-user-tie me-1"></i> Supervisor
                        </a>
                    </div>
                    @endif
                    
                    <!-- Notification Dropdown with Alpine.js -->
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

                        <!-- Notifications Dropdown Panel -->
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
                            <span class="fw-medium">{{ $user->name }}</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.75rem;">{{ $user->job_title ?? 'Employee' }}</span>
                        <a href="{{ route('profile.show') }}" class="text-white-50" style="font-size: 0.75rem; text-decoration: none;">
                            <i class="fas fa-user-circle me-1"></i> Profile
                        </a>
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
                                    <i class="fas fa-home me-2 moic-navy"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('leave.index') }}">
                                    <i class="fas fa-calendar-alt me-2 moic-accent"></i> Leave
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('leave.balance') }}">
                                    <i class="fas fa-wallet me-2 moic-navy"></i> Balance
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('calendar.index') }}">
                                    <i class="fas fa-calendar me-2 moic-accent"></i> Calendar
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
                        <button class="btn btn-outline-light btn-sm" type="button" id="mobileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mobile-menu" aria-labelledby="mobileMenu">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-gradient me-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->employee_number }}</div>
                                        <div class="small text-muted">{{ $user->job_title ?? 'Employee' }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if($user->user_type === 'supervisor')
                            <li>
                                <a class="dropdown-item" href="{{ route('supervisor.dashboard') }}">
                                    <i class="fas fa-user-tie me-2 moic-accent"></i> Supervisor Panel
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home me-2 moic-navy"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('leave.index') }}">
                                    <i class="fas fa-calendar-alt me-2 moic-accent"></i> Leave
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('leave.balance') }}">
                                    <i class="fas fa-wallet me-2 moic-navy"></i> Balance
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('calendar.index') }}">
                                    <i class="fas fa-calendar me-2 moic-accent"></i> Calendar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user-circle me-2 moic-navy"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('appraisals.create') }}">
                                    <i class="fas fa-plus me-2 text-success"></i> New Appraisal
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
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-custom px-3">
            <!-- Welcome Card -->
            <div class="card card-moic mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="h4 fw-bold moic-navy mb-3">Welcome, {{ explode(' ', $user->name)[0] }}!</h2>
                            <div class="row mb-3">
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-id-badge me-2 text-muted"></i>
                                        <span class="text-muted">ID:</span>
                                        <span class="fw-bold ms-1">{{ $user->employee_number }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-building me-2 text-muted"></i>
                                        <span class="text-muted">Dept:</span>
                                        <span class="fw-bold ms-1">{{ $user->department ?? 'Not specified' }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-briefcase me-2 text-muted"></i>
                                        <span class="text-muted">Role:</span>
                                        <span class="fw-bold ms-1">{{ $user->job_title ?? 'Not specified' }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- User Badges -->
                            <div class="d-flex flex-wrap gap-2">
                                @if($user->user_type === 'supervisor')
                                <span class="user-badge bg-purple-100 text-purple-800">
                                    <i class="fas fa-user-tie me-1"></i> Supervisor
                                </span>
                                @php
                                    $subordinateCount = $user->subordinates()->count();
                                @endphp
                                <span class="user-badge bg-blue-100 moic-navy">
                                    <i class="fas fa-users me-1"></i> {{ $subordinateCount }} Team Members
                                </span>
                                @endif
                                
                                @if($user->workstation_type === 'hq')
                                <span class="user-badge bg-blue-100 moic-navy">
                                    <i class="fas fa-building me-1"></i> Headquarters
                                </span>
                                @elseif($user->workstation_type === 'toll_plaza')
                                <span class="user-badge bg-green-100 text-green-800">
                                    <i class="fas fa-road me-1"></i> Toll Plaza
                                </span>
                                @endif
                                
                                @if($user->toll_plaza)
                                <span class="user-badge bg-orange-100 moic-accent">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($user->toll_plaza, 15) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            @if(!$user->onboarded)
                            <a href="{{ route('profile.show') }}" class="btn btn-accent">
                                <i class="fas fa-exclamation-circle me-2"></i> Complete Profile
                            </a>
                            @else
                            <div class="text-end">
                                <a href="{{ route('profile.show') }}" class="user-badge bg-success bg-opacity-10 text-success mb-2">
                                    <i class="fas fa-check-circle me-1"></i> Profile Complete
                                </a>
                                @if($user->last_login_at)
                                <p class="text-muted small mt-2">
                                    Last login: {{ $user->last_login_at->diffForHumans() }}
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supervisor Selection Card - FIXED VERSION -->
            @if($user->user_type === 'supervisor')
            <div class="card card-moic mb-4 supervisor-select-card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                
                                <!-- Fixed Icon -->
                                <div class="bg-light p-3 rounded-circle shadow-sm">
                                    <i class="fas fa-user-tie text-primary fs-5"></i>
                                </div>

                                <h3 class="h5 fw-bold moic-navy mb-0">
                                    Select Your Supervisor for Appraisal
                                </h3>
                            </div>

                            <p class="text-muted small mb-0">
                                When you're in employee mode, choose who should review your appraisals. 
                                This ensures your submissions are routed to the correct supervisor for approval.
                            </p>
                        </div>
                        
                        <div class="col-lg-4 mt-3 mt-lg-0">
                            <form action="{{ route('profile.update-supervisor') }}" method="POST" id="supervisorForm">
                                @csrf
                                @method('PUT')

                                <div class="d-flex gap-2">
                                    <select name="manager_id" id="manager_id" class="form-select form-select-sm" required>
                                        <option value="">-- Select supervisor --</option>
                                        @if(isset($supervisors) && $supervisors->count() > 0)
                                            @foreach($supervisors as $supervisor)
                                                <option value="{{ $supervisor->employee_number }}" 
                                                    {{ ($user->manager_id == $supervisor->employee_number) ? 'selected' : '' }}>
                                                    {{ $supervisor->name }} ({{ $supervisor->employee_number }})
                                                    @if($supervisor->job_title) - {{ $supervisor->job_title }} @endif
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No supervisors available</option>
                                        @endif
                                    </select>

                                    <button type="button" onclick="loadSupervisors()" 
                                        class="btn btn-outline-secondary btn-sm" title="Refresh">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

                                <div id="supervisorLoading" class="mt-2 small text-muted" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Loading...
                                </div>

                                <button type="submit" id="submitSupervisorBtn" 
                                    class="btn btn-moic btn-sm w-100 mt-2 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Save Supervisor
                                    <span id="supervisorLoadingBtn" class="loading-spinner" style="display: none;"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if($user->manager)
                    <div class="mt-3 p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-25 p-2 rounded-circle me-2">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>

                            <div>
                                <p class="small text-success mb-0">
                                    <span class="fw-bold">Your supervisor:</span> 
                                    {{ $user->manager->name }} 
                                    @if($user->manager->job_title) 
                                        ({{ $user->manager->job_title }}) 
                                    @endif
                                </p>
                                <p class="small text-muted mb-0">
                                    Employee #: {{ $user->manager->employee_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mt-3 p-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-25 p-2 rounded-circle me-2">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>

                            <p class="small text-warning mb-0">
                                <span class="fw-bold">No supervisor selected.</span> 
                                Please select a supervisor above.
                            </p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-blue-50">
                                <i class="fas fa-file-alt moic-navy"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">
                                    @if($user->user_type === 'supervisor')
                                        Team
                                    @else
                                        My
                                    @endif
                                </p>
                                <p class="h4 mb-0 fw-bold">{{ $totalAppraisals }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-yellow-50">
                                <i class="fas fa-edit moic-accent"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Drafts</p>
                                <p class="h4 mb-0 fw-bold">{{ $draftCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-cyan-50">
                                <i class="fas fa-paper-plane text-primary"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Submitted</p>
                                <p class="h4 mb-0 fw-bold">{{ $submittedCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-green-50">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Approved</p>
                                <p class="h4 mb-0 fw-bold">{{ $approvedCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-red-50">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Rejected</p>
                                <p class="h4 mb-0 fw-bold">{{ $rejectedCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card card-moic mb-4">
                <div class="card-body">
                    <h3 class="h5 fw-bold moic-navy mb-4">Quick Actions</h3>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('appraisals.create') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #110484, #1a0c9e);">
                                <i class="fas fa-plus fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">New Appraisal</p>
                                <p class="small opacity-90 mb-0 desktop-only">Start performance review</p>
                            </a>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('appraisals.index') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-list fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">My Appraisals</p>
                                <p class="small opacity-90 mb-0 desktop-only">View all appraisals</p>
                            </a>
                        </div>
                        
                        @if($user->user_type === 'supervisor')
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('supervisor.dashboard') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-user-tie fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">Team</p>
                                <p class="small opacity-90 mb-0 desktop-only">Manage team</p>
                            </a>
                        </div>
                        @else
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('profile.show') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                                <i class="fas fa-user-edit fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">Profile</p>
                                <p class="small opacity-90 mb-0 desktop-only">Update info</p>
                            </a>
                        </div>
                        @endif
                        
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('profile.password') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                                <i class="fas fa-key fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">Password</p>
                                <p class="small opacity-90 mb-0 desktop-only">Change settings</p>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('leave.index') }}" 
                               class="action-card" 
                               style="background: linear-gradient(135deg, #110484, #1a0c9e);">
                                <i class="fas fa-calendar-alt fa-lg mb-2"></i>
                                <p class="fw-medium mb-1">Leave</p>
                                <p class="small opacity-90 mb-0 desktop-only">Apply for leave</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Status Section -->
            @if(isset($pendingLeaves) && ($pendingLeaves > 0 || $upcomingApprovedLeaves > 0))
            <div class="card card-moic mb-4 bg-gradient">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 fw-bold moic-navy mb-0">
                            <i class="fas fa-calendar-alt me-2"></i> Leave Status
                        </h3>
                        <a href="{{ route('leave.index') }}" class="btn btn-moic btn-sm">
                            Manage <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    
                    <div class="row g-3">
                        @if($pendingLeaves > 0)
                        <div class="col-6">
                            <div class="border rounded p-3 bg-yellow-50">
                                <div class="d-flex align-items-center">
                                    <div class="bg-yellow-100 p-2 rounded-circle me-2">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0">Pending Leave</p>
                                        <p class="h5 mb-0 fw-bold">{{ $pendingLeaves }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($upcomingApprovedLeaves > 0)
                        <div class="col-6">
                            <div class="border rounded p-3 bg-green-50">
                                <div class="d-flex align-items-center">
                                    <div class="bg-green-100 p-2 rounded-circle me-2">
                                        <i class="fas fa-calendar-check text-success"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-0">Upcoming</p>
                                        <p class="h5 mb-0 fw-bold">{{ $upcomingApprovedLeaves }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Appraisals Table with Delete for Submitted -->
            <div class="card card-moic">
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h3 class="h5 fw-bold moic-navy mb-1">
                                @if($user->user_type === 'supervisor')
                                    Team Appraisals
                                    @if($user->subordinates()->count() > 0)
                                        <span class="text-muted small fw-normal ms-2">
                                            ({{ $user->subordinates()->count() }} members)
                                        </span>
                                    @endif
                                @else
                                    Recent Appraisals
                                @endif
                            </h3>
                            <p class="text-muted small mb-0">
                                Showing {{ min(5, $totalAppraisals) }} of {{ $totalAppraisals }}
                            </p>
                        </div>
                        <a href="{{ route('appraisals.index') }}" class="btn btn-moic btn-sm">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                
                @if($recentAppraisals && $recentAppraisals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-moic mb-0">
                        <thead>
                             <tr>
                                <th>ID</th>
                                @if($user->user_type === 'supervisor')
                                    <th>Employee</th>
                                @endif
                                <th>Period</th>
                                <th class="desktop-only">Job Title</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppraisals as $appraisal)
                            @php
                                $appraisalUser = \App\Models\User::where('employee_number', $appraisal->employee_number)->first();
                            @endphp
                            <tr>
                                <td class="fw-bold moic-navy">#{{ $appraisal->id }}</td>
                                
                                @if($user->user_type === 'supervisor')
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($appraisalUser)
                                            <div class="avatar avatar-gradient me-2">
                                                {{ strtoupper(substr($appraisalUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium small">{{ Str::limit($appraisalUser->name ?? 'Unknown', 15) }}</div>
                                            <div class="text-muted small">
                                                @if($appraisalUser && $appraisalUser->employee_number === $user->employee_number)
                                                    <span class="moic-accent fw-medium">(You)</span>
                                                @else
                                                    {{ Str::limit($appraisal->employee_number, 8) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
                                
                                <td>
                                    <span class="fw-medium desktop-only">{{ $appraisal->period ?? 'Q4 ' . date('Y') }}</span>
                                    <span class="mobile-only">{{ $appraisal->created_at->format('d/m/y') }}</span>
                                    <div class="text-muted small desktop-only">{{ $appraisal->created_at->format('M d, Y') }}</div>
                                </td>
                                
                                <td class="text-muted desktop-only">{{ Str::limit($appraisalUser->job_title ?? ($appraisal->job_title ?? 'N/A'), 20) }}</td>
                                
                                <td>
                                    @php
                                        $statusClasses = [
                                            'draft' => 'badge-draft',
                                            'submitted' => 'badge-submitted',
                                            'approved' => 'badge-approved',
                                            'completed' => 'badge-completed',
                                            'rejected' => 'badge-rejected',
                                            'pending' => 'badge-pending',
                                        ];
                                        $statusClass = $statusClasses[$appraisal->status] ?? 'badge-pending';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        <span class="desktop-only">{{ ucfirst($appraisal->status) }}</span>
                                        <span class="mobile-only">{{ substr($appraisal->status, 0, 3) }}</span>
                                    </span>
                                </td>
                                
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                           class="action-btn bg-primary bg-opacity-10 text-primary"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($appraisal->status === 'draft' && $appraisal->employee_number == $user->employee_number)
                                        <a href="{{ route('appraisals.edit', $appraisal->id) }}" 
                                           class="action-btn bg-success bg-opacity-10 text-success"
                                           title="Edit Draft">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('appraisals.submit', $appraisal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-btn bg-purple bg-opacity-10 text-purple border-0"
                                                    title="Submit"
                                                    onclick="return confirm('Are you sure you want to submit this appraisal?')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn bg-danger bg-opacity-10 text-danger border-0"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this draft?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- NEW: Delete button for SUBMITTED appraisals (before approval) -->
                                        @if($appraisal->status === 'submitted' && $appraisal->employee_number == $user->employee_number && $appraisal->status !== 'approved')
                                        <button type="button" 
                                                class="action-btn bg-danger bg-opacity-10 text-danger border-0"
                                                title="Delete Submitted Appraisal"
                                                onclick="showDeleteConfirmationModal({{ $appraisal->id }}, '{{ $appraisal->period }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        
                                        @if($user->user_type === 'supervisor' && in_array($appraisal->status, ['submitted', 'pending']) && $appraisal->employee_number != $user->employee_number)
                                        <a href="{{ route('supervisor.review', $appraisal->id) }}" 
                                           class="action-btn bg-orange-50 moic-accent"
                                           title="Review">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        @endif
                                    </div>
                                 </td
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($totalAppraisals > 5)
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted small mb-0">
                            Showing 5 of {{ $totalAppraisals }} appraisals
                        </p>
                        <a href="{{ route('appraisals.index') }}" 
                           class="btn btn-outline-primary btn-sm">
                            View all <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endif
                
                @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 bg-blue-50 rounded-circle">
                            <i class="fas fa-file-alt moic-navy fa-3x"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold moic-navy mb-2">No appraisals found</h4>
                    <p class="text-muted mb-4">
                        @if($user->user_type === 'supervisor')
                            No appraisals from your team members yet.
                        @else
                            Start by creating your first appraisal
                        @endif
                    </p>
                    @if($user->user_type !== 'supervisor' || ($user->user_type === 'supervisor' && $user->subordinates()->count() == 0))
                    <a href="{{ route('appraisals.create') }}" class="btn btn-moic">
                        <i class="fas fa-plus me-2"></i> Create First Appraisal
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Mobile Swipe Indicator -->
            <div class="d-sm-none text-center mt-3">
                <div class="d-inline-flex align-items-center bg-dark bg-opacity-10 text-dark px-3 py-1 rounded-pill small">
                    <i class="fas fa-arrows-alt-h me-2"></i>
                    Swipe to see more
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
                                <a href="{{ route('appraisals.create') }}" 
                                   class="btn btn-sm btn-accent" 
                                   style="padding: 0.25rem 1rem; font-size: 0.75rem;">
                                    <i class="fas fa-plus me-1"></i> New Appraisal
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade modal-moic" id="deleteAppraisalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Submitted Appraisal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    </div>
                    <h5 class="text-center mb-3">Are you sure you want to delete this appraisal?</h5>
                    <p class="text-muted text-center mb-3">
                        <strong>Appraisal #<span id="deleteAppraisalId"></span></strong><br>
                        Period: <span id="deleteAppraisalPeriod"></span>
                    </p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Warning:</strong> This appraisal has been <strong>submitted</strong> but not yet approved.<br>
                        Deleting it will permanently remove all data and cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <form id="deleteAppraisalForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i>Yes, Delete Appraisal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-fixed alert-slide d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-1">Success!</h6>
            <p class="mb-0">{{ session('success') }}</p>
            @if(session('info'))
            <p class="small mb-0 mt-1">{{ session('info') }}</p>
            @endif
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

        // Mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Touch device detection
            if ('ontouchstart' in window || navigator.maxTouchPoints) {
                document.body.classList.add('touch-device');
            }
            
            // Load supervisors if user is supervisor
            @if(isset($user) && $user->user_type === 'supervisor')
                loadSupervisors();
            @endif
        });

        // Function to show delete confirmation modal for submitted appraisals
        function showDeleteConfirmationModal(appraisalId, period) {
            document.getElementById('deleteAppraisalId').textContent = appraisalId;
            document.getElementById('deleteAppraisalPeriod').textContent = period;
            const form = document.getElementById('deleteAppraisalForm');
            form.action = '/appraisals/' + appraisalId;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteAppraisalModal'));
            modal.show();
        }

        // Load supervisors function
        function loadSupervisors() {
            const supervisorSelect = document.getElementById('manager_id');
            const loadingDiv = document.getElementById('supervisorLoading');
            
            if (!supervisorSelect || !loadingDiv) return;
            
            loadingDiv.style.display = 'block';
            supervisorSelect.disabled = true;
            
            fetch('{{ route("profile.supervisors") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingDiv.style.display = 'none';
                supervisorSelect.disabled = false;
                
                const currentValue = supervisorSelect.value;
                supervisorSelect.innerHTML = '<option value="">-- Select your supervisor --</option>';
                
                if (data.success && data.supervisors && data.supervisors.length > 0) {
                    data.supervisors.forEach(supervisor => {
                        const option = document.createElement('option');
                        option.value = supervisor.employee_number;
                        option.textContent = supervisor.display_name || `${supervisor.name} (${supervisor.employee_number})`;
                        
                        if (currentValue && currentValue === supervisor.employee_number) {
                            option.selected = true;
                        }
                        
                        supervisorSelect.appendChild(option);
                    });
                } else {
                    supervisorSelect.innerHTML = '<option value="">No supervisors available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading supervisors:', error);
                loadingDiv.style.display = 'none';
                supervisorSelect.disabled = false;
                supervisorSelect.innerHTML = '<option value="">Error loading supervisors</option>';
                
                // Show user-friendly error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-2';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    <strong>Error!</strong> Could not load supervisors. Please refresh the page.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                loadingDiv.parentNode.insertBefore(alertDiv, loadingDiv.nextSibling);
                
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            });
        }

        // Supervisor form submission
        const supervisorForm = document.getElementById('supervisorForm');
        if (supervisorForm) {
            supervisorForm.addEventListener('submit', function(e) {
                const select = document.getElementById('manager_id');
                const submitBtn = document.getElementById('submitSupervisorBtn');
                const loadingSpan = document.getElementById('supervisorLoadingBtn');
                
                if (!select || !select.value) {
                    e.preventDefault();
                    alert('Please select a supervisor from the list.');
                    select?.focus();
                    return false;
                }
                
                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                }
                if (loadingSpan) loadingSpan.style.display = 'inline-block';
                
                // Optional confirmation
                if (!confirm('Save this as your supervisor?')) {
                    e.preventDefault();
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Supervisor';
                    }
                    if (loadingSpan) loadingSpan.style.display = 'none';
                    return false;
                }
                
                return true;
            });
        }

        // Handle responsive tables
        function checkTableOverflow() {
            const tables = document.querySelectorAll('.table-responsive');
            tables.forEach(table => {
                if (table.scrollWidth > table.clientWidth) {
                    table.classList.add('has-overflow');
                } else {
                    table.classList.remove('has-overflow');
                }
            });
        }

        window.addEventListener('load', checkTableOverflow);
        window.addEventListener('resize', checkTableOverflow);

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