<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Password Management - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            --muted: #64748b;
            
            --bg-light: #f8fafc;
            --surface: #ffffff;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            overflow-x: hidden;
        }
        
        body {
            background-color: var(--bg-light);
            background-image: url("{{ asset('images/TK.png') }}");
            background-size: 70% auto;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }
        
        /* Fixed Top Container */
        .fixed-top-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            background: transparent;
        }
        
        /* Header */
        .header-custom {
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid rgba(17, 4, 132, 0.1);
        }
        
        .brand-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .brand-logo {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--moic-gradient-mixed);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: var(--shadow-sm);
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        
        .brand-title {
            font-weight: 700;
            font-size: 1rem;
            background: var(--moic-gradient-mixed);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 0.7rem;
            color: var(--muted);
        }
        
        .header-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-back {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            background: transparent;
            border: 1px solid rgba(17, 4, 132, 0.2);
            color: var(--moic-navy);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-back:hover {
            background: var(--moic-gradient-mixed);
            color: white;
            border-color: transparent;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        /* Partnership Banner */
        .partnership-banner {
            background: var(--moic-gradient-mixed);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin: 0.75rem auto;
            max-width: 1200px;
            width: 95%;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }
        
        .partnership-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        @media (min-width: 768px) {
            .partnership-content {
                flex-direction: row;
                justify-content: space-between;
            }
        }
        
        .logo-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-item {
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-item img {
            height: 2rem;
            width: auto;
            object-fit: contain;
        }
        
        .partnership-icon {
            background: white;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }
        
        .partnership-icon i {
            color: var(--moic-accent);
            font-size: 1.25rem;
        }
        
        .partnership-text {
            color: white;
            text-align: center;
            flex: 1;
        }
        
        @media (min-width: 768px) {
            .partnership-text {
                text-align: left;
            }
        }
        
        .partnership-badge {
            display: inline-block;
            background: white;
            color: var(--moic-navy);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
        }
        
        /* Main Content Area */
        .main-content {
            padding-top: 200px;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            padding-bottom: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Password Card */
        .password-card {
            max-width: 550px;
            width: 90%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow-lg), 0 2px 20px rgba(255, 255, 255, 0.3) inset;
            border: 1px solid rgba(255, 255, 255, 0.6);
            position: relative;
            overflow: hidden;
            border-left: 3px solid var(--moic-accent);
            border-right: 3px solid var(--moic-navy);
        }
        
        .password-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--moic-gradient-mixed);
            z-index: 1;
        }
        
        .password-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(17, 4, 132, 0.03) 0%, 
                rgba(231, 88, 28, 0.03) 100%);
            z-index: -1;
        }
        
        .password-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .icon-circle {
            width: 5rem;
            height: 5rem;
            background: var(--moic-gradient-mixed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: var(--shadow-md);
        }
        
        .icon-circle i {
            font-size: 2rem;
            color: white;
        }
        
        .password-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--moic-navy);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        
        .password-header p {
            color: #334155;
            font-size: 0.95rem;
        }
        
        /* Form Elements */
        .form-group-custom {
            margin-bottom: 1.5rem;
        }
        
        .form-group-custom label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--moic-navy);
            margin-bottom: 0.5rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: white;
            border: 1.5px solid rgba(17, 4, 132, 0.15);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            padding-right: 3rem;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: var(--moic-navy);
            box-shadow: 0 0 0 4px rgba(17, 4, 132, 0.1);
        }
        
        .input-wrapper input.is-invalid {
            border-color: var(--danger);
            background-color: var(--danger-light);
        }
        
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 0.25rem;
            font-size: 1.1rem;
        }
        
        .toggle-password:hover {
            color: var(--moic-navy);
        }
        
        /* Strength Meter */
        .strength-meter {
            margin-top: 0.75rem;
        }
        
        .strength-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 0.25rem;
        }
        
        .meter-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .meter-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        
        /* Requirements Grid */
        .req-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-top: 0.75rem;
        }
        
        .req-item {
            background: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 0.75rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .req-met {
            color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
        }
        
        .req-not-met {
            color: var(--muted);
        }
        
        .req-item i {
            font-size: 0.6rem;
        }
        
        /* Match Indicator */
        .match-indicator {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            min-height: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Alert Messages */
        .alert-custom {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .alert-success {
            border-left-color: var(--success);
            background: rgba(16, 185, 129, 0.1);
        }
        
        .alert-danger {
            border-left-color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }
        
        .alert-warning {
            border-left-color: var(--warning);
            background: rgba(245, 158, 11, 0.1);
        }
        
        .alert-info {
            border-left-color: var(--info);
            background: rgba(59, 130, 246, 0.1);
        }
        
        .alert-custom ul {
            margin: 0.5rem 0 0 1rem;
            padding: 0;
        }
        
        /* Buttons */
        .btn-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-primary-custom {
            background: var(--moic-gradient-mixed);
            color: white;
            box-shadow: var(--shadow-md);
            flex: 1;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-outline-custom {
            background: rgba(255, 255, 255, 0.8);
            border: 1.5px solid var(--moic-navy);
            color: var(--moic-navy);
            flex: 1;
        }
        
        .btn-outline-custom:hover {
            background: var(--moic-navy);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-skip-custom {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(100, 116, 139, 0.3);
            color: var(--muted);
            width: 100%;
        }
        
        .btn-skip-custom:hover {
            background: white;
            color: var(--moic-navy);
            border-color: var(--moic-navy);
        }
        
        .action-row {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        /* Skip Card */
        .skip-card {
            margin-top: 2rem;
            padding: 1.5rem;
            background: rgba(248, 250, 252, 0.7);
            border-radius: 12px;
            border: 1px solid rgba(17, 4, 132, 0.1);
            backdrop-filter: blur(4px);
        }
        
        /* Footer Link */
        .footer-link {
            text-align: center;
            margin-top: 2rem;
        }
        
        .footer-link a {
            color: var(--moic-navy);
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 30px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        
        .footer-link a:hover {
            background: white;
            gap: 0.75rem;
            box-shadow: var(--shadow-sm);
        }
        
        /* Footer */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 1rem;
            text-align: center;
            color: var(--moic-navy);
            font-size: 0.8rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(17, 4, 132, 0.1);
            z-index: 1040;
        }
        
        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background: var(--moic-gradient-mixed);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 25s infinite linear;
            filter: blur(1px);
        }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.12;
            }
            90% {
                opacity: 0.12;
            }
            100% {
                transform: translateY(-100px) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                background-size: 90% auto;
            }
            
            .main-content {
                padding-top: 180px;
                padding-bottom: 70px;
            }
            
            .password-card {
                padding: 2rem 1.5rem;
                width: 95%;
            }
            
            .action-row {
                flex-direction: column;
            }
            
            .btn-custom {
                width: 100%;
            }
            
            .req-grid {
                grid-template-columns: 1fr;
            }
            
            .password-header h2 {
                font-size: 1.75rem;
            }
        }
        
        @media (max-width: 576px) {
            .header-custom {
                padding: 0.5rem 1rem;
            }
            
            .brand-title {
                font-size: 0.9rem;
            }
            
            .partnership-banner {
                padding: 0.75rem 1rem;
            }
            
            .logo-item img {
                height: 1.5rem;
            }
            
            .password-card {
                padding: 1.5rem 1rem;
            }
        }
        
        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
        
        /* Loading State */
        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.5rem;
            height: 1.5rem;
            margin-top: -0.75rem;
            margin-left: -0.75rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="particles" id="particles"></div>

    <!-- Fixed Top Container -->
    <div class="fixed-top-container">
        <!-- Header -->
        <header class="header-custom">
            <div class="brand-container">
                <div class="brand-logo">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-title">Performance Appraisal</span>
                    <span class="brand-subtitle">Password Management</span>
                </div>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Dashboard</span>
                </a>
            </div>
        </header>

        <!-- Partnership Banner -->
        <div class="partnership-banner">
            <div class="partnership-content">
                <div class="logo-group">
                    <div class="logo-item">
                        <img src="{{ asset('images/moic.png') }}" alt="MOIC" 
                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Inter%22 font-size=%2224%22 fill=%22%23110484%22>MOIC</text></svg>';">
                    </div>
                    <div class="partnership-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="logo-item">
                        <img src="{{ asset('images/TKC.png') }}" alt="TKC"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Inter%22 font-size=%2220%22 fill=%22%23e7581c%22>TKC</text></svg>';">
                    </div>
                </div>
                <div class="partnership-text">
                    <span class="partnership-badge">
                        <i class="fas fa-shield-alt me-1"></i> Secure Password Management
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="password-card">
            <div class="password-header">
                <div class="icon-circle">
                    <i class="fas fa-key"></i>
                </div>
                <h2>
                    @if($requiresPasswordSetup ?? false)
                        Set Up Password
                    @else
                        Update Password
                    @endif
                </h2>
                <p>Secure your account with a strong password</p>
            </div>

            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert-custom alert-success">
                    <i class="fas fa-check-circle mt-1"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="alert-custom alert-danger">
                    <i class="fas fa-exclamation-circle mt-1"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        @if($errors->any())
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ session('error') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($requiresPasswordSetup ?? false)
                <!-- Setup Form -->
                <form action="{{ route('profile.password.setup') }}" method="POST" id="setupForm">
                    @csrf
                    
                    <div class="form-group-custom">
                        <label for="setup_new_password">New Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="new_password" 
                                   id="setup_new_password" 
                                   placeholder="Enter new password" 
                                   required 
                                   minlength="6"
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('setup_new_password', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div id="setupStrength" class="strength-meter d-none">
                            <div class="strength-label">
                                <span>Password strength</span>
                                <span id="setupStrengthText"></span>
                            </div>
                            <div class="meter-bar">
                                <div id="setupStrengthBar" class="meter-fill" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="setup_confirm">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="setup_confirm" 
                                   placeholder="Confirm your password" 
                                   required 
                                   minlength="6"
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('setup_confirm', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div id="setupMatch" class="match-indicator"></div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('dashboard') }}" class="btn-custom btn-outline-custom">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn-custom btn-primary-custom" id="setupSubmitBtn">
                            <i class="fas fa-save"></i> Set Password
                        </button>
                    </div>
                </form>

                <!-- Skip Card -->
                <div class="skip-card">
                    <p class="fw-semibold mb-2" style="color: var(--moic-navy);">Don't want a password?</p>
                    <form action="{{ route('profile.password.skip') }}" method="POST" id="skipForm">
                        @csrf
                        <button type="button" onclick="skipPassword()" class="btn-custom btn-skip-custom">
                            <i class="fas fa-forward"></i> Skip Password Setup
                        </button>
                    </form>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Login with just your employee number. You can set a password later.
                    </p>
                </div>

            @else
                <!-- Update Form -->
                <form action="{{ route('profile.password.update') }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group-custom">
                        <label for="update_current">Current Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="current_password" 
                                   id="update_current" 
                                   placeholder="Enter current password" 
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('update_current', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="update_new">New Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="new_password" 
                                   id="update_new" 
                                   placeholder="Enter new password" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('update_new', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>

                        <!-- Requirements Grid -->
                        <div class="req-grid" id="reqGrid">
                            <span id="req-length" class="req-item req-not-met">
                                <i class="fas fa-times"></i> 8+ characters
                            </span>
                            <span id="req-upper" class="req-item req-not-met">
                                <i class="fas fa-times"></i> uppercase
                            </span>
                            <span id="req-lower" class="req-item req-not-met">
                                <i class="fas fa-times"></i> lowercase
                            </span>
                            <span id="req-number" class="req-item req-not-met">
                                <i class="fas fa-times"></i> number
                            </span>
                            <span id="req-special" class="req-item req-not-met" style="grid-column: span 2;">
                                <i class="fas fa-times"></i> special (@$!%*?&)
                            </span>
                        </div>

                        <div class="strength-meter mt-3">
                            <div class="strength-label">
                                <span>Strength</span>
                                <span id="updateStrengthText"></span>
                            </div>
                            <div class="meter-bar">
                                <div id="updateStrengthBar" class="meter-fill" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="update_confirm">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="update_confirm" 
                                   placeholder="Confirm new password" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('update_confirm', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div id="updateMatch" class="match-indicator"></div>
                    </div>

                    <div class="action-row">
                        <a href="{{ route('dashboard') }}" class="btn-custom btn-outline-custom">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn-custom btn-primary-custom" id="updateSubmitBtn">
                            <i class="fas fa-rotate"></i> Update Password
                        </button>
                    </div>
                </form>
            @endif

            <div class="footer-link">
                <a href="{{ route('profile.show') }}">
                    <i class="fas fa-chevron-left"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <i class="fas fa-copyright me-1"></i> {{ date('Y') }} MOIC & TKC Consortium — Password Management. 
            <span class="d-none d-md-inline">All rights reserved. Powered by SmartWave Solutions.</span>
        </div>
    </footer>

    <!-- Bootstrap JS (for any interactions) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            'use strict';

            // Set current year in footer
            document.getElementById('year')?.setAttribute('content', new Date().getFullYear());

            // Create floating particles
            function createParticles() {
                const particlesContainer = document.getElementById('particles');
                if (!particlesContainer) return;
                
                const particleCount = 15;
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    const size = Math.random() * 30 + 10;
                    const posX = Math.random() * 100;
                    const delay = Math.random() * 20;
                    const duration = Math.random() * 20 + 30;
                    
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = `${posX}%`;
                    particle.style.animationDelay = `${delay}s`;
                    particle.style.animationDuration = `${duration}s`;
                    particle.style.opacity = (Math.random() * 0.08 + 0.05).toString();
                    
                    particlesContainer.appendChild(particle);
                }
            }
            
            createParticles();

            // Toggle password visibility
            window.togglePassword = function(inputId, btn) {
                const input = document.getElementById(inputId);
                const icon = btn.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye', 'far');
                    icon.classList.add('fa-eye-slash', 'far');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye', 'far');
                }
            };

            // Skip password setup confirmation
            window.skipPassword = function() {
                if (confirm('Skip password setup? You can login with just your employee number. You can set a password later from your profile.')) {
                    const btn = event.target.closest('button');
                    btn.classList.add('loading');
                    document.getElementById('skipForm').submit();
                }
            };

            @if($requiresPasswordSetup ?? false)
            // Setup mode validation
            const setupPw = document.getElementById('setup_new_password');
            const setupConf = document.getElementById('setup_confirm');
            const strengthDiv = document.getElementById('setupStrength');
            const strengthBar = document.getElementById('setupStrengthBar');
            const strengthText = document.getElementById('setupStrengthText');
            const matchDiv = document.getElementById('setupMatch');
            const setupSubmit = document.getElementById('setupSubmitBtn');

            function updateSetupStrength(password) {
                if (password.length === 0) {
                    strengthDiv.classList.add('d-none');
                    return;
                }
                
                strengthDiv.classList.remove('d-none');
                
                let score = 0;
                if (password.length >= 6) score++;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[@$!%*?&]/.test(password)) score++;
                
                const percentage = (score / 5) * 100;
                strengthBar.style.width = percentage + '%';
                
                if (score <= 1) {
                    strengthBar.style.backgroundColor = '#ef4444';
                    strengthText.innerText = 'Weak';
                } else if (score <= 2) {
                    strengthBar.style.backgroundColor = '#f59e0b';
                    strengthText.innerText = 'Fair';
                } else if (score <= 3) {
                    strengthBar.style.backgroundColor = '#3b82f6';
                    strengthText.innerText = 'Good';
                } else {
                    strengthBar.style.backgroundColor = '#10b981';
                    strengthText.innerText = 'Strong';
                }
            }

            function checkSetupMatch() {
                if (setupPw.value.length > 0 || setupConf.value.length > 0) {
                    if (setupPw.value === setupConf.value && setupPw.value !== '') {
                        matchDiv.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981"></i><span style="color:#10b981">Passwords match</span>';
                    } else if (setupConf.value !== '') {
                        matchDiv.innerHTML = '<i class="fas fa-exclamation-circle" style="color:#ef4444"></i><span style="color:#ef4444">Passwords do not match</span>';
                    } else {
                        matchDiv.innerHTML = '';
                    }
                }
            }

            setupPw.addEventListener('input', function() {
                updateSetupStrength(this.value);
                checkSetupMatch();
            });
            
            setupConf.addEventListener('input', checkSetupMatch);

            document.getElementById('setupForm').addEventListener('submit', function(e) {
                if (setupPw.value.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long');
                    setupPw.focus();
                    return;
                }
                
                if (setupPw.value !== setupConf.value) {
                    e.preventDefault();
                    alert('Passwords do not match');
                    setupConf.focus();
                    return;
                }
                
                setupSubmit.classList.add('loading');
            });
            @endif

            @if(!($requiresPasswordSetup ?? false))
            // Update mode validation
            const newPw = document.getElementById('update_new');
            const confPw = document.getElementById('update_confirm');
            const strengthBarUp = document.getElementById('updateStrengthBar');
            const strengthTextUp = document.getElementById('updateStrengthText');
            const matchDivUp = document.getElementById('updateMatch');
            const updateSubmit = document.getElementById('updateSubmitBtn');

            function checkRequirements(password) {
                return {
                    length: password.length >= 8,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[@$!%*?&]/.test(password)
                };
            }

            function updateRequirementUI() {
                const password = newPw.value;
                const reqs = checkRequirements(password);
                
                for (const [key, met] of Object.entries(reqs)) {
                    const element = document.getElementById(`req-${key}`);
                    const icon = element.querySelector('i');
                    
                    if (met) {
                        element.classList.add('req-met');
                        element.classList.remove('req-not-met');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-check');
                    } else {
                        element.classList.add('req-not-met');
                        element.classList.remove('req-met');
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-times');
                    }
                }
                
                const score = Object.values(reqs).filter(Boolean).length;
                const percentage = (score / 5) * 100;
                strengthBarUp.style.width = percentage + '%';
                
                if (score <= 1) {
                    strengthBarUp.style.backgroundColor = '#ef4444';
                    strengthTextUp.innerText = 'Very Weak';
                } else if (score === 2) {
                    strengthBarUp.style.backgroundColor = '#f59e0b';
                    strengthTextUp.innerText = 'Weak';
                } else if (score === 3) {
                    strengthBarUp.style.backgroundColor = '#fbbf24';
                    strengthTextUp.innerText = 'Fair';
                } else if (score === 4) {
                    strengthBarUp.style.backgroundColor = '#3b82f6';
                    strengthTextUp.innerText = 'Good';
                } else {
                    strengthBarUp.style.backgroundColor = '#10b981';
                    strengthTextUp.innerText = 'Strong';
                }
            }

            function checkMatchUpdate() {
                if (confPw.value.length > 0 || newPw.value.length > 0) {
                    if (newPw.value === confPw.value && newPw.value !== '') {
                        matchDivUp.innerHTML = '<i class="fas fa-check-circle" style="color:#10b981"></i><span style="color:#10b981">Passwords match</span>';
                    } else if (confPw.value !== '') {
                        matchDivUp.innerHTML = '<i class="fas fa-exclamation-circle" style="color:#ef4444"></i><span style="color:#ef4444">Passwords do not match</span>';
                    } else {
                        matchDivUp.innerHTML = '';
                    }
                }
            }

            newPw.addEventListener('input', function() {
                updateRequirementUI();
                checkMatchUpdate();
            });
            
            confPw.addEventListener('input', checkMatchUpdate);

            document.getElementById('updateForm').addEventListener('submit', function(e) {
                const currentPw = document.getElementById('update_current').value;
                
                if (!currentPw) {
                    e.preventDefault();
                    alert('Please enter your current password');
                    document.getElementById('update_current').focus();
                    return;
                }
                
                const reqs = checkRequirements(newPw.value);
                if (!Object.values(reqs).every(Boolean)) {
                    e.preventDefault();
                    alert('New password does not meet all security requirements');
                    newPw.focus();
                    return;
                }
                
                if (newPw.value !== confPw.value) {
                    e.preventDefault();
                    alert('New passwords do not match');
                    confPw.focus();
                    return;
                }
                
                updateSubmit.classList.add('loading');
            });
            @endif
        })();
    </script>
</body>
</html>