<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Verify Email • MOIC Enterprise Appraisal System</title>

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

    /* Verify Container */
    .verify-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 80px 15px 40px;
      position: relative;
    }

    /* Verify Box - Matching design */
    .verify-box {
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
    .verify-box::before {
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
    .verify-box::after {
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

    /* Email Icon Animation */
    .email-icon {
      text-align: center;
      margin-bottom: 30px;
      position: relative;
    }

    .email-icon-inner {
      display: inline-block;
      position: relative;
      width: 100px;
      height: 100px;
    }

    .email-icon-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: var(--gradient-mixed);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2.5rem;
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.3);
      animation: pulse 2s infinite;
    }

    .email-icon-envelope {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: float 3s ease-in-out infinite;
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
      margin-bottom: 15px;
      line-height: 1.2;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
    }

    .form-header p {
      color: var(--muted);
      font-size: 1rem;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
      line-height: 1.6;
    }

    /* Info Box */
    .info-box {
      background: rgba(17, 4, 132, 0.05);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      border-left: 4px solid var(--accent);
    }

    .info-box-content {
      display: flex;
      align-items: flex-start;
      gap: 15px;
    }

    .info-icon {
      color: var(--accent);
      font-size: 1.2rem;
      margin-top: 2px;
      flex-shrink: 0;
    }

    .info-text {
      color: var(--navy);
      font-size: 0.95rem;
      line-height: 1.6;
      text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    /* Success Message */
    .success-message {
      background: rgba(16, 185, 129, 0.1);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
      border-left: 4px solid var(--success);
      display: flex;
      align-items: center;
      gap: 15px;
      animation: slideIn 0.5s ease-out;
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

    /* Form Actions */
    .form-actions {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 30px;
    }

    /* Buttons Container */
    .buttons-container {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
    }

    /* Verification Button */
    .verify-button {
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
      min-width: 200px;
    }

    .verify-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.5);
    }

    .verify-button:active {
      transform: translateY(0);
    }

    /* Logout Button */
    .logout-button {
      background: rgba(255, 255, 255, 0.9);
      color: var(--navy);
      border: 2px solid var(--navy);
      padding: 14px 28px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      min-width: 200px;
    }

    .logout-button:hover {
      background: var(--navy);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(17, 4, 132, 0.2);
    }

    /* Check Email Instructions */
    .check-email {
      text-align: center;
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid rgba(17, 4, 132, 0.1);
    }

    .check-email-title {
      font-weight: 600;
      color: var(--navy);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .check-email-list {
      list-style: none;
      padding: 0;
      margin: 15px 0;
      text-align: left;
      max-width: 400px;
      margin: 0 auto;
    }

    .check-email-list li {
      color: var(--muted);
      font-size: 0.9rem;
      margin-bottom: 8px;
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .check-email-list li i {
      color: var(--accent);
      font-size: 0.8rem;
      margin-top: 4px;
    }

    /* Did Not Receive Section */
    .did-not-receive {
      text-align: center;
      margin-top: 30px;
      padding: 20px;
      background: rgba(17, 4, 132, 0.02);
      border-radius: 10px;
      border: 1px dashed rgba(17, 4, 132, 0.1);
    }

    .did-not-receive p {
      color: var(--muted);
      font-size: 0.9rem;
      margin-bottom: 15px;
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

    /* Animations */
    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(17, 4, 132, 0.4);
      }
      70% {
        box-shadow: 0 0 0 15px rgba(17, 4, 132, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(17, 4, 132, 0);
      }
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive */
    @media (max-width: 768px) {
      body {
        background-size: 85% auto;
      }
      
      .verify-box {
        padding: 30px 25px;
        max-width: 90%;
      }
      
      .form-header h1 {
        font-size: 1.6rem;
      }
      
      .form-header p {
        font-size: 0.95rem;
      }
      
      header {
        flex-direction: row;
        gap: 8px;
        padding: 10px 15px;
      }
      
      .buttons-container {
        flex-direction: column;
        align-items: stretch;
      }
      
      .verify-button,
      .logout-button {
        width: 100%;
        min-width: auto;
      }
      
      .email-icon-inner {
        width: 80px;
        height: 80px;
      }
      
      .email-icon-circle {
        width: 80px;
        height: 80px;
        font-size: 2rem;
      }
      
      .info-box,
      .success-message {
        padding: 15px;
      }
    }

    @media (max-width: 480px) {
      body {
        background-size: 95% auto;
      }
      
      .verify-box {
        padding: 25px 20px;
      }
      
      .form-header h1 {
        font-size: 1.4rem;
      }
      
      .form-header p {
        font-size: 0.9rem;
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
      
      .verify-button,
      .logout-button {
        padding: 12px 20px;
        font-size: 0.9rem;
      }
      
      .email-icon-inner {
        width: 70px;
        height: 70px;
      }
      
      .email-icon-circle {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
      }
      
      .check-email-list {
        font-size: 0.85rem;
      }
    }

    /* For very large screens */
    @media (min-width: 1100px) {
      body {
        background-size: 75% auto;
      }
    }

    /* Loading state for button */
    .verify-button.loading {
      position: relative;
      color: transparent;
    }

    .verify-button.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      border: 2px solid white;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
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
    
    <div class="actions">
      <a href="{{ route('login') }}" class="login-btn">Log in</a>
      <a href="{{ route('register') }}" class="register-btn">Register</a>
    </div>
  </header>

  <!-- Verify Container -->
  <main class="verify-container">
    <div class="verify-box">
      
      <!-- Email Icon Animation -->
      <div class="email-icon">
        <div class="email-icon-inner">
          <div class="email-icon-circle"></div>
          <div class="email-icon-envelope">
            <i class="fas fa-envelope"></i>
          </div>
        </div>
      </div>

      <!-- Form Header -->
      <div class="form-header">
        <h1>Verify Your Email Address</h1>
        <p>You're almost there! Please verify your email to complete your registration.</p>
      </div>

      <!-- Success Message -->
      @if (session('status') == 'verification-link-sent')
        <div class="success-message">
          <div class="success-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="success-text">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
          </div>
        </div>
      @endif

      <!-- Info Box -->
      <div class="info-box">
        <div class="info-box-content">
          <div class="info-icon">
            <i class="fas fa-info-circle"></i>
          </div>
          <div class="info-text">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <div class="buttons-container">
          <!-- Resend Verification Email Form -->
          <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
            @csrf
            <button type="submit" class="verify-button" id="verifyButton">
              <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
          </form>

          <!-- Logout Form -->
          <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="submit" class="logout-button">
              <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
          </form>
        </div>
      </div>

      <!-- Check Email Instructions -->
      <div class="check-email">
        <div class="check-email-title">
          <i class="fas fa-inbox"></i> Check Your Email
        </div>
        <ul class="check-email-list">
          <li>
            <i class="fas fa-check-circle"></i>
            Look for an email from <strong>noreply@moic.com</strong>
          </li>
          <li>
            <i class="fas fa-check-circle"></i>
            Check your spam or junk folder if you don't see it
          </li>
          <li>
            <i class="fas fa-check-circle"></i>
            Click the verification link in the email
          </li>
          <li>
            <i class="fas fa-check-circle"></i>
            The link will expire in 24 hours for security
          </li>
        </ul>
      </div>

      <!-- Did Not Receive Section -->
      <div class="did-not-receive">
        <p><strong>Didn't receive the email?</strong></p>
        <p>Make sure you entered the correct email address during registration. If you still don't see it, try clicking "Resend Verification Email" above.</p>
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

    // Button loading state
    function setupButtonLoading() {
      const verifyButton = document.getElementById('verifyButton');
      const resendForm = document.getElementById('resendForm');
      
      if (verifyButton && resendForm) {
        resendForm.addEventListener('submit', function(e) {
          // Show loading state
          verifyButton.classList.add('loading');
          verifyButton.disabled = true;
          verifyButton.innerHTML = '';
          
          // Add loading spinner
          const spinner = document.createElement('span');
          spinner.style.display = 'inline-block';
          spinner.style.width = '20px';
          spinner.style.height = '20px';
          spinner.style.border = '2px solid white';
          spinner.style.borderRadius = '50%';
          spinner.style.borderTopColor = 'transparent';
          spinner.style.animation = 'spin 0.8s linear infinite';
          verifyButton.appendChild(spinner);
          
          // Re-enable button after 3 seconds (in case submission fails)
          setTimeout(() => {
            verifyButton.classList.remove('loading');
            verifyButton.disabled = false;
            verifyButton.innerHTML = '<i class="fas fa-paper-plane"></i> Resend Verification Email';
          }, 3000);
        });
      }
    }

    // Confirmation for logout
    function setupLogoutConfirmation() {
      const logoutForm = document.getElementById('logoutForm');
      
      if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
          if (!confirm('Are you sure you want to log out? You\'ll need to log back in after verification.')) {
            e.preventDefault();
          }
        });
      }
    }

    // Email icon animation
    function animateEmailIcon() {
      const emailIcon = document.querySelector('.email-icon-envelope');
      if (emailIcon) {
        // Add subtle bounce animation
        setInterval(() => {
          emailIcon.style.animation = 'float 3s ease-in-out infinite';
        }, 100);
      }
    }

    // Success message animation
    function animateSuccessMessage() {
      const successMessage = document.querySelector('.success-message');
      if (successMessage) {
        successMessage.style.animation = 'slideIn 0.5s ease-out';
        
        // Add slight pulse effect
        setInterval(() => {
          successMessage.style.transform = 'scale(1.02)';
          setTimeout(() => {
            successMessage.style.transform = 'scale(1)';
          }, 300);
        }, 3000);
      }
    }

    // Countdown timer for resend cooldown
    function setupResendCooldown() {
      let cooldown = false;
      const verifyButton = document.getElementById('verifyButton');
      
      if (verifyButton) {
        verifyButton.addEventListener('click', function() {
          if (cooldown) return;
          
          cooldown = true;
          const originalText = this.innerHTML;
          let seconds = 60;
          
          // Update button text every second
          const countdownInterval = setInterval(() => {
            this.innerHTML = `<i class="fas fa-clock"></i> Resend in ${seconds}s`;
            seconds--;
            
            if (seconds < 0) {
              clearInterval(countdownInterval);
              this.innerHTML = originalText;
              cooldown = false;
            }
          }, 1000);
        });
      }
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      checkBackgroundImage();
      setupButtonLoading();
      setupLogoutConfirmation();
      animateEmailIcon();
      animateSuccessMessage();
      setupResendCooldown();
      
      // Add loading animation to verify box
      const verifyBox = document.querySelector('.verify-box');
      verifyBox.style.opacity = '0';
      verifyBox.style.transform = 'translateY(20px) scale(0.95)';
      verifyBox.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        verifyBox.style.opacity = '1';
        verifyBox.style.transform = 'translateY(0) scale(1)';
      }, 150);
      
      // Add animation to email icon
      const emailCircle = document.querySelector('.email-icon-circle');
      if (emailCircle) {
        emailCircle.style.animation = 'pulse 2s infinite';
      }
      
      // Add hover effect to buttons
      const buttons = document.querySelectorAll('.verify-button, .logout-button');
      buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
        });
      });
      
      // Add typing animation to text (simulated)
      const infoText = document.querySelector('.info-text');
      if (infoText) {
        const originalText = infoText.textContent;
        infoText.textContent = '';
        let i = 0;
        
        function typeWriter() {
          if (i < originalText.length) {
            infoText.textContent += originalText.charAt(i);
            i++;
            setTimeout(typeWriter, 10);
          }
        }
        
        // Start typing animation after a delay
        setTimeout(typeWriter, 500);
      }
    });

    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        to {
          transform: rotate(360deg);
        }
      }
      
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