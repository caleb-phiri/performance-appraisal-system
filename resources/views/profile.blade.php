<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>My Profile - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Meta CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
        }
        
        /* Base styles */
        html {
            font-size: 16px;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
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
        }
        
        .btn-moic:hover {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-accent {
            background: var(--moic-gradient-accent);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
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
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
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
        
        /* Card Styling */
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
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
        
        /* Avatar */
        .avatar-large {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 2rem;
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        /* Progress Circle */
        .progress-circle {
            transform: rotate(-90deg);
        }
        
        .progress-circle-bg {
            stroke: #e5e7eb;
            stroke-width: 3;
            fill: none;
        }
        
        .progress-circle-fill {
            stroke: #10b981;
            stroke-width: 3;
            fill: none;
            stroke-linecap: round;
            transition: stroke-dasharray 0.3s ease;
        }
        
        /* Form Styles */
        .form-control-moic {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .form-control-moic:focus {
            outline: none;
            border-color: var(--moic-navy);
            box-shadow: 0 0 0 3px rgba(17, 4, 132, 0.1);
        }
        
        .form-control-moic.border-error {
            border-color: #ef4444;
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
        
        /* Alert styling */
        .alert-fixed {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1050;
            min-width: 20rem;
            border-radius: 0.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Animation */
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
        
        /* ===== APPROVAL CHAIN STYLES - ENHANCED ===== */
        .approval-chain-container {
            background: linear-gradient(135deg, rgba(17, 4, 132, 0.02), rgba(231, 88, 28, 0.02));
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: 1px solid rgba(17, 4, 132, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .approval-chain-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--moic-navy), var(--moic-accent));
        }
        
        .approval-chain-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px dashed rgba(17, 4, 132, 0.1);
        }
        
        .approval-chain-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--moic-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(17, 4, 132, 0.2);
        }
        
        .chain-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 1.5rem;
        }
        
        .chain-item:last-child {
            margin-bottom: 0;
        }
        
        .chain-item::before {
            content: '';
            position: absolute;
            left: 1.1rem;
            top: 1.8rem;
            bottom: -1.5rem;
            width: 2px;
            background: linear-gradient(to bottom, var(--moic-navy), var(--moic-accent));
            opacity: 0.3;
        }
        
        .chain-item:last-child::before {
            display: none;
        }
        
        .chain-marker {
            position: absolute;
            left: 0;
            top: 0.25rem;
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .chain-marker.level-1 {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .chain-marker.level-2 {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .chain-marker.level-3 {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }
        
        .chain-marker.level-4 {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .chain-content {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }
        
        .chain-content:hover {
            border-color: var(--moic-navy);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.08);
            transform: translateX(4px);
        }
        
        .chain-role-badge {
            display: inline-block;
            padding: 0.2rem 0.75rem;
            background: rgba(17, 4, 132, 0.05);
            color: var(--moic-navy);
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            border: 1px solid rgba(17, 4, 132, 0.1);
        }
        
        .chain-user-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        
        .chain-user-details {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .chain-user-details i {
            color: var(--moic-accent);
            width: 1rem;
        }
        
        .chain-connector {
            position: absolute;
            left: 1rem;
            bottom: -1.2rem;
            color: var(--moic-accent);
            font-size: 0.8rem;
            opacity: 0.5;
            z-index: 1;
        }
        
        .empty-chain {
            text-align: center;
            padding: 2rem;
            background: rgba(17, 4, 132, 0.02);
            border-radius: 0.75rem;
            border: 2px dashed rgba(17, 4, 132, 0.1);
        }
        
        .empty-chain-icon {
            width: 4rem;
            height: 4rem;
            background: rgba(17, 4, 132, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: var(--moic-navy);
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
        
        /* Responsive breakpoints */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .avatar-large {
                width: 4rem;
                height: 4rem;
                font-size: 1.5rem;
            }
            
            .desktop-only {
                display: none !important;
            }
            
            .mobile-only {
                display: block !important;
            }
            
            .chain-item {
                padding-left: 2.5rem;
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
        
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
            
            .card-moic:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header with Animated Gradient (Matching Dashboard) -->
    <div class="gradient-header text-white">
        <div class="container-custom py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Logo Section (Matching Dashboard) -->
                <div class="d-flex align-items-center">
                    <div class="logo-container me-3">
                        <div class="logo-inner">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                    </div>
                                    <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                </div>
                                
                                <div class="position-relative">
                                    <div class="rounded-circle bg-gradient-to-br from-[#110484] to-[#e7581c]" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-handshake text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="position-absolute top-100 start-50 translate-middle mt-1">
                                        <span class="status-badge bg-white moic-navy">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo">
                                    </div>
                                    <span class="status-badge moic-accent-bg text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h1 class="h5 mb-0 fw-bold">My Profile</h1>
                        <p class="mb-0 text-white-50 small">Manage your account information</p>
                    </div>
                </div>

                <!-- Back to Dashboard -->
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="py-4">
        <div class="container-custom">
            <!-- Success & Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-slide d-flex align-items-center mb-4">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div>
                        <h6 class="mb-1">Success!</h6>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-slide d-flex align-items-center mb-4">
                    <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                    <div>
                        <h6 class="mb-1">Error!</h6>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-slide mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                        <h6 class="mb-0">Please fix the following errors:</h6>
                    </div>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Header Card -->
            <div class="card card-moic mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-large avatar-gradient">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="h4 fw-bold moic-navy mb-2">{{ $user->name }}</h2>
                            <div class="row g-3 mb-3">
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-id-badge me-2 text-muted"></i>
                                        <span class="text-muted">ID:</span>
                                        <span class="fw-bold ms-1">{{ $user->employee_number }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-briefcase me-2 text-muted"></i>
                                        <span class="text-muted">Job Title:</span>
                                        <span class="fw-bold ms-1">{{ $user->job_title ?? 'Not specified' }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-building me-2 text-muted"></i>
                                        <span class="text-muted">Dept:</span>
                                        <span class="fw-bold ms-1">{{ $user->department ?? 'Not specified' }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- User Badges -->
                            <div class="d-flex flex-wrap gap-2">
                                @if($user->user_type === 'supervisor')
                                <span class="user-badge bg-purple-50 text-purple-800">
                                    <i class="fas fa-user-tie me-1"></i> Supervisor
                                    @if($user->supervisor_level)
                                        (Level {{ $user->supervisor_level }})
                                    @endif
                                </span>
                                @else
                                <span class="user-badge bg-blue-50 text-blue-800">
                                    <i class="fas fa-user me-1"></i> Employee
                                </span>
                                @endif
                                
                                @if($user->workstation_type === 'hq')
                                <span class="user-badge bg-blue-50 moic-navy">
                                    <i class="fas fa-building me-1"></i> Headquarters
                                </span>
                                @elseif($user->workstation_type === 'toll_plaza')
                                <span class="user-badge bg-green-50 text-green-800">
                                    <i class="fas fa-road me-1"></i> Toll Plaza
                                </span>
                                @endif
                                
                                @if($user->toll_plaza)
                                <span class="user-badge bg-orange-50 moic-accent">
                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                    @if($user->toll_plaza == 'kafulafuta')
                                        Kafulafuta Toll Plaza
                                    @elseif($user->toll_plaza == 'abram_zayoni_mokola')
                                        Abram Zayoni Mokola Toll Plaza
                                    @elseif($user->toll_plaza == 'katuba')
                                        Katuba Toll Plaza
                                    @elseif($user->toll_plaza == 'manyumbi')
                                        Manyumbi Toll Plaza
                                    @elseif($user->toll_plaza == 'konkola')
                                        Konkola Toll Plaza
                                    @else
                                        {{ Str::limit($user->toll_plaza, 20) }}
                                    @endif
                                </span>
                                @endif
                                
                                @if($user->is_onboarded)
                                <span class="user-badge bg-green-50 text-green-800">
                                    <i class="fas fa-check-circle me-1"></i> Profile Complete
                                </span>
                                @else
                                <span class="user-badge bg-yellow-50 text-yellow-800">
                                    <i class="fas fa-exclamation-circle me-1"></i> Profile Incomplete
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column: Personal Information -->
                <div class="col-lg-8">
                    <!-- Personal Information Card -->
                    <div class="card card-moic mb-4">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-user-circle me-2"></i>Personal Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <!-- Basic Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Full Name *</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                                   class="form-control form-control-moic @error('name') border-error @enderror"
                                                   required>
                                            @error('name')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Employee ID *</label>
                                            <input type="text" name="employee_number" value="{{ old('employee_number', $user->employee_number) }}" 
                                                   class="form-control form-control-moic @error('employee_number') border-error @enderror"
                                                   required>
                                            @error('employee_number')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Email Address *</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                                   class="form-control form-control-moic @error('email') border-error @enderror"
                                                   required>
                                            @error('email')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Job Information -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Job Title *</label>
                                            <select name="job_title" id="job_title" class="form-control-moic @error('job_title') border-error @enderror" required>
                                                <option value="">Select Job Title</option>
                                                <option value="Plaza Manager" {{ old('job_title', $user->job_title) == 'Plaza Manager' ? 'selected' : '' }}>Plaza Manager</option>
                                                <option value="Admin Clerk" {{ old('job_title', $user->job_title) == 'Admin Clerk' ? 'selected' : '' }}>Admin Clerk</option>
                                                <option value="E&M Technician" {{ old('job_title', $user->job_title) == 'E&M Technician' ? 'selected' : '' }}>E&M Technician</option>
                                                <option value="Shift Manager" {{ old('job_title', $user->job_title) == 'Shift Manager' ? 'selected' : '' }}>Shift Manager</option>
                                                <option value="Senior Toll Collector" {{ old('job_title', $user->job_title) == 'Senior Toll Collector' ? 'selected' : '' }}>Senior Toll Collector</option>
                                                <option value="Toll Collector" {{ old('job_title', $user->job_title) == 'Toll Collector' ? 'selected' : '' }}>Toll Collector</option>
                                                <option value="TCE Technician" {{ old('job_title', $user->job_title) == 'TCE Technician' ? 'selected' : '' }}>TCE Technician</option>
                                                <option value="Route Patrol Driver" {{ old('job_title', $user->job_title) == 'Route Patrol Driver' ? 'selected' : '' }}>Route Patrol Driver</option>
                                                <option value="Plaza Attendant" {{ old('job_title', $user->job_title) == 'Plaza Attendant' ? 'selected' : '' }}>Plaza Attendant</option>
                                                <option value="Lane Attendant" {{ old('job_title', $user->job_title) == 'Lane Attendant' ? 'selected' : '' }}>Lane Attendant</option>
                                                <option value="HR Assistant" {{ old('job_title', $user->job_title) == 'HR Assistant' ? 'selected' : '' }}>HR Assistant</option>
                                                <option value="Admin Manager" {{ old('job_title', $user->job_title) == 'Admin Manager' ? 'selected' : '' }}>Admin Manager</option>
                                                <option value="Trainer" {{ old('job_title', $user->job_title) == 'Trainer' ? 'selected' : '' }}>Trainer</option>
                                                <option value="Senior Trainer" {{ old('job_title', $user->job_title) == 'Senior Trainer' ? 'selected' : '' }}>Senior Trainer</option>
                                                <option value="Senior TCE" {{ old('job_title', $user->job_title) == 'Senior TCE' ? 'selected' : '' }}>Senior TCE</option>
                                                <option value="Media and Customer Coordinator" {{ old('job_title', $user->job_title) == 'Media and Customer Coordinator' ? 'selected' : '' }}>Media and Customer Coordinator</option>
                                                <option value="Verification Clerk" {{ old('job_title', $user->job_title) == 'Verification Clerk' ? 'selected' : '' }}>Verification Clerk</option>
                                                <option value="Other" {{ old('job_title', $user->job_title) == 'Other' ? 'selected' : '' }}>Other (Specify below)</option>
                                            </select>
                                            
                                            <div id="otherJobTitleContainer" class="mt-2 {{ old('job_title', $user->job_title) == 'Other' ? '' : 'd-none' }}">
                                                <input type="text" name="other_job_title" 
                                                       value="{{ old('other_job_title', $user->job_title) }}"
                                                       class="form-control form-control-moic"
                                                       placeholder="Enter your job title">
                                            </div>
                                            
                                            @error('job_title')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Department *</label>
                                            <select name="department" class="form-control-moic @error('department') border-error @enderror" required>
                                                <option value="">Select Department</option>
                                                <option value="operations" {{ old('department', $user->department) == 'operations' ? 'selected' : '' }}>Operations</option>
                                                <option value="finance" {{ old('department', $user->department) == 'finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="hr" {{ old('department', $user->department) == 'hr' ? 'selected' : '' }}>Human Resources</option>
                                                <option value="it" {{ old('department', $user->department) == 'it' ? 'selected' : '' }}>IT</option>
                                                <option value="admin" {{ old('department', $user->department) == 'admin' ? 'selected' : '' }}>Administration</option>
                                                <option value="technical" {{ old('department', $user->department) == 'technical' ? 'selected' : '' }}>Technical</option>
                                                <option value="support" {{ old('department', $user->department) == 'support' ? 'selected' : '' }}>Support</option>
                                                <option value="verification" {{ old('department', $user->department) == 'verification' ? 'selected' : '' }}>Verification</option>
                                            </select>
                                            @error('department')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Workstation Type *</label>
                                            <select name="workstation_type" id="workstation_type" class="form-control-moic @error('workstation_type') border-error @enderror" required>
                                                <option value="">Select Workstation</option>
                                                <option value="hq" {{ old('workstation_type', $user->workstation_type) == 'hq' ? 'selected' : '' }}>Headquarters (HQ)</option>
                                                <option value="toll_plaza" {{ old('workstation_type', $user->workstation_type) == 'toll_plaza' ? 'selected' : '' }}>Toll Plaza</option>
                                                <option value="field" {{ old('workstation_type', $user->workstation_type) == 'field' ? 'selected' : '' }}>Field</option>
                                            </select>
                                            @error('workstation_type')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Toll Plaza Field - UPDATED WITH MANYUMBI TOLL PLAZA -->
                                        <div id="tollPlazaContainer" class="{{ old('workstation_type', $user->workstation_type) == 'toll_plaza' ? '' : 'd-none' }}">
                                            <label class="form-label fw-medium">Toll Plaza *</label>
                                            <select name="toll_plaza" id="toll_plaza_select" class="form-control-moic @error('toll_plaza') border-error @enderror">
                                                <option value="">Select Toll Plaza</option>
                                                <option value="kafulafuta" {{ old('toll_plaza', $user->toll_plaza) == 'kafulafuta' ? 'selected' : '' }}>
                                                    Kafulafuta Toll Plaza
                                                </option>
                                                <option value="abram_zayoni_mokola" {{ old('toll_plaza', $user->toll_plaza) == 'abram_zayoni_mokola' ? 'selected' : '' }}>
                                                    Abram Zayoni Mokola Toll Plaza
                                                </option>
                                                <option value="katuba" {{ old('toll_plaza', $user->toll_plaza) == 'katuba' ? 'selected' : '' }}>
                                                    Katuba Toll Plaza
                                                </option>
                                                <option value="manyumbi" {{ old('toll_plaza', $user->toll_plaza) == 'manyumbi' ? 'selected' : '' }}>
                                                    Manyumbi Toll Plaza
                                                </option>
                                                <option value="konkola" {{ old('toll_plaza', $user->toll_plaza) == 'konkola' ? 'selected' : '' }}>
                                                    Konkola Toll Plaza
                                                </option>
                                                <option value="other" {{ old('toll_plaza') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            
                                            <div id="otherTollPlazaContainer" class="mt-2 {{ old('toll_plaza') == 'other' ? '' : 'd-none' }}">
                                                <input type="text" name="other_toll_plaza" 
                                                       value="{{ old('other_toll_plaza') }}"
                                                       class="form-control form-control-moic"
                                                       placeholder="Enter toll plaza name">
                                            </div>
                                            
                                            @error('toll_plaza')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Supervisor Selection Section -->
                                <div class="mt-4 pt-4 border-top">
                                    <h4 class="h6 fw-bold moic-navy mb-3">
                                        <i class="fas fa-user-tie me-2"></i>Supervisor Assignment
                                    </h4>
                                    
                                    <div class="bg-blue-50 p-3 rounded mb-3">
                                        <p class="small text-blue-800 mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            @if($user->user_type === 'supervisor')
                                                As a supervisor, you can select your own supervisor for your appraisals.
                                            @else
                                                Select your direct supervisor. This determines who will approve your requests.
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label class="form-label fw-medium">Direct Supervisor</label>
                                            <div class="d-flex gap-2">
                                                <select name="manager_id" id="manager_id" class="form-control-moic flex-grow-1">
                                                    <option value="">Select Supervisor</option>
                                                    @if($user->manager)
                                                        <option value="{{ $user->manager->employee_number }}" selected>
                                                            {{ $user->manager->name }} ({{ $user->manager->employee_number }})
                                                        </option>
                                                    @endif
                                                </select>
                                                <button type="button" onclick="loadSupervisors()" 
                                                        class="btn btn-outline-moic btn-sm" title="Refresh">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                            <div id="supervisorLoading" class="small text-muted mt-1 d-none">
                                                <span class="spinner-border spinner-border-sm me-1"></span>
                                                Loading...
                                            </div>
                                            @error('manager_id')
                                                <p class="text-danger small mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        @if($user->finalApprover)
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Final Approver</label>
                                            <div class="p-2 bg-gray-50 rounded border">
                                                <p class="mb-0 fw-medium">{{ $user->finalApprover->name }}</p>
                                                <p class="small text-muted mb-0">{{ $user->finalApprover->employee_number }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="mt-4 pt-4 border-top d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-2"></i>Reset
                                    </button>
                                    <button type="submit" id="submitProfileBtn" class="btn btn-moic">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                        <span id="profileLoading" class="loading-spinner d-none"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Approval Chain Card - ENHANCED WITH ICON HIERARCHY -->
                    @if(isset($approvalChain) && count($approvalChain) > 0)
                    <div class="card card-moic mb-4">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-sitemap me-2"></i>Approval Hierarchy
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="approval-chain-container">
                                <div class="approval-chain-title">
                                    <div class="approval-chain-icon">
                                        <i class="fas fa-arrow-up"></i>
                                    </div>
                                    <div>
                                        <h4 class="h6 fw-bold moic-navy mb-1">Your Approval Chain</h4>
                                        <p class="small text-muted mb-0">Hierarchy of approvers for your requests</p>
                                    </div>
                                </div>
                                
                                <div class="position-relative">
                                    @foreach($approvalChain as $index => $level)
                                        @php
                                            $levelNumber = $index + 1;
                                            $levelClass = 'level-' . min($levelNumber, 4);
                                            $roleType = $level['type'] ?? 'supervisor';
                                            $roleLabel = ucfirst(str_replace('_', ' ', $roleType));
                                            
                                            // Determine icon based on role
                                            $roleIcon = 'fa-user-tie';
                                            if($roleType === 'final_approver') $roleIcon = 'fa-crown';
                                            if($roleType === 'hr_approver') $roleIcon = 'fa-users';
                                            if($roleType === 'department_head') $roleIcon = 'fa-building';
                                        @endphp
                                        
                                        <div class="chain-item">
                                            <div class="chain-marker {{ $levelClass }}">
                                                {{ $levelNumber }}
                                            </div>
                                            
                                            <div class="chain-content">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="chain-role-badge">
                                                        <i class="fas {{ $roleIcon }} me-1"></i>
                                                        Level {{ $levelNumber }} - {{ $roleLabel }}
                                                    </span>
                                                    @if(($level['user']->employee_number ?? null) === $user->manager_id)
                                                        <span class="badge bg-blue-50 moic-navy" style="font-size: 0.6rem;">
                                                            <i class="fas fa-star me-1"></i>Direct Supervisor
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <div class="chain-user-name">
                                                    {{ $level['user']->name ?? 'Unknown' }}
                                                </div>
                                                
                                                <div class="chain-user-details">
                                                    <span><i class="fas fa-id-badge"></i> {{ $level['user']->employee_number ?? 'N/A' }}</span>
                                                    @if($level['user']->job_title ?? false)
                                                        <span><i class="fas fa-briefcase"></i> {{ $level['user']->job_title }}</span>
                                                    @endif
                                                    @if($level['user']->department ?? false)
                                                        <span><i class="fas fa-building"></i> {{ $level['user']->department }}</span>
                                                    @endif
                                                </div>
                                                
                                                @if($index === 0)
                                                    <div class="mt-2 pt-2 border-top small text-muted">
                                                        <i class="fas fa-clock me-1"></i> First-level approver for your requests
                                                    </div>
                                                @elseif($index === count($approvalChain) - 1)
                                                    <div class="mt-2 pt-2 border-top small text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Final approval authority
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if(!$loop->last)
                                                <div class="chain-connector">
                                                    <i class="fas fa-arrow-down"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Chain Summary -->
                                <div class="mt-3 p-2 bg-gray-50 rounded small text-center">
                                    <i class="fas fa-info-circle me-1 moic-navy"></i>
                                    <span class="text-muted">Your requests go through </span>
                                    <span class="fw-bold moic-navy">{{ count($approvalChain) }} level{{ count($approvalChain) > 1 ? 's' : '' }}</span>
                                    <span class="text-muted"> of approval</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Password Update Card -->
                    <div class="card card-moic">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.password.update') }}" method="POST" id="passwordForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-medium">Current Password *</label>
                                        <input type="password" name="current_password" 
                                               class="form-control form-control-moic @error('current_password') border-error @enderror"
                                               required>
                                        @error('current_password')
                                            <p class="text-danger small mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">New Password *</label>
                                        <input type="password" name="new_password" 
                                               class="form-control form-control-moic @error('new_password') border-error @enderror"
                                               required>
                                        @error('new_password')
                                            <p class="text-danger small mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Confirm New Password *</label>
                                        <input type="password" name="new_password_confirmation" 
                                               class="form-control form-control-moic"
                                               required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="bg-yellow-50 p-3 rounded small">
                                            <p class="fw-bold mb-2">Password Requirements:</p>
                                            <ul class="mb-0 ps-3">
                                                <li>Minimum 8 characters</li>
                                                <li>At least one uppercase letter (A-Z)</li>
                                                <li>At least one lowercase letter (a-z)</li>
                                                <li>At least one number (0-9)</li>
                                                <li>At least one special character (@$!%*?&)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" id="submitPasswordBtn" class="btn btn-accent">
                                            <i class="fas fa-key me-2"></i>Update Password
                                            <span id="passwordLoading" class="loading-spinner d-none"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile Stats & Quick Actions -->
                <div class="col-lg-4">
                    <!-- Profile Completion Card -->
                    <div class="card card-moic mb-4">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-chart-line me-2"></i>Profile Completion
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            @php
                                $profileFields = [
                                    'name' => !empty($user->name),
                                    'employee_number' => !empty($user->employee_number),
                                    'email' => !empty($user->email),
                                    'department' => !empty($user->department),
                                    'job_title' => !empty($user->job_title),
                                    'workstation_type' => !empty($user->workstation_type),
                                ];
                                
                                if ($user->workstation_type === 'toll_plaza') {
                                    $profileFields['toll_plaza'] = !empty($user->toll_plaza);
                                }
                                
                                $profileFields['manager_id'] = !empty($user->manager_id);
                                
                                $completedFields = array_sum($profileFields);
                                $totalFields = count($profileFields);
                                $profileCompletion = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
                            @endphp
                            
                            <div class="position-relative d-inline-block mb-3">
                                <svg width="120" height="120" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#10b981" stroke-width="3"
                                            stroke-dasharray="{{ $profileCompletion }}, 100"
                                            stroke-dashoffset="0"
                                            stroke-linecap="round"
                                            transform="rotate(-90 18 18)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="h3 fw-bold moic-navy mb-0">{{ $profileCompletion }}%</span>
                                </div>
                            </div>
                            
                            <p class="text-muted small mb-3">{{ $completedFields }} of {{ $totalFields }} fields completed</p>
                            
                            @if($profileCompletion < 100)
                                <div class="text-start">
                                    <p class="small fw-bold mb-2">Complete these fields:</p>
                                    <ul class="small text-amber-600 list-unstyled">
                                        @if(empty($user->job_title))
                                            <li><i class="fas fa-exclamation-circle me-1"></i> Job Title</li>
                                        @endif
                                        @if(empty($user->workstation_type))
                                            <li><i class="fas fa-exclamation-circle me-1"></i> Workstation Type</li>
                                        @endif
                                        @if(empty($user->toll_plaza) && $user->workstation_type === 'toll_plaza')
                                            <li><i class="fas fa-exclamation-circle me-1"></i> Toll Plaza</li>
                                        @endif
                                        @if(empty($user->manager_id))
                                            <li><i class="fas fa-exclamation-circle me-1"></i> Supervisor</li>
                                        @endif
                                    </ul>
                                </div>
                            @else
                                <div class="bg-green-50 text-green-800 p-2 rounded">
                                    <i class="fas fa-check-circle me-1"></i> Profile Complete!
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Account Information Card -->
                    <div class="card card-moic mb-4">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-info-circle me-2"></i>Account Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="small text-muted mb-1">Account Type</p>
                                <p class="fw-medium mb-0">
                                    @if($user->user_type === 'supervisor')
                                        <i class="fas fa-user-tie moic-accent me-1"></i> Supervisor
                                    @else
                                        <i class="fas fa-user moic-navy me-1"></i> Employee
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="small text-muted mb-1">Current Supervisor</p>
                                @if($user->manager)
                                    <p class="fw-medium mb-0">{{ $user->manager->name }}</p>
                                    <p class="small text-muted mb-0">{{ $user->manager->employee_number }}</p>
                                @else
                                    <p class="text-amber-600 mb-0">
                                        <i class="fas fa-exclamation-circle me-1"></i> Not Assigned
                                    </p>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <p class="small text-muted mb-1">Account Created</p>
                                <p class="fw-medium mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                <p class="small text-muted mb-0">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="small text-muted mb-1">Last Updated</p>
                                <p class="fw-medium mb-0">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                            
                            <div>
                                <p class="small text-muted mb-1">Account Status</p>
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-circle fa-xs me-1"></i> Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links Card -->
                    <div class="card card-moic">
                        <div class="card-header bg-white border-bottom bg-gray-50">
                            <h3 class="h5 fw-bold moic-navy mb-0">
                                <i class="fas fa-link me-2"></i>Quick Links
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-blue-50 p-2 rounded me-3">
                                        <i class="fas fa-home moic-navy"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">Dashboard</p>
                                        <p class="small text-muted mb-0">Back to main dashboard</p>
                                    </div>
                                </a>
                                
                                <a href="{{ route('appraisals.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-green-50 p-2 rounded me-3">
                                        <i class="fas fa-file-alt text-success"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">New Appraisal</p>
                                        <p class="small text-muted mb-0">Start performance review</p>
                                    </div>
                                </a>
                                
                                <a href="{{ route('appraisals.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-purple-50 p-2 rounded me-3">
                                        <i class="fas fa-list text-purple-800"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">My Appraisals</p>
                                        <p class="small text-muted mb-0">View all appraisals</p>
                                    </div>
                                </a>
                                
                                @if($user->user_type === 'supervisor')
                                <a href="{{ route('supervisor.dashboard') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-orange-50 p-2 rounded me-3">
                                        <i class="fas fa-user-tie moic-accent"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">Supervisor Dashboard</p>
                                        <p class="small text-muted mb-0">Manage team appraisals</p>
                                    </div>
                                </a>
                                @endif
                                
                                <a href="{{ route('leave.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <div class="bg-cyan-50 p-2 rounded me-3">
                                        <i class="fas fa-calendar-alt moic-navy"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-0">Leave Requests</p>
                                        <p class="small text-muted mb-0">Apply for leave</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer (Matching Dashboard) -->
            <footer class="mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded me-3">
                                <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                            </div>
                            <div>
                                <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                                <p class="text-muted small">Version 1.0.0</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap justify-content-lg-end gap-4">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                            <a href="#" class="text-decoration-none text-muted small">
                                <i class="fas fa-question-circle me-1"></i> Help
                            </a>
                            <a href="#" class="text-decoration-none text-muted small">
                                <i class="fas fa-shield-alt me-1"></i> Privacy
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide messages
        setTimeout(() => {
            document.querySelectorAll('.alert-fixed, .alert-slide').forEach(alert => {
                if (alert.classList.contains('alert')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        document.addEventListener('DOMContentLoaded', function() {
            // Job Title Other field handler
            const jobTitleSelect = document.getElementById('job_title');
            const otherJobTitleContainer = document.getElementById('otherJobTitleContainer');
            
            if (jobTitleSelect && otherJobTitleContainer) {
                function handleJobTitleChange() {
                    if (jobTitleSelect.value === 'Other') {
                        otherJobTitleContainer.classList.remove('d-none');
                    } else {
                        otherJobTitleContainer.classList.add('d-none');
                    }
                }
                jobTitleSelect.addEventListener('change', handleJobTitleChange);
            }
            
            // Workstation Type handler
            const workstationType = document.getElementById('workstation_type');
            const tollPlazaContainer = document.getElementById('tollPlazaContainer');
            
            if (workstationType && tollPlazaContainer) {
                function handleWorkstationChange() {
                    if (workstationType.value === 'toll_plaza') {
                        tollPlazaContainer.classList.remove('d-none');
                    } else {
                        tollPlazaContainer.classList.add('d-none');
                    }
                }
                workstationType.addEventListener('change', handleWorkstationChange);
            }
            
            // Toll Plaza Other handler
            const tollPlazaSelect = document.getElementById('toll_plaza_select');
            const otherTollPlazaContainer = document.getElementById('otherTollPlazaContainer');
            
            if (tollPlazaSelect && otherTollPlazaContainer) {
                function handleTollPlazaChange() {
                    if (tollPlazaSelect.value === 'other') {
                        otherTollPlazaContainer.classList.remove('d-none');
                    } else {
                        otherTollPlazaContainer.classList.add('d-none');
                    }
                }
                tollPlazaSelect.addEventListener('change', handleTollPlazaChange);
            }
            
            // Password form validation
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const newPassword = this.querySelector('input[name="new_password"]');
                    const confirmPassword = this.querySelector('input[name="new_password_confirmation"]');
                    
                    if (newPassword.value.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long');
                        newPassword.focus();
                        return;
                    }
                    
                    if (newPassword.value !== confirmPassword.value) {
                        e.preventDefault();
                        alert('Passwords do not match');
                        confirmPassword.focus();
                        return;
                    }
                    
                    const hasUpperCase = /[A-Z]/.test(newPassword.value);
                    const hasLowerCase = /[a-z]/.test(newPassword.value);
                    const hasNumbers = /\d/.test(newPassword.value);
                    const hasSpecialChar = /[@$!%*?&]/.test(newPassword.value);
                    
                    if (!hasUpperCase || !hasLowerCase || !hasNumbers || !hasSpecialChar) {
                        e.preventDefault();
                        alert('Password must contain uppercase, lowercase, number, and special character');
                        newPassword.focus();
                    }
                });
            }
            
            // Load supervisors
            loadSupervisors();
        });

        function loadSupervisors() {
            const supervisorSelect = document.getElementById('manager_id');
            const loadingDiv = document.getElementById('supervisorLoading');
            
            if (!supervisorSelect || !loadingDiv) return;
            
            loadingDiv.classList.remove('d-none');
            supervisorSelect.disabled = true;
            
            fetch('{{ route("profile.supervisors") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                loadingDiv.classList.add('d-none');
                supervisorSelect.disabled = false;
                
                const currentValue = supervisorSelect.value;
                supervisorSelect.innerHTML = '<option value="">Select Supervisor</option>';
                
                if (data.success && data.supervisors && data.supervisors.length > 0) {
                    data.supervisors.forEach(supervisor => {
                        const option = document.createElement('option');
                        option.value = supervisor.employee_number;
                        option.textContent = supervisor.display_name || `${supervisor.name} (${supervisor.employee_number})`;
                        
                        @if($user->manager)
                            if (supervisor.employee_number === '{{ $user->manager->employee_number }}') {
                                option.selected = true;
                            }
                        @endif
                        
                        supervisorSelect.appendChild(option);
                    });
                } else {
                    supervisorSelect.innerHTML = '<option value="">No supervisors available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading supervisors:', error);
                loadingDiv.classList.add('d-none');
                supervisorSelect.disabled = false;
                supervisorSelect.innerHTML = '<option value="">Error loading supervisors</option>';
            });
        }
    </script>
</body>
</html>