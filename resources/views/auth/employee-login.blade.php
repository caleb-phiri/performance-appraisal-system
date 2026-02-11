<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MOIC Performance Appraisal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #110484 0%, #1a0c9e 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #110484 0%, #e7581c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .password-placeholder {
            letter-spacing: 2px;
            font-weight: bold;
        }
        
        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #110484;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Slide-in animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- MOIC Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white p-3 rounded-lg shadow-sm mb-4">
                <img src="{{ asset('images/moic.png') }}" alt="MOIC Logo" class="h-12 w-auto mx-auto" onerror="this.style.display='none'">
            </div>
            <h1 class="text-3xl font-bold gradient-text">Performance Appraisal System</h1>
            <p class="text-gray-600 mt-2">Ministry of Infrastructure and Communications</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="gradient-bg text-white py-4 px-6">
                <h2 class="text-xl font-bold">Sign In to Your Account</h2>
                <p class="text-blue-100 text-sm mt-1">Enter your credentials to continue</p>
            </div>
            
            <div class="p-6">
                @if(session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <div class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Login Failed:</div>
                        <ul class="text-sm ml-5">
                            @foreach($errors->all() as $error)
                                <li class="list-disc">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('show_onboarding_link'))
                    <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded-lg mb-4 slide-in">
                        <div class="flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            <div>
                                <p class="font-semibold">New Employee?</p>
                                <p class="text-sm mt-1">Please complete your profile setup first.</p>
                                <a href="{{ route('onboarding.survey') }}" class="inline-block mt-2 text-white bg-[#110484] hover:bg-[#0a035e] px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                    <i class="fas fa-user-edit mr-1"></i> Complete Profile Setup
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.login') }}" id="loginForm">
                    @csrf

                    <div class="space-y-5">
                        <!-- Employee Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-badge mr-2 text-[#110484]"></i>Employee Number *
                            </label>
                            <input type="text" 
                                   name="employee_number" 
                                   id="employee_number"
                                   value="{{ old('employee_number') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent transition duration-200"
                                   placeholder="Enter your employee number"
                                   required
                                   autofocus
                                   minlength="3"
                                   maxlength="20">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Your unique employee ID (minimum 3 characters)
                            </p>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    <i class="fas fa-lock mr-2 text-[#110484]"></i>Password
                                    <span id="passwordStatus" class="text-xs font-normal ml-2 text-gray-500">
                                       
                                    </span>
                                </label>
                                <span class="text-xs text-gray-500" id="passwordInfo">
                                    Optional for first-time login
                                </span>
                            </div>
                            
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent transition duration-200"
                                       placeholder="●●●●●●●●"
                                       autocomplete="current-password">
                                <button type="button" 
                                        id="togglePasswordBtn" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password Instructions -->
                            <div id="passwordInstructions" class="mt-2 text-xs space-y-1">
                                <p class="text-gray-600 hidden" id="firstTimeMsg">
                                    <i class="fas fa-unlock text-blue-500 mr-1"></i>
                                    <strong>First time?</strong> Leave password blank to proceed
                                </p>
                                <p class="text-gray-600 hidden" id="returningMsg">
                                    <i class="fas fa-lock text-green-500 mr-1"></i>
                                    <strong>Returning user?</strong> Enter your password
                                </p>
                            </div>
                            
                            <!-- Login Information -->
                            <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-3">
                                <h4 class="font-semibold text-[#110484] text-sm mb-1 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>Login Rules
                                </h4>
                                <p class="text-xs text-gray-600">
                                    • <strong>First-time users</strong>: Login with just employee number (leave password blank)<br>
                                    • <strong>Returning users with password</strong>: Must enter correct password<br>
                                    • <strong>Forgot password?</strong>: Contact system administrator<br>
                                    • <strong>New employee?</strong>: Complete profile setup first
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                id="submitBtn"
                                class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                        </button>
                    </div>
                </form>

                <!-- Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center space-y-3">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-question-circle mr-1 text-[#110484]"></i>
                            New to the system? 
                            <a href="{{ route('onboarding.survey') }}" class="text-[#110484] font-medium hover:text-[#e7581c] transition duration-200">
                                Complete your profile setup
                            </a>
                        </p>
                        
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-3">
                            <h4 class="font-semibold text-green-800 text-sm mb-1">
                                <i class="fas fa-shield-alt mr-1"></i>Security Note
                            </h4>
                            <p class="text-xs text-gray-600">
                                For security, set up a password after your first login. 
                                This adds an extra layer of protection to your account.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                MOIC Performance Appraisal System © {{ date('Y') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Version 1.0.0 • powered by SmartWave Solutions


            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeInput = document.getElementById('employee_number');
            const passwordInput = document.getElementById('password');
            const passwordInfo = document.getElementById('passwordInfo');
            const passwordStatus = document.getElementById('passwordStatus');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const firstTimeMsg = document.getElementById('firstTimeMsg');
            const returningMsg = document.getElementById('returningMsg');
            
            // Show/hide messages based on employee number input
            function updatePasswordInstructions() {
                const empNumber = employeeInput.value.trim();
                
                if (empNumber.length >= 3) {
                    // For demonstration - you could add logic here based on known patterns
                    // For example, if employee number matches certain pattern, show different message
                    firstTimeMsg.classList.remove('hidden');
                    returningMsg.classList.remove('hidden');
                    passwordInfo.textContent = 'Enter password if you have one';
                    passwordStatus.textContent = '(Enter password if you have one)';
                } else {
                    firstTimeMsg.classList.add('hidden');
                    returningMsg.classList.add('hidden');
                    passwordInfo.textContent = 'Enter employee number first';
                    passwordStatus.textContent = '(Enter employee number first)';
                }
            }
            
            // Initialize
            updatePasswordInstructions();
            
            // Event listeners for employee number input
            employeeInput.addEventListener('input', updatePasswordInstructions);
            employeeInput.addEventListener('blur', updatePasswordInstructions);
            
            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    this.setAttribute('aria-label', 'Hide password');
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                    this.setAttribute('aria-label', 'Show password');
                }
            });
            
            // Press Enter in employee field to move to password field
            employeeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim().length >= 3) {
                    e.preventDefault();
                    passwordInput.focus();
                }
            });
            
            // Press Enter in password field to submit
            passwordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    // Form will submit naturally
                }
            });
            
            // Form submission validation
            loginForm.addEventListener('submit', function(e) {
                const employeeNumber = employeeInput.value.trim();
                const password = passwordInput.value;
                
                // Basic validation
                if (!employeeNumber) {
                    e.preventDefault();
                    showError('Please enter your employee number');
                    employeeInput.focus();
                    return false;
                }
                
                if (employeeNumber.length < 3) {
                    e.preventDefault();
                    showError('Please enter a valid employee number (minimum 3 characters)');
                    employeeInput.focus();
                    return false;
                }
                
                // Show loading state
                submitBtn.innerHTML = '<div class="spinner mr-2"></div> Signing in...';
                submitBtn.disabled = true;
                
                // Change cursor to indicate loading
                document.body.style.cursor = 'wait';
                
                // Auto re-enable after 8 seconds if submission fails
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> Sign In';
                    submitBtn.disabled = false;
                    document.body.style.cursor = 'default';
                }, 8000);
                
                return true;
            });
            
            // Function to show temporary error messages
            function showError(message) {
                // Remove any existing error alerts
                const existingAlerts = document.querySelectorAll('.temp-error-alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // Create error alert
                const errorDiv = document.createElement('div');
                errorDiv.className = 'temp-error-alert bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 slide-in';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                // Insert after session messages or before form
                const sessionMessages = document.querySelector('.bg-green-50, .bg-blue-50, .bg-yellow-50, .bg-red-50');
                if (sessionMessages) {
                    sessionMessages.parentNode.insertBefore(errorDiv, sessionMessages.nextSibling);
                } else {
                    loginForm.parentNode.insertBefore(errorDiv, loginForm);
                }
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.parentNode.removeChild(errorDiv);
                    }
                }, 5000);
                
                // Scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Add animation to form elements on load
            const formElements = document.querySelectorAll('#loginForm input, #loginForm button');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';
                element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
            
            // Focus on employee input with a slight delay for better UX
            setTimeout(() => {
                if (employeeInput.value === '') {
                    employeeInput.focus();
                }
            }, 300);
        });
    </script>
</body>
</html>