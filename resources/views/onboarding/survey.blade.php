<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Your Profile - MOIC Performance Appraisal System</title>
    <!-- FAVICON - Using TK.png -->
  <link rel="icon" type="image/png" href="{{ asset('images/TK.png') }}">
  <link rel="shortcut icon" href="{{ asset('images/TK.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/TK.png') }}">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Fonts & icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Apple Touch Icon (for iOS home screen) -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
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
            --moic-gradient-mixed: linear-gradient(135deg, #110484, #e7581c);
            
            --muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            font-size: 16px;
            height: 100%;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Container - matching login page */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }
        
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            opacity: 0.7;
        }
        
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.88);
            z-index: 1;
        }
        
        .bg-gradient-fallback {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(17,4,132,0.05) 0%, rgba(231,88,28,0.05) 50%, rgba(17,4,132,0.05) 100%);
            z-index: 0;
        }
        
        /* Particles - matching welcome/login */
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
        }
        
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.12; }
            90% { opacity: 0.12; }
            100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }

        .onboarding-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .onboarding-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            padding: 30px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-top: 4px solid var(--moic-accent);
            border-bottom: 3px solid var(--moic-navy);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .onboarding-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 40px -16px rgba(0, 0, 0, 0.25);
        }

        /* Progress Bar */
        .progress-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--moic-gradient-mixed);
            width: 33%;
            transition: width 0.3s ease;
        }

        /* Logo Container */
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .logo {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--moic-navy);
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .header p {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            color: var(--moic-navy);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--moic-accent);
            box-shadow: 0 0 0 4px rgba(231, 88, 28, 0.15);
        }

        .form-control:disabled {
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-hint {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 0.75rem;
        }

        /* Radio Group */
        .radio-group {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .radio-option {
            flex: 1;
        }

        .radio-input {
            display: none;
        }

        .radio-label {
            display: block;
            padding: 15px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            background-color: white;
        }

        .radio-label:hover {
            border-color: var(--moic-navy-light);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .radio-input:checked + .radio-label {
            border-color: var(--moic-accent);
            background: rgba(231, 88, 28, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .radio-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: var(--moic-navy);
        }

        .radio-input:checked + .radio-label .radio-icon {
            color: var(--moic-accent);
        }

        /* Workstation Details */
        .workstation-details {
            background: rgba(17, 4, 132, 0.04);
            border-radius: 20px;
            padding: 15px;
            margin-top: 12px;
            border-left: 3px solid var(--moic-accent);
            display: none;
            font-size: 0.9rem;
            border: 1px solid rgba(17, 4, 132, 0.12);
        }

        .workstation-details h4 {
            margin-bottom: 6px;
            font-size: 0.85rem;
            color: var(--moic-navy);
            font-weight: 800;
        }

        .workstation-details.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Buttons */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--moic-gradient-mixed);
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(17, 4, 132, 0.35);
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-accent-light));
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            border: none;
            border-left: 4px solid;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert i {
            font-size: 1.1rem;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left-color: #10b981;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border-left-color: #f59e0b;
        }

        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border-left-color: #3b82f6;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border-left-color: #ef4444;
        }

        .alert ul {
            margin: 5px 0 0 20px;
            padding: 0;
        }

        .alert li {
            margin: 2px 0;
        }

        /* Loading Spinner */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--moic-navy);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Supervisor selection container */
        #supervisorSelection {
            transition: all 0.3s ease;
        }

        #supervisorLoading {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 0.85rem;
            padding: 8px 0;
        }

        /* Other job title container */
        #otherJobTitleContainer {
            margin-top: 8px;
            animation: fadeIn 0.3s ease;
        }

        /* Partnership Banner */
        .partnership-banner-compact {
            background: var(--moic-gradient-mixed);
            border-radius: 30px;
            padding: 8px 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 0 auto 20px auto;
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .partnership-banner-compact span {
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .partnership-banner-compact i {
            color: white;
            font-size: 0.9rem;
        }

        .version-tag {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .onboarding-card {
                padding: 20px;
            }

            .radio-group {
                flex-direction: column;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            .header p {
                font-size: 0.85rem;
            }

            .logo {
                height: 45px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .onboarding-card {
                padding: 18px;
            }

            .logo {
                height: 40px;
            }

            .btn-submit {
                padding: 12px;
                font-size: 0.95rem;
            }

            .radio-label {
                padding: 12px 10px;
            }
        }

        /* Accessibility - Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .onboarding-card:hover,
            .btn-submit:hover,
            .radio-label:hover {
                transform: none;
            }
            
            .particle {
                display: none;
            }
        }

        /* Focus visible for accessibility */
        :focus-visible {
            outline: 2px solid var(--moic-accent);
            outline-offset: 2px;
        }
    </style>
</head>
<body>

    <!-- Background Container - identical to login page -->
    <div class="background-container">
        <div class="bg-image" id="bgImage"></div>
        <div class="bg-overlay"></div>
        <div class="bg-gradient-fallback"></div>
    </div>

    <!-- Particles animation - matching login page -->
    <div class="particles" id="particles"></div>

    <div class="onboarding-container">
        <div class="onboarding-card">
            <!-- Compact Partnership Banner -->
            <div class="text-center">
                <div class="partnership-banner-compact">
                    <i class="fas fa-handshake"></i>
                    <span>MOIC • TKC PARTNERSHIP</span>
                    <span class="version-tag">v2.0</span>
                </div>
            </div>
            
            <!-- Logos -->
            <div class="logo-container">
                <img src="{{ asset('images/moic.png') }}" alt="MOIC Logo" class="logo">
                <img src="{{ asset('images/TKC.png') }}" alt="TKC Logo" class="logo" onerror="this.style.display='none'">
            </div>

            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>

            <div class="header">
                <h1>Complete Your Profile</h1>
                <p>Tell us about yourself to personalize your experience</p>
            </div>

            <!-- Alert container for AJAX messages -->
            <div id="employeeCheckAlert" style="display: none;"></div>

            <!-- Display session messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('onboarding.submit') }}" id="onboardingForm">
                @csrf

                <!-- Name & Employee Number -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter your full name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Employee Number *</label>
                        <input type="text" name="employee_number" id="employee_number" class="form-control" required placeholder="e.g., MOIC-00123" value="{{ old('employee_number') }}">
                        <span class="form-hint">Check if you're already registered</span>
                    </div>
                </div>

                <!-- Job Title -->
                <div class="form-group">
                    <label class="form-label">Job Title *</label>
                    <select name="job_title" id="job_title" class="form-control" required>
                        <option value="">Select Job Title</option>
                        <option value="Plaza Manager">Plaza Manager</option>
                        <option value="Admin Clerk">Admin Clerk</option>
                        <option value="E&M Technician">E&M Technician</option>
                        <option value="Shift Manager">Shift Manager</option>
                        <option value="Senior Toll Collector">Senior Toll Collector</option>
                        <option value="Toll Collector">Toll Collector</option>
                        <option value="TCE Technician">TCE Technician</option>
                        <option value="Route Patrol Driver">Route Patrol Driver</option>
                        <option value="Plaza Attendant">Plaza Attendant</option>
                        <option value="Lane Attendant">Lane Attendant</option>
                        <option value="HR Assistant">HR Assistant</option>
                        <option value="Admin Manager">Admin Manager</option>
                        <option value="Trainer">Trainer</option>
                        <option value="Senior Trainer">Senior Trainer</option>
                        <option value="Senior TCE">Senior TCE</option>
                        <option value="Media and Customer Coordinator">Media and Customer Coordinator</option>
                        <option value="Verification Clerk">Verification Clerk</option>
                        <option value="Other">Other (Specify below)</option>
                    </select>
                    <div id="otherJobTitleContainer" style="display: none;">
                        <input type="text" name="other_job_title" id="other_job_title" class="form-control" placeholder="Please specify your job title">
                    </div>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="user_type" value="employee" id="employee" class="radio-input" required {{ old('user_type') == 'employee' ? 'checked' : '' }}>
                            <label for="employee" class="radio-label">
                                <div class="radio-icon"><i class="fas fa-user"></i></div>
                                <div>Employee</div>
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="user_type" value="supervisor" id="supervisor" class="radio-input" required {{ old('user_type') == 'supervisor' ? 'checked' : '' }}>
                            <label for="supervisor" class="radio-label">
                                <div class="radio-icon"><i class="fas fa-user-tie"></i></div>
                                <div>Supervisor</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Workstation Type -->
                <div class="form-group">
                    <label class="form-label">Workstation Type *</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="workstation_type" value="hq" id="workstation_hq" class="radio-input" required {{ old('workstation_type') == 'hq' ? 'checked' : '' }}>
                            <label for="workstation_hq" class="radio-label">
                                <div class="radio-icon"><i class="fas fa-building"></i></div>
                                <div>Headquarters (HQ)</div>
                            </label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="workstation_type" value="toll_plaza" id="workstation_toll" class="radio-input" required {{ old('workstation_type') == 'toll_plaza' ? 'checked' : '' }}>
                            <label for="workstation_toll" class="radio-label">
                                <div class="radio-icon"><i class="fas fa-road"></i></div>
                                <div>Toll Plaza</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Conditional HQ -->
                <div class="form-group" id="hqDepartmentSelection" style="display:none;">
                    <label class="form-label">HQ Department *</label>
                    <select name="hq_department" class="form-control">
                        <option value="">Select HQ Department</option>
                        <option value="hq_operations" {{ old('hq_department') == 'hq_operations' ? 'selected' : '' }}>Operations Management</option>
                        <option value="hq_finance" {{ old('hq_department') == 'hq_finance' ? 'selected' : '' }}>Finance & Accounting</option>
                        <option value="hq_hr" {{ old('hq_department') == 'hq_hr' ? 'selected' : '' }}>Human Resources</option>
                        <option value="hq_it" {{ old('hq_department') == 'hq_it' ? 'selected' : '' }}>IT</option>
                        <option value="hq_admin" {{ old('hq_department') == 'hq_admin' ? 'selected' : '' }}>Administration</option>
                    </select>
                </div>

                <!-- Conditional Toll Plaza -->
                <div class="form-group" id="tollPlazaSelection" style="display:none;">
                    <label class="form-label">Select Toll Plaza *</label>
                    <select name="toll_plaza" class="form-control">
                        <option value="">Select your toll plaza</option>
                        <option value="TP-001" {{ old('toll_plaza') == 'TP-001' ? 'selected' : '' }}>Kafulafuta Toll Plaza</option>
                        <option value="TP-002" {{ old('toll_plaza') == 'TP-002' ? 'selected' : '' }}>Abram Zayoni Mokola Toll Plaza</option>
                        <option value="TP-003" {{ old('toll_plaza') == 'TP-003' ? 'selected' : '' }}>Katuba Toll Plaza</option>
                        <option value="TP-004" {{ old('toll_plaza') == 'TP-004' ? 'selected' : '' }}>Manyumbi Toll Plaza</option>
                        <option value="TP-005" {{ old('toll_plaza') == 'TP-005' ? 'selected' : '' }}>Konkola Toll Plaza</option>
                    </select>
                </div>

                <!-- Supervisor Selection -->
                <div class="form-group" id="supervisorSelection">
                    <label class="form-label">Select Supervisor *</label>
                    <div id="supervisorLoading" style="display: none;">
                        <div class="loading"></div> <span>Loading supervisors...</span>
                    </div>
                    <select name="manager_id" id="manager_id" class="form-control" disabled>
                        <option value="">Loading supervisors...</option>
                    </select>
                    <span class="form-hint">Sets up your reporting structure</span>
                </div>

                <!-- Department -->
                <div class="form-group">
                    <label class="form-label">Department *</label>
                    <select name="department" class="form-control" required>
                        <option value="">Select Department</option>
                        <option value="operations" {{ old('department') == 'operations' ? 'selected' : '' }}>Operations</option>
                        <option value="finance" {{ old('department') == 'finance' ? 'selected' : '' }}>Finance</option>
                        <option value="hr" {{ old('department') == 'hr' ? 'selected' : '' }}>Human Resources</option>
                        <option value="it" {{ old('department') == 'it' ? 'selected' : '' }}>IT</option>
                        <option value="admin" {{ old('department') == 'admin' ? 'selected' : '' }}>Administration</option>
                        <option value="technical" {{ old('department') == 'technical' ? 'selected' : '' }}>Technical</option>
                        <option value="support" {{ old('department') == 'support' ? 'selected' : '' }}>Support</option>
                        <option value="verification" {{ old('department') == 'verification' ? 'selected' : '' }}>Verification</option>
                    </select>
                </div>

                <div id="workstationSummary" class="workstation-details">
                    <h4>Workstation Summary</h4>
                    <p id="summaryText">Select your workstation type to see details</p>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Complete Setup & Continue
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        // Create particles animation
        function createParticles() {
            const container = document.getElementById('particles');
            if (!container) return;
            const count = 12;
            for (let i = 0; i < count; i++) {
                const p = document.createElement('div');
                p.classList.add('particle');
                const size = Math.random() * 24 + 8;
                p.style.width = size + 'px';
                p.style.height = size + 'px';
                p.style.left = Math.random() * 100 + '%';
                p.style.animationDelay = Math.random() * 20 + 's';
                p.style.animationDuration = Math.random() * 20 + 28 + 's';
                p.style.opacity = Math.random() * 0.1 + 0.05;
                container.appendChild(p);
            }
        }
        createParticles();
        
        // Background image fallback
        const bgDiv = document.getElementById('bgImage');
        if (bgDiv) {
            const testImg = new Image();
            testImg.src = '/images/Purple Yellow Grey Illustrative Marketing Instagram Post (2).png';
            testImg.onload = () => { bgDiv.style.backgroundImage = `url('/images/Purple Yellow Grey Illustrative Marketing Instagram Post (2).png')`; bgDiv.style.backgroundSize = 'cover'; };
        }
        
        const hqDeptDiv = document.getElementById('hqDepartmentSelection');
        const tollPlazaDiv = document.getElementById('tollPlazaSelection');
        const supervisorDiv = document.getElementById('supervisorSelection');
        const supervisorSelect = document.getElementById('manager_id');
        const supervisorLoading = document.getElementById('supervisorLoading');
        const summaryDiv = document.getElementById('workstationSummary');
        const summaryText = document.getElementById('summaryText');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const empInput = document.getElementById('employee_number');
        const alertDiv = document.getElementById('employeeCheckAlert');
        const jobTitleSelect = document.getElementById('job_title');
        const otherContainer = document.getElementById('otherJobTitleContainer');
        const otherInput = document.getElementById('other_job_title');
        
        const checkEmployeeUrl = "{{ route('onboarding.check') }}";
        const getSupervisorsUrl = "{{ route('onboarding.supervisors') }}";

        function showAlert(message, type = 'info') {
            const icon = type === 'success' ? 'fa-check-circle' :
                        type === 'warning' ? 'fa-exclamation-triangle' :
                        type === 'danger' ? 'fa-times-circle' : 'fa-info-circle';
            
            alertDiv.innerHTML = `<div class="alert alert-${type}"><i class="fas ${icon}"></i> <span>${message}</span></div>`;
            alertDiv.style.display = 'block';
            
            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    alertDiv.style.display = 'none';
                }, 5000);
            }
        }

        function updateWorkstationSummary() {
            const ws = document.querySelector('input[name="workstation_type"]:checked');
            if(!ws){
                summaryDiv.classList.remove('show');
                return;
            }
            if(ws.value === 'hq'){
                const dept = document.querySelector('select[name="hq_department"]');
                summaryText.textContent = dept.value ? `You are in HQ department: ${dept.options[dept.selectedIndex].text}` : 'Select HQ department';
            } else if(ws.value === 'toll_plaza'){
                const plaza = document.querySelector('select[name="toll_plaza"]');
                summaryText.textContent = plaza.value ? `You are at ${plaza.options[plaza.selectedIndex].text}` : 'Select your toll plaza';
            }
            summaryDiv.classList.add('show');
        }

        function toggleWorkstationFields(){
            const ws = document.querySelector('input[name="workstation_type"]:checked');
            if(!ws) return;
            if(ws.value === 'hq'){
                hqDeptDiv.style.display = 'block'; 
                hqDeptDiv.querySelector('select').required = true;
                tollPlazaDiv.style.display = 'none'; 
                tollPlazaDiv.querySelector('select').required = false;
            } else if(ws.value === 'toll_plaza'){
                tollPlazaDiv.style.display = 'block'; 
                tollPlazaDiv.querySelector('select').required = true;
                hqDeptDiv.style.display = 'none'; 
                hqDeptDiv.querySelector('select').required = false;
            }
            updateWorkstationSummary();
        }

        function toggleSupervisorSelection(){
            const role = document.querySelector('input[name="user_type"]:checked');
            if(role && role.value === 'employee'){
                supervisorDiv.style.display = 'block'; 
                loadSupervisors();
            } else {
                supervisorDiv.style.display = 'none'; 
                supervisorSelect.required = false;
            }
        }

        function loadSupervisors() {
            supervisorLoading.style.display = 'flex';
            supervisorSelect.disabled = true;
            supervisorSelect.innerHTML = '<option value="">Loading supervisors...</option>';
            
            fetch(getSupervisorsUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                supervisorLoading.style.display = 'none';
                
                if (data.success && data.supervisors.length > 0) {
                    supervisorSelect.innerHTML = '<option value="">Select your supervisor</option>';
                    data.supervisors.forEach(supervisor => {
                        const option = document.createElement('option');
                        option.value = supervisor.employee_number;
                        option.textContent = `${supervisor.name} (${supervisor.employee_number}) - ${supervisor.department || 'No Department'}`;
                        supervisorSelect.appendChild(option);
                    });
                    supervisorSelect.disabled = false;
                    supervisorSelect.required = true;
                } else {
                    supervisorSelect.innerHTML = '<option value="">No supervisors available</option>';
                    supervisorSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading supervisors:', error);
                supervisorLoading.style.display = 'none';
                supervisorSelect.innerHTML = '<option value="">Error loading supervisors</option>';
                supervisorSelect.disabled = true;
            });
        }

        function toggleOtherJobTitle() {
            if (jobTitleSelect.value === 'Other') {
                otherContainer.style.display = 'block';
                otherInput.required = true;
            } else {
                otherContainer.style.display = 'none';
                otherInput.required = false;
                otherInput.value = '';
            }
        }

        let checkTimeout;
        if (empInput) {
            empInput.addEventListener('blur', function(){
                clearTimeout(checkTimeout);
                const empNumber = this.value.trim();
                if(empNumber.length < 3) return;
                
                checkTimeout = setTimeout(() => {
                    fetch(checkEmployeeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({employee_number: empNumber})
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            if (data.registered && data.onboarded) {
                                showAlert('✓ Employee found! Redirecting to login...', 'success');
                                setTimeout(() => {
                                    window.location.href = data.login_url || '/login';
                                }, 3000);
                            } else if (data.exists && !data.onboarded) {
                                showAlert('Employee found. Please complete your profile setup below.', 'warning');
                                if (data.user) {
                                    document.querySelector('input[name="name"]').value = data.user.name || '';
                                    if (data.user.job_title) {
                                        const jobTitles = ['Plaza Manager', 'Admin Clerk', 'E&M Technician', 'Shift Manager', 'Senior Toll Collector', 'Toll Collector', 'TCE Technician', 'Route Patrol Driver', 'Plaza Attendant', 'Lane Attendant', 'HR Assistant', 'Admin Manager', 'Trainer', 'Senior Trainer', 'Senior TCE', 'Media and Customer Coordinator', 'Verification Clerk'];
                                        if (jobTitles.includes(data.user.job_title)) {
                                            jobTitleSelect.value = data.user.job_title;
                                        } else {
                                            jobTitleSelect.value = 'Other';
                                            otherInput.value = data.user.job_title;
                                        }
                                        toggleOtherJobTitle();
                                    }
                                    if (data.user.user_type) {
                                        const userTypeRadio = document.querySelector(`input[name="user_type"][value="${data.user.user_type}"]`);
                                        if (userTypeRadio) userTypeRadio.checked = true;
                                        toggleSupervisorSelection();
                                    }
                                    if (data.user.workstation_type) {
                                        const wsRadio = document.querySelector(`input[name="workstation_type"][value="${data.user.workstation_type}"]`);
                                        if (wsRadio) wsRadio.checked = true;
                                        toggleWorkstationFields();
                                        if (data.user.workstation_type === 'hq' && data.user.hq_department) {
                                            document.querySelector('select[name="hq_department"]').value = data.user.hq_department;
                                        } else if (data.user.workstation_type === 'toll_plaza' && data.user.toll_plaza) {
                                            document.querySelector('select[name="toll_plaza"]').value = data.user.toll_plaza;
                                        }
                                    }
                                    if (data.user.department) {
                                        document.querySelector('select[name="department"]').value = data.user.department;
                                    }
                                    updateWorkstationSummary();
                                }
                            }
                        } else {
                            showAlert('New employee number. Please complete all fields below.', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }, 800);
            });
        }

        document.querySelectorAll('input[name="workstation_type"]').forEach(r => {
            r.addEventListener('change', toggleWorkstationFields);
        });
        
        document.querySelectorAll('input[name="user_type"]').forEach(r => {
            r.addEventListener('change', toggleSupervisorSelection);
        });
        
        document.querySelectorAll('select[name="hq_department"], select[name="toll_plaza"]').forEach(s => {
            s.addEventListener('change', updateWorkstationSummary);
        });
        
        if (jobTitleSelect) {
            jobTitleSelect.addEventListener('change', toggleOtherJobTitle);
        }

        toggleSupervisorSelection();
        toggleWorkstationFields();
        toggleOtherJobTitle();
        
        // Card entrance animation
        const card = document.querySelector('.onboarding-card');
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(18px)';
            card.style.transition = 'opacity 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1), transform 0.5s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 80);
        }
        
        const form = document.getElementById('onboardingForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const employeeNumber = empInput.value.trim();
                if (employeeNumber.length < 3) {
                    e.preventDefault();
                    showAlert('Please enter a valid employee number (at least 3 characters)', 'danger');
                    empInput.focus();
                    return false;
                }
                
                if (!jobTitleSelect.value) {
                    e.preventDefault();
                    showAlert('Please select your job title', 'danger');
                    jobTitleSelect.focus();
                    return false;
                }
                
                if (jobTitleSelect.value === 'Other' && !otherInput.value.trim()) {
                    e.preventDefault();
                    showAlert('Please specify your job title', 'danger');
                    otherInput.focus();
                    return false;
                }
                
                const userType = document.querySelector('input[name="user_type"]:checked');
                if (!userType) {
                    e.preventDefault();
                    showAlert('Please select your role', 'danger');
                    return false;
                }
                
                const workstationType = document.querySelector('input[name="workstation_type"]:checked');
                if (!workstationType) {
                    e.preventDefault();
                    showAlert('Please select your workstation type', 'danger');
                    return false;
                }
                
                if (workstationType.value === 'hq') {
                    const hqDept = document.querySelector('select[name="hq_department"]');
                    if (!hqDept.value) {
                        e.preventDefault();
                        showAlert('Please select your HQ department', 'danger');
                        hqDept.focus();
                        return false;
                    }
                } else if (workstationType.value === 'toll_plaza') {
                    const tollPlaza = document.querySelector('select[name="toll_plaza"]');
                    if (!tollPlaza.value) {
                        e.preventDefault();
                        showAlert('Please select your toll plaza', 'danger');
                        tollPlaza.focus();
                        return false;
                    }
                }
                
                if (userType.value === 'employee') {
                    const supervisorId = document.getElementById('manager_id').value;
                    if (!supervisorId || supervisorId === '') {
                        e.preventDefault();
                        showAlert('Please select your supervisor', 'danger');
                        return false;
                    }
                }
                
                const department = document.querySelector('select[name="department"]');
                if (!department.value) {
                    e.preventDefault();
                    showAlert('Please select your department', 'danger');
                    department.focus();
                    return false;
                }
                
                const submitBtn = form.querySelector('.btn-submit');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 10000);
            });
        }
    });
    </script>
</body>
</html>