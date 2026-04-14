<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes" />
  <title>Welcome • MOIC Enterprise Appraisal System</title>

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
      --moic-gradient-mixed: linear-gradient(135deg, var(--moic-navy), var(--moic-accent));
      
      --bg: #f8fafc;
      --surface: #ffffff;
      --muted: #64748b;
      --success: #10b981;
      --radius: 10px;
      --shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    html, body {
      height: 100%;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      line-height: 1.5;
      overflow-x: hidden;
      background: linear-gradient(135deg, #f0f5ff 0%, #e6eeff 50%, #fef5e8 100%);
    }
    
    a {
      text-decoration: none;
      color: inherit;
    }

    /* Background container with multiple fallbacks */
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
    
    /* Primary background image */
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
      transition: all 0.5s ease;
    }
    
    /* Fallback gradient overlay */
    .bg-gradient-fallback {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, 
        rgba(17, 4, 132, 0.05) 0%,
        rgba(231, 88, 28, 0.05) 50%,
        rgba(17, 4, 132, 0.05) 100%);
      z-index: 0;
    }
    
    /* Overlay to improve text readability */
    .bg-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.85);
      z-index: 1;
    }
    
    /* Responsive background sizes */
    @media (min-width: 1920px) {
      .bg-image {
        background-size: contain;
      }
    }
    
    @media (min-width: 1280px) and (max-width: 1919px) {
      .bg-image {
        background-size: 85% auto;
      }
    }
    
    @media (min-width: 1024px) and (max-width: 1279px) {
      .bg-image {
        background-size: 90% auto;
      }
    }
    
    @media (min-width: 768px) and (max-width: 1023px) {
      .bg-image {
        background-size: 95% auto;
      }
    }
    
    @media (max-width: 767px) {
      .bg-image {
        background-size: cover;
      }
    }

    /* Fixed Container for header and partnership banner */
    .fixed-top-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background: transparent;
    }

    /* Header */
    header {
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(17, 4, 132, 0.1);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(17, 4, 132, 0.08);
    }
    
    .brand {
      display: flex; 
      align-items: center; 
      gap: 8px;
    }
    
    .brand .logo {
      width: 30px; 
      height: 30px; 
      border-radius: 6px;
      background: var(--moic-gradient-mixed);
      display: flex; 
      align-items: center; 
      justify-content: center; 
      color: white; 
      font-weight: 700; 
      font-size: 12px;
      box-shadow: 0 2px 5px rgba(17, 4, 132, 0.15);
    }
    
    .brand .title {
      font-weight: 700;
      background: var(--moic-gradient-mixed);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-size: 0.85rem;
    }
    
    .brand .subtitle {
      font-size: 9px;
      color: var(--muted);
    }
    
    .actions a {
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-left: 5px;
      font-size: 0.8rem;
    }
    
    .login-btn {
      border: 1px solid rgba(17,4,132,0.2);
      background: transparent;
      color: var(--moic-navy);
    }
    
    .login-btn:hover {
      background: var(--moic-gradient-mixed);
      color: white;
      transform: translateY(-1px);
      border-color: transparent;
      box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
    }

    /* Partnership Banner */
    .partnership-banner {
      background: var(--moic-gradient-mixed);
      border-radius: 12px;
      padding: 12px 20px;
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.2);
      margin: 10px auto;
      max-width: 1200px;
      width: 95%;
      position: relative;
      z-index: 30;
      animation: gradientShift 15s ease infinite;
      background-size: 300% 300%;
    }
    
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    .partnership-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }
    
    @media (min-width: 768px) {
      .partnership-content {
        flex-direction: row;
        justify-content: space-between;
        gap: 15px;
      }
    }
    
    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .logo-pair {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .logo-box {
      background: white;
      border-radius: 8px;
      padding: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .logo-box img {
      height: 30px;
      width: auto;
      object-fit: contain;
    }
    
    .partnership-icon {
      background: white;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    
    .partnership-icon i {
      color: var(--moic-accent);
      font-size: 16px;
    }
    
    .partnership-text {
      text-align: center;
      color: white;
      flex: 1;
    }
    
    @media (min-width: 768px) {
      .partnership-text {
        text-align: left;
      }
    }
    
    .partnership-text h3 {
      font-weight: 700;
      font-size: 1rem;
      margin: 0;
      line-height: 1.2;
    }
    
    .partnership-text p {
      font-size: 0.8rem;
      opacity: 0.9;
      margin: 2px 0 0;
    }
    
    .partnership-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      font-size: 0.7rem;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      margin-top: 4px;
    }
    
    .partnership-info {
      text-align: center;
      color: white;
      padding: 8px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.15);
    }
    
    @media (min-width: 768px) {
      .partnership-info {
        text-align: right;
      }
    }
    
    .partnership-info .system-name {
      font-weight: 600;
      font-size: 0.85rem;
      margin: 0;
    }
    
    .partnership-info .system-desc {
      font-size: 0.75rem;
      opacity: 0.9;
      margin: 2px 0 5px;
    }
    
    .version-badge {
      display: inline-block;
      background: white;
      color: var(--moic-navy);
      font-size: 0.65rem;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Main content area */
    .main-content {
      padding-top: 180px;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    /* Hero Section */
    .hero {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: calc(100vh - 180px);
      padding: 20px 15px;
      text-align: center;
      position: relative;
    }

    /* Content container */
    .hero-content {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-radius: var(--radius);
      padding: 35px 25px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.5);
      max-width: 700px;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      border-left: 3px solid rgba(231, 88, 28, 0.7);
      border-right: 3px solid rgba(17, 4, 132, 0.7);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hero-content:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .hero-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(135deg, rgba(17, 4, 132, 0.7), rgba(231, 88, 28, 0.7));
      z-index: 1;
    }

    .hero h1 {
      font-size: 2rem;
      font-weight: 800;
      color: var(--moic-navy);
      margin-bottom: 15px;
      line-height: 1.2;
    }
    
    .hero h1 span {
      background: var(--moic-gradient-mixed);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    
    .hero p {
      max-width: 600px;
      margin: 0 auto 25px;
      color: #1e293b;
      font-size: 0.95rem;
      line-height: 1.6;
    }
    
    .hero .cta-buttons {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 30px;
    }
    
    .hero .cta-buttons a {
      padding: 10px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      min-width: 130px;
      text-align: center;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    
    .hero .btn-primary {
      background: var(--moic-gradient-mixed);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(17, 4, 132, 0.4);
    }
    
    .hero .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(17,4,132,0.5);
    }
    
    .hero .btn-ghost {
      background: rgba(255, 255, 255, 0.6);
      border: 1.5px solid var(--moic-navy);
      color: var(--moic-navy);
    }
    
    .hero .btn-ghost:hover {
      background: var(--moic-navy);
      color: white;
      transform: translateY(-3px);
    }

    /* Footer */
    footer {
      padding: 20px;
      text-align: center;
      color: var(--moic-navy);
      font-size: 0.8rem;
      background: rgba(255, 255, 255, 0.95);
      border-top: 1px solid rgba(17, 4, 132, 0.15);
      backdrop-filter: blur(8px);
      font-weight: 500;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 40;
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
      .main-content {
        padding-top: 160px;
      }
      
      .hero h1 {
        font-size: 1.7rem;
      }
      
      .hero-content {
        padding: 30px 20px;
        margin: 10px;
      }
      
      .hero .cta-buttons {
        flex-direction: column;
        align-items: center;
      }
      
      .hero .cta-buttons a {
        width: 100%;
        max-width: 220px;
      }
    }

    @media (max-width: 480px) {
      .main-content {
        padding-top: 150px;
      }
      
      .hero h1 {
        font-size: 1.5rem;
      }
      
      .hero-content {
        padding: 25px 15px;
      }
      
      footer {
        font-size: 0.7rem;
        padding: 15px;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
      }
      
      .particle {
        display: none;
      }
    }
  </style>
</head>
<body>

  <!-- Responsive Background Container with Multiple Fallbacks -->
  <div class="background-container">
    <div class="bg-image" id="bgImage"></div>
    <div class="bg-overlay"></div>
    <div class="bg-gradient-fallback"></div>
  </div>

  <!-- Animated Background Particles -->
  <div class="particles" id="particles"></div>

  <!-- Fixed Top Container -->
  <div class="fixed-top-container">
    <header>
      <div class="brand">
        <div class="logo">P</div>
        <div>
          <div class="title">Performance</div>
          <div class="subtitle">Performance Appraisal System</div>
        </div>
      </div>
      
      <div class="actions">
        <a href="{{ route('login') }}" class="login-btn">
          <i class="fas fa-sign-in-alt"></i> Login
        </a>
      </div>
    </header>

    <div class="partnership-banner">
      <div class="partnership-content">
        <div class="logo-container">
          <div class="logo-pair">
            <div class="logo-box">
              <img src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                   onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23110484%22/><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2224%22>MOIC</text></svg>'">
            </div>
            <div class="partnership-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="logo-box">
              <img src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                   onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 fill=%22%23e7581c%22/><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2220%22>TKC</text></svg>'">
            </div>
          </div>
          
          <div class="partnership-text">
            <h3>MOIC & TKC Partnership</h3>
            <p>Strategic alliance for performance excellence</p>
            <span class="partnership-badge">OFFICIAL PARTNERS</span>
          </div>
        </div>
        
        <div class="partnership-info">
          <div class="system-name">Performance Appraisal System</div>
          <div class="system-desc">Jointly managed platform</div>
          <span class="version-badge">v1.0</span>
        </div>
      </div>
    </div>
  </div>
        
  <div class="main-content">
    <main class="hero">
      <div class="hero-content">
        <h1>Welcome to <span>MOIC & TKC</span> Performance Appraisal System</h1>
        <p>Performance appraisals, manage structured reviews, and make data-driven decisions with our enterprise-grade solution designed for modern organizations.</p>

        <div class="cta-buttons">
          <a href="{{ route('onboarding.survey') }}" class="btn-primary">
            <i class="fas fa-sign-in-alt"></i> Get Started
          </a>
          <a href="#features" class="btn-ghost">
            <i class="fas fa-info-circle"></i> Learn More
          </a>
        </div>
      </div>
    </main>
  </div>
  <!-- Footer - Fixed at bottom -->
  <footer>
    © <span id="year"></span> MOIC & TKC Consortium — Performance Management SIFs. All rights reserved powered by smartwave solutions.
  </footer>

  <!-- Bootstrap JS (optional, for any interactive components) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Set current year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Create animated particles - fewer for cleaner look
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 12; /* Fewer particles */
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Random properties
        const size = Math.random() * 25 + 8; // 8-33px
        const posX = Math.random() * 100;
        const delay = Math.random() * 20;
        const duration = Math.random() * 20 + 30; // 30-50s duration
        
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.animationDuration = `${duration}s`;
        particle.style.opacity = Math.random() * 0.08 + 0.05; /* Lighter */
        
        particlesContainer.appendChild(particle);
      }
    }

    // Check if background image loads
    function checkBackgroundImage() {
      const img = new Image();
      img.src = '/images/Purple Yellow Grey Illustrative Marketing Instagram Post (2).png';
      
      img.onload = function() {
        console.log('Background image loaded successfully');
        document.body.style.backgroundImage = `url('${img.src}')`;
      };
      
      img.onerror = function() {
        console.log('Background image failed to load');
        // Fallback to gradient background
        document.body.style.background = 'linear-gradient(135deg, #f0f5ff 0%, #e6eeff 100%)';
        document.body.style.backgroundAttachment = 'fixed';
        document.body.style.backgroundSize = 'cover';
      };
    }

    // Smooth scroll for anchor links
    function initSmoothScroll() {
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 180, // Account for fixed header + banner
              behavior: 'smooth'
            });
          }
        });
      });
    }

    // Initialize everything when page loads
    document.addEventListener('DOMContentLoaded', function() {
      createParticles();
      checkBackgroundImage();
      initSmoothScroll();
      
      // Add loading animation to hero content
      const heroContent = document.querySelector('.hero-content');
      heroContent.style.opacity = '0';
      heroContent.style.transform = 'translateY(15px) scale(0.95)';
      heroContent.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        heroContent.style.opacity = '1';
        heroContent.style.transform = 'translateY(0) scale(1)';
      }, 150);
      
      // Add animation to partnership banner
      const partnershipBanner = document.querySelector('.partnership-banner');
      partnershipBanner.style.opacity = '0';
      partnershipBanner.style.transform = 'translateY(-10px)';
      partnershipBanner.style.transition = 'opacity 0.5s ease, transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.1)';
      
      setTimeout(() => {
        partnershipBanner.style.opacity = '1';
        partnershipBanner.style.transform = 'translateY(0)';
      }, 200);
      
      // Initialize tooltips if any
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
</body>
</html>