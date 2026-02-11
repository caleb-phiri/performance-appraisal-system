<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MOIC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Use built Tailwind via Vite (do not use CDN in production) --}}
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #110484;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Field validation */
        .border-error {
            border-color: #ef4444;
        }
        .border-success {
            border-color: #10b981;
        }
        
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header with MOIC Gradient -->
    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo Section -->
                <div class="flex items-center space-x-4">
                    <!-- Dual Logo Container -->
                    <div class="relative p-1.5 rounded-md bg-white">
                        <div class="flex items-center space-x-3">
                            <!-- MOIC Logo -->
                            <div class="flex flex-col items-center">
                                <img class="h-7 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                <span class="text-[6px] font-bold text-[#110484] mt-0.5">MOIC</span>
                            </div>
                            
                            <!-- Partnership Divider -->
                            <div class="h-6 w-px bg-gray-300"></div>
                            
                            <!-- TKC Logo -->
                            <div class="flex flex-col items-center">
                                <img class="h-7 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo">
                                <span class="text-[6px] font-bold text-[#e7581c] mt-0.5">TKC</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Page Title -->
                    <div class="flex items-center">
                        <div class="h-8 w-[1px] bg-white/30 mx-3"></div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">My Profile</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">Manage your account information</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" 
                       class="bg-white text-[#110484] px-3 py-1 rounded text-sm hover:bg-gray-100 transition duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Success & Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-500"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i> {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i> {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <div class="flex items-center">
                <div class="mr-6">
                    <div class="w-24 h-24 bg-gradient-to-r from-[#110484] to-[#1a0c9e] rounded-full flex items-center justify-center text-white text-4xl">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-[#110484] mb-2">{{ $user->name }}</h2>
                    <div class="flex flex-wrap gap-4">
                        <p class="text-gray-600">
                            <i class="fas fa-id-badge mr-1"></i> Employee ID: <span class="font-semibold">{{ $user->employee_number }}</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-briefcase mr-1"></i> Job Title: <span class="font-semibold">{{ $user->job_title ?? 'Not specified' }}</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-building mr-1"></i> Department: <span class="font-semibold">{{ $user->department }}</span>
                        </p>
                    </div>
                    
                    <!-- Profile Status Badges -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($user->user_type === 'supervisor')
                            <span class="inline-flex items-center bg-gradient-to-r from-purple-100 to-violet-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-user-tie mr-1"></i> Supervisor
                            </span>
                        @else
                            <span class="inline-flex items-center bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i> Employee
                            </span>
                        @endif
                        
                        @if($user->workstation_type === 'hq')
                            <span class="inline-flex items-center bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-building mr-1"></i> Headquarters
                            </span>
                        @elseif($user->workstation_type === 'toll_plaza')
                            <span class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-road mr-1"></i> Toll Plaza
                            </span>
                        @endif
                        
                        @if($user->toll_plaza)
                            <span class="inline-flex items-center bg-gradient-to-r from-orange-100 to-amber-100 text-[#e7581c] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $user->toll_plaza }}
                            </span>
                        @endif
                        
                        @if($user->is_onboarded)
                            <span class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Profile Complete
                            </span>
                        @else
                            <span class="inline-flex items-center bg-gradient-to-r from-yellow-100 to-amber-100 text-[#e7581c] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i> Profile Incomplete
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Profile Information -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Personal Information Card - UPDATED with Job Title Dropdown -->
<div class="bg-white rounded-lg shadow border border-gray-200 profile-card">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        <h3 class="text-lg font-bold text-[#110484]">
            <i class="fas fa-user-circle mr-2"></i>Personal Information
        </h3>
        <p class="text-sm text-gray-600 mt-1">Update your personal and job information</p>
    </div>
    <div class="p-6">
        <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('name') border-error @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                        <input type="text" name="employee_number" value="{{ old('employee_number', $user->employee_number) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('employee_number') border-error @enderror"
                               required>
                        @error('employee_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('email') border-error @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Supervisor Selection for Employees -->
                    @if($user->user_type === 'employee')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supervisor *</label>
                        <div class="flex items-center space-x-2">
                            <select name="manager_id" id="manager_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('manager_id') border-error @enderror"
                                    required>
                                <option value="">Select Supervisor</option>
                                @if($user->manager)
                                    <option value="{{ $user->manager->employee_number }}" selected>
                                        {{ $user->manager->name }} ({{ $user->manager->employee_number }})
                                    </option>
                                @endif
                            </select>
                            <button type="button" onclick="loadSupervisors()" 
                                    class="px-3 py-2 bg-[#110484] text-white rounded-lg hover:bg-[#0e0370] transition duration-200">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div id="supervisorLoading" class="hidden mt-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="loading-spinner mr-2"></div>
                                Loading supervisors...
                            </div>
                        </div>
                        @error('manager_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Select or update your reporting supervisor</p>
                    </div>
                    @endif
                </div>
                
                <!-- Job Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('department') border-error @enderror"
                                required>
                            <option value="">Select Department</option>
                            <option value="operations" {{ old('department', $user->department) == 'operations' ? 'selected' : '' }}>Operations</option>
                            <option value="finance" {{ old('department', $user->department) == 'finance' ? 'selected' : '' }}>Finance</option>
                            <option value="hr" {{ old('department', $user->department) == 'hr' ? 'selected' : '' }}>Human Resources</option>
                            <option value="it" {{ old('department', $user->department) == 'it' ? 'selected' : '' }}>IT</option>
                            <option value="admin" {{ old('department', $user->department) == 'admin' ? 'selected' : '' }}>Administration</option>
                            <option value="technical" {{ old('department', $user->department) == 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="support" {{ old('department', $user->department) == 'support' ? 'selected' : '' }}>Support</option>
                        </select>
                        @error('department')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                 <!-- Job Title Dropdown - FIXED -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
    <select name="job_title" id="job_title" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('job_title') border-error @enderror"
            required>
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
        <!-- NEWLY ADDED JOB TITLES -->
        <option value="Admin Manager" {{ old('job_title', $user->job_title) == 'Admin Manager' ? 'selected' : '' }}>Admin Manager</option>
        <option value="Trainer" {{ old('job_title', $user->job_title) == 'Trainer' ? 'selected' : '' }}>Trainer</option>
        <option value="Senior Trainer" {{ old('job_title', $user->job_title) == 'Senior Trainer' ? 'selected' : '' }}>Senior Trainer</option>
        <option value="Senior TCE" {{ old('job_title', $user->job_title) == 'Senior TCE' ? 'selected' : '' }}>Senior TCE</option>
        <option value="Media and Customer Coordinator" {{ old('job_title', $user->job_title) == 'Media and Customer Coordinator' ? 'selected' : '' }}>Media and Customer Coordinator</option>
        <option value="Other" {{ old('job_title', $user->job_title) == 'Other' ? 'selected' : '' }}>Other (Specify below)</option>
    </select>
    
    <!-- FIXED: Use consistent ID -->
    <div id="otherJobTitleContainer" class="mt-2 {{ (old('job_title', $user->job_title) == 'Other' || (!in_array(old('job_title', $user->job_title), ['Plaza Manager', 'Admin Clerk', 'E&M Technician', 'Shift Manager', 'Senior Toll Collector', 'Toll Collector', 'TCE Technician', 'Route Patrol Driver', 'Plaza Attendant', 'Lane Attendant', 'HR Assistant', 'Admin Manager', 'Trainer', 'Senior Trainer', 'Senior TCE', 'Media and Customer Coordinator']) && old('job_title', $user->job_title) && old('job_title', $user->job_title) != '')) ? 'block' : 'hidden' }}">
        <input type="text" name="other_job_title" 
               value="{{ old('other_job_title', (old('job_title', $user->job_title) == 'Other' || !in_array(old('job_title', $user->job_title), ['Plaza Manager', 'Admin Clerk', 'E&M Technician', 'Shift Manager', 'Senior Toll Collector', 'Toll Collector', 'TCE Technician', 'Route Patrol Driver', 'Plaza Attendant', 'Lane Attendant', 'HR Assistant', 'Admin Manager', 'Trainer', 'Senior Trainer', 'Senior TCE', 'Media and Customer Coordinator'])) ? old('other_job_title', $user->job_title) : '') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
               placeholder="Enter your job title">
        <p class="text-xs text-gray-500 mt-1">Enter your job title if not listed above</p>
    </div>
    
    @error('job_title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
    @error('other_job_title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Workstation Type *</label>
                        <select name="workstation_type" id="workstation_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('workstation_type') border-error @enderror"
                                required>
                            <option value="">Select Workstation</option>
                            <option value="hq" {{ old('workstation_type', $user->workstation_type) == 'hq' ? 'selected' : '' }}>Headquarters (HQ)</option>
                            <option value="toll_plaza" {{ old('workstation_type', $user->workstation_type) == 'toll_plaza' ? 'selected' : '' }}>Toll Plaza</option>
                            <option value="field" {{ old('workstation_type', $user->workstation_type) == 'field' ? 'selected' : '' }}>Field</option>
                        </select>
                        @error('workstation_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Toll Plaza Dropdown -->
                    <div id="toll_plaza_container">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Toll Plaza *</label>
                        <select name="toll_plaza" id="toll_plaza_select"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('toll_plaza') border-error @enderror">
                            <option value="">Select Toll Plaza</option>
                            @foreach($tollPlazas as $code => $name)
                                <option value="{{ $code }}" {{ old('toll_plaza', $user->toll_plaza) == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                            <option value="other" {{ old('toll_plaza') == 'other' ? 'selected' : '' }}>Other (Specify)</option>
                        </select>
                        
                        <div id="other_toll_plaza_container" class="mt-2 {{ old('toll_plaza') == 'other' ? 'block' : 'hidden' }}">
                            <input type="text" name="other_toll_plaza" 
                                   value="{{ old('other_toll_plaza') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   placeholder="Enter custom toll plaza name">
                            <p class="text-xs text-gray-500 mt-1">Enter the name of your toll plaza if not listed above</p>
                        </div>
                        
                        @error('toll_plaza')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('other_toll_plaza')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <button type="reset" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
                <button type="submit" id="submitProfileBtn"
                        class="px-6 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition duration-200 font-medium flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    <span>Save Changes</span>
                    <span id="profileLoading" class="hidden ml-2">
                        <div class="loading-spinner"></div>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

                <!-- Password Update Card -->
                <div class="bg-white rounded-lg shadow border border-gray-200 profile-card">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h3 class="text-lg font-bold text-[#110484]">
                            <i class="fas fa-lock mr-2"></i>Change Password
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Update your password for security</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('profile.password.update') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password *</label>
                                    <input type="password" name="current_password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('current_password') border-error @enderror"
                                           placeholder="Enter your current password" required>
                                    @error('current_password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password *</label>
                                        <input type="password" name="new_password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent @error('new_password') border-error @enderror"
                                               placeholder="Enter new password" required>
                                        @error('new_password')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password *</label>
                                        <input type="password" name="new_password_confirmation" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                               placeholder="Confirm new password" required>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-amber-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-800 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Password Requirements
                                    </h4>
                                    <ul class="text-sm text-amber-700 space-y-1">
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle text-xs mr-2"></i>
                                            Minimum 8 characters
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle text-xs mr-2"></i>
                                            At least one uppercase letter
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle text-xs mr-2"></i>
                                            At least one lowercase letter
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle text-xs mr-2"></i>
                                            At least one number
                                        </li>
                                        <li class="flex items-center">
                                            <i class="fas fa-check-circle text-xs mr-2"></i>
                                            At least one special character (@$!%*?&)
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-200 flex justify-end">
                                    <button type="submit" id="submitPasswordBtn"
                                            class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow transition duration-200 font-medium flex items-center">
                                        <i class="fas fa-key mr-2"></i>
                                        <span>Update Password</span>
                                        <span id="passwordLoading" class="hidden ml-2">
                                            <div class="loading-spinner"></div>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Stats & Quick Actions -->
            <div class="space-y-6">
                <!-- Profile Completion Card -->
                <div class="bg-white rounded-lg shadow border border-gray-200 profile-card">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h3 class="text-lg font-bold text-[#110484]">
                            <i class="fas fa-chart-line mr-2"></i>Profile Completion
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            // Calculate profile completion percentage
                            $profileFields = [
                                'name' => !empty($user->name),
                                'employee_number' => !empty($user->employee_number),
                                'department' => !empty($user->department),
                                'job_title' => !empty($user->job_title),
                                'workstation_type' => !empty($user->workstation_type),
                                'toll_plaza' => !empty($user->toll_plaza),
                                'email' => !empty($user->email),
                                'manager_id' => ($user->user_type !== 'employee' || !empty($user->manager_id)),
                            ];
                            $completedFields = array_sum($profileFields);
                            $totalFields = count($profileFields);
                            $profileCompletion = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
                        @endphp
                        
                        <div class="text-center mb-6">
                            <div class="inline-block relative">
                                <svg class="w-32 h-32" viewBox="0 0 36 36">
                                    <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none"
                                          stroke="#e5e7eb"
                                          stroke-width="3"/>
                                    <path d="M18 2.0845
                                            a 15.9155 15.9155 0 0 1 0 31.831
                                            a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none"
                                          stroke="#10b981"
                                          stroke-width="3"
                                          stroke-dasharray="{{ $profileCompletion }}, 100"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-3xl font-bold text-[#110484]">{{ $profileCompletion }}%</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-4">{{ $completedFields }} of {{ $totalFields }} fields completed</p>
                        </div>
                        
                        <!-- Completion Tips -->
                        @if($profileCompletion < 100)
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-700">Complete these fields:</h4>
                            @if(empty($user->job_title))
                            <div class="flex items-center text-sm text-amber-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Add Job Title</span>
                            </div>
                            @endif
                           
                            @if(empty($user->workstation_type))
                            <div class="flex items-center text-sm text-amber-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Select Workstation Type</span>
                            </div>
                            @endif
                            @if(empty($user->toll_plaza) && $user->workstation_type === 'toll_plaza')
                            <div class="flex items-center text-sm text-amber-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Specify Toll Plaza</span>
                            </div>
                            @endif
                            @if($user->user_type === 'employee' && empty($user->manager_id))
                            <div class="flex items-center text-sm text-amber-600">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Select Supervisor</span>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="text-center">
                            <div class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 px-4 py-2 rounded-full">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span class="font-medium">Profile Complete!</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">All required information is filled</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="bg-white rounded-lg shadow border border-gray-200 profile-card">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-violet-50">
                        <h3 class="text-lg font-bold text-[#110484]">
                            <i class="fas fa-info-circle mr-2"></i>Account Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Account Type</p>
                                <p class="font-semibold text-[#110484]">
                                    @if($user->user_type === 'supervisor')
                                        Supervisor Account
                                    @else
                                        Employee Account
                                    @endif
                                </p>
                            </div>
                            
                            @if($user->user_type === 'employee' && $user->manager)
                            <div>
                                <p class="text-sm text-gray-500">Current Supervisor</p>
                                <p class="font-semibold text-gray-800">
                                    {{ $user->manager->name }} ({{ $user->manager->employee_number }})
                                </p>
                                <p class="text-xs text-gray-600 mt-1">{{ $user->manager->department }}</p>
                            </div>
                            @elseif($user->user_type === 'employee')
                            <div>
                                <p class="text-sm text-gray-500">Supervisor</p>
                                <p class="font-semibold text-amber-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Not Assigned
                                </p>
                            </div>
                            @endif
                            
                            <div>
                                <p class="text-sm text-gray-500">Account Created</p>
                                <p class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Last Profile Update</p>
                                <p class="font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Account Status</p>
                                <p class="font-semibold text-green-600">
                                    <i class="fas fa-circle text-xs mr-1"></i> Active
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Need help?</span>
                                <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                                    Contact Support <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Card -->
                <div class="bg-white rounded-lg shadow border border-gray-200 profile-card">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
                        <h3 class="text-lg font-bold text-[#110484]">
                            <i class="fas fa-link mr-2"></i>Quick Links
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-lg transition duration-200">
                                <div class="bg-white p-2 rounded mr-3">
                                    <i class="fas fa-home text-[#110484]"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-[#110484]">Dashboard</p>
                                    <p class="text-xs text-gray-600">Back to main dashboard</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('appraisals.create') }}" 
                               class="flex items-center p-3 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-lg transition duration-200">
                                <div class="bg-white p-2 rounded mr-3">
                                    <i class="fas fa-file-alt text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-[#110484]">New Appraisal</p>
                                    <p class="text-xs text-gray-600">Start performance review</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('appraisals.index') }}" 
                               class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-violet-50 hover:from-purple-100 hover:to-violet-100 rounded-lg transition duration-200">
                                <div class="bg-white p-2 rounded mr-3">
                                    <i class="fas fa-list text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-[#110484]">My Appraisals</p>
                                    <p class="text-xs text-gray-600">View all appraisals</p>
                                </div>
                            </a>
                            
                            @if($user->user_type === 'supervisor')
                            <a href="{{ route('supervisor.dashboard') }}" 
                               class="flex items-center p-3 bg-gradient-to-r from-orange-50 to-amber-50 hover:from-orange-100 hover:to-amber-100 rounded-lg transition duration-200">
                                <div class="bg-white p-2 rounded mr-3">
                                    <i class="fas fa-user-tie text-[#e7581c]"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-[#110484]">Supervisor Dashboard</p>
                                    <p class="text-xs text-gray-600">Manage team appraisals</p>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- MOIC Logo in footer -->
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Version 1.0.0 • Profile Management</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-question-circle mr-1"></i> Help
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-shield-alt mr-1"></i> Privacy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-scroll to top if there are errors
            if (document.querySelector('.bg-gradient-to-r.from-red-50')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            
            // Handle toll plaza "Other" option
            const tollPlazaSelect = document.getElementById('toll_plaza_select');
            const otherContainer = document.getElementById('other_toll_plaza_container');
            const workstationType = document.getElementById('workstation_type');
            const tollPlazaContainer = document.getElementById('toll_plaza_container');
            
            // Initialize toll plaza field visibility
            function updateTollPlazaVisibility() {
                if (workstationType && tollPlazaContainer) {
                    if (workstationType.value === 'toll_plaza') {
                        tollPlazaContainer.style.display = 'block';
                        tollPlazaSelect.required = true;
                    } else {
                        tollPlazaContainer.style.display = 'block'; // Keep visible but not required
                        tollPlazaSelect.required = false;
                    }
                }
            }
            
            // Initialize on page load
            updateTollPlazaVisibility();
            
            // Handle workstation type change
            if (workstationType) {
                workstationType.addEventListener('change', updateTollPlazaVisibility);
            }
            
            // Handle toll plaza "Other" option
            if (tollPlazaSelect && otherContainer) {
                // Initialize on page load
                if (tollPlazaSelect.value === 'other') {
                    otherContainer.classList.remove('hidden');
                }
                
                // Handle change event
                tollPlazaSelect.addEventListener('change', function() {
                    if (this.value === 'other') {
                        otherContainer.classList.remove('hidden');
                    } else {
                        otherContainer.classList.add('hidden');
                    }
                });
            }
            
            // Form validation for password form
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitPasswordBtn');
                    const loadingSpan = document.getElementById('passwordLoading');
                    
                    // Show loading
                    submitBtn.disabled = true;
                    loadingSpan.classList.remove('hidden');
                    
                    const currentPassword = this.querySelector('input[name="current_password"]');
                    const newPassword = this.querySelector('input[name="new_password"]');
                    const confirmPassword = this.querySelector('input[name="new_password_confirmation"]');
                    
                    // Basic password validation
                    if (newPassword.value.length < 8) {
                        e.preventDefault();
                        alert('New password must be at least 8 characters long');
                        newPassword.focus();
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                    
                    if (newPassword.value !== confirmPassword.value) {
                        e.preventDefault();
                        alert('New passwords do not match');
                        confirmPassword.focus();
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                    
                    // Password complexity validation
                    const hasUpperCase = /[A-Z]/.test(newPassword.value);
                    const hasLowerCase = /[a-z]/.test(newPassword.value);
                    const hasNumbers = /\d/.test(newPassword.value);
                    const hasSpecialChar = /[@$!%*?&]/.test(newPassword.value);
                    
                    if (!hasUpperCase || !hasLowerCase || !hasNumbers || !hasSpecialChar) {
                        e.preventDefault();
                        alert('Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&)');
                        newPassword.focus();
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                });
            }
            
            // Load supervisors on page load for employees
            @if($user->user_type === 'employee')
                loadSupervisors();
            @endif
        });

        function loadSupervisors() {
            const supervisorSelect = document.getElementById('manager_id');
            const loadingDiv = document.getElementById('supervisorLoading');
            
            if (!supervisorSelect || !loadingDiv) return;
            
            loadingDiv.classList.remove('hidden');
            supervisorSelect.disabled = true;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("profile.supervisors") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingDiv.classList.add('hidden');
                supervisorSelect.disabled = false;
                
                // Clear existing options except the first one
                const currentValue = supervisorSelect.value;
                supervisorSelect.innerHTML = '<option value="">Select Supervisor</option>';
                
                if (data.success && data.supervisors && data.supervisors.length > 0) {
                    data.supervisors.forEach(supervisor => {
                        const option = document.createElement('option');
                        option.value = supervisor.employee_number;
                        option.textContent = `${supervisor.name} (${supervisor.employee_number}) - ${supervisor.department}`;
                        
                        // Preselect current supervisor
                        @if($user->manager_id)
                            if (supervisor.employee_number === '{{ $user->manager_id }}') {
                                option.selected = true;
                            }
                        @endif
                        
                        supervisorSelect.appendChild(option);
                    });
                    
                    // If no supervisor was selected but there's a current manager, try to select it
                    if (!currentValue && '{{ $user->manager_id }}') {
                        supervisorSelect.value = '{{ $user->manager_id }}';
                    }
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No supervisors available';
                    supervisorSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading supervisors:', error);
                loadingDiv.classList.add('hidden');
                supervisorSelect.disabled = false;
                
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Error loading supervisors';
                supervisorSelect.innerHTML = '';
                supervisorSelect.appendChild(option);
            });
        }

        // Form validation for profile form
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitProfileBtn');
                const loadingSpan = document.getElementById('profileLoading');
                
                // Show loading
                submitBtn.disabled = true;
                loadingSpan.classList.remove('hidden');
                
                // Check workstation type
                const workstationType = document.getElementById('workstation_type');
                const tollPlazaSelect = document.getElementById('toll_plaza_select');
                const otherTollPlaza = document.querySelector('input[name="other_toll_plaza"]');
                
                // If workstation is toll plaza, validate toll plaza selection
                if (workstationType && workstationType.value === 'toll_plaza') {
                    if (!tollPlazaSelect.value) {
                        e.preventDefault();
                        alert('Please select a toll plaza');
                        tollPlazaSelect.focus();
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                    
                    // If "Other" is selected, validate other_toll_plaza field
                    if (tollPlazaSelect.value === 'other') {
                        if (!otherTollPlaza || !otherTollPlaza.value.trim()) {
                            e.preventDefault();
                            alert('Please specify your toll plaza name');
                            if (otherTollPlaza) otherTollPlaza.focus();
                            submitBtn.disabled = false;
                            loadingSpan.classList.add('hidden');
                            return false;
                        }
                    }
                }
                
                @if($user->user_type === 'employee')
                    const supervisorSelect = document.getElementById('manager_id');
                    if (supervisorSelect && !supervisorSelect.value) {
                        e.preventDefault();
                        alert('Please select a supervisor');
                        supervisorSelect.focus();
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                @endif
                
                // Validate required fields
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidField = null;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                    }
                    submitBtn.disabled = false;
                    loadingSpan.classList.add('hidden');
                    return false;
                }
                
                // Special validation for employee number changes
                const employeeNumberField = this.querySelector('input[name="employee_number"]');
                const currentEmployeeNumber = '{{ $user->employee_number }}';
                
                if (employeeNumberField && employeeNumberField.value !== currentEmployeeNumber) {
                    const confirmChange = confirm('Changing your employee number may affect system references. Are you sure you want to change it?');
                    if (!confirmChange) {
                        e.preventDefault();
                        employeeNumberField.value = currentEmployeeNumber;
                        submitBtn.disabled = false;
                        loadingSpan.classList.add('hidden');
                        return false;
                    }
                }
                
                return true;
            });
        }
    </script>

    <!-- Auto-scroll to top if there are errors -->
    @if($errors->any())
    <script>
        window.onload = function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
    @endif
 <script>
    // SIMPLIFIED VERSION - Add this to your script
document.addEventListener('DOMContentLoaded', function() {
    const jobTitleSelect = document.getElementById('job_title');
    const otherJobTitleContainer = document.getElementById('otherJobTitleContainer');
    
    if (jobTitleSelect && otherJobTitleContainer) {
        // Function to show/hide other job title field
        function handleJobTitleChange() {
            if (jobTitleSelect.value === 'Other') {
                otherJobTitleContainer.style.display = 'block';
                // Make the input required
                const otherInput = otherJobTitleContainer.querySelector('input[name="other_job_title"]');
                if (otherInput) {
                    otherInput.required = true;
                }
            } else {
                otherJobTitleContainer.style.display = 'none';
                // Remove required attribute
                const otherInput = otherJobTitleContainer.querySelector('input[name="other_job_title"]');
                if (otherInput) {
                    otherInput.required = false;
                }
            }
        }
        
        // Initialize on page load
        handleJobTitleChange();
        
        // Add event listener for changes
        jobTitleSelect.addEventListener('change', handleJobTitleChange);
        
        // Debug logging
        console.log('Job title select found:', jobTitleSelect);
        console.log('Other container found:', otherJobTitleContainer);
    } else {
        console.error('Job title elements not found:', {
            jobTitleSelect: !!jobTitleSelect,
            otherJobTitleContainer: !!otherJobTitleContainer
        });
    }
});
 </script>
</body>
</html>