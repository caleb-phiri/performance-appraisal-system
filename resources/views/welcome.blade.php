<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Welcome • MOIC Enterprise Appraisal System</title>

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
      overflow-x: hidden;
    }
    a{text-decoration:none;color:inherit;}

    /* Background Image - SMALLER SIZE */
    body {
      background-image: url('/images/TK.png');
      background-size: 70% auto;
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      position: relative;
      background-color: #f8fafc;
    }

    /* Fixed Container for header and partnership banner */
    .fixed-top-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
      background: transparent;
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

    /* Partnership Banner - FIXED POSITION */
    .partnership-banner {
      background: var(--gradient-mixed);
      border-radius: 12px;
      padding: 12px 20px;
      box-shadow: 0 8px 25px rgba(17, 4, 132, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.2);
      margin: 10px auto;
      max-width: 1200px;
      width: 95%;
      position: relative;
      z-index: 30;
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
      color: var(--accent);
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
      color: var(--navy);
      font-size: 0.65rem;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Main content area - Scrollable */
    .main-content {
      padding-top: 180px; /* Space for fixed header + partnership banner */
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    /* Hero Section - Now scrollable */
    .hero{
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      min-height: calc(100vh - 180px);
      padding: 20px 15px;
      text-align:center;
      position: relative;
    }

    /* Content container - MORE TRANSPARENT */
    .hero-content {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border-radius: 16px;
      padding: 35px 25px;
      box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.15),
        0 2px 10px rgba(255, 255, 255, 0.4) inset;
      border: 1px solid rgba(255, 255, 255, 0.5);
      max-width: 700px;
      margin: 0 auto;
      position: relative;
      overflow: hidden;
      border-left: 3px solid rgba(231, 88, 28, 0.7);
      border-right: 3px solid rgba(17, 4, 132, 0.7);
    }

    /* Gradient top border - More transparent */
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

    /* More transparent gradient overlay */
    .hero-content::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, 
        rgba(17, 4, 132, 0.03) 0%, 
        rgba(231, 88, 28, 0.03) 100%);
      z-index: -1;
    }

    /* Main heading - NAVY */
    .hero h1{
      font-size:2rem;
      font-weight:800;
      color:var(--navy);
      margin-bottom:15px;
      line-height: 1.2;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
    }
    
    /* Highlight MOIC with gradient */
    .hero h1 span {
      background: var(--gradient-mixed);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 1px 3px rgba(255, 255, 255, 0.7);
    }
    
    /* Paragraph - DARK GRAY */
    .hero p{
      max-width:600px;
      margin:0 auto 25px;
      color:#1e293b;
      font-size:0.95rem;
      line-height: 1.6;
      font-weight: 400;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
    }
    
    /* CTA Buttons */
    .hero .cta-buttons{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      justify-content:center;
      margin-bottom:30px;
    }
    
    .hero .cta-buttons a{
      padding:10px 24px;
      border-radius:8px;
      font-weight:600;
      font-size:0.9rem;
      transition:0.3s;
      min-width: 130px;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .hero .btn-primary{
      background:var(--gradient-mixed); 
      color:white;
      box-shadow: 0 4px 15px rgba(17, 4, 132, 0.4);
      border: none;
    }
    
    .hero .btn-primary:hover{
      transform:translateY(-3px); 
      box-shadow:0 8px 20px rgba(17,4,132,0.5);
    }
    
    .hero .btn-ghost{
      background:rgba(255, 255, 255, 0.6);
      border:1.5px solid var(--navy); 
      color:var(--navy);
      backdrop-filter: blur(4px);
    }
    
    .hero .btn-ghost:hover{
      background:var(--navy); 
      color:white;
      border-color: var(--navy);
      transform:translateY(-3px);
      box-shadow:0 8px 20px rgba(17,4,132,0.3);
    }

    /* KPIs - More transparent */
    .kpis{
      display:flex;
      gap:15px;
      flex-wrap:wrap;
      justify-content:center;
      margin-top:30px;
    }
    
    .kpi{
      background: rgba(255, 255, 255, 0.7);
      padding:18px 22px;
      border-radius:12px;
      box-shadow: 
        0 6px 15px rgba(0, 0, 0, 0.1),
        0 2px 8px rgba(255, 255, 255, 0.4) inset;
      border-top:4px solid var(--navy);
      min-width:120px;
      transition:0.3s;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.5);
      position: relative;
      overflow: hidden;
    }
    
    .kpi::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-mixed);
      opacity: 0.05;
      z-index: -1;
    }
    
    .kpi:nth-child(2){border-top-color:var(--accent);}
    .kpi:nth-child(3){border-top-color:var(--success);}
    
    .kpi:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 12px 25px rgba(0, 0, 0, 0.15),
        0 3px 10px rgba(255, 255, 255, 0.5) inset;
    }
    
    /* KPI numbers - NAVY */
    .kpi .num{
      font-weight:900;
      font-size:1.6rem;
      color:var(--navy);
      margin-bottom: 6px;
      text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
    }
    
    /* KPI labels - DARK GRAY */
    .kpi .label{
      color:#334155;
      font-size:0.8rem;
      font-weight: 600;
      letter-spacing: 0.2px;
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
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 40;
      margin-top: 0;
    }
    
    /* Floating Particles - Lighter for smaller background */
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
      /* On mobile, adjust background size */
      body {
        background-size: 80% auto;
      }
      
      /* Adjust fixed container height */
      .main-content {
        padding-top: 160px;
      }
      
      /* Adjust partnership banner for mobile */
      .partnership-banner {
        padding: 10px 15px;
        margin: 5px auto;
      }
      
      .hero {
        min-height: calc(100vh - 160px);
        padding: 15px 10px;
      }
      
      .hero h1 {
        font-size: 1.7rem;
      }
      .hero p {
        font-size: 0.9rem;
        max-width: 95%;
      }
      .hero-content {
        padding: 30px 20px;
        margin: 10px;
        max-width: 95%;
      }
      .kpis {
        flex-direction: column;
        align-items: center;
        gap: 12px;
      }
      .kpi {
        width: 100%;
        max-width: 220px;
        padding: 16px 20px;
      }
      header {
        flex-direction: row;
        gap: 8px;
        padding: 8px 15px;
      }
      .hero .cta-buttons {
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }
      .hero .cta-buttons a {
        width: 100%;
        max-width: 220px;
        padding: 9px 20px;
      }
      .actions {
        display: flex;
        gap: 6px;
      }
      .actions a {
        margin: 0;
        padding: 5px 10px;
        font-size: 0.8rem;
      }
      
      /* Partnership banner mobile adjustments */
      .logo-box img {
        height: 25px;
      }
      
      .partnership-icon {
        width: 30px;
        height: 30px;
      }
      
      .partnership-text h3 {
        font-size: 0.9rem;
      }
      
      .partnership-text p {
        font-size: 0.75rem;
      }
    }

    @media (max-width: 480px) {
      body {
        background-size: 90% auto;
      }
      
      .main-content {
        padding-top: 150px;
      }
      
      .hero {
        min-height: calc(100vh - 150px);
      }
      
      .hero h1 {
        font-size: 1.5rem;
      }
      .hero p {
        font-size: 0.85rem;
      }
      .hero-content {
        padding: 25px 15px;
      }
      .kpi .num {
        font-size: 1.4rem;
      }
      .kpi {
        padding: 14px 18px;
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
      
      /* Hide partnership text on very small screens */
      .partnership-text p {
        display: none;
      }
      
      .partnership-badge {
        font-size: 0.65rem;
        padding: 1px 6px;
      }
    }

    /* For very large screens, keep background proportional */
    @media (min-width: 1100px) {
      body {
        background-size: 75% auto;
      }
    }

    /* Dark overlay for very bright backgrounds */
    @media (min-width: 769px) {
      .hero::after {
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

  <!-- Fixed Top Container - Header and Partnership Banner -->
  <div class="fixed-top-container">
    <!-- Header - Clean with smaller logo -->
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

    <!-- Partnership Banner - FIXED (Non-scrollable) -->
    <div class="partnership-banner">
      <div class="partnership-content">
        <div class="logo-container">
          <div class="logo-pair">
            <!-- MOIC Logo -->
            <div class="logo-box">
              <img src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                   onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22%23110484%22>MOIC</text></svg>';">
            </div>
            
            <!-- Partnership Icon -->
            <div class="partnership-icon">
              <i class="fas fa-handshake"></i>
            </div>
            
            <!-- TKC Logo -->
            <div class="logo-box">
              <img src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                   onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2220%22 fill=%22%23e7581c%22>TKC</text></svg>';">
            </div>
          </div>
          
          <!-- Partnership Text -->
          <div class="partnership-text">
            
          </div>
        </div>
        
        <!-- Partnership Info -->
        <div class="partnership-info">
          <div class="system-name">Performance Appraisal System</div>
          <div class="system-desc">Jointly managed platform</div>
          <span class="version-badge">v1.0</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Scrollable Main Content -->
  <div class="main-content">
    <!-- Hero / Welcome - Scrollable content -->
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
      </div>
    </main>
  </div>

  <!-- Footer - Fixed at bottom -->
  <footer>
    © <span id="year"></span> MOIC & TKC Consortium — Performance Management SIFs. All rights reserved powered by smartwave solutions.
  </footer>

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
      img.src = '/images/TK.png';
      
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

    // Animate KPI numbers
    function animateKPI(element, start, end, duration) {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = progress * (end - start) + start;
        
        if (element.textContent.includes('%')) {
          element.textContent = Math.round(value) + '%';
        } else if (element.textContent.includes('.')) {
          element.textContent = value.toFixed(1);
        } else {
          element.textContent = Math.round(value);
        }
        
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    }

    // Initialize KPI animations
    function initKPIAnimations() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            setTimeout(() => {
              entry.target.style.opacity = '1';
              entry.target.style.transform = 'translateY(0) scale(1)';
              
              // Animate the number
              const numElement = entry.target.querySelector('.num');
              const valueText = numElement.textContent;
              const value = parseFloat(valueText);
              
              if (!isNaN(value)) {
                if (valueText.includes('%')) {
                  numElement.textContent = '0%';
                  setTimeout(() => {
                    animateKPI(numElement, 0, value, 1500);
                  }, 200);
                } else if (valueText.includes('.')) {
                  numElement.textContent = '0.0';
                  setTimeout(() => {
                    animateKPI(numElement, 0, value, 1500);
                  }, 200);
                } else {
                  numElement.textContent = '0';
                  setTimeout(() => {
                    animateKPI(numElement, 0, value, 1500);
                  }, 200);
                }
              }
            }, index * 150);
            
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.3 });

      document.querySelectorAll('.kpi').forEach(kpi => {
        kpi.style.opacity = '0';
        kpi.style.transform = 'translateY(15px) scale(0.95)';
        kpi.style.transition = 'opacity 0.5s ease, transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.1), box-shadow 0.3s ease';
        observer.observe(kpi);
      });
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
      initKPIAnimations();
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
    });
  </script>
</body>
</html>