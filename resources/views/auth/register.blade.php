<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register • MOIC Enterprise Appraisal System</title>

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

    /* Register Container */
    .register-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 80px 15px 40px;
      position: relative;
    }

    /* Register Form Box - Matching design */
    .register-box {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 18px;
      padding: 40px 35px;
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.2),
        0 5px 15px rgba(255, 255, 255, 0.6) inset;
      border: 1px solid rgba(255, 255, 255, 0.6);
      max-width: 480px;
      width: 100%;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      border-left: 4px solid var(--accent);
      border-right: 4px solid var(--navy);
    }

    /* Gradient top border */
    .register-box::before {
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
    .register-box::after {
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
      margin-bottom: 8px;
      line-height: 1.2;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
    }

    .form-header p {
      color: var(--muted);
      font-size: 0.9rem;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.9rem;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
    }

    .form-input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid rgba(17, 4, 132, 0.2);
      background: rgba(255, 255, 255, 0.9);
      font-family: Inter, sans-serif;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      color: var(--navy);
      box-shadow: 0 2px 5px rgba(17, 4, 132, 0.05);
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

    /* Password Strength Indicator */
    .password-strength {
      margin-top: 8px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .strength-bar {
      flex: 1;
      height: 4px;
      background: rgba(100, 116, 139, 0.2);
      border-radius: 2px;
      overflow: hidden;
    }

    .strength-fill {
      height: 100%;
      width: 0%;
      background: #dc2626;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .strength-text {
      font-size: 0.75rem;
      color: var(--muted);
      min-width: 60px;
      text-align: right;
    }

    /* Error Messages */
    .error-message {
      color: #dc2626;
      font-size: 0.85rem;
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 5px;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
    }

    /* Password Requirements */
    .password-requirements {
      margin-top: 10px;
      padding: 10px 15px;
      background: rgba(17, 4, 132, 0.03);
      border-radius: 6px;
      border-left: 3px solid var(--navy);
    }

    .requirements-title {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--navy);
      margin-bottom: 6px;
    }

    .requirement {
      font-size: 0.8rem;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 4px;
    }

    .requirement.valid {
      color: var(--success);
    }

    .requirement i {
      font-size: 0.7rem;
    }

    /* Form Actions */
    .form-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 25px;
    }

    .login-link {
      color: var(--navy);
      font-size: 0.85rem;
      font-weight: 500;
      transition: color 0.3s ease;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    .login-link:hover {
      color: var(--accent);
      text-decoration: underline;
    }

    /* Register Button */
    .register-button {
      background: var(--gradient-mixed);
      color: white;
      border: none;
      padding: 12px 28px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(17, 4, 132, 0.4);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .register-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.5);
    }

    .register-button:active {
      transform: translateY(0);
    }

    /* Session Status Message */
    .session-status {
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--success);
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
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
      
      .register-box {
        padding: 30px 25px;
        max-width: 90%;
      }
      
      .form-header h1 {
        font-size: 1.6rem;
      }
      
      header {
        flex-direction: row;
        gap: 8px;
        padding: 10px 15px;
      }
      
      .form-actions {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
      }
      
      .login-link {
        order: 2;
        text-align: center;
      }
      
      .register-button {
        order: 1;
        justify-content: center;
      }
      
      .password-requirements {
        padding: 8px 12px;
      }
    }

    @media (max-width: 480px) {
      body {
        background-size: 95% auto;
      }
      
      .register-box {
        padding: 25px 20px;
      }
      
      .form-header h1 {
        font-size: 1.4rem;
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
    }

    /* For very large screens */
    @media (min-width: 1100px) {
      body {
        background-size: 75% auto;
      }
    }

    /* Dark overlay for very bright backgrounds */
    @media (min-width: 769px) {
      .register-container::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(to top, rgba(0,0,0,0.05), transparent);
        z-index: -1;
        pointer-events: none;
      }
    }
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
    
  </header>

  <!-- Register Container -->
  <main class="register-container">
    <div class="register-box">
      <!-- Session Status -->
      @if (session('status'))
        <div class="session-status">
          {{ session('status') }}
        </div>
      @endif

      <div class="form-header">
        <h1>Create Account</h1>
        <p>Join MOIC Enterprise Appraisal System</p>
      </div>

      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div class="form-group">
          <label class="form-label" for="name">Full Name</label>
          <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name">
          @if ($errors->has('name'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('name') }}
            </div>
          @endif
        </div>

        <!-- Email Address -->
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">
          @if ($errors->has('email'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('email') }}
            </div>
          @endif
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Create a strong password">
          
          <!-- Password Strength Indicator -->
          <div class="password-strength" id="passwordStrength">
            <div class="strength-bar">
              <div class="strength-fill" id="strengthFill"></div>
            </div>
            <div class="strength-text" id="strengthText">Weak</div>
          </div>
          
          @if ($errors->has('password'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('password') }}
            </div>
          @endif
          
          <!-- Password Requirements -->
          <div class="password-requirements">
            <div class="requirements-title">Password Requirements:</div>
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
          </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirm Password</label>
          <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
          
          <!-- Password Match Indicator -->
          <div id="passwordMatch" style="margin-top: 8px; font-size: 0.85rem; display: none;">
            <i class="fas fa-check-circle" style="color: var(--success); margin-right: 5px;"></i>
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
          <a class="login-link" href="{{ route('login') }}">
            Already have an account? Log in
          </a>

          <button type="submit" class="register-button">
            <i class="fas fa-user-plus"></i> Create Account
          </button>
        </div>
      </form>
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

    // Password Strength Checker
    function checkPasswordStrength(password) {
      let score = 0;
      const requirements = {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false
      };

      // Check length
      if (password.length >= 8) {
        score += 25;
        requirements.length = true;
        document.getElementById('reqLength').classList.add('valid');
        document.getElementById('reqLength').innerHTML = '<i class="fas fa-check-circle"></i> At least 8 characters';
      } else {
        document.getElementById('reqLength').classList.remove('valid');
        document.getElementById('reqLength').innerHTML = '<i class="fas fa-circle"></i> At least 8 characters';
      }

      // Check uppercase
      if (/[A-Z]/.test(password)) {
        score += 25;
        requirements.uppercase = true;
        document.getElementById('reqUppercase').classList.add('valid');
        document.getElementById('reqUppercase').innerHTML = '<i class="fas fa-check-circle"></i> One uppercase letter';
      } else {
        document.getElementById('reqUppercase').classList.remove('valid');
        document.getElementById('reqUppercase').innerHTML = '<i class="fas fa-circle"></i> One uppercase letter';
      }

      // Check lowercase
      if (/[a-z]/.test(password)) {
        score += 25;
        requirements.lowercase = true;
        document.getElementById('reqLowercase').classList.add('valid');
        document.getElementById('reqLowercase').innerHTML = '<i class="fas fa-check-circle"></i> One lowercase letter';
      } else {
        document.getElementById('reqLowercase').classList.remove('valid');
        document.getElementById('reqLowercase').innerHTML = '<i class="fas fa-circle"></i> One lowercase letter';
      }

      // Check number
      if (/[0-9]/.test(password)) {
        score += 25;
        requirements.number = true;
        document.getElementById('reqNumber').classList.add('valid');
        document.getElementById('reqNumber').innerHTML = '<i class="fas fa-check-circle"></i> One number';
      } else {
        document.getElementById('reqNumber').classList.remove('valid');
        document.getElementById('reqNumber').innerHTML = '<i class="fas fa-circle"></i> One number';
      }

      // Update strength bar and text
      const strengthFill = document.getElementById('strengthFill');
      const strengthText = document.getElementById('strengthText');
      
      strengthFill.style.width = `${score}%`;
      
      if (score >= 75) {
        strengthFill.style.background = var('--success');
        strengthText.textContent = 'Strong';
        strengthText.style.color = 'var(--success)';
      } else if (score >= 50) {
        strengthFill.style.background = '#f59e0b';
        strengthText.textContent = 'Medium';
        strengthText.style.color = '#f59e0b';
      } else if (score >= 25) {
        strengthFill.style.background = '#f59e0b';
        strengthText.textContent = 'Weak';
        strengthText.style.color = '#f59e0b';
      } else {
        strengthFill.style.background = '#dc2626';
        strengthText.textContent = 'Very Weak';
        strengthText.style.color = '#dc2626';
      }
    }

    // Check if passwords match
    function checkPasswordMatch() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      const matchIndicator = document.getElementById('passwordMatch');
      
      if (password && confirmPassword) {
        if (password === confirmPassword) {
          matchIndicator.style.display = 'flex';
          matchIndicator.style.alignItems = 'center';
          matchIndicator.style.color = 'var(--success)';
          matchIndicator.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 5px;"></i><span>Passwords match</span>';
        } else {
          matchIndicator.style.display = 'flex';
          matchIndicator.style.alignItems = 'center';
          matchIndicator.style.color = '#dc2626';
          matchIndicator.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i><span>Passwords do not match</span>';
        }
      } else {
        matchIndicator.style.display = 'none';
      }
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      checkBackgroundImage();
      
      // Add loading animation to register box
      const registerBox = document.querySelector('.register-box');
      registerBox.style.opacity = '0';
      registerBox.style.transform = 'translateY(20px) scale(0.95)';
      registerBox.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        registerBox.style.opacity = '1';
        registerBox.style.transform = 'translateY(0) scale(1)';
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
      
      // Validate form on submit
      const registerForm = document.getElementById('registerForm');
      if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
          const password = document.getElementById('password').value;
          const confirmPassword = document.getElementById('password_confirmation').value;
          
          if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please make sure both password fields are identical.');
            return false;
          }
          
          if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return false;
          }
        });
      }
    });
  </script>
</body>
</html>