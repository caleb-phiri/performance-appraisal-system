<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover" />
  <title>Login • MOIC Enterprise Appraisal System</title>

  <!-- Bootstrap 5 CSS (Production) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Fonts & icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* MOIC Brand Colors - matching welcome page exactly */
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
      --radius: 16px;
      --shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html {
      height: 100%;
      -webkit-text-size-adjust: 100%;
      scroll-behavior: smooth;
    }
    
    body {
      font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.5;
      min-height: 100vh;
      background: linear-gradient(135deg, #f0f5ff 0%, #e6eeff 50%, #fef5e8 100%);
      overflow-x: hidden;
      position: relative;
    }
    
    /* Background Container - identical to welcome blade */
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
    }
    
    .bg-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.88);
      z-index: 1;
    }
    
    .bg-gradient-fallback {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(17,4,132,0.05) 0%, rgba(231,88,28,0.05) 50%, rgba(17,4,132,0.05) 100%);
      z-index: 0;
    }
    
    /* Particles - matching welcome */
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
      0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10% { opacity: 0.12; }
      90% { opacity: 0.12; }
      100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
    }
    
    /* Fixed Header - responsive */
    .fixed-top-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background: transparent;
    }
    
    header {
      width: 100%;
      background: rgba(255, 255, 255, 0.96);
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 12px rgba(17, 4, 132, 0.08);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(17, 4, 132, 0.1);
      flex-wrap: wrap;
      gap: 12px;
    }
    
    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }
    
    .brand .logo {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: var(--moic-gradient-mixed);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 800;
      font-size: 14px;
      box-shadow: 0 3px 8px rgba(17, 4, 132, 0.2);
    }
    
    .brand .title {
      font-weight: 800;
      background: var(--moic-gradient-mixed);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-size: 0.9rem;
      letter-spacing: -0.2px;
    }
    
    .brand .subtitle {
      font-size: 9px;
      color: var(--muted);
    }
    
    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }
    
    .actions a {
      padding: 6px 14px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 0.8rem;
    }
    
    .register-btn {
      border: 1px solid rgba(17,4,132,0.25);
      background: transparent;
      color: var(--moic-navy);
    }
    
    .register-btn:hover {
      background: var(--moic-gradient-mixed);
      color: white;
      transform: translateY(-2px);
      border-color: transparent;
      box-shadow: 0 6px 14px rgba(17, 4, 132, 0.25);
    }
    
    /* Partnership Banner - responsive */
    .partnership-banner {
      background: var(--moic-gradient-mixed);
      border-radius: 20px;
      padding: 12px 20px;
      box-shadow: 0 10px 28px rgba(17, 4, 132, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.25);
      margin: 12px auto;
      max-width: 1200px;
      width: calc(100% - 24px);
      position: relative;
      z-index: 30;
      animation: gradientShift 12s ease infinite;
      background-size: 200% 200%;
    }
    
    @keyframes gradientShift {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    
    .partnership-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
    }
    
    @media (min-width: 768px) {
      .partnership-content {
        flex-direction: row;
        justify-content: space-between;
        gap: 20px;
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
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }
    
    .logo-box {
      background: white;
      border-radius: 14px;
      padding: 8px 12px;
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 60px;
    }
    
    .logo-box img {
      height: 32px;
      width: auto;
      object-fit: contain;
    }
    
    @media (max-width: 480px) {
      .logo-box img {
        height: 26px;
      }
      .logo-box {
        padding: 6px 10px;
      }
    }
    
    .partnership-icon {
      background: white;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .partnership-icon i {
      color: var(--moic-accent);
      font-size: 18px;
    }
    
    .partnership-text {
      text-align: center;
      color: white;
    }
    
    @media (min-width: 768px) {
      .partnership-text {
        text-align: left;
      }
    }
    
    .partnership-text h3 {
      font-weight: 700;
      font-size: 0.95rem;
      margin: 0;
    }
    
    .partnership-text p {
      font-size: 0.75rem;
      opacity: 0.9;
      margin: 2px 0 0;
    }
    
    .partnership-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.25);
      color: white;
      font-size: 0.65rem;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 30px;
      margin-top: 6px;
    }
    
    .partnership-info {
      text-align: center;
      color: white;
      padding: 8px 14px;
      background: rgba(255, 255, 255, 0.12);
      border-radius: 16px;
      backdrop-filter: blur(4px);
    }
    
    @media (min-width: 768px) {
      .partnership-info {
        text-align: right;
      }
    }
    
    .partnership-info .system-name {
      font-weight: 700;
      font-size: 0.85rem;
    }
    
    .partnership-info .system-desc {
      font-size: 0.7rem;
      opacity: 0.9;
    }
    
    .version-badge {
      display: inline-block;
      background: white;
      color: var(--moic-navy);
      font-size: 0.6rem;
      font-weight: 800;
      padding: 2px 8px;
      border-radius: 20px;
    }
    
    /* Main Content - reduced top padding to minimize space between head and content */
    .main-content {
      padding-top: 20px;
      padding-bottom: 90px;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 1;
    }
    
    @media (max-width: 768px) {
      .main-content {
        padding-top: 16px;
        padding-bottom: 80px;
        padding-left: 16px;
        padding-right: 16px;
      }
    }
    
    @media (max-width: 480px) {
      .main-content {
        padding-top: 12px;
        padding-bottom: 70px;
      }
    }
    
    /* Login Card - fully responsive */
    .login-card {
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(12px);
      border-radius: 28px;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.6);
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      overflow: hidden;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      border-top: 3px solid var(--moic-accent);
      border-bottom: 3px solid var(--moic-navy);
    }
    
    @media (max-width: 560px) {
      .login-card {
        max-width: 100%;
        border-radius: 24px;
      }
    }
    
    .login-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 24px 40px -16px rgba(0, 0, 0, 0.25);
    }
    
    .card-header-gradient {
      background: var(--moic-gradient-mixed);
      padding: 1.8rem 1.5rem;
      text-align: center;
      color: white;
    }
    
    @media (max-width: 480px) {
      .card-header-gradient {
        padding: 1.4rem 1rem;
      }
    }
    
    .card-header-gradient h2 {
      font-weight: 800;
      font-size: 1.8rem;
      margin-bottom: 0.25rem;
      letter-spacing: -0.3px;
    }
    
    @media (max-width: 480px) {
      .card-header-gradient h2 {
        font-size: 1.5rem;
      }
    }
    
    .card-header-gradient p {
      opacity: 0.92;
      font-size: 0.85rem;
    }
    
    .login-body {
      padding: 2rem;
    }
    
    @media (max-width: 480px) {
      .login-body {
        padding: 1.5rem;
      }
    }
    
    /* Form Elements - touch friendly */
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-label {
      font-weight: 700;
      color: var(--moic-navy);
      margin-bottom: 0.6rem;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
    }
    
    .form-control-custom {
      width: 100%;
      padding: 0.9rem 1rem;
      border: 1.5px solid #e2e8f0;
      border-radius: 18px;
      font-size: 1rem;
      transition: all 0.25s ease;
      background: white;
      font-family: inherit;
    }
    
    @media (max-width: 480px) {
      .form-control-custom {
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
      }
    }
    
    .form-control-custom:focus {
      outline: none;
      border-color: var(--moic-accent);
      box-shadow: 0 0 0 4px rgba(231, 88, 28, 0.15);
    }
    
    .password-wrapper {
      position: relative;
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
      padding: 8px;
      border-radius: 30px;
      transition: all 0.2s;
    }
    
    .toggle-password:hover {
      color: var(--moic-navy);
      background: rgba(17,4,132,0.05);
    }
    
    .info-badge {
      margin-top: 0.6rem;
      font-size: 0.75rem;
      color: var(--muted);
      display: flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
    }
    
    /* Login Rules Card */
    .login-rules {
      background: rgba(17, 4, 132, 0.04);
      border-radius: 20px;
      padding: 1.1rem;
      margin: 1.5rem 0;
      border: 1px solid rgba(17, 4, 132, 0.12);
    }
    
    .login-rules h4 {
      font-size: 0.85rem;
      font-weight: 800;
      color: var(--moic-navy);
      margin-bottom: 0.7rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .login-rules ul {
      margin: 0;
      padding-left: 1.2rem;
      font-size: 0.75rem;
      color: #334155;
    }
    
    .login-rules li {
      margin-bottom: 0.45rem;
      line-height: 1.4;
    }
    
    /* Button */
    .btn-login {
      background: var(--moic-gradient-mixed);
      color: white;
      border: none;
      padding: 0.95rem;
      border-radius: 40px;
      font-weight: 800;
      font-size: 1rem;
      width: 100%;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      cursor: pointer;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(17, 4, 132, 0.35);
      background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-accent-light));
    }
    
    .btn-login:disabled {
      opacity: 0.7;
      transform: none;
      cursor: not-allowed;
    }
    
    /* Alerts */
    .alert-custom {
      border-radius: 20px;
      padding: 1rem 1.2rem;
      margin-bottom: 1.5rem;
      border-left: 5px solid;
      animation: slideIn 0.3s ease-out;
      font-size: 0.85rem;
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .alert-error {
      background: #fee2e2;
      border-left-color: #dc2626;
      color: #991b1b;
    }
    
    .alert-success {
      background: #d1fae5;
      border-left-color: #10b981;
      color: #065f46;
    }
    
    .alert-info {
      background: #dbeafe;
      border-left-color: #3b82f6;
      color: #1e40af;
    }
    
    .alert-warning {
      background: #fef3c7;
      border-left-color: #f59e0b;
      color: #92400e;
    }
    
    .footer-links {
      text-align: center;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid rgba(17, 4, 132, 0.12);
    }
    
    .footer-links a {
      color: var(--moic-navy);
      font-weight: 700;
      text-decoration: none;
      font-size: 0.85rem;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    
    .footer-links a:hover {
      color: var(--moic-accent);
      transform: translateX(2px);
    }
    
    /* Global Footer */
    footer {
      padding: 14px 20px;
      text-align: center;
      color: var(--moic-navy);
      font-size: 0.7rem;
      background: rgba(255, 255, 255, 0.96);
      border-top: 1px solid rgba(17, 4, 132, 0.12);
      backdrop-filter: blur(8px);
      font-weight: 500;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 40;
    }
    
    @media (max-width: 480px) {
      footer {
        font-size: 0.65rem;
        padding: 10px 12px;
      }
    }
    
    /* Spinner */
    .spinner-border-sm {
      width: 1.2rem;
      height: 1.2rem;
      border-width: 0.15rem;
    }
    
    /* Touch-friendly adjustments */
    @media (hover: none) and (pointer: coarse) {
      .btn-login, .toggle-password, .actions a {
        cursor: default;
      }
      .form-control-custom {
        font-size: 16px; /* Prevents zoom on iOS */
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
    
    /* Extra small devices */
    @media (max-width: 380px) {
      .card-header-gradient h2 {
        font-size: 1.3rem;
      }
      .login-body {
        padding: 1.2rem;
      }
      .form-control-custom {
        padding: 0.7rem 0.9rem;
      }
      .partnership-text h3 {
        font-size: 0.8rem;
      }
      .brand .title {
        font-size: 0.75rem;
      }
    }
  </style>
</head>
<body>

  <!-- Background Container - identical to welcome blade -->
  <div class="background-container">
    <div class="bg-image" id="bgImage"></div>
    <div class="bg-overlay"></div>
    <div class="bg-gradient-fallback"></div>
  </div>

  <!-- Particles animation -->
  <div class="particles" id="particles"></div>

  <!-- Partnership Banner - directly after particles, minimal spacing -->
  <div class="partnership-banner">
    <div class="partnership-content">
      <div class="logo-container">
        <div class="logo-pair">
          <div class="logo-box">
            <img src="{{ asset('images/moic.png') }}" alt="MOIC" 
                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect width=%22100%22 height=%22100%22 fill=%22%23110484%22/%3E%3Ctext x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2224%22%3EMOIC%3C/text%3E%3C/svg%3E'">
          </div>
          <div class="partnership-icon">
            <i class="fas fa-handshake"></i>
          </div>
          <div class="logo-box">
            <img src="{{ asset('images/TKC.png') }}" alt="TKC"
                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect width=%22100%22 height=%22100%22 fill=%22%23e7581c%22/%3E%3Ctext x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2220%22%3ETKC%3C/text%3E%3C/svg%3E'">
          </div>
        </div>
        <div class="partnership-text">
          <h3>MOIC & TKC Partnership</h3>
          <p>Strategic alliance for excellence</p>
          <span class="partnership-badge">OFFICIAL PARTNERS</span>
        </div>
      </div>
      <div class="partnership-info">
        <div class="system-name">Performance Appraisal System</div>
        <div class="system-desc">Jointly managed platform</div>
        <span class="version-badge">v2.0</span>
      </div>
    </div>
  </div>

  <!-- Minimal header section (compact) -->
  <header>
    <div class="brand">
      <div class="logo">M</div>
      <div>
        <div class="title">MOIC Enterprise Appraisal</div>
        <div class="subtitle">powered by TKC</div>
      </div>
    </div>
    <div class="actions">
      <a href="{{ route('onboarding.survey') }}" class="register-btn"><i class="fas fa-user-plus me-1"></i> Register / Setup</a>
    </div>
  </header>

  <!-- Main Login Area - reduced top spacing (now padding-top: 20px on .main-content) -->
  <div class="main-content">
    <div class="login-card">
      <div class="card-header-gradient">
        <h2><i class="fas fa-lock me-2"></i> Sign In</h2>
        <p>Access your performance dashboard</p>
      </div>
      
      <div class="login-body">
        <!-- Session Alerts (Laravel dynamic) -->
        @if(session('status'))
          <div class="alert-custom alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
          </div>
        @endif
        
        @if(session('error'))
          <div class="alert-custom alert-error">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
          </div>
        @endif
        
        @if(session('info'))
          <div class="alert-custom alert-info">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
          </div>
        @endif
        
        @if($errors->any())
          <div class="alert-custom alert-error">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Login Failed:</strong>
            <ul class="mb-0 mt-2 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        
        @if(session('show_onboarding_link'))
          <div class="alert-custom alert-warning">
            <div class="d-flex align-items-start gap-2 flex-wrap">
              <i class="fas fa-user-plus mt-1"></i>
              <div>
                <strong>New Employee?</strong>
                <p class="small mb-2">Please complete your profile setup first.</p>
                <a href="{{ route('onboarding.survey') }}" class="btn btn-sm" style="background: var(--moic-accent); color: white; border-radius: 40px; padding: 5px 16px;">
                  <i class="fas fa-user-edit me-1"></i> Complete Profile
                </a>
              </div>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('employee.login') }}" id="loginForm">
          @csrf
          
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-id-card"></i> Employee Number
            </label>
            <input type="text" 
                   name="employee_number" 
                   id="employee_number"
                   value="{{ old('employee_number') }}"
                   class="form-control-custom"
                   placeholder="e.g., MOIC-00123"
                   required
                   autofocus>
            <div class="info-badge">
              <i class="fas fa-info-circle"></i>
              <span>Your unique employee ID (minimum 3 characters)</span>
            </div>
          </div>
          
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-key"></i> Password
              <span id="passwordHint" class="small fw-normal ms-1 text-muted">(Optional for first login)</span>
            </label>
            <div class="password-wrapper">
              <input type="password" 
                     name="password" 
                     id="password"
                     class="form-control-custom"
                     placeholder="••••••••"
                     autocomplete="current-password">
              <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div id="dynamicMessage" class="info-badge mt-2">
              <i class="fas fa-unlock-alt"></i>
              <span id="passwordInstruction">First time? Leave password blank to proceed</span>
            </div>
          </div>
          
          <div class="login-rules">
            <h4><i class="fas fa-shield-alt"></i> Login Rules</h4>
            <ul>
              <li><strong>First-time users:</strong> Login with employee number only (leave password blank)</li>
              <li><strong>Returning users:</strong> Must enter your registered password</li>
              <li><strong>Forgot password?</strong> Contact system administrator</li>
              <li><strong>New employee?</strong> Complete profile setup first via Register button</li>
            </ul>
          </div>
          
          <button type="submit" id="submitBtn" class="btn-login">
            <i class="fas fa-sign-in-alt"></i> Sign In
          </button>
        </form>
        
        <div class="footer-links">
          <a href="{{ route('onboarding.survey') }}">
            <i class="fas fa-user-plus"></i> New Employee? Complete Profile Setup
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <footer>
    © <span id="year"></span> MOIC & TKC Consortium — Performance Management System. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function() {
      // Set current year
      document.getElementById('year').textContent = new Date().getFullYear();
      
      // Particles generator (matching welcome)
      function createParticles() {
        const container = document.getElementById('particles');
        if (!container) return;
        const count = 12;
        for (let i = 0; i < count; i++) {
          const p = document.createElement('div');
          p.classList.add('particle');
          const size = Math.random() * 24 + 8;
          p.style.width = size + 'px';
          p.style.height = size + 'px';
          p.style.left = Math.random() * 100 + '%';
          p.style.animationDelay = Math.random() * 20 + 's';
          p.style.animationDuration = Math.random() * 20 + 28 + 's';
          p.style.opacity = Math.random() * 0.1 + 0.05;
          container.appendChild(p);
        }
      }
      createParticles();
      
      // Background image fallback
      const bgDiv = document.getElementById('bgImage');
      if (bgDiv) {
        const testImg = new Image();
        testImg.src = '/images/Purple Yellow Grey Illustrative Marketing Instagram Post (2).png';
        testImg.onload = () => { bgDiv.style.backgroundImage = `url('/images/Purple Yellow Grey Illustrative Marketing Instagram Post (2).png')`; bgDiv.style.backgroundSize = 'cover'; };
        testImg.onerror = () => { /* keep gradient fallback from body */ };
      }
      
      // Toggle password visibility
      const toggleBtn = document.getElementById('togglePassword');
      const pwdField = document.getElementById('password');
      if (toggleBtn && pwdField) {
        toggleBtn.addEventListener('click', () => {
          const type = pwdField.type === 'password' ? 'text' : 'password';
          pwdField.type = type;
          toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
      }
      
      // Dynamic password instruction based on employee number
      const empField = document.getElementById('employee_number');
      const pwdInstructionSpan = document.getElementById('passwordInstruction');
      const passwordHintSpan = document.getElementById('passwordHint');
      
      function updateDynamicHint() {
        const val = empField?.value.trim() || '';
        if (val.length >= 3) {
          if (pwdInstructionSpan) pwdInstructionSpan.innerHTML = '<i class="fas fa-key me-1"></i> Returning user? Enter password. First time? Leave blank.';
          if (passwordHintSpan) passwordHintSpan.textContent = '(Optional for first login)';
        } else {
          if (pwdInstructionSpan) pwdInstructionSpan.innerHTML = '<i class="fas fa-info-circle me-1"></i> Enter employee number first to see options';
          if (passwordHintSpan) passwordHintSpan.textContent = '(Enter employee number)';
        }
      }
      
      if (empField) {
        empField.addEventListener('input', updateDynamicHint);
        empField.addEventListener('blur', updateDynamicHint);
        updateDynamicHint();
      }
      
      // Form submission with loading & validation
      const loginForm = document.getElementById('loginForm');
      const submitBtn = document.getElementById('submitBtn');
      
      function showTempError(msg) {
        const existing = document.querySelector('.temp-error-alert');
        if (existing) existing.remove();
        const errDiv = document.createElement('div');
        errDiv.className = 'alert-custom alert-error temp-error-alert';
        errDiv.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${msg}`;
        const formContainer = document.querySelector('.login-body');
        const formElem = document.getElementById('loginForm');
        if (formContainer && formElem) {
          formContainer.insertBefore(errDiv, formElem);
        }
        setTimeout(() => errDiv.remove?.(), 5000);
      }
      
      if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
          const empNumber = empField?.value.trim() || '';
          if (!empNumber) {
            e.preventDefault();
            showTempError('Please enter your employee number');
            empField?.focus();
            return false;
          }
          if (empNumber.length < 3) {
            e.preventDefault();
            showTempError('Employee number must be at least 3 characters');
            empField?.focus();
            return false;
          }
          
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Signing in...';
          setTimeout(() => {
            if (submitBtn.disabled) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
            }
          }, 8000);
          return true;
        });
      }
      
      // Entrance animation for login card
      const card = document.querySelector('.login-card');
      if (card) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(18px)';
        card.style.transition = 'opacity 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1), transform 0.5s ease';
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 80);
      }
      
      // Auto-focus on employee field after load (mobile friendly)
      setTimeout(() => {
        if (empField && !empField.value) empField.focus();
      }, 200);
    })();
  </script>
</body>
</html>