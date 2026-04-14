<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Appraisals - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom CSS - ENHANCED TABLE STYLES -->
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
            
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
        }
        
        /* Base styles for consistent zoom */
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
            scroll-behavior: smooth;
        }
        
        body {
            background: #f0f3f8;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(17, 4, 132, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(231, 88, 28, 0.03) 0%, transparent 50%),
                repeating-linear-gradient(45deg, rgba(0,0,0,0.01) 0px, rgba(0,0,0,0.01) 1px, transparent 1px, transparent 10px);
            min-height: 100vh;
            font-size: 0.875rem;
            line-height: 1.6;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
            position: relative;
        }
        
        /* ============================================
           ENHANCED TABLE STYLES - PROFESSIONAL & MODERN
           ============================================ */
        
        /* Main Table Container */
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .table-container:hover {
            box-shadow: 0 8px 30px rgba(17, 4, 132, 0.1);
        }
        
        /* Enhanced Table */
        .table-moic-enhanced {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
        }
        
        /* Table Header */
        .table-moic-enhanced thead th {
            background: linear-gradient(135deg, #110484 0%, #1a0c9e 100%);
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1rem;
            border-bottom: 3px solid var(--moic-accent);
            position: sticky;
            top: 0;
            z-index: 10;
            white-space: nowrap;
        }
        
        .table-moic-enhanced thead th:first-child {
            border-top-left-radius: 12px;
        }
        
        .table-moic-enhanced thead th:last-child {
            border-top-right-radius: 12px;
        }
        
        /* Table Body Rows */
        .table-moic-enhanced tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #eef2f6;
        }
        
        .table-moic-enhanced tbody tr:hover {
            background: linear-gradient(90deg, rgba(17, 4, 132, 0.02), rgba(231, 88, 28, 0.02));
            transform: translateX(2px);
        }
        
        /* Table Cells */
        .table-moic-enhanced tbody td {
            padding: 1rem 1rem;
            vertical-align: middle;
            font-size: 0.85rem;
            color: #1f2937;
            border-bottom: 1px solid #f0f2f5;
        }
        
        /* Status Badges - Enhanced */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.875rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        
        .status-badge i {
            font-size: 0.7rem;
        }
        
        .status-badge:hover {
            transform: scale(1.02);
        }
        
        .status-draft {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        .status-submitted {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .status-in-review {
            background: #f3e8ff;
            color: #6b21a5;
            border: 1px solid #e9d5ff;
        }
        
        .status-completed {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .status-archived {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }
        
        /* Score Cards Inside Table - Updated for proper percentage display */
        .score-card {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f9fafb;
            padding: 0.375rem 0.875rem;
            border-radius: 12px;
            border-left: 3px solid;
            min-width: 130px;
        }
        
        .score-high {
            border-left-color: #10b981;
        }
        
        .score-medium {
            border-left-color: #f59e0b;
        }
        
        .score-low {
            border-left-color: #ef4444;
        }
        
        .score-value {
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        .score-percent {
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        /* Progress Bar Enhanced */
        .progress-enhanced {
            width: 80px;
            height: 6px;
            background-color: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .progress-bar-enhanced {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .progress-bar-high {
            background: linear-gradient(90deg, #10b981, #34d399);
        }
        
        .progress-bar-medium {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }
        
        .progress-bar-low {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }
        
        /* Supervisor Avatars */
        .supervisor-avatar-group {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        
        .supervisor-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 0.25rem 0.625rem;
            font-size: 0.7rem;
            transition: all 0.2s ease;
        }
        
        .supervisor-badge:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }
        
        .supervisor-badge.rated {
            border-color: #10b981;
            background: #f0fdf4;
        }
        
        .supervisor-badge i {
            font-size: 0.65rem;
        }
        
        .primary-badge {
            background: #8b5cf6;
            color: white;
            font-size: 0.55rem;
            padding: 0.125rem 0.375rem;
            border-radius: 10px;
            margin-left: 0.25rem;
        }
        
        /* Action Buttons Enhanced */
        .action-group {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .action-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }
        
        .action-icon i {
            font-size: 0.875rem;
        }
        
        .action-icon:hover {
            transform: translateY(-2px);
        }
        
        .action-view {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .action-view:hover {
            background: #3b82f6;
            color: white;
        }
        
        .action-edit {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .action-edit:hover {
            background: #10b981;
            color: white;
        }
        
        .action-submit {
            background: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }
        
        .action-submit:hover {
            background: #8b5cf6;
            color: white;
        }
        
        .action-rate {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .action-rate:hover {
            background: #f59e0b;
            color: white;
        }
        
        .action-delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .action-delete:hover {
            background: #ef4444;
            color: white;
        }
        
        .action-print {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }
        
        .action-print:hover {
            background: #6b7280;
            color: white;
        }
        
        /* Employee Info Cell */
        .employee-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .employee-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .employee-details {
            min-width: 0;
        }
        
        .employee-name {
            font-weight: 600;
            color: #110484;
            margin-bottom: 0.125rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .employee-id {
            font-size: 0.7rem;
            color: #6b7280;
        }
        
        /* Period Info */
        .period-info {
            display: flex;
            flex-direction: column;
        }
        
        .period-name {
            font-weight: 600;
            color: #1f2937;
        }
        
        .period-type {
            font-size: 0.65rem;
            color: #6b7280;
        }
        
        /* Date Cell */
        .date-cell {
            white-space: nowrap;
        }
        
        .date-day {
            font-weight: 500;
        }
        
        .date-time {
            font-size: 0.65rem;
            color: #6b7280;
        }
        
        /* Table Footer */
        .table-footer {
            background: #f9fafb;
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        /* Filter Label */
        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        /* Responsive Table */
        @media (max-width: 768px) {
            .table-moic-enhanced thead th {
                font-size: 0.65rem;
                padding: 0.75rem 0.5rem;
            }
            
            .table-moic-enhanced tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .employee-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
            
            .employee-name {
                font-size: 0.8rem;
            }
            
            .action-icon {
                width: 28px;
                height: 28px;
            }
            
            .action-icon i {
                font-size: 0.75rem;
            }
            
            .progress-enhanced {
                width: 50px;
            }
            
            .score-card {
                min-width: 110px;
                padding: 0.25rem 0.5rem;
            }
        }
        
        /* Header Styles */
        .moic-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
            box-shadow: 0 2px 10px rgba(17, 4, 132, 0.15);
            color: white;
            position: relative;
            z-index: 1020;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .card-moic {
            border: none;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            background-color: white;
        }
        
        .card-moic:hover {
            box-shadow: 0 10px 20px rgba(17, 4, 132, 0.08);
            transform: translateY(-2px);
        }
        
        .stat-card {
            border: none;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            background-color: white;
            padding: 1rem;
            height: 100%;
        }
        
        .stat-card:hover {
            box-shadow: 0 10px 20px rgba(17, 4, 132, 0.08);
            transform: translateY(-2px);
        }
        
        .filter-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .btn-moic {
            background: linear-gradient(135deg, #110484, #1a0c9e);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
        }
        
        .btn-moic:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #e7581c, #ff7c45);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-accent:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 1px solid #110484;
            color: #110484 !important;
            transition: all 0.3s ease;
        }
        
        .btn-outline-moic:hover {
            background: #110484;
            color: white !important;
            transform: translateY(-1px);
        }
        
        .nav-link-moic {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .nav-link-moic:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link-footer {
            color: #110484;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        
        .nav-link-footer:hover {
            background: linear-gradient(135deg, rgba(17, 4, 132, 0.05), rgba(231, 88, 28, 0.05));
            transform: translateY(-1px);
        }
        
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .avatar-gradient {
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .stat-icon i {
            font-size: 20px;
        }
        
        .stat-number {
            font-size: 22px;
            font-weight: 700;
        }
        
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        .bg-orange-50 { background-color: #fff7ed !important; }
        
        .text-green-600 { color: #059669 !important; }
        .text-purple-600 { color: #7c3aed !important; }
        .text-blue-600 { color: #2563eb !important; }
        .text-yellow-600 { color: #d97706 !important; }
        .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; }
        
        .pagination-moic .page-link {
            color: #110484;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        .pagination-moic .page-item.active .page-link {
            background: linear-gradient(135deg, #110484, #1a0c9e);
            border-color: #110484;
            color: white;
        }
        
        @media (max-width: 768px) {
            .desktop-only {
                display: none !important;
            }
            .mobile-only {
                display: block !important;
            }
            .stat-number {
                font-size: 18px;
            }
            .stat-icon {
                width: 38px;
                height: 38px;
            }
            .stat-icon i {
                font-size: 16px;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
        }
        
        .truncate-tooltip {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 120px;
            cursor: help;
        }
        
        .min-w-0 {
            min-width: 0;
        }
    </style>
</head>
<body>
    <!-- Header with MOIC Gradient -->
    <header class="moic-header">
        <div class="container-custom px-3 py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-1 rounded me-3">
                        <img class="img-fluid" style="height: 2rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold">Performance Appraisals</h1>
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="text-white-50 small me-3">
                                <i class="fas fa-user me-1"></i>
                                {{ auth()->user()->user_type === 'supervisor' ? 'Supervisor' : 'Employee' }}: 
                                <span class="fw-medium">{{ auth()->user()->name ?? 'User' }}</span>
                            </span>
                            <span class="text-white-50 small">
                                <i class="fas fa-id-badge me-1"></i>
                                ID: <span class="fw-medium">{{ auth()->user()->employee_number ?? 'N/A' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="text-white d-none d-md-inline">{{ auth()->user()->name ?? 'User' }}</span>
                    
                    <div class="dropdown desktop-only">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 moic-navy"></i> Profile</a></li>
                            <li><a href="{{ route('appraisals.quarterly-summary.index', request()->all()) }}" class="dropdown-item"><i class="fas fa-chart-bar me-2 moic-navy"></i> Quarterly Summary</a></li>
                            <li><a href="{{ route('appraisals.quarterly-summary.download', request()->all()) }}" class="dropdown-item" target="_blank"><i class="fas fa-download me-2 moic-navy"></i> Download Excel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2 moic-navy"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.index') }}"><i class="fas fa-calendar-alt me-2 moic-accent"></i> Leave</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.balance') }}"><i class="fas fa-wallet me-2 moic-navy"></i> Balance</a></li>
                            <li><a class="dropdown-item" href="{{ route('calendar.index') }}"><i class="fas fa-calendar me-2 moic-accent"></i> Calendar</a></li>
                            <li><a class="dropdown-item" href="{{ route('appraisals.index') }}"><i class="fas fa-file-alt me-2 moic-navy"></i> Appraisals</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="dropdown mobile-only">
                        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mobile-menu">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-gradient me-3">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->name ?? 'User' }}</div>
                                        <div class="small text-muted">{{ auth()->user()->employee_number ?? '' }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2 moic-navy"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('appraisals.quarterly-summary.index', request()->all()) }}"><i class="fas fa-chart-bar me-2 moic-navy"></i> Quarterly Summary</a></li>
                            <li><a class="dropdown-item" href="{{ route('appraisals.quarterly-summary.download', request()->all()) }}" target="_blank"><i class="fas fa-download me-2 moic-navy"></i> Download Excel</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.index') }}"><i class="fas fa-calendar-alt me-2 moic-accent"></i> Leave</a></li>
                            <li><a class="dropdown-item" href="{{ route('leave.balance') }}"><i class="fas fa-wallet me-2 moic-navy"></i> Balance</a></li>
                            <li><a class="dropdown-item" href="{{ route('calendar.index') }}"><i class="fas fa-calendar me-2 moic-accent"></i> Calendar</a></li>
                            <li><a class="dropdown-item" href="{{ route('appraisals.index') }}"><i class="fas fa-file-alt me-2 moic-navy"></i> Appraisals</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 moic-navy"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('appraisals.create') }}"><i class="fas fa-plus me-2 text-success"></i> New Appraisal</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        <div class="container-custom px-3">
            <!-- Back to Dashboard Link -->
            <div class="mb-3">
                <a href="{{ route('dashboard') }}" class="text-decoration-none moic-navy">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>

            <!-- Filter Card with Quarter Filter -->
            <div class="filter-card p-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-filter me-2" style="color: var(--moic-accent);"></i>
                    <h3 class="h5 fw-bold mb-0" style="color: var(--moic-navy);">Filter Appraisals</h3>
                </div>
                
                <form method="GET" action="{{ route('appraisals.index') }}" id="filterForm">
                    <div class="row g-3">
                        @if($isSupervisor ?? false)
                        <div class="col-md-4">
                            <label class="filter-label"><i class="fas fa-user me-1"></i> Employee Search</label>
                            <select name="employee_number" id="employeeSelect" class="form-select">
                                <option value="">-- All Employees --</option>
                                @if(isset($teamMembers) && $teamMembers->count())
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->employee_number }}" {{ request('employee_number') == $member->employee_number ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->employee_number }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif
                        
                        <!-- ADDED: Quarter Filter -->
                        <div class="col-md-3">
                            <label class="filter-label"><i class="fas fa-calendar-alt me-1"></i> Quarter</label>
                            <select name="quarter" class="form-select">
                                <option value="">-- All Quarters --</option>
                                @if(isset($availableQuarters) && count($availableQuarters) > 0)
                                    @foreach($availableQuarters as $q)
                                        <option value="{{ $q }}" {{ request('quarter') == $q ? 'selected' : '' }}>
                                            {{ $q }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Q1 2024" {{ request('quarter') == 'Q1 2024' ? 'selected' : '' }}>Q1 2024 (Jan - Mar)</option>
                                    <option value="Q2 2024" {{ request('quarter') == 'Q2 2024' ? 'selected' : '' }}>Q2 2024 (Apr - Jun)</option>
                                    <option value="Q3 2024" {{ request('quarter') == 'Q3 2024' ? 'selected' : '' }}>Q3 2024 (Jul - Sep)</option>
                                    <option value="Q4 2024" {{ request('quarter') == 'Q4 2024' ? 'selected' : '' }}>Q4 2024 (Oct - Dec)</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-moic flex-grow-1"><i class="fas fa-search me-2"></i>Apply</button>
                                @if(request()->has('employee_number') || request()->has('quarter') || request()->has('search'))
                                <a href="{{ route('appraisals.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Clear</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                @if(request()->has('employee_number') || request()->has('quarter') || request()->has('search'))
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex flex-wrap gap-2">
                        <span class="text-muted small">Active filters:</span>
                        @if(request('employee_number'))
                            @php 
                                $empFilter = \App\Models\User::where('employee_number', request('employee_number'))->first(); 
                            @endphp
                            <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                                <i class="fas fa-user me-1"></i> Employee: {{ $empFilter->name ?? request('employee_number') }}
                                <a href="{{ route('appraisals.index', array_merge(request()->except('employee_number'), ['employee_number' => null])) }}" class="text-decoration-none ms-2 fw-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('quarter'))
                            <span class="badge bg-success bg-opacity-10 text-success py-2 px-3">
                                <i class="fas fa-calendar me-1"></i> Quarter: {{ request('quarter') }}
                                <a href="{{ route('appraisals.index', array_merge(request()->except('quarter'), ['quarter' => null])) }}" class="text-decoration-none ms-2 fw-bold">&times;</a>
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="badge bg-info bg-opacity-10 text-info py-2 px-3">
                                <i class="fas fa-search me-1"></i> Search: "{{ request('search') }}"
                                <a href="{{ route('appraisals.index', array_merge(request()->except('search'), ['search' => null])) }}" class="text-decoration-none ms-2 fw-bold">&times;</a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Employee Profile Card -->
            @if((auth()->user()->user_type === 'employee' && !request()->has('employee_number')) || (auth()->user()->user_type === 'supervisor' && request()->has('employee_number')))
            <div class="mb-4">
                <div class="card card-moic shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
                            <div class="profile-avatar bg-gradient" style="width: 70px; height: 70px; border-radius: 16px; background: linear-gradient(135deg, #110484, #e7581c); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                            <div class="flex-grow-1 w-100">
                                <h2 class="h4 fw-bold moic-navy mb-2">{{ $employee->name ?? auth()->user()->name }}</h2>
                                <div class="row g-2 g-md-3">
                                    <div class="col-6 col-md-3">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-muted small mb-1">Employee ID</p>
                                            <p class="fw-semibold mb-0">{{ $employee->employee_number ?? auth()->user()->employee_number ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-muted small mb-1">Department</p>
                                            <p class="fw-semibold mb-0">{{ $employee->department ?? auth()->user()->department ?? 'Not specified' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-muted small mb-1">Job Title</p>
                                            <p class="fw-semibold mb-0">{{ $employee->job_title ?? auth()->user()->job_title ?? 'Not specified' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-muted small mb-1">Supervisors</p>
                                            <p class="fw-semibold mb-0">{{ ($employee->ratingSupervisors->count() ?? 0) }} supervisor(s)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Stats Summary -->
            <div class="row g-2 g-md-3 mb-4">
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="stat-card h-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon rounded-circle bg-blue-50">
                                <i class="fas fa-file-alt moic-navy"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-muted small mb-1">Total Appraisals</p>
                                <p class="stat-number mb-0">{{ $appraisals->total() ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="stat-card h-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon rounded-circle bg-green-50">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Submitted</p>
                                <p class="stat-number mb-0">{{ $submittedCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="stat-card h-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon rounded-circle bg-yellow-50">
                                <i class="fas fa-edit moic-accent"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Drafts</p>
                                <p class="stat-number mb-0">{{ $draftCount ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6 col-lg-3">
                    <div class="stat-card h-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon rounded-circle bg-purple-50">
                                <i class="fas fa-star text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Avg Score</p>
                                <p class="stat-number mb-0">{{ number_format($averageScore ?? 0, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Appraisal Table -->
            <div class="table-container">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            @php
                                $currentEmployee = $employee ?? auth()->user();
                                $employeeName = $currentEmployee->name ?? auth()->user()->name;
                                $employeeNumber = $currentEmployee->employee_number ?? auth()->user()->employee_number;
                                
                                if (auth()->user()->user_type === 'supervisor' && !request()->has('employee_number')) {
                                    $title = "My Assigned Employees' Appraisals";
                                    $subtitle = "View all appraisals for employees assigned to you";
                                } elseif (auth()->user()->user_type === 'supervisor' && request()->has('employee_number')) {
                                    $title = "Appraisal History for " . $employeeName;
                                    $subtitle = "View all performance appraisals for Employee ID: " . $employeeNumber;
                                } else {
                                    $title = "My Appraisal History";
                                    $subtitle = "View all your performance appraisals";
                                }
                            @endphp
                            <h2 class="h5 fw-bold moic-navy mb-1">{{ $title }}</h2>
                            <p class="text-muted small mb-0">{{ $subtitle }}</p>
                            
                            @if(auth()->user()->user_type === 'supervisor' && request()->has('employee_number'))
                            <p class="text-muted small mt-1">
                                <i class="fas fa-info-circle text-primary me-1"></i>
                                You are an assigned supervisor. You can rate and provide feedback on this employee's appraisals.
                            </p>
                            @endif
                        </div>
                        
                        @php
                            $currentUserEmployeeNumber = auth()->user()->employee_number ?? null;
                            $viewingEmployeeNumber = $employeeNumber ?? $currentUserEmployeeNumber;
                            $isViewingOwnProfile = $currentUserEmployeeNumber && $viewingEmployeeNumber && $currentUserEmployeeNumber == $viewingEmployeeNumber;
                            $isSupervisorViewingTeam = auth()->user()->user_type === 'supervisor' && !request()->has('employee_number');
                        @endphp
                        
                        @if($isViewingOwnProfile && !$isSupervisorViewingTeam)
                        <a href="{{ route('appraisals.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i> Create New Appraisal
                        </a>
                        @endif
                    </div>
                </div>

                @if($appraisals->count() > 0)
                <div class="table-responsive">
                    <table class="table-moic-enhanced mb-0">
                        <thead>
                            <tr>
                                @if(auth()->user()->user_type === 'supervisor' && !request()->has('employee_number'))
                                <th><i class="fas fa-user me-1"></i> Employee</th>
                                @endif
                                <th><i class="fas fa-hashtag me-1"></i> ID</th>
                                <th><i class="fas fa-calendar-alt me-1"></i> Period</th>
                                <th><i class="fas fa-clock me-1"></i> Duration</th>
                                <th><i class="fas fa-tag me-1"></i> Status</th>
                                <th><i class="fas fa-chart-line me-1"></i> Self Score</th>
                                <th><i class="fas fa-star me-1"></i> Final Score</th>
                                <th><i class="fas fa-user-tie me-1"></i> Supervisor Ratings</th>
                                <th><i class="fas fa-calendar-plus me-1"></i> Created</th>
                                <th class="text-end"><i class="fas fa-cogs me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appraisals as $appraisal)
                            @php
                                $currentUserEmployeeNumber = auth()->user()->employee_number ?? null;
                                $appraisalEmployeeNumber = $appraisal->employee_number ?? null;
                                $status = $appraisal->status ?? 'draft';
                                $hasKpasLoaded = isset($appraisal->kpas) && $appraisal->kpas->count() > 0;
                                
                                // Calculate scores
                                $calculatedSelfScore = 0;
                                $calculatedFinalScore = 0;
                                $hasSupervisorRatings = false;
                                $currentUserHasRated = false;
                                $ratedSupervisorsCount = 0;
                                $totalSupervisorsCount = 0;
                                
                                $employee = $appraisal->user ?? null;
                                if ($employee) {
                                    $totalSupervisorsCount = $employee->ratingSupervisors->count() ?? 0;
                                    
                                    if ($hasKpasLoaded && $currentUserEmployeeNumber) {
                                        foreach ($appraisal->kpas as $kpa) {
                                            if ($kpa->hasSupervisorRating($currentUserEmployeeNumber)) {
                                                $currentUserHasRated = true;
                                                break;
                                            }
                                        }
                                        
                                        $ratedSupervisors = collect();
                                        foreach ($appraisal->kpas as $kpa) {
                                            $ratings = $kpa->ratedSupervisors() ?? collect();
                                            foreach ($ratings as $rating) {
                                                if ($rating->supervisor_id && !$ratedSupervisors->contains($rating->supervisor_id)) {
                                                    $ratedSupervisors->push($rating->supervisor_id);
                                                }
                                            }
                                        }
                                        $ratedSupervisorsCount = $ratedSupervisors->count();
                                    }
                                }
                                
                                if ($hasKpasLoaded) {
                                    $totalWeight = 0;
                                    $selfWeightedSum = 0;
                                    $finalWeightedSum = 0;
                                    
                                    foreach ($appraisal->kpas as $kpa) {
                                        $weight = $kpa->weight ?? 0;
                                        $totalWeight += $weight;
                                        
                                        $selfRating = $kpa->self_rating ?? 0;
                                        $kpi = $kpa->kpi ?? 4;
                                        if ($kpi == 0) $kpi = 4;
                                        
                                        if ($selfRating > 0) {
                                            $selfPercentage = ($selfRating / $kpi) * 100;
                                            $selfWeightedSum += ($selfPercentage * $weight) / 100;
                                        }
                                        
                                        $finalRating = $kpa->getFinalSupervisorRatingAttribute() ?? $selfRating;
                                        if ($finalRating > 0) {
                                            $finalPercentage = ($finalRating / $kpi) * 100;
                                            $finalWeightedSum += ($finalPercentage * $weight) / 100;
                                        }
                                        
                                        if ($kpa->supervisor_rating > 0 || $kpa->ratedSupervisors()->count() > 0) {
                                            $hasSupervisorRatings = true;
                                        }
                                    }
                                    
                                    if ($totalWeight > 0) {
                                        $calculatedSelfScore = $selfWeightedSum > 0 ? min(100, max(0, round($selfWeightedSum, 2))) : 0;
                                        $calculatedFinalScore = $finalWeightedSum > 0 ? min(100, max(0, round($finalWeightedSum, 2))) : 0;
                                    }
                                }
                                
                                $selfScore = $appraisal->self_score > 0 ? (float) $appraisal->self_score : $calculatedSelfScore;
                                $finalScore = $appraisal->final_score > 0 ? (float) $appraisal->final_score : $calculatedFinalScore;
                                
                                $displayFinalScore = false;
                                $displayAwaitingRating = false;
                                
                                if (in_array($status, ['approved', 'completed', 'in_review', 'submitted'])) {
                                    if ($hasSupervisorRatings || $finalScore > 0) {
                                        $displayFinalScore = true;
                                    } else {
                                        $displayAwaitingRating = true;
                                    }
                                }
                                
                                $selfScoreColor = $selfScore >= 70 ? 'high' : ($selfScore >= 50 ? 'medium' : 'low');
                                $finalScoreColor = $finalScore >= 70 ? 'high' : ($finalScore >= 50 ? 'medium' : 'low');
                                $employeeName = $appraisal->employee_name ?? 'N/A';
                                
                                $isAssignedSupervisor = false;
                                if (auth()->user()->user_type === 'supervisor' && $employee) {
                                    foreach ($employee->ratingSupervisors as $supervisor) {
                                        if ($supervisor->employee_number === auth()->user()->employee_number) {
                                            $isAssignedSupervisor = true;
                                            break;
                                        }
                                    }
                                }
                                
                                $statusClass = 'status-' . str_replace('_', '-', $status);
                            @endphp
                            <tr>
                                @if(auth()->user()->user_type === 'supervisor' && !request()->has('employee_number'))
                                <td>
                                    <div class="employee-info">
                                        <div class="employee-avatar">
                                            {{ strtoupper(substr($employeeName, 0, 1)) }}
                                        </div>
                                        <div class="employee-details">
                                            <div class="employee-name">{{ $employeeName }}</div>
                                            <div class="employee-id">{{ $appraisal->employee_number }}</div>
                                            <a href="{{ route('appraisals.index', ['employee_number' => $appraisal->employee_number]) }}" class="text-decoration-none small text-primary">
                                                View all →
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                @endif
                                
                                <td class="fw-semibold moic-navy">#{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</td>
                                
                                <td>
                                    <div class="period-info">
                                        <span class="period-name">{{ $appraisal->period }}</span>
                                        <span class="period-type">{{ $appraisal->appraisal_type ?? 'Annual' }}</span>
                                    </div>
                                </td>
                                
                                <td class="date-cell">
                                    @php
                                        try {
                                            $startDate = \Carbon\Carbon::parse($appraisal->start_date);
                                            $endDate = \Carbon\Carbon::parse($appraisal->end_date);
                                            echo '<span class="date-day">' . $startDate->format('M d') . '</span>';
                                            echo '<span class="text-muted"> - </span>';
                                            echo '<span class="date-day">' . $endDate->format('M d') . '</span>';
                                            echo '<div class="date-time">' . $endDate->format('Y') . '</div>';
                                        } catch (\Exception $e) {
                                            echo '<span class="text-muted">Invalid date</span>';
                                        }
                                    @endphp
                                </td>
                                
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas {{ $status === 'draft' ? 'fa-edit' : ($status === 'submitted' ? 'fa-paper-plane' : ($status === 'approved' ? 'fa-check-circle' : ($status === 'in_review' ? 'fa-search' : 'fa-clock'))) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                
                                <!-- Self Score - Proper percentage formatting -->
                                <td>
                                    <div class="score-card score-{{ $selfScoreColor }}">
                                        <div class="progress-enhanced">
                                            <div class="progress-bar-enhanced progress-bar-{{ $selfScoreColor }}" style="width: {{ min($selfScore, 100) }}%"></div>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <span class="score-value {{ $selfScoreColor === 'high' ? 'text-green-600' : ($selfScoreColor === 'medium' ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ number_format($selfScore, 1) }}
                                            </span>
                                            <span class="score-percent ms-0">%</span>
                                        </div>
                                        <i class="fas fa-user-check text-muted ms-1" style="font-size: 0.65rem;" title="Self Rating"></i>
                                    </div>
                                </td>
                                
                                <!-- Final Score - Proper percentage formatting -->
                                <td>
                                    @if($displayFinalScore && $finalScore > 0)
                                    <div class="score-card score-{{ $finalScoreColor }}">
                                        <div class="progress-enhanced">
                                            <div class="progress-bar-enhanced progress-bar-{{ $finalScoreColor }}" style="width: {{ min($finalScore, 100) }}%"></div>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <span class="score-value {{ $finalScoreColor === 'high' ? 'text-green-600' : ($finalScoreColor === 'medium' ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ number_format($finalScore, 1) }}
                                            </span>
                                            <span class="score-percent ms-0">%</span>
                                        </div>
                                        @if($hasSupervisorRatings)
                                        <i class="fas fa-check-circle text-green-600 ms-1" style="font-size: 0.7rem;" title="Rated by supervisors"></i>
                                        @else
                                        <i class="fas fa-star text-muted ms-1" style="font-size: 0.65rem;" title="Awaiting rating"></i>
                                        @endif
                                    </div>
                                    @elseif($displayAwaitingRating)
                                    <div class="d-flex align-items-center text-warning">
                                        <i class="fas fa-clock me-2"></i>
                                        <span class="small">Awaiting Rating</span>
                                    </div>
                                    @else
                                    <div class="text-muted small">
                                        @switch($status)
                                            @case('draft')<i class="fas fa-edit me-1"></i>Draft@break
                                            @case('submitted')<i class="fas fa-paper-plane me-1"></i>Submitted@break
                                            @case('in_review')<i class="fas fa-search me-1"></i>In Review@break
                                            @case('rejected')<i class="fas fa-times-circle me-1"></i>Rejected@break
                                            @default<i class="fas fa-minus-circle me-1"></i>Not Rated
                                        @endswitch
                                    </div>
                                    @endif
                                </td>
                                
                                <td style="min-width: 200px;">
                                    @if($totalSupervisorsCount > 0)
                                        <div class="mb-2">
                                            <span class="badge {{ $ratedSupervisorsCount > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-light text-muted' }} py-1 px-2">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $ratedSupervisorsCount }}/{{ $totalSupervisorsCount }} rated
                                            </span>
                                        </div>
                                        
                                        @php
                                            $supervisors = $employee->ratingSupervisors ?? collect();
                                        @endphp
                                        
                                        @if($supervisors->count() > 0)
                                            <div class="supervisor-avatar-group">
                                                @foreach($supervisors as $supervisor)
                                                    @php
                                                        $isPrimary = $supervisor->pivot->is_primary ?? false;
                                                        $supervisorHasRated = false;
                                                        
                                                        if ($hasKpasLoaded) {
                                                            foreach ($appraisal->kpas as $kpa) {
                                                                if ($kpa->hasSupervisorRating($supervisor->employee_number)) {
                                                                    $supervisorHasRated = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        
                                                        $isCurrentUser = auth()->user()->employee_number === $supervisor->employee_number;
                                                    @endphp
                                                    
                                                    <div class="supervisor-badge {{ $supervisorHasRated ? 'rated' : '' }}" 
                                                         title="{{ $supervisor->name }} {{ $isPrimary ? '(Primary)' : '' }}">
                                                        <i class="fas fa-user-tie"></i>
                                                        <span class="{{ $isCurrentUser ? 'fw-bold text-primary' : '' }}">{{ $supervisor->name }}</span>
                                                        @if($isPrimary)
                                                            <span class="primary-badge">P</span>
                                                        @endif
                                                        @if($supervisorHasRated)
                                                            <i class="fas fa-check-circle text-green-600"></i>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        @if(auth()->user()->user_type === 'supervisor' && $isAssignedSupervisor)
                                            <div class="mt-2 pt-2 border-top">
                                                <div class="small">
                                                    @if($currentUserHasRated)
                                                        <span class="text-green-600">
                                                            <i class="fas fa-check-circle me-1"></i>You have rated
                                                        </span>
                                                    @elseif(in_array($status, ['submitted', 'in_review']))
                                                        <span class="text-warning">
                                                            <i class="fas fa-star me-1"></i>Your rating required
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @elseif($employee && $employee->manager)
                                        <div>
                                            <div class="text-muted small mb-1">Direct Manager:</div>
                                            <div class="supervisor-badge">
                                                <i class="fas fa-user-tie"></i>
                                                <span>{{ $employee->manager->name }}</span>
                                                <span class="primary-badge">M</span>
                                            </div>
                                            @if($hasSupervisorRatings)
                                                <span class="text-green-600 small mt-1 d-block">
                                                    <i class="fas fa-check-circle me-1"></i>Rated
                                                </span>
                                            @elseif(in_array($status, ['submitted', 'in_review']))
                                                <span class="text-warning small mt-1 d-block">
                                                    <i class="fas fa-clock me-1"></i>Awaiting rating
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">No supervisors assigned</span>
                                    @endif
                                </td>
                                
                                <td class="date-cell">
                                    @php
                                        try {
                                            $createdDate = \Carbon\Carbon::parse($appraisal->created_at);
                                            echo '<div class="date-day">' . $createdDate->format('M d, Y') . '</div>';
                                            echo '<div class="date-time">' . $createdDate->format('h:i A') . '</div>';
                                        } catch (\Exception $e) {
                                            echo '<span class="text-muted">Invalid date</span>';
                                        }
                                    @endphp
                                </td>
                                
                                <td class="text-end">
                                    <div class="action-group">
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" class="action-icon action-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(($status === 'draft') && $currentUserEmployeeNumber && $appraisalEmployeeNumber && $currentUserEmployeeNumber == $appraisalEmployeeNumber)
                                        <a href="{{ route('appraisals.edit', $appraisal->id) }}" class="action-icon action-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('appraisals.submit', $appraisal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-icon action-submit border-0" title="Submit" onclick="return confirm('Are you sure you want to submit this appraisal?')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if(auth()->user()->user_type === 'supervisor' && $isAssignedSupervisor && in_array($status, ['submitted', 'in_review']) && $hasKpasLoaded && !$currentUserHasRated)
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" class="action-icon action-rate" title="Rate/Review" onclick="return confirm('Go to appraisal details page to rate this submission?')">
                                            <i class="fas fa-star"></i>
                                        </a>
                                        @endif
                                        
                                        @if(($status === 'draft') && $currentUserEmployeeNumber && $appraisalEmployeeNumber && $currentUserEmployeeNumber == $appraisalEmployeeNumber)
                                        <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon action-delete border-0" title="Delete" onclick="return confirm('Are you sure you want to delete this appraisal? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}?print=true" class="action-icon action-print" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($appraisals->hasPages())
                <div class="table-footer">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small">
                            Showing {{ $appraisals->firstItem() }} to {{ $appraisals->lastItem() }} of {{ $appraisals->total() }} results
                            <span class="ms-2">(Page {{ $appraisals->currentPage() }} of {{ $appraisals->lastPage() }})</span>
                        </div>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <div class="d-flex align-items-center text-muted small">
                                <span class="me-2">Show:</span>
                                <select onchange="window.location.href = this.value" class="form-select form-select-sm border-secondary" style="width: auto;">
                                    @php
                                        $currentPerPage = request()->get('per_page', 5);
                                        $perPageOptions = [5, 10, 25, 50];
                                        $currentUrl = request()->fullUrlWithoutQuery(['page', 'per_page']);
                                        $queryParams = array_merge(request()->except(['page', 'per_page']), []);
                                    @endphp
                                    @foreach($perPageOptions as $option)
                                    <option value="{{ $currentUrl . (count($queryParams) ? '?' . http_build_query($queryParams) . '&' : '?') }}per_page={{ $option }}" 
                                            {{ $currentPerPage == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="ms-1">entries</span>
                            </div>
                            
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm pagination-moic mb-0">
                                    <li class="page-item {{ $appraisals->onFirstPage() ? 'disabled' : '' }}">
                                        @if($appraisals->onFirstPage())
                                        <span class="page-link"><i class="fas fa-chevron-left me-1"></i> Prev</span>
                                        @else
                                        <a class="page-link" href="{{ $appraisals->previousPageUrl() . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('quarter') ? '&quarter=' . request('quarter') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') }}">
                                            <i class="fas fa-chevron-left me-1"></i> Prev
                                        </a>
                                        @endif
                                    </li>
                                    
                                    @php
                                        $currentPage = $appraisals->currentPage();
                                        $lastPage = $appraisals->lastPage();
                                        $startPage = max(1, $currentPage - 1);
                                        $endPage = min($lastPage, $currentPage + 1);
                                        
                                        if ($startPage > 1) {
                                            echo '<li class="page-item"><a class="page-link" href="' . $appraisals->url(1) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('quarter') ? '&quarter=' . request('quarter') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '">1</a></li>';
                                            if ($startPage > 2) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        }
                                        
                                        for ($page = $startPage; $page <= $endPage; $page++) {
                                            $activeClass = $currentPage == $page ? 'active' : '';
                                            echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="' . $appraisals->url($page) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('quarter') ? '&quarter=' . request('quarter') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '">' . $page . '</a></li>';
                                        }
                                        
                                        if ($endPage < $lastPage) {
                                            if ($endPage < $lastPage - 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                            echo '<li class="page-item"><a class="page-link" href="' . $appraisals->url($lastPage) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('quarter') ? '&quarter=' . request('quarter') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '">' . $lastPage . '</a></li>';
                                        }
                                    @endphp
                                    
                                    <li class="page-item {{ !$appraisals->hasMorePages() ? 'disabled' : '' }}">
                                        @if(!$appraisals->hasMorePages())
                                        <span class="page-link">Next <i class="fas fa-chevron-right ms-1"></i></span>
                                        @else
                                        <a class="page-link" href="{{ $appraisals->nextPageUrl() . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('quarter') ? '&quarter=' . request('quarter') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') }}">
                                            Next <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
                
                @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <div class="d-inline-flex p-4 bg-blue-50 rounded-circle">
                            <i class="fas fa-file-alt moic-navy fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="h4 fw-bold moic-navy mb-3">No appraisals found</h3>
                    <p class="text-muted mb-4">
                        @php
                            $currentUserEmployeeNumber = auth()->user()->employee_number ?? null;
                            $viewingEmployeeNumber = $employeeNumber ?? $currentUserEmployeeNumber;
                            $isViewingOwnProfile = $currentUserEmployeeNumber && $viewingEmployeeNumber && $currentUserEmployeeNumber == $viewingEmployeeNumber;
                            
                            if (auth()->user()->user_type === 'supervisor' && !request()->has('employee_number')) {
                                echo 'No appraisals found for your assigned employees.';
                            } elseif (auth()->user()->user_type === 'supervisor' && request()->has('employee_number')) {
                                echo 'No performance appraisals found for this employee.';
                            } elseif ($isViewingOwnProfile) {
                                echo 'You haven\'t created any performance appraisals yet. Start by creating your first appraisal to track your performance.';
                            } else {
                                echo 'No performance appraisals found.';
                            }
                        @endphp
                    </p>
                    @if($isViewingOwnProfile && !(auth()->user()->user_type === 'supervisor' && !request()->has('employee_number')))
                    <a href="{{ route('appraisals.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i> Create Your First Appraisal
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Add tooltips for truncated text
            const truncatedElements = document.querySelectorAll('.truncate-tooltip');
            truncatedElements.forEach(element => {
                element.setAttribute('title', element.textContent);
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle image loading errors
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>