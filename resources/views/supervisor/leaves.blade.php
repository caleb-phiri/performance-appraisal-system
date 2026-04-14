{{-- resources/views/supervisor/leaves.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Leave Management - MOIC Supervisor Portal</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Font Awesome 6 (Production) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Custom Production CSS -->
    <style>
        /* MOIC Brand Colors */
        :root {
            --moic-navy: #110484;
            --moic-navy-light: #3328a5;
            --moic-navy-dark: #0a0463;
            --moic-accent: #e7581c;
            --moic-accent-light: #ff6b2d;
            --moic-accent-dark: #cc4a15;
            --moic-blue: #1a0c9e;
            --moic-blue-light: #2d1fd1;
            --moic-gradient: linear-gradient(135deg, var(--moic-navy), var(--moic-blue));
            --moic-gradient-accent: linear-gradient(135deg, var(--moic-accent), var(--moic-accent-light));
        }
        
        /* Base styles */
        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background-color: #f8fafc;
        }
        
        /* Custom Color Classes */
        .moic-navy { color: var(--moic-navy) !important; }
        .moic-navy-bg { background-color: var(--moic-navy) !important; }
        .moic-accent { color: var(--moic-accent) !important; }
        .moic-accent-bg { background-color: var(--moic-accent) !important; }
        
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
        
        /* MOIC Buttons */
        .btn-moic {
            background: var(--moic-gradient);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        .btn-moic:hover {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-moic:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(17, 4, 132, 0.2);
        }
        
        .btn-accent {
            background: var(--moic-gradient-accent);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--moic-accent-light), #ff8d5c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-accent:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(231, 88, 28, 0.2);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 2px solid var(--moic-navy);
            color: var(--moic-navy);
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* Card Styling */
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        
        .card-moic:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(17, 4, 132, 0.1), 0 8px 10px -6px rgba(17, 4, 132, 0.1);
        }
        
        /* Stat Cards */
        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background-color: white;
            padding: 1.25rem 1rem;
            height: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
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
        
        /* Status Badges */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        
        .status-pending { 
            background-color: #fef3c7; 
            color: #92400e;
            border-color: #fcd34d;
        }
        .status-approved { 
            background-color: #d1fae5; 
            color: #065f46;
            border-color: #6ee7b7;
        }
        .status-rejected { 
            background-color: #fee2e2; 
            color: #991b1b;
            border-color: #fecaca;
        }
        .status-cancelled { 
            background-color: #f3f4f6; 
            color: #374151;
            border-color: #d1d5db;
        }
        
        /* Filter Tags */
        .filter-tag {
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 1rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid transparent;
        }
        
        .filter-tag:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        
        .filter-tag a {
            color: inherit;
            opacity: 0.7;
            margin-left: 0.5rem;
            text-decoration: none;
        }
        
        .filter-tag a:hover {
            opacity: 1;
        }
        
        /* Table Styling */
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
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
            border-radius: 0.5rem;
        }
        
        .table-responsive::-webkit-scrollbar {
            height: 6px;
            background-color: #f1f1f1;
            border-radius: 10px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 10px;
        }
        
        /* Form Controls */
        .form-control-moic {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s;
            width: 100%;
            font-size: 0.875rem;
            background-color: white;
        }
        
        .form-control-moic:focus {
            border-color: var(--moic-accent);
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        .form-select-moic {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            transition: all 0.3s;
            width: 100%;
            font-size: 0.875rem;
            background-color: white;
            cursor: pointer;
        }
        
        .form-select-moic:focus {
            border-color: var(--moic-accent);
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        /* Pagination */
        .pagination-custom {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        
        .page-item-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .page-item-custom:hover:not(.active):not(.disabled) {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            transform: translateY(-1px);
            text-decoration: none;
        }
        
        .page-item-custom.active {
            background: var(--moic-gradient);
            color: white;
            border-color: var(--moic-navy);
        }
        
        .page-item-custom.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f3f4f6;
            pointer-events: none;
        }
        
        /* Modal */
        .modal-content-custom {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Action Buttons */
        .action-btn {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .action-btn.approve {
            background-color: #d1fae5;
            color: #166534;
        }
        
        .action-btn.approve:hover {
            background-color: #bbf7d0;
        }
        
        .action-btn.reject {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .action-btn.reject:hover {
            background-color: #fecaca;
        }
        
        .action-btn.view {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .action-btn.view:hover {
            background-color: #bfdbfe;
        }
        
        /* Avatar */
        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            background: var(--moic-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Alert Messages */
        .alert-fixed {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1050;
            min-width: 20rem;
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
        }
        
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
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
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
        
        /* Background Utilities */
        .bg-blue-50 { background-color: #eff6ff; }
        .bg-green-50 { background-color: #f0fdf4; }
        .bg-yellow-50 { background-color: #fefce8; }
        .bg-red-50 { background-color: #fef2f2; }
        .bg-purple-50 { background-color: #faf5ff; }
        .bg-indigo-50 { background-color: #eef2ff; }
        
        /* Text Utilities */
        .text-blue-800 { color: #1e40af; }
        .text-green-800 { color: #166534; }
        .text-yellow-800 { color: #92400e; }
        .text-red-800 { color: #991b1b; }
        .text-purple-800 { color: #6b21a8; }
        
        /* Container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem 0.75rem;
            }
            
            .desktop-only {
                display: none !important;
            }
            
            .mobile-only {
                display: block !important;
            }
            
            .btn-moic, .btn-accent {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            .table-moic thead th {
                padding: 0.75rem;
                font-size: 0.7rem;
            }
            
            .table-moic tbody td {
                padding: 0.75rem;
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
            .container-custom {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .action-btn {
                width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
            
            .card-moic:hover,
            .stat-card:hover,
            .btn-moic:hover,
            .btn-accent:hover {
                transform: none;
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .gradient-header {
                background: #110484 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .btn-moic, .btn-accent {
                background: #110484 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Header with Animated Gradient -->
    <div class="gradient-header text-white no-print">
        <div class="container-custom py-2">
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
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" loading="lazy">
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
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo" loading="lazy">
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
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">Leave Management</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">Supervisor Portal</p>
                        </div>
                    </div>
                    
                    <!-- Mobile Title -->
                    <div class="mobile-only ms-2">
                        <h1 class="h6 mb-0 fw-bold">Leave Management</h1>
                        <p class="mb-0 text-white-50 small">Supervisor</p>
                    </div>
                </div>

                <!-- User Section -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Supervisor Dashboard Link (Desktop) -->
                    <div class="desktop-only">
                        <a href="{{ route('supervisor.dashboard') }}" class="btn btn-accent btn-sm">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </div>
                    
                    <!-- User Info (Desktop) -->
                    <div class="desktop-only d-flex flex-column align-items-end">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-success rounded-circle me-1" style="width: 0.5rem; height: 0.5rem;"></div>
                            <span class="fw-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.75rem;">{{ Auth::user()->employee_number }}</span>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <div class="dropdown mobile-only">
                        <button class="btn btn-outline-light btn-sm" type="button" id="mobileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mobile-menu" aria-labelledby="mobileMenu">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                                        <div class="small text-muted">{{ Auth::user()->employee_number }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('supervisor.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 moic-accent"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('supervisor.leaves') }}">
                                    <i class="fas fa-calendar-alt me-2 moic-navy"></i> Leave Management
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
                    
                    <!-- Desktop Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="desktop-only">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Message Display -->
    @if(session('error'))
    <div class="alert alert-danger alert-fixed alert-slide d-flex align-items-center">
        <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-1">Error!</h6>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Success Message Display -->
    @if(session('success'))
    <div class="alert alert-success alert-fixed alert-slide d-flex align-items-center">
        <i class="fas fa-check-circle me-3 fa-lg"></i>
        <div>
            <h6 class="mb-1">Success!</h6>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-custom">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 fw-bold moic-navy mb-1">Team Leave Management</h2>
                    <p class="text-muted small mb-0">Review and manage leave requests from your team members</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="exportLeaves()" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn btn-moic btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Leave Statistics -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-blue-50">
                                <i class="fas fa-calendar-alt moic-navy"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Total Leaves</p>
                                <p class="h4 mb-0 fw-bold">{{ $leaveStats['total'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card border-start border-4 border-warning">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-yellow-50">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Pending</p>
                                <p class="h4 mb-0 fw-bold text-warning">{{ $leaveStats['pending'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card border-start border-4 border-success">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-green-50">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Approved</p>
                                <p class="h4 mb-0 fw-bold text-success">{{ $leaveStats['approved'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card border-start border-4 border-danger">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-red-50">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Rejected</p>
                                <p class="h4 mb-0 fw-bold text-danger">{{ $leaveStats['rejected'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-lg">
                    <div class="stat-card border-start border-4 border-purple">
                        <div class="d-flex align-items-center">
                            <div class="rounded p-2 me-3 bg-purple-50">
                                <i class="fas fa-calendar-day text-purple-800"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Avg Days</p>
                                <p class="h4 mb-0 fw-bold moic-navy">{{ $leaveStats['avg_days'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card card-moic mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('supervisor.leaves') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Employee Name Filter -->
                            <div class="col-md-4 col-lg">
                                <label class="form-label small fw-medium text-muted mb-1">Employee Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" name="employee_name" value="{{ request('employee_name') }}" 
                                           placeholder="Search by name..." 
                                           class="form-control form-control-moic border-2">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-4 col-lg">
                                <label class="form-label small fw-medium text-muted mb-1">Status</label>
                                <select name="status" class="form-select form-select-moic">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <!-- Leave Type Filter -->
                            <div class="col-md-4 col-lg">
                                <label class="form-label small fw-medium text-muted mb-1">Leave Type</label>
                                <select name="type" class="form-select form-select-moic">
                                    <option value="">All Types</option>
                                    <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                                    <option value="sick" {{ request('type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                    <option value="maternity" {{ request('type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                    <option value="paternity" {{ request('type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                                    <option value="study" {{ request('type') == 'study' ? 'selected' : '' }}>Study Leave</option>
                                    <option value="compassionate" {{ request('type') == 'compassionate' ? 'selected' : '' }}>Compassionate Leave</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div class="col-md-6 col-lg">
                                <label class="form-label small fw-medium text-muted mb-1">Date Range</label>
                                <div class="d-flex gap-2">
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                           class="form-control form-control-moic border-2">
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                           class="form-control form-control-moic border-2">
                                </div>
                            </div>
                        </div>

                        <!-- Search and Actions -->
                        <div class="row mt-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           placeholder="Search by employee number, reason..." 
                                           class="form-control form-control-moic border-2">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-accent flex-grow-1">
                                        <i class="fas fa-filter me-2"></i> Apply
                                    </button>
                                    <a href="{{ route('supervisor.leaves') }}" class="btn btn-moic flex-grow-1">
                                        <i class="fas fa-redo me-2"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Per Page Selector -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <label for="perPage" class="text-muted small me-2">Show entries:</label>
                                    <select name="per_page" id="perPage" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters -->
                    @php
                        $hasActiveFilters = request('employee_name') || request('status') || request('type') || request('search') || request('start_date') || request('end_date');
                    @endphp
                    
                    @if($hasActiveFilters)
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="text-muted small me-2">Active filters:</span>
                            
                            @foreach(['employee_name', 'status', 'type', 'search', 'start_date', 'end_date'] as $filter)
                                @if(request($filter))
                                <span class="filter-tag 
                                    @if($filter == 'status' && request('status') == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($filter == 'status' && request('status') == 'approved') bg-green-100 text-green-800
                                    @elseif($filter == 'status' && request('status') == 'rejected') bg-red-100 text-red-800
                                    @elseif($filter == 'type') bg-purple-100 text-purple-800
                                    @elseif(in_array($filter, ['start_date', 'end_date'])) bg-indigo-100 text-indigo-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    <i class="fas 
                                        @if($filter == 'employee_name') fa-user
                                        @elseif($filter == 'status') fa-tag
                                        @elseif($filter == 'type') fa-clock
                                        @elseif($filter == 'search') fa-search
                                        @elseif(in_array($filter, ['start_date', 'end_date'])) fa-calendar
                                        @endif
                                    me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $filter)) }}: 
                                    @if(in_array($filter, ['start_date', 'end_date']))
                                        {{ \Carbon\Carbon::parse(request($filter))->format('M d, Y') }}
                                    @else
                                        {{ ucfirst(request($filter)) }}
                                    @endif
                                    <a href="{{ route('supervisor.leaves', request()->except([$filter, 'page'])) }}" class="ms-2">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                                @endif
                            @endforeach
                            
                            <a href="{{ route('supervisor.leaves') }}" class="text-danger small ms-2 text-decoration-none">
                                <i class="fas fa-times-circle me-1"></i> Clear all
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Leaves Table Card -->
            <div class="card card-moic">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-list me-2"></i> Leave Requests
                            </h3>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-database me-1"></i> Total: {{ $leaves->total() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-moic mb-0">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Details</th>
                                <th>Status</th>
                                <th>Date Applied</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-medium small">{{ $leave->user->name ?? 'Unknown' }}</div>
                                            <div class="text-muted small">{{ $leave->employee_number ?? 'N/A' }}</div>
                                            <div class="text-muted small desktop-only">{{ $leave->user->department ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium moic-navy small">
                                        @php
                                            $leaveTypes = [
                                                'annual' => 'Annual Leave',
                                                'sick' => 'Sick Leave',
                                                'maternity' => 'Maternity Leave',
                                                'paternity' => 'Paternity Leave',
                                                'study' => 'Study Leave',
                                                'compassionate' => 'Compassionate Leave',
                                                'other' => 'Other Leave',
                                            ];
                                        @endphp
                                        {{ $leaveTypes[$leave->leave_type] ?? ucfirst($leave->leave_type) }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $leave->total_days }} day(s)
                                    </div>
                                    @if($leave->reason)
                                    <div class="text-muted small desktop-only" title="{{ $leave->reason }}">
                                        <i class="fas fa-comment me-1"></i>
                                        {{ Str::limit($leave->reason, 30) }}
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($leave->status) {
                                            'pending' => 'status-pending',
                                            'approved' => 'status-approved',
                                            'rejected' => 'status-rejected',
                                            'cancelled' => 'status-cancelled',
                                            default => 'status-pending'
                                        };
                                        $statusIcon = match($leave->status) {
                                            'pending' => 'fa-clock',
                                            'approved' => 'fa-check',
                                            'rejected' => 'fa-times',
                                            'cancelled' => 'fa-ban',
                                            default => 'fa-clock'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        <span>{{ ucfirst($leave->status) }}</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if($leave->status == 'pending')
                                            <button onclick="showApproveModal({{ $leave->id }})" 
                                                    class="action-btn approve"
                                                    title="Approve">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <button onclick="showRejectModal({{ $leave->id }})" 
                                                    class="action-btn reject"
                                                    title="Reject">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                        
                                        <button onclick="viewLeaveDetails({{ $leave->id }})" 
                                                class="action-btn view"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                        <p class="h5 fw-normal mb-2">No leave requests found</p>
                                        <p class="small">
                                            @if($hasActiveFilters)
                                                Try changing your filters
                                            @else
                                                No leave requests have been submitted yet
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($leaves->hasPages() || $leaves->total() > $leaves->perPage())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                        <div class="text-muted small">
                            Showing <span class="fw-medium">{{ $leaves->firstItem() }}</span> 
                            to <span class="fw-medium">{{ $leaves->lastItem() }}</span> 
                            of <span class="fw-medium">{{ $leaves->total() }}</span> entries
                        </div>
                        
                        <div class="pagination-custom">
                            @if($leaves->onFirstPage())
                                <span class="page-item-custom disabled">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $leaves->previousPageUrl() }}" class="page-item-custom">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif
                            
                            @for($i = 1; $i <= $leaves->lastPage(); $i++)
                                @if($i == $leaves->currentPage())
                                    <span class="page-item-custom active">{{ $i }}</span>
                                @else
                                    <a href="{{ $leaves->url($i) }}" class="page-item-custom">{{ $i }}</a>
                                @endif
                            @endfor
                            
                            @if($leaves->hasMorePages())
                                <a href="{{ $leaves->nextPageUrl() }}" class="page-item-custom">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="page-item-custom disabled">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
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

            <!-- Footer -->
            <footer class="mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded me-3">
                                <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" loading="lazy">
                            </div>
                            <div>
                                <p class="text-muted small mb-0">MOIC Leave Management System © {{ date('Y') }}</p>
                                <p class="text-muted small">Version 1.0.0 powered by SmartWave Solutions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap justify-content-lg-end gap-4">
                            <a href="{{ route('supervisor.dashboard') }}" class="text-decoration-none text-muted small">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                            </a>
                            <a href="#" class="text-decoration-none text-muted small">
                                <i class="fas fa-question-circle me-1"></i> Help
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-success mb-1" id="approveModalLabel">Approve Leave Request</h5>
                            <p class="text-muted small mb-0">Add any comments (optional)</p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="approveComments" class="form-label fw-medium text-muted small">Comments</label>
                        <textarea id="approveComments" rows="3" 
                                  class="form-control form-control-moic"
                                  placeholder="Add any comments about this approval..."></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" onclick="submitApproval()" class="btn btn-success">
                            <span class="spinner-border spinner-border-sm d-none" role="status" id="approveSpinner"></span>
                            <span class="btn-text"><i class="fas fa-check me-2"></i> Approve</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="fas fa-times-circle text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-danger mb-1" id="rejectModalLabel">Reject Leave Request</h5>
                            <p class="text-muted small mb-0">Please provide a reason for rejection</p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="mb-4">
                        <label for="rejectReason" class="form-label fw-medium text-muted small">
                            Reason for Rejection <span class="text-danger">*</span>
                        </label>
                        <textarea id="rejectReason" rows="4" 
                                  class="form-control form-control-moic"
                                  placeholder="Enter the reason for rejecting this leave request..."
                                  required></textarea>
                        <div class="invalid-feedback">Please provide a reason</div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" onclick="submitRejection()" class="btn btn-danger">
                            <span class="spinner-border spinner-border-sm d-none" role="status" id="rejectSpinner"></span>
                            <span class="btn-text"><i class="fas fa-times me-2"></i> Reject Leave</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden inputs -->
    <input type="hidden" id="currentLeaveId" value="">

    <!-- Bootstrap JS Bundle (Production) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        (function() {
            'use strict';
            
            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            // Modal instances
            let approveModal, rejectModal;
            
            // Initialize on DOM load
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap modals
                approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
                rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
                
                // Auto-submit filters on change
                const autoSubmitElements = ['status', 'type', 'start_date', 'end_date', 'perPage'];
                autoSubmitElements.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('change', function() {
                            document.getElementById('filterForm').submit();
                        });
                    }
                });
                
                // Auto-hide alerts
                setTimeout(() => {
                    document.querySelectorAll('.alert-fixed').forEach(alert => {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            });
            
            // View leave details
            window.viewLeaveDetails = function(leaveId) {
                window.open(`/leave/${leaveId}`, '_blank');
            };
            
            // Show approve modal
            window.showApproveModal = function(leaveId) {
                document.getElementById('currentLeaveId').value = leaveId;
                document.getElementById('approveComments').value = '';
                approveModal.show();
            };
            
            // Show reject modal
            window.showRejectModal = function(leaveId) {
                document.getElementById('currentLeaveId').value = leaveId;
                document.getElementById('rejectReason').value = '';
                document.getElementById('rejectReason').classList.remove('is-invalid');
                rejectModal.show();
            };
            
            // Submit approval
            window.submitApproval = async function() {
                const leaveId = document.getElementById('currentLeaveId').value;
                const comments = document.getElementById('approveComments').value;
                const approveBtn = document.querySelector('#approveModal .btn-success');
                const spinner = document.getElementById('approveSpinner');
                const btnText = approveBtn.querySelector('.btn-text');
                
                if (!leaveId) {
                    showToast('error', 'No leave selected');
                    return;
                }
                
                // Show loading
                spinner.classList.remove('d-none');
                btnText.style.opacity = '0.5';
                approveBtn.disabled = true;
                
                try {
                    const response = await fetch(`/supervisor/leaves/${leaveId}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ remarks: comments })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        approveModal.hide();
                        showToast('success', data.message || 'Leave approved successfully');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.message || 'Failed to approve leave');
                        spinner.classList.add('d-none');
                        btnText.style.opacity = '1';
                        approveBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while approving the leave');
                    spinner.classList.add('d-none');
                    btnText.style.opacity = '1';
                    approveBtn.disabled = false;
                }
            };
            
            // Submit rejection
            window.submitRejection = async function() {
                const leaveId = document.getElementById('currentLeaveId').value;
                const reason = document.getElementById('rejectReason').value;
                const rejectBtn = document.querySelector('#rejectModal .btn-danger');
                const spinner = document.getElementById('rejectSpinner');
                const btnText = rejectBtn.querySelector('.btn-text');
                
                if (!reason.trim()) {
                    document.getElementById('rejectReason').classList.add('is-invalid');
                    return;
                }
                
                document.getElementById('rejectReason').classList.remove('is-invalid');
                
                // Show loading
                spinner.classList.remove('d-none');
                btnText.style.opacity = '0.5';
                rejectBtn.disabled = true;
                
                try {
                    const response = await fetch(`/supervisor/leaves/${leaveId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ remarks: reason })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        rejectModal.hide();
                        showToast('success', data.message || 'Leave rejected successfully');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.message || 'Failed to reject leave');
                        spinner.classList.add('d-none');
                        btnText.style.opacity = '1';
                        rejectBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while rejecting the leave');
                    spinner.classList.add('d-none');
                    btnText.style.opacity = '1';
                    rejectBtn.disabled = false;
                }
            };
            
            // Export leaves
            window.exportLeaves = function() {
                showToast('info', 'Export feature coming soon!');
            };
            
            // Toast notification
            function showToast(type, message) {
                // Remove existing toasts
                document.querySelectorAll('.alert-fixed').forEach(toast => toast.remove());
                
                const toast = document.createElement('div');
                toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-fixed d-flex align-items-center`;
                
                toast.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-3 fa-lg"></i>
                    <div>
                        <h6 class="mb-1">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</h6>
                        <p class="mb-0">${message}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()" aria-label="Close"></button>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.5s';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 5000);
            }
            
            // Close modals on Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (approveModal?._isShown) approveModal.hide();
                    if (rejectModal?._isShown) rejectModal.hide();
                }
            });
        })();
    </script>
</body>
</html>