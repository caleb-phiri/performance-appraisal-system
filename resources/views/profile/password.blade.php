<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Management - MOIC</title>
    {{-- Use built Tailwind via Vite (do not use CDN in production) --}}
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .password-strength-meter {
            height: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .password-requirement {
            transition: all 0.3s ease;
        }
        
        .requirement-met {
            color: #10b981;
        }
        
        .requirement-not-met {
            color: #9ca3af;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-white p-1.5 rounded-md mr-3">
                        <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-xl font-bold">
                        @if($requiresPasswordSetup)
                            Set Up Password
                        @else
                            Update Password
                        @endif
                    </h1>
                </div>
                <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-r from-[#110484] to-[#1a0c9e] mb-4">
                    <i class="fas fa-key text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-[#110484] mb-2">
                    @if($requiresPasswordSetup)
                        Set Up Your Password
                    @else
                        Update Your Password
                    @endif
                </h2>
                <p class="text-gray-600">Manage your account security</p>
            </div>

            @if($requiresPasswordSetup)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-[#110484] mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>First Time Password Setup
                </h4>
                <p class="text-sm text-gray-600">
                    Setting up a password is optional but recommended. Once set, you'll need to enter it every time you login.
                    <span class="block mt-1 font-medium text-[#110484]">If you skip, you can login with just your employee number.</span>
                </p>
            </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ session('info') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FIXED FORM: Separate logic for setup vs update -->
            @if($requiresPasswordSetup)
                <!-- ============================================== -->
                <!-- FIRST TIME PASSWORD SETUP FORM -->
                <!-- ============================================== -->
                <form action="{{ route('profile.password.setup') }}" method="POST" id="setupForm">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="setup_new_password">
                                New Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password" 
                                       id="setup_new_password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                       placeholder="Enter your new password" 
                                       required
                                       minlength="6">
                                <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                        onclick="togglePasswordVisibility('setup_new_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Minimum 6 characters. This password will be required for future logins.
                            </p>
                            
                            <!-- Password strength indicator for setup -->
                            <div id="setupPasswordStrength" class="mt-2 hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                                    <span id="setupStrengthText" class="text-xs font-medium"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="setupStrengthBar" class="password-strength-meter h-1.5 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="setup_new_password_confirmation">
                                Confirm New Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password_confirmation" 
                                       id="setup_new_password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                       placeholder="Confirm your new password" 
                                       required
                                       minlength="6">
                                <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                        onclick="togglePasswordVisibility('setup_new_password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password match indicator -->
                            <div id="setupPasswordMatch" class="mt-2 hidden">
                                <p id="setupMatchText" class="text-xs"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition duration-200 font-medium">
                            Set Password
                        </button>
                    </div>
                </form>
                
            @else
                <!-- ============================================== -->
                <!-- UPDATE EXISTING PASSWORD FORM -->
                <!-- ============================================== -->
                <form action="{{ route('profile.password.update') }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="update_current_password">
                                Current Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="current_password" 
                                       id="update_current_password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                       placeholder="Enter your current password"
                                       required>
                                <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                        onclick="togglePasswordVisibility('update_current_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="update_new_password">
                                New Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password" 
                                       id="update_new_password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                       placeholder="Enter new password" 
                                       required
                                       minlength="8">
                                <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                        onclick="togglePasswordVisibility('update_new_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-1">
                                Password must contain at least:
                            </p>
                            <ul class="text-xs text-gray-500 mt-1 space-y-1">
                                <li id="req-length" class="password-requirement requirement-not-met">
                                    <i class="fas fa-times mr-1"></i> 8 characters
                                </li>
                                <li id="req-upper" class="password-requirement requirement-not-met">
                                    <i class="fas fa-times mr-1"></i> One uppercase letter
                                </li>
                                <li id="req-lower" class="password-requirement requirement-not-met">
                                    <i class="fas fa-times mr-1"></i> One lowercase letter
                                </li>
                                <li id="req-number" class="password-requirement requirement-not-met">
                                    <i class="fas fa-times mr-1"></i> One number
                                </li>
                                <li id="req-special" class="password-requirement requirement-not-met">
                                    <i class="fas fa-times mr-1"></i> One special character (@$!%*?&)
                                </li>
                            </ul>
                            
                            <!-- Password strength indicator for update -->
                            <div id="updatePasswordStrength" class="mt-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                                    <span id="updateStrengthText" class="text-xs font-medium"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="updateStrengthBar" class="password-strength-meter h-1.5 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="update_new_password_confirmation">
                                Confirm New Password *
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password_confirmation" 
                                       id="update_new_password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                       placeholder="Confirm new password" 
                                       required
                                       minlength="8">
                                <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                        onclick="togglePasswordVisibility('update_new_password_confirmation', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password match indicator -->
                            <div id="updatePasswordMatch" class="mt-2">
                                <p id="updateMatchText" class="text-xs"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition duration-200 font-medium">
                            Update Password
                        </button>
                    </div>
                </form>
            @endif
            
            @if($requiresPasswordSetup)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-4">Don't want to set a password?</p>
                <form action="{{ route('profile.password.skip') }}" method="POST" id="skipForm">
                    @csrf
                    <button type="button" onclick="skipPassword()"
                            class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                        Skip Password Setup
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2">
                    You can login with just your employee number. You can set up a password later from your profile.
                </p>
            </div>
            @endif
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('profile.show') }}" class="text-[#110484] hover:text-[#e7581c] font-medium">
                <i class="fas fa-user-circle mr-1"></i> Back to Profile
            </a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Skip password confirmation
        function skipPassword() {
            if (confirm('Are you sure? You can login with just your employee number, but this is less secure. You can set up a password later from your profile.')) {
                document.getElementById('skipForm').submit();
            }
        }
        
        // ==============================================
        // PASSWORD VALIDATION FOR SETUP FORM
        // ==============================================
        @if($requiresPasswordSetup)
        document.addEventListener('DOMContentLoaded', function() {
            const setupNewPassword = document.getElementById('setup_new_password');
            const setupConfirmPassword = document.getElementById('setup_new_password_confirmation');
            const setupStrengthContainer = document.getElementById('setupPasswordStrength');
            const setupStrengthBar = document.getElementById('setupStrengthBar');
            const setupStrengthText = document.getElementById('setupStrengthText');
            const setupMatchContainer = document.getElementById('setupPasswordMatch');
            const setupMatchText = document.getElementById('setupMatchText');
            
            // Setup password strength check
            if (setupNewPassword) {
                setupNewPassword.addEventListener('input', function() {
                    const password = this.value;
                    
                    if (password.length > 0) {
                        setupStrengthContainer.classList.remove('hidden');
                        updateSetupPasswordStrength(password);
                    } else {
                        setupStrengthContainer.classList.add('hidden');
                    }
                    
                    // Check password match
                    if (setupConfirmPassword.value.length > 0) {
                        checkSetupPasswordMatch();
                    }
                });
            }
            
            // Setup confirm password match check
            if (setupConfirmPassword) {
                setupConfirmPassword.addEventListener('input', function() {
                    checkSetupPasswordMatch();
                });
            }
            
            function updateSetupPasswordStrength(password) {
                let score = 0;
                const maxScore = 5;
                
                // Simple scoring for setup (minimum 6 chars)
                if (password.length >= 6) score++;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[@$!%*?&]/.test(password)) score++;
                
                // Calculate percentage
                const percentage = (score / maxScore) * 100;
                
                // Update strength bar
                setupStrengthBar.style.width = `${percentage}%`;
                
                // Update colors and text
                if (score <= 1) {
                    setupStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-red-500';
                    setupStrengthText.textContent = 'Weak';
                    setupStrengthText.className = 'text-xs font-medium text-red-500';
                } else if (score <= 2) {
                    setupStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-yellow-500';
                    setupStrengthText.textContent = 'Fair';
                    setupStrengthText.className = 'text-xs font-medium text-yellow-500';
                } else if (score <= 3) {
                    setupStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-blue-500';
                    setupStrengthText.textContent = 'Good';
                    setupStrengthText.className = 'text-xs font-medium text-blue-500';
                } else {
                    setupStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-green-500';
                    setupStrengthText.textContent = 'Strong';
                    setupStrengthText.className = 'text-xs font-medium text-green-500';
                }
            }
            
            function checkSetupPasswordMatch() {
                const password = setupNewPassword.value;
                const confirmPassword = setupConfirmPassword.value;
                
                if (password.length > 0 && confirmPassword.length > 0) {
                    setupMatchContainer.classList.remove('hidden');
                    
                    if (password === confirmPassword) {
                        setupMatchText.textContent = 'Passwords match ✓';
                        setupMatchText.className = 'text-xs text-green-600';
                        setupConfirmPassword.classList.remove('border-red-300');
                        setupConfirmPassword.classList.add('border-green-300');
                    } else {
                        setupMatchText.textContent = 'Passwords do not match ✗';
                        setupMatchText.className = 'text-xs text-red-600';
                        setupConfirmPassword.classList.remove('border-green-300');
                        setupConfirmPassword.classList.add('border-red-300');
                    }
                } else {
                    setupMatchContainer.classList.add('hidden');
                    setupConfirmPassword.classList.remove('border-red-300', 'border-green-300');
                }
            }
            
            // Form validation for setup
            const setupForm = document.getElementById('setupForm');
            if (setupForm) {
                setupForm.addEventListener('submit', function(e) {
                    const password = setupNewPassword.value;
                    const confirmPassword = setupConfirmPassword.value;
                    
                    // Check minimum length
                    if (password.length < 6) {
                        e.preventDefault();
                        alert('Password must be at least 6 characters long.');
                        setupNewPassword.focus();
                        return false;
                    }
                    
                    // Check password match
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match. Please confirm your password.');
                        setupConfirmPassword.focus();
                        return false;
                    }
                    
                    return true;
                });
            }
        });
        @endif
        
        // ==============================================
        // PASSWORD VALIDATION FOR UPDATE FORM
        // ==============================================
        @if(!$requiresPasswordSetup)
        document.addEventListener('DOMContentLoaded', function() {
            const updateNewPassword = document.getElementById('update_new_password');
            const updateConfirmPassword = document.getElementById('update_new_password_confirmation');
            const updateStrengthBar = document.getElementById('updateStrengthBar');
            const updateStrengthText = document.getElementById('updateStrengthText');
            const updateMatchText = document.getElementById('updateMatchText');
            
            // Update password strength check
            if (updateNewPassword) {
                updateNewPassword.addEventListener('input', function() {
                    const password = this.value;
                    updatePasswordRequirements(password);
                    updatePasswordStrength(password);
                    
                    // Check password match
                    if (updateConfirmPassword.value.length > 0) {
                        checkUpdatePasswordMatch();
                    }
                });
            }
            
            // Update confirm password match check
            if (updateConfirmPassword) {
                updateConfirmPassword.addEventListener('input', function() {
                    checkUpdatePasswordMatch();
                });
            }
            
            function updatePasswordRequirements(password) {
                const requirements = {
                    length: password.length >= 8,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[@$!%*?&]/.test(password)
                };
                
                // Update requirement indicators
                Object.keys(requirements).forEach(key => {
                    const element = document.getElementById(`req-${key}`);
                    const icon = element.querySelector('i');
                    
                    if (requirements[key]) {
                        element.classList.remove('requirement-not-met');
                        element.classList.add('requirement-met');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-check');
                    } else {
                        element.classList.remove('requirement-met');
                        element.classList.add('requirement-not-met');
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-times');
                    }
                });
            }
            
            function updatePasswordStrength(password) {
                let score = 0;
                const maxScore = 5;
                
                // Check requirements
                const checks = {
                    length: password.length >= 8,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[@$!%*?&]/.test(password)
                };
                
                // Count met requirements
                Object.values(checks).forEach(check => {
                    if (check) score++;
                });
                
                // Calculate percentage
                const percentage = (score / maxScore) * 100;
                
                // Update strength bar
                updateStrengthBar.style.width = `${percentage}%`;
                
                // Update colors and text
                if (score <= 1) {
                    updateStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-red-500';
                    updateStrengthText.textContent = 'Very Weak';
                    updateStrengthText.className = 'text-xs font-medium text-red-500';
                } else if (score <= 2) {
                    updateStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-orange-500';
                    updateStrengthText.textContent = 'Weak';
                    updateStrengthText.className = 'text-xs font-medium text-orange-500';
                } else if (score <= 3) {
                    updateStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-yellow-500';
                    updateStrengthText.textContent = 'Fair';
                    updateStrengthText.className = 'text-xs font-medium text-yellow-500';
                } else if (score <= 4) {
                    updateStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-blue-500';
                    updateStrengthText.textContent = 'Good';
                    updateStrengthText.className = 'text-xs font-medium text-blue-500';
                } else {
                    updateStrengthBar.className = 'password-strength-meter h-1.5 rounded-full bg-green-500';
                    updateStrengthText.textContent = 'Strong';
                    updateStrengthText.className = 'text-xs font-medium text-green-500';
                }
            }
            
            function checkUpdatePasswordMatch() {
                const password = updateNewPassword.value;
                const confirmPassword = updateConfirmPassword.value;
                
                if (password.length > 0 && confirmPassword.length > 0) {
                    if (password === confirmPassword) {
                        updateMatchText.textContent = 'Passwords match ✓';
                        updateMatchText.className = 'text-xs text-green-600';
                        updateConfirmPassword.classList.remove('border-red-300');
                        updateConfirmPassword.classList.add('border-green-300');
                    } else {
                        updateMatchText.textContent = 'Passwords do not match ✗';
                        updateMatchText.className = 'text-xs text-red-600';
                        updateConfirmPassword.classList.remove('border-green-300');
                        updateConfirmPassword.classList.add('border-red-300');
                    }
                } else {
                    updateMatchText.textContent = '';
                    updateConfirmPassword.classList.remove('border-red-300', 'border-green-300');
                }
            }
            
            // Form validation for update
            const updateForm = document.getElementById('updateForm');
            if (updateForm) {
                updateForm.addEventListener('submit', function(e) {
                    const currentPassword = document.getElementById('update_current_password').value;
                    const newPassword = updateNewPassword.value;
                    const confirmPassword = updateConfirmPassword.value;
                    
                    // Check current password
                    if (!currentPassword) {
                        e.preventDefault();
                        alert('Please enter your current password.');
                        document.getElementById('update_current_password').focus();
                        return false;
                    }
                    
                    // Check new password requirements
                    const requirements = {
                        length: newPassword.length >= 8,
                        upper: /[A-Z]/.test(newPassword),
                        lower: /[a-z]/.test(newPassword),
                        number: /\d/.test(newPassword),
                        special: /[@$!%*?&]/.test(newPassword)
                    };
                    
                    // Check if all requirements are met
                    const allMet = Object.values(requirements).every(met => met);
                    
                    if (!allMet) {
                        e.preventDefault();
                        alert('New password does not meet all requirements. Please check the password requirements.');
                        updateNewPassword.focus();
                        return false;
                    }
                    
                    // Check password match
                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('New passwords do not match. Please confirm your new password.');
                        updateConfirmPassword.focus();
                        return false;
                    }
                    
                    // Check if new password is same as current (optional)
                    if (newPassword === currentPassword) {
                        if (!confirm('New password is the same as current password. Are you sure you want to continue?')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    
                    return true;
                });
            }
        });
        @endif
    </script>
</body>
</html>