<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reset Password • MOIC Enterprise Appraisal System</title>

  <!-- Fonts & icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root{
      --bg:#f8fafc;
      --surface:#ffffff;
      --muted:#64748b;
      --navy:#110484;
      --accent:#e7581c;
      --success:#10b981;
      --radius:10px;
      --shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      --gradient-primary: linear-gradient(135deg, #110484, #0c0361);
      --gradient-accent: linear-gradient(135deg, #e7581c, #c24716);
      --gradient-mixed: linear-gradient(135deg, #110484, #e7581c);
    }

    *{box-sizing:border-box}
    html, body{
      height:100%;
      margin:0;
      font-family:Inter,sans-serif;
      line-height:1.5;
    }
    a{text-decoration:none;color:inherit;}

    /* Background Image */
    body {
      background-image: url('/images/TKC.png');
      background-size: 70% auto;
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      position: relative;
      background-color: #f8fafc;
    }

    /* Header - Clean design */
    header{
      width:100%;
      background: rgba(255, 251, 251, 0.9);
      padding:10px 20px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      box-shadow: 0 2px 10px rgba(17, 4, 132, 0.1);
      position:fixed; 
      top:0; 
      left:0; 
      z-index:50;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(17, 4, 132, 0.08);
    }
    .brand{
      display:flex; 
      align-items:center; 
      gap:8px;
    }
    /* Smaller logo */
    .brand .logo{
      width:30px; 
      height:30px; 
      border-radius:6px;
      background:var(--gradient-mixed);
      display:flex; 
      align-items:center; 
      justify-content:center; 
      color:white; 
      font-weight:700; 
      font-size:12px;
      box-shadow: 0 2px 5px rgba(17, 4, 132, 0.15);
    }
    .brand .title{
      font-weight:700;
      background:var(--gradient-mixed);
      -webkit-background-clip:text;
      background-clip:text;
      color:transparent;
      font-size:0.85rem;
    }
    .brand .subtitle {
      font-size:9px;
      color:var(--muted);
    }
    .actions a{
      padding:5px 10px;
      border-radius:5px;
      font-weight:600;
      transition:0.2s;
      margin-left:5px;
      font-size: 0.8rem;
    }
    .login-btn{
      border:1px solid rgba(17,4,132,0.2);
      background:transparent;
      color:var(--navy);
    }
    .login-btn:hover{
      background:var(--gradient-mixed);
      color:white;
      transform: translateY(-1px);
      border-color: transparent;
    }
    .register-btn{
      background:var(--gradient-mixed);
      color:white;
      border:none;
      box-shadow: 0 2px 6px rgba(17, 4, 132, 0.2);
    }
    .register-btn:hover{
      transform:translateY(-1px);
      box-shadow:0 3px 8px rgba(231,88,28,0.3);
    }

    /* Reset Container */
    .reset-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 80px 15px 40px;
      position: relative;
    }

    /* Reset Form Box - Matching design */
    .reset-box {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 18px;
      padding: 40px 35px;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.2),
        0 5px 15px rgba(255, 255, 255, 0.6) inset;
      border: 1px solid rgba(255, 255, 255, 0.6);
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      border-left: 4px solid var(--accent);
      border-right: 4px solid var(--navy);
    }

    /* Gradient top border */
    .reset-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--gradient-mixed);
      z-index: 1;
    }

    /* Subtle gradient overlay */
    .reset-box::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, 
        rgba(17, 4, 132, 0.05) 0%, 
        rgba(231, 88, 28, 0.05) 100%);
      z-index: -1;
    }

    /* Form Header */
    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-header h1 {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--navy);
      margin-bottom: 12px;
      line-height: 1.2;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .form-header h1 i {
      color: var(--accent);
      font-size: 1.6rem;
    }

    .form-header p {
      color: var(--muted);
      font-size: 0.95rem;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
      line-height: 1.6;
    }

    /* Success Message Box */
    .success-box {
      background: rgba(16, 185, 129, 0.1);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
      border-left: 4px solid var(--success);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .success-icon {
      color: var(--success);
      font-size: 1.5rem;
      background: rgba(16, 185, 129, 0.15);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .success-text {
      color: var(--navy);
      font-size: 0.95rem;
      line-height: 1.6;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 25px;
    }

    .form-label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.95rem;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-label i {
      color: var(--accent);
    }

    .form-input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px 14px 45px;
      border-radius: 10px;
      border: 1px solid rgba(17, 4, 132, 0.2);
      background: rgba(255, 255, 255, 0.9);
      font-family: Inter, sans-serif;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      color: var(--navy);
      box-shadow: 0 2px 8px rgba(17, 4, 132, 0.08);
    }

    .form-input:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
      background: rgba(255, 255, 255, 0.95);
    }

    .form-input::placeholder {
      color: rgba(100, 116, 139, 0.6);
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 1.1rem;
    }

    .toggle-password {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      font-size: 1.1rem;
      padding: 5px;
      transition: color 0.3s ease;
    }

    .toggle-password:hover {
      color: var(--accent);
    }

    /* Password Strength Indicator */
    .password-strength {
      margin-top: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .strength-bar {
      flex: 1;
      height: 6px;
      background: rgba(100, 116, 139, 0.2);
      border-radius: 3px;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      width: 0%;
      background: #dc2626;
      border-radius: 3px;
      transition: all 0.3s ease;
    }

    .strength-text {
      font-size: 0.8rem;
      color: var(--muted);
      min-width: 80px;
      text-align: right;
      font-weight: 500;
    }

    /* Password Requirements */
    .password-requirements {
      margin-top: 15px;
      padding: 15px;
      background: rgba(17, 4, 132, 0.03);
      border-radius: 8px;
      border-left: 3px solid var(--navy);
    }

    .requirements-title {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--navy);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .requirements-title i {
      color: var(--accent);
    }

    .requirement {
      font-size: 0.8rem;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 6px;
      transition: color 0.3s ease;
    }

    .requirement.valid {
      color: var(--success);
    }

    .requirement i {
      font-size: 0.7rem;
      transition: all 0.3s ease;
    }

    /* Password Match Indicator */
    .match-indicator {
      font-size: 0.85rem;
      margin-top: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .match-indicator.visible {
      opacity: 1;
    }

    .match-indicator.match {
      color: var(--success);
    }

    .match-indicator.mismatch {
      color: #dc2626;
    }

    /* Error Messages */
    .error-message {
      color: #dc2626;
      font-size: 0.85rem;
      margin-top: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
    }

    .error-message i {
      font-size: 0.9rem;
    }

    /* Form Actions */
    .form-actions {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 30px;
    }

    /* Reset Button */
    .reset-button {
      background: var(--gradient-mixed);
      color: white;
      border: none;
      padding: 14px 28px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(17, 4, 132, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
    }

    .reset-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.5);
    }

    .reset-button:active {
      transform: translateY(0);
    }

    /* Back Link */
    .back-link {
      text-align: center;
      margin-top: 25px;
    }

    .back-link a {
      color: var(--navy);
      font-size: 0.9rem;
      font-weight: 500;
      transition: color 0.3s ease;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .back-link a:hover {
      color: var(--accent);
      text-decoration: underline;
    }

    /* Footer - Fixed at bottom */
    footer{
      padding:20px;
      text-align:center;
      color:var(--navy);
      font-size:0.8rem;
      background: rgba(255, 255, 255, 0.8);
      border-top: 1px solid rgba(17, 4, 132, 0.15);
      box-shadow: 0 -2px 10px rgba(17, 4, 132, 0.05);
      backdrop-filter: blur(8px);
      font-weight: 500;
      
      /* Make it fixed at the bottom */
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 40;
      
      /* Remove margin-top since it's fixed */
      margin-top: 0;
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
      background: var(--gradient-mixed);
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
        background-size: 85% auto;
      }
      
      .reset-box {
        padding: 30px 25px;
        max-width: 90%;
      }
      
      .form-header h1 {
        font-size: 1.6rem;
      }
      
      .form-header p {
        font-size: 0.9rem;
      }
      
      header {
        flex-direction: row;
        gap: 8px;
        padding: 10px 15px;
      }
      
      .success-box {
        padding: 15px;
        flex-direction: column;
        text-align: center;
        gap: 10px;
      }
      
      .password-requirements {
        padding: 12px;
      }
      
      .reset-button {
        padding: 12px 24px;
      }
    }

    @media (max-width: 480px) {
      body {
        background-size: 95% auto;
      }
      
      .reset-box {
        padding: 25px 20px;
      }
      
      .form-header h1 {
        font-size: 1.4rem;
        flex-direction: column;
        gap: 8px;
      }
      
      .form-header p {
        font-size: 0.85rem;
      }
      
      .brand .logo {
        width: 28px;
        height: 28px;
        font-size: 11px;
      }
      
      .brand .title {
        font-size: 0.8rem;
      }
      
      .brand .subtitle {
        font-size: 8px;
      }
      
      .form-input {
        padding: 12px 16px 12px 40px;
      }
      
      .input-icon {
        left: 12px;
      }
      
      .toggle-password {
        right: 12px;
      }
    }

    /* For very large screens */
    @media (min-width: 1100px) {
      body {
        background-size: 75% auto;
      }
    }

    /* Animation for success */
    @keyframes successAnimation {
      0% {
        transform: scale(0.8);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .success-box {
      animation: successAnimation 0.5s ease-out;
    }

    /* Password strength colors */
    .strength-very-weak { background: #dc2626; }
    .strength-weak { background: #f59e0b; }
    .strength-medium { background: #f59e0b; }
    .strength-strong { background: #10b981; }
    .strength-very-strong { background: #10b981; }
  </style>
</head>
<body>

  <!-- Animated Background Particles -->
  <div class="particles" id="particles"></div>

  <!-- Header -->
  <header>
    <div class="brand">
      <div class="logo">M</div>
      <div>
        <div class="title">MOIC Consortium</div>
        <div class="subtitle">Performance Management SIFs</div>
      </div>
    </div>
    
    <div class="actions">
      <a href="{{ route('login') }}" class="login-btn">Log in</a>
      <a href="{{ route('register') }}" class="register-btn">Register</a>
    </div>
  </header>

  <!-- Reset Container -->
  <main class="reset-container">
    <div class="reset-box">
      
      <!-- Success Message -->
      @if (session('status'))
        <div class="success-box">
          <div class="success-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="success-text">
            {{ session('status') }}
          </div>
        </div>
      @endif

      <div class="form-header">
        <h1>
          <i class="fas fa-key"></i> Reset Password
        </h1>
        <p>Create a new password for your MOIC account</p>
      </div>

      <form method="POST" action="{{ route('password.store') }}" id="resetForm">
        @csrf
        
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
          <label class="form-label" for="email">
            <i class="fas fa-envelope"></i> Email Address
          </label>
          
          <div class="form-input-wrapper">
            <i class="fas fa-at input-icon"></i>
            <input id="email" class="form-input" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="Enter your email address">
          </div>
          
          @if ($errors->has('email'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('email') }}
            </div>
          @endif
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">
            <i class="fas fa-lock"></i> New Password
          </label>
          
          <div class="form-input-wrapper">
            <i class="fas fa-key input-icon"></i>
            <input id="password" class="form-input" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Create a strong password">
            
            <button type="button" class="toggle-password" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <!-- Password Strength Indicator -->
          <div class="password-strength">
            <div class="strength-bar">
              <div class="strength-fill" id="strengthFill"></div>
            </div>
            <div class="strength-text" id="strengthText">Enter password</div>
          </div>
          
          <!-- Password Requirements -->
          <div class="password-requirements">
            <div class="requirements-title">
              <i class="fas fa-list-check"></i> Password Requirements:
            </div>
            <div class="requirement" id="reqLength">
              <i class="fas fa-circle"></i>
              At least 8 characters
            </div>
            <div class="requirement" id="reqUppercase">
              <i class="fas fa-circle"></i>
              One uppercase letter
            </div>
            <div class="requirement" id="reqLowercase">
              <i class="fas fa-circle"></i>
              One lowercase letter
            </div>
            <div class="requirement" id="reqNumber">
              <i class="fas fa-circle"></i>
              One number
            </div>
            <div class="requirement" id="reqSpecial">
              <i class="fas fa-circle"></i>
              One special character
            </div>
          </div>
          
          @if ($errors->has('password'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('password') }}
            </div>
          @endif
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label" for="password_confirmation">
            <i class="fas fa-lock"></i> Confirm New Password
          </label>
          
          <div class="form-input-wrapper">
            <i class="fas fa-key input-icon"></i>
            <input id="password_confirmation" class="form-input" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Confirm your password">
            
            <button type="button" class="toggle-password" id="toggleConfirmPassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          
          <!-- Password Match Indicator -->
          <div class="match-indicator" id="matchIndicator">
            <i class="fas fa-check-circle"></i>
            <span>Passwords match</span>
          </div>
          
          @if ($errors->has('password_confirmation'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('password_confirmation') }}
            </div>
          @endif
        </div>

        <div class="form-actions">
          <button type="submit" class="reset-button">
            <i class="fas fa-rotate"></i> Reset Password
          </button>
        </div>
      </form>

      <div class="back-link">
        <a href="{{ route('login') }}">
          <i class="fas fa-arrow-left"></i> Back to Login
        </a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    © <span id="year"></span> MOIC Consortium — Performance Management SIFs. All rights reserved powered by smartwave solutions.
  </footer>

  <script>
    // Set current year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Create animated particles
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 12;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        const size = Math.random() * 25 + 8;
        const posX = Math.random() * 100;
        const delay = Math.random() * 20;
        const duration = Math.random() * 20 + 30;
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.animationDuration = `${duration}s`;
        particle.style.opacity = Math.random() * 0.08 + 0.05;
        
        particlesContainer.appendChild(particle);
      }
    }

    // Check if background image loads
    function checkBackgroundImage() {
      const img = new Image();
      img.src = '/images/TKC.png';
      
      img.onload = function() {
        console.log('Background image loaded successfully');
        document.body.style.backgroundImage = `url('${img.src}')`;
      };
      
      img.onerror = function() {
        console.log('Background image failed to load');
        document.body.style.background = 'linear-gradient(135deg, #f0f5ff 0%, #e6eeff 100%)';
        document.body.style.backgroundAttachment = 'fixed';
        document.body.style.backgroundSize = 'cover';
      };
    }

    // Toggle password visibility
    function setupPasswordToggle() {
      const togglePassword = document.getElementById('togglePassword');
      const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirmation');
      
      function toggleVisibility(button, input) {
        if (button && input) {
          button.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle eye icon
            const eyeIcon = this.querySelector('i');
            if (type === 'text') {
              eyeIcon.classList.remove('fa-eye');
              eyeIcon.classList.add('fa-eye-slash');
              this.setAttribute('title', 'Hide password');
            } else {
              eyeIcon.classList.remove('fa-eye-slash');
              eyeIcon.classList.add('fa-eye');
              this.setAttribute('title', 'Show password');
            }
          });
        }
      }
      
      toggleVisibility(togglePassword, passwordInput);
      toggleVisibility(toggleConfirmPassword, confirmPasswordInput);
    }

    // Check password strength
    function checkPasswordStrength(password) {
      let score = 0;
      const requirements = {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false,
        special: false
      };

      // Check length (8+ characters)
      if (password.length >= 8) {
        score += 20;
        requirements.length = true;
        updateRequirement('reqLength', true);
      } else {
        updateRequirement('reqLength', false);
      }

      // Check uppercase letters
      if (/[A-Z]/.test(password)) {
        score += 20;
        requirements.uppercase = true;
        updateRequirement('reqUppercase', true);
      } else {
        updateRequirement('reqUppercase', false);
      }

      // Check lowercase letters
      if (/[a-z]/.test(password)) {
        score += 20;
        requirements.lowercase = true;
        updateRequirement('reqLowercase', true);
      } else {
        updateRequirement('reqLowercase', false);
      }

      // Check numbers
      if (/[0-9]/.test(password)) {
        score += 20;
        requirements.number = true;
        updateRequirement('reqNumber', true);
      } else {
        updateRequirement('reqNumber', false);
      }

      // Check special characters
      if (/[^A-Za-z0-9]/.test(password)) {
        score += 20;
        requirements.special = true;
        updateRequirement('reqSpecial', true);
      } else {
        updateRequirement('reqSpecial', false);
      }

      // Update strength bar and text
      const strengthFill = document.getElementById('strengthFill');
      const strengthText = document.getElementById('strengthText');
      
      strengthFill.style.width = `${score}%`;
      
      // Set color and text based on score
      if (score >= 80) {
        strengthFill.className = 'strength-fill strength-very-strong';
        strengthText.textContent = 'Very Strong';
        strengthText.style.color = 'var(--success)';
      } else if (score >= 60) {
        strengthFill.className = 'strength-fill strength-strong';
        strengthText.textContent = 'Strong';
        strengthText.style.color = 'var(--success)';
      } else if (score >= 40) {
        strengthFill.className = 'strength-fill strength-medium';
        strengthText.textContent = 'Medium';
        strengthText.style.color = '#f59e0b';
      } else if (score >= 20) {
        strengthFill.className = 'strength-fill strength-weak';
        strengthText.textContent = 'Weak';
        strengthText.style.color = '#f59e0b';
      } else {
        strengthFill.className = 'strength-fill strength-very-weak';
        strengthText.textContent = 'Very Weak';
        strengthText.style.color = '#dc2626';
      }
      
      return requirements;
    }

    function updateRequirement(elementId, isValid) {
      const element = document.getElementById(elementId);
      if (element) {
        if (isValid) {
          element.classList.add('valid');
          element.innerHTML = '<i class="fas fa-check-circle"></i> ' + element.textContent.replace('●', '').trim();
        } else {
          element.classList.remove('valid');
          element.innerHTML = '<i class="fas fa-circle"></i> ' + element.textContent.replace('✓', '').trim();
        }
      }
    }

    // Check if passwords match
    function checkPasswordMatch() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      const matchIndicator = document.getElementById('matchIndicator');
      
      if (password && confirmPassword) {
        matchIndicator.classList.add('visible');
        if (password === confirmPassword) {
          matchIndicator.classList.remove('mismatch');
          matchIndicator.classList.add('match');
          matchIndicator.innerHTML = '<i class="fas fa-check-circle"></i><span>Passwords match</span>';
        } else {
          matchIndicator.classList.remove('match');
          matchIndicator.classList.add('mismatch');
          matchIndicator.innerHTML = '<i class="fas fa-exclamation-circle"></i><span>Passwords do not match</span>';
        }
      } else {
        matchIndicator.classList.remove('visible');
      }
    }

    // Form validation
    function validateForm() {
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirmation');
      
      let isValid = true;
      
      // Validate email
      const email = emailInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showError(emailInput, 'Please enter a valid email address');
        isValid = false;
      }
      
      // Validate password strength
      const password = passwordInput.value;
      const requirements = checkPasswordStrength(password);
      
      if (password.length < 8) {
        showError(passwordInput, 'Password must be at least 8 characters');
        isValid = false;
      }
      
      // Check if all requirements are met
      const allRequirementsMet = Object.values(requirements).every(req => req === true);
      if (!allRequirementsMet) {
        showError(passwordInput, 'Please meet all password requirements');
        isValid = false;
      }
      
      // Validate password match
      if (password !== confirmPasswordInput.value) {
        showError(confirmPasswordInput, 'Passwords do not match');
        isValid = false;
      }
      
      return isValid;
    }

    function showError(input, message) {
      // Remove existing error
      const existingError = input.parentNode.parentNode.querySelector('.error-message');
      if (existingError) {
        existingError.remove();
      }
      
      // Add new error
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
      input.parentNode.parentNode.appendChild(errorDiv);
      
      // Highlight input
      input.style.borderColor = '#dc2626';
      input.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
      
      // Remove highlight after input
      input.addEventListener('input', function() {
        this.style.borderColor = 'rgba(17, 4, 132, 0.2)';
        this.style.boxShadow = '0 2px 8px rgba(17, 4, 132, 0.08)';
        if (errorDiv.parentNode) {
          errorDiv.remove();
        }
      }, { once: true });
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      checkBackgroundImage();
      setupPasswordToggle();
      
      // Add loading animation to reset box
      const resetBox = document.querySelector('.reset-box');
      resetBox.style.opacity = '0';
      resetBox.style.transform = 'translateY(20px) scale(0.95)';
      resetBox.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        resetBox.style.opacity = '1';
        resetBox.style.transform = 'translateY(0) scale(1)';
      }, 150);
      
      // Add event listeners for password validation
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('password_confirmation');
      
      if (passwordInput) {
        passwordInput.addEventListener('input', function() {
          checkPasswordStrength(this.value);
          checkPasswordMatch();
        });
      }
      
      if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
      }
      
      // Add form validation
      const resetForm = document.getElementById('resetForm');
      if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
          if (!validateForm()) {
            e.preventDefault();
          }
        });
      }
      
      // If there's a success message, show animation
      const successMessage = document.querySelector('.success-box');
      if (successMessage) {
        successMessage.style.animation = 'successAnimation 0.5s ease-out';
      }
    });

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>