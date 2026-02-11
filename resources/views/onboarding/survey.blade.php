<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Your Profile - MOIC</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- Compact Styles --- */
        :root {
            --navy: #110484;
            --accent: #e7581c;
            --gradient-mixed: linear-gradient(135deg, #110484, #e7581c);
            --muted: #64748b;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            font-family:'Inter',sans-serif;
            background:linear-gradient(135deg,#f8fafc 0%,#e6eeff 100%);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:15px;
        }
        .onboarding-container{
            max-width:600px; /* Reduced from 800px */
            width:100%;
        }
        .onboarding-card{
            background:white;
            border-radius:16px; /* Reduced from 20px */
            padding:30px; /* Reduced from 40px */
            box-shadow:0 10px 25px rgba(0,0,0,0.08); /* Lighter shadow */
            border-top:4px solid var(--accent); /* Thinner border */
        }
        .progress-bar{
            height:4px; /* Thinner */
            background:#e2e8f0;
            border-radius:2px;
            margin-bottom:20px; /* Reduced from 30px */
            overflow:hidden;
        }
        .progress-fill{
            height:100%;
            background:var(--gradient-mixed);
            width:33%;
            transition:width 0.3s ease;
        }
        /* UPDATED: Logo container styles */
        .logo-container{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px; /* Space between logos */
            margin-bottom:15px; /* Reduced from 20px */
            flex-wrap: wrap; /* Allow wrapping on small screens */
        }
        .logo{
            height:50px; /* Reduced from 60px */
            width:auto;
        }
        .tkc-logo-container {
            display: flex;
            align-items: center;
        }
        .tkc-logo-container img {
            height: 40px; /* Slightly smaller for TKC logo */
            width: auto;
        }
        .header{
            text-align:center;
            margin-bottom:25px; /* Reduced from 30px */
        }
        .header h1{
            color:var(--navy);
            font-size:1.6rem; /* Reduced from 2rem */
            margin-bottom:8px; /* Reduced from 10px */
        }
        .header p{
            color:var(--muted);
            font-size:0.9rem; /* Reduced from 1rem */
        }
        .form-group{
            margin-bottom:18px; /* Reduced from 25px */
        }
        .form-label{
            display:block;
            margin-bottom:6px; /* Reduced from 8px */
            font-weight:600;
            color:var(--navy);
            font-size:0.9rem; /* Reduced from 0.95rem */
        }
        .form-control{
            width:100%;
            padding:12px 14px; /* Reduced from 14px 16px */
            border:1.5px solid #e2e8f0; /* Thinner border */
            border-radius:8px; /* Reduced from 10px */
            font-family:'Inter',sans-serif;
            font-size:0.95rem; /* Reduced from 1rem */
            transition:all 0.2s ease;
        }
        .form-control:focus{
            outline:none;
            border-color:var(--accent);
            box-shadow:0 0 0 2px rgba(231,88,28,0.1); /* Smaller shadow */
        }
        .form-row{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:15px; /* Reduced from 20px */
        }
        .radio-group{
            display:flex;
            gap:12px; /* Reduced from 20px */
            margin-top:8px; /* Reduced from 10px */
        }
        .radio-option{
            flex:1;
        }
        .radio-input{
            display:none;
        }
        .radio-label{
            display:block;
            padding:15px 12px; /* Reduced from 20px */
            border:1.5px solid #e2e8f0; /* Thinner border */
            border-radius:8px; /* Reduced from 10px */
            text-align:center;
            cursor:pointer;
            transition:all 0.2s ease;
            font-size:0.9rem; /* Smaller font */
        }
        .radio-input:checked + .radio-label{
            border-color:var(--accent);
            background:rgba(231,88,28,0.05);
            transform:translateY(-1px); /* Reduced from -2px */
            box-shadow:0 3px 8px rgba(0,0,0,0.08); /* Smaller shadow */
        }
        .radio-icon{
            font-size:1.5rem; /* Reduced from 2rem */
            margin-bottom:8px; /* Reduced from 10px */
            color:var(--navy);
        }
        .radio-input:checked + .radio-label .radio-icon{
            color:var(--accent);
        }
        .form-hint{
            display:block;
            margin-top:4px; /* Reduced from 5px */
            color:var(--muted);
            font-size:0.8rem; /* Reduced from 0.85rem */
        }
        .btn-submit{
            width:100%;
            padding:14px; /* Reduced from 16px */
            background:var(--gradient-mixed);
            color:white;
            border:none;
            border-radius:8px; /* Reduced from 10px */
            font-size:1rem; /* Reduced from 1.1rem */
            font-weight:600;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px; /* Reduced from 10px */
            margin-top:25px; /* Reduced from 30px */
            transition:all 0.2s ease;
        }
        .btn-submit:hover{
            transform:translateY(-1px); /* Reduced from -2px */
            box-shadow:0 6px 15px rgba(17,4,132,0.2); /* Smaller shadow */
        }
        .workstation-details{
            background:#f8fafc;
            border-radius:8px; /* Reduced from 10px */
            padding:15px; /* Reduced from 20px */
            margin-top:12px; /* Reduced from 15px */
            border-left:3px solid var(--accent); /* Thinner border */
            display:none;
            font-size:0.9rem;
        }
        .workstation-details h4{
            margin-bottom:6px; /* Reduced from default */
            font-size:0.95rem;
            color:var(--navy);
        }
        .workstation-details.show{
            display:block;
            animation:fadeIn 0.3s ease;
        }
        @keyframes fadeIn{
            from{opacity:0;transform:translateY(-8px);} /* Reduced from -10px */
            to{opacity:1;transform:translateY(0);}
        }
        
        /* Alert styles */
        .alert {
            padding:10px 15px; /* Reduced from 12px 20px */
            border-radius:6px; /* Reduced from 8px */
            margin-bottom:15px; /* Reduced from 20px */
            font-size:0.9rem; /* Reduced from 0.95rem */
        }
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .alert-warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
        }
        .alert-info {
            background-color: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e40af;
        }
        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        
        /* Loading spinner */
        .loading {
            display: inline-block;
            width: 16px; /* Reduced from 20px */
            height: 16px; /* Reduced from 20px */
            border: 2px solid #f3f3f3; /* Reduced from 3px */
            border-top: 2px solid #110484; /* Reduced from 3px */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Mobile responsiveness */
        @media(max-width:768px){
            .onboarding-card{
                padding:20px; /* Reduced from 25px */
            }
            .radio-group{
                flex-direction:column;
            }
            .form-row{
                grid-template-columns:1fr;
            }
            .header h1{
                font-size:1.4rem;
            }
            .header p{
                font-size:0.85rem;
            }
            /* Adjust logos for smaller screens */
            .logo-container {
                gap: 15px;
            }
            .logo {
                height: 45px;
            }
            .tkc-logo-container img {
                height: 35px;
            }
        }
        
        @media(max-width:480px){
            .onboarding-card{
                padding:18px; /* Even smaller on very small screens */
            }
            body{
                padding:10px;
            }
            /* Stack logos vertically on very small screens */
            .logo-container {
                flex-direction: column;
                gap: 10px;
            }
            .logo {
                height: 40px;
            }
            .tkc-logo-container img {
                height: 30px;
            }
        }
    </style>
</head>
<body>
<div class="onboarding-container">
    <div class="onboarding-card">
        <!-- UPDATED: Logos side by side -->
        <div class="logo-container">
            <img src="{{ asset('images/moic.png') }}" alt="MOIC Logo" class="logo">
            <div class="tkc-logo-container">
                <img src="{{ asset('images/TKC.png') }}" alt="TKC Logo" class="logo" onerror="this.style.display='none'">
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <div class="header">
            <h1>Complete Your Profile</h1>
            <p>Tell us about yourself to personalize your experience</p>
        </div>

        <!-- Alert container -->
        <div id="employeeCheckAlert" style="display: none;"></div>

        <!-- Display session messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('onboarding.submit') }}" id="onboardingForm">
            @csrf

            <!-- Name & Employee Number -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label class="form-label">Employee Number *</label>
                    <input type="text" name="employee_number" id="employee_number" class="form-control" required placeholder="e.g., MOIC-00123">
                    <small class="form-hint">Check if you're already registered</small>
                </div>
            </div>

            <!-- Job Title - UPDATED WITH NEW TITLES -->
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
                    <!-- NEWLY ADDED JOB TITLES -->
                    <option value="Admin Manager">Admin Manager</option>
                    <option value="Trainer">Trainer</option>
                    <option value="Senior Trainer">Senior Trainer</option>
                    <option value="Senior TCE">Senior TCE</option>
                    <option value="Media and Customer Coordinator">Media and Customer Coordinator</option>
                    <option value="Other">Other (Specify below)</option>
                </select>
                <div id="otherJobTitleContainer" style="display: none; margin-top: 8px;">
                    <input type="text" name="other_job_title" id="other_job_title" class="form-control" placeholder="Please specify your job title">
                </div>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label class="form-label">Role *</label>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" name="user_type" value="employee" id="employee" class="radio-input" required>
                        <label for="employee" class="radio-label">
                            <div class="radio-icon"><i class="fas fa-user"></i></div>
                            <div>Employee</div>
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="user_type" value="supervisor" id="supervisor" class="radio-input" required>
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
                        <input type="radio" name="workstation_type" value="hq" id="workstation_hq" class="radio-input" required>
                        <label for="workstation_hq" class="radio-label">
                            <div class="radio-icon"><i class="fas fa-building"></i></div>
                            <div>Headquarters (HQ)</div>
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" name="workstation_type" value="toll_plaza" id="workstation_toll" class="radio-input" required>
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
                    <option value="hq_operations">Operations Management</option>
                    <option value="hq_finance">Finance & Accounting</option>
                    <option value="hq_hr">Human Resources</option>
                    <option value="hq_it">IT</option>
                    <option value="hq_admin">Administration</option>
                </select>
            </div>

            <!-- Conditional Toll Plaza -->
            <div class="form-group" id="tollPlazaSelection" style="display:none;">
                <label class="form-label">Select Toll Plaza *</label>
                <select name="toll_plaza" class="form-control">
                    <option value="">Select your toll plaza</option>
                    <option value="TP-001">Kafulafuta Toll Plaza</option>
                    <option value="TP-002">Abram Zayoni Mokola Toll Plaza</option>
                    <option value="TP-003">Katuba Toll Plaza</option>
                    <option value="TP-004">Manyumbi Toll Plaza</option>
                    <option value="TP-005">Konkola Toll Plaza</option>
                </select>
            </div>

            <!-- Supervisor Selection - DYNAMICALLY LOADED -->
            <div class="form-group" id="supervisorSelection">
                <label class="form-label">Select Supervisor *</label>
                <div id="supervisorLoading" style="display: none; margin-bottom: 8px;">
                    <div class="loading"></div> <span style="color: var(--muted); font-size: 0.85rem;">Loading supervisors...</span>
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
                    <option value="operations">Operations</option>
                    <option value="finance">Finance</option>
                    <option value="hr">Human Resources</option>
                    <option value="it">IT</option>
                    <option value="admin">Administration</option>
                    <option value="technical">Technical</option>
                    <option value="support">Support</option>
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

<script>
document.addEventListener('DOMContentLoaded', function(){
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
    
    // Store the route URL from Laravel
    const checkEmployeeUrl = "{{ route('onboarding.check') }}";
    const getSupervisorsUrl = "{{ route('onboarding.supervisors') }}";

    function showAlert(message, type = 'info') {
        alertDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        alertDiv.style.display = 'block';
        
        // Auto-hide after 5 seconds for success/info messages
        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }
    }

    function updateWorkstationSummary() {
        const ws = document.querySelector('input[name="workstation_type"]:checked');
        if(!ws){summaryDiv.classList.remove('show'); return;}
        if(ws.value==='hq'){
            const dept = document.querySelector('select[name="hq_department"]');
            summaryText.textContent = dept.value ? `You are in HQ department: ${dept.options[dept.selectedIndex].text}` : 'Select HQ department';
        }else if(ws.value==='toll_plaza'){
            const plaza = document.querySelector('select[name="toll_plaza"]');
            summaryText.textContent = plaza.value ? `You are at ${plaza.options[plaza.selectedIndex].text}` : 'Select your toll plaza';
        }
        summaryDiv.classList.add('show');
    }

    function toggleWorkstationFields(){
        const ws = document.querySelector('input[name="workstation_type"]:checked');
        if(!ws) return;
        if(ws.value==='hq'){
            hqDeptDiv.style.display='block'; 
            hqDeptDiv.querySelector('select').required=true;
            tollPlazaDiv.style.display='none'; 
            tollPlazaDiv.querySelector('select').required=false;
        } else if(ws.value==='toll_plaza'){
            tollPlazaDiv.style.display='block'; 
            tollPlazaDiv.querySelector('select').required=true;
            hqDeptDiv.style.display='none'; 
            hqDeptDiv.querySelector('select').required=false;
        }
        updateWorkstationSummary();
    }

    function toggleSupervisorSelection(){
        const role = document.querySelector('input[name="user_type"]:checked');
        if(role && role.value==='employee'){
            supervisorDiv.style.display='block'; 
            loadSupervisors(); // Load supervisors when employee is selected
        } else {
            supervisorDiv.style.display='none'; 
            supervisorSelect.required=false;
        }
    }

    // Load supervisors from database
    function loadSupervisors() {
        supervisorLoading.style.display = 'block';
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
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            supervisorLoading.style.display = 'none';
            
            if (data.success && data.supervisors.length > 0) {
                supervisorSelect.innerHTML = '<option value="">Select your supervisor</option>';
                
                data.supervisors.forEach(supervisor => {
                    const option = document.createElement('option');
                    option.value = supervisor.employee_number;
                    option.textContent = `${supervisor.name} (${supervisor.employee_number}) - ${supervisor.department}`;
                    supervisorSelect.appendChild(option);
                });
                
                supervisorSelect.disabled = false;
                supervisorSelect.required = true;
            } else {
                supervisorSelect.innerHTML = '<option value="">No supervisors available</option>';
                supervisorSelect.disabled = true;
                showAlert('No supervisors available. Please contact administrator.', 'warning');
            }
        })
        .catch(error => {
            console.error('Error loading supervisors:', error);
            supervisorLoading.style.display = 'none';
            supervisorSelect.innerHTML = '<option value="">Error loading supervisors</option>';
            supervisorSelect.disabled = true;
            showAlert('Error loading supervisors list. Please try again.', 'danger');
        });
    }

    // Handle "Other" job title selection
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

    // Employee number check (AJAX) - UPDATED WITH NEW JOB TITLES
let checkTimeout;
empInput.addEventListener('blur', function(){
    clearTimeout(checkTimeout);
    
    const empNumber = this.value.trim();
    if(empNumber.length < 3) {
        showAlert('Please enter at least 3 characters for employee number', 'warning');
        return;
    }
    
    checkTimeout = setTimeout(() => {
        showAlert('Checking employee number...', 'info');
        
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
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.exists) {
                if (data.registered && data.onboarded) {
                    // User exists and is onboarded - show login message
                    showAlert('✓ Employee found! Please login to continue.', 'success');
                    
                    // Check if we should redirect to login
                    if (data.redirect_to_login) {
                        // Show login redirect message
                        showAlert('✓ Account found! Redirecting to login page...', 'success');
                        
                        // Auto-redirect to login after 3 seconds
                        setTimeout(() => {
                            window.location.href = data.login_url || '/login';
                        }, 3000);
                    } else if (data.redirect) {
                        // Fallback for old response format
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                    
                } else if (data.exists && !data.onboarded) {
                    // User exists but not onboarded
                    showAlert('Employee found. Please complete your profile setup below.', 'warning');
                    
                    // Pre-fill form if user data is provided
                    if (data.user) {
                        document.querySelector('input[name="name"]').value = data.user.name || '';
                        
                        // Set job title - UPDATED WITH NEW JOB TITLES
                        if (data.user.job_title) {
                            const jobTitles = [
                                'Plaza Manager', 'Admin Clerk', 'E&M Technician', 'Shift Manager',
                                'Senior Toll Collector', 'Toll Collector', 'TCE Technician',
                                'Route Patrol Driver', 'Plaza Attendant', 'Lane Attendant', 
                                'HR Assistant', 'Admin Manager', 'Trainer', 'Senior Trainer', 
                                'Senior TCE', 'Media and Customer Coordinator'
                            ];
                            
                            if (jobTitles.includes(data.user.job_title)) {
                                jobTitleSelect.value = data.user.job_title;
                                toggleOtherJobTitle();
                            } else {
                                jobTitleSelect.value = 'Other';
                                toggleOtherJobTitle();
                                otherInput.value = data.user.job_title;
                            }
                        }
                        
                        // Set user type
                        if (data.user.user_type) {
                            const userTypeRadio = document.querySelector(`input[name="user_type"][value="${data.user.user_type}"]`);
                            if (userTypeRadio) {
                                userTypeRadio.checked = true;
                                toggleSupervisorSelection();
                                
                                // If employee, load supervisors and select if available
                                if (data.user.user_type === 'employee' && data.user.manager_id) {
                                    setTimeout(() => {
                                        if (supervisorSelect) {
                                            supervisorSelect.value = data.user.manager_id;
                                        }
                                    }, 1000);
                                }
                            }
                        }
                        
                        // Set workstation type
                        if (data.user.workstation_type) {
                            const wsRadio = document.querySelector(`input[name="workstation_type"][value="${data.user.workstation_type}"]`);
                            if (wsRadio) {
                                wsRadio.checked = true;
                                toggleWorkstationFields();
                                
                                // Set department/plaza
                                if (data.user.workstation_type === 'hq' && data.user.hq_department) {
                                    document.querySelector('select[name="hq_department"]').value = data.user.hq_department;
                                } else if (data.user.workstation_type === 'toll_plaza' && data.user.toll_plaza) {
                                    document.querySelector('select[name="toll_plaza"]').value = data.user.toll_plaza;
                                }
                            }
                        }
                        
                        document.querySelector('select[name="department"]').value = data.user.department || '';
                        
                        updateWorkstationSummary();
                    }
                }
            } else {
                // New employee
                showAlert('New employee number. Please complete all fields below.', 'info');
            }
        })
        .catch(error => {
            console.error('Error checking employee number:', error);
            showAlert('Error checking employee number. Please try again.', 'danger');
        });
    }, 800);
});

    // Event listeners for form fields
    document.querySelectorAll('input[name="workstation_type"]').forEach(r => {
        r.addEventListener('change', toggleWorkstationFields);
    });
    
    document.querySelectorAll('input[name="user_type"]').forEach(r => {
        r.addEventListener('change', toggleSupervisorSelection);
    });
    
    document.querySelectorAll('select[name="hq_department"], select[name="toll_plaza"]').forEach(s => {
        s.addEventListener('change', updateWorkstationSummary);
    });
    
    // Job title event listener
    jobTitleSelect.addEventListener('change', toggleOtherJobTitle);

    // Initialize form
    toggleSupervisorSelection();
    toggleWorkstationFields();
    toggleOtherJobTitle();
    
    // Form submission handling
    const form = document.getElementById('onboardingForm');
    form.addEventListener('submit', function(e) {
        // Validate employee number
        const employeeNumber = empInput.value.trim();
        if (employeeNumber.length < 3) {
            e.preventDefault();
            showAlert('Please enter a valid employee number (at least 3 characters)', 'danger');
            empInput.focus();
            return false;
        }
        
        // Check if job title is selected
        if (!jobTitleSelect.value) {
            e.preventDefault();
            showAlert('Please select your job title', 'danger');
            jobTitleSelect.focus();
            return false;
        }
        
        // Validate "Other" job title
        if (jobTitleSelect.value === 'Other' && !otherInput.value.trim()) {
            e.preventDefault();
            showAlert('Please specify your job title', 'danger');
            otherInput.focus();
            return false;
        }
        
        // Check if user type is selected
        const userType = document.querySelector('input[name="user_type"]:checked');
        if (!userType) {
            e.preventDefault();
            showAlert('Please select your role (Employee or Supervisor)', 'danger');
            return false;
        }
        
        // Check if workstation type is selected
        const workstationType = document.querySelector('input[name="workstation_type"]:checked');
        if (!workstationType) {
            e.preventDefault();
            showAlert('Please select your workstation type', 'danger');
            return false;
        }
        
        // Validate conditional fields based on workstation type
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
        
        // If employee, check supervisor is selected
        if (userType.value === 'employee') {
            const supervisorId = document.getElementById('manager_id').value;
            if (!supervisorId || supervisorId === '') {
                e.preventDefault();
                showAlert('Please select your supervisor', 'danger');
                return false;
            }
        }
        
        // Check department
        const department = document.querySelector('select[name="department"]');
        if (!department.value) {
            e.preventDefault();
            showAlert('Please select your department', 'danger');
            department.focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;
        
        // Re-enable button if form submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
});
</script>
</body>
</html>