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
      max-width: 450px;
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
      margin-bottom: 25px;
    }

    .form-header h1 {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--navy);
      margin-bottom: 12px;
      line-height: 1.2;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
    }

    .form-header p {
      color: var(--muted);
      font-size: 0.95rem;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
      line-height: 1.6;
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--navy);
      font-size: 0.9rem;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-label i {
      color: var(--accent);
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

    /* Info Box */
    .info-box {
      background: rgba(17, 4, 132, 0.05);
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 25px;
      border-left: 4px solid var(--accent);
    }

    .info-box-content {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .info-icon {
      color: var(--accent);
      font-size: 1.2rem;
      margin-top: 2px;
    }

    .info-text {
      color: var(--navy);
      font-size: 0.9rem;
      line-height: 1.6;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
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

    /* Success Message */
    .session-status {
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--success);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 25px;
      font-size: 0.9rem;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
      border-left: 4px solid var(--success);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .session-status i {
      font-size: 1.1rem;
    }

    /* Form Actions */
    .form-actions {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 25px;
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

    /* Back to Login Link */
    .back-link {
      text-align: center;
      margin-top: 20px;
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
      .reset-container::after {
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

    /* Email Sent Success State */
    .email-sent {
      text-align: center;
      padding: 20px;
    }

    .email-sent-icon {
      font-size: 3rem;
      color: var(--success);
      margin-bottom: 20px;
      animation: bounce 1s ease infinite;
    }

    @keyframes bounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    .email-sent h2 {
      color: var(--navy);
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .email-sent p {
      color: var(--muted);
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 25px;
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

  <!-- Reset Container -->
  <main class="reset-container">
    <div class="reset-box">
      
      <!-- Success Message (shown after submission) -->
      @if (session('status'))
        <div class="session-status">
          <i class="fas fa-check-circle"></i>
          {{ session('status') }}
        </div>
      @endif

      <div class="form-header">
        <h1>Reset Your Password</h1>
        <p>Enter your email address and we'll send you a link to reset your password.</p>
      </div>

      <!-- Info Box -->
      <div class="info-box">
        <div class="info-box-content">
          <div class="info-icon">
            <i class="fas fa-info-circle"></i>
          </div>
          <div class="info-text">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('password.email') }}" id="resetForm">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
          <label class="form-label" for="email">
            <i class="fas fa-envelope"></i> Email Address
          </label>
          <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="Enter your registered email address">
          
          @if ($errors->has('email'))
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              {{ $errors->first('email') }}
            </div>
          @endif
        </div>

        <div class="form-actions">
          <button type="submit" class="reset-button">
            <i class="fas fa-paper-plane"></i> Send Reset Link
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

    // Form validation
    function validateForm() {
      const emailInput = document.getElementById('email');
      const email = emailInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      if (!emailRegex.test(email)) {
        showError(emailInput, 'Please enter a valid email address');
        return false;
      }
      
      return true;
    }

    function showError(input, message) {
      // Remove existing error
      const existingError = input.parentNode.querySelector('.error-message');
      if (existingError) {
        existingError.remove();
      }
      
      // Add new error
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
      input.parentNode.appendChild(errorDiv);
      
      // Highlight input
      input.style.borderColor = '#dc2626';
      input.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)';
      
      // Remove highlight after input
      input.addEventListener('input', function() {
        this.style.borderColor = 'rgba(17, 4, 132, 0.2)';
        this.style.boxShadow = '0 2px 5px rgba(17, 4, 132, 0.05)';
        if (errorDiv.parentNode) {
          errorDiv.remove();
        }
      }, { once: true });
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      checkBackgroundImage();
      
      // Add loading animation to reset box
      const resetBox = document.querySelector('.reset-box');
      resetBox.style.opacity = '0';
      resetBox.style.transform = 'translateY(20px) scale(0.95)';
      resetBox.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        resetBox.style.opacity = '1';
        resetBox.style.transform = 'translateY(0) scale(1)';
      }, 150);
      
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
      const successMessage = document.querySelector('.session-status');
      if (successMessage) {
        successMessage.style.animation = 'fadeIn 0.5s ease';
      }
    });

    // Add CSS for fadeIn animation
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