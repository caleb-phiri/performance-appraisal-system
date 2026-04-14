<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>HR Advisor Performance Appraisal - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS (Production) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Meta CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
        
        /* Base styles */
        html {
            font-size: 16px;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
        }
        
        /* Custom Color Classes */
        .moic-navy { color: var(--moic-navy) !important; }
        .moic-navy-bg { background-color: var(--moic-navy) !important; }
        .moic-accent { color: var(--moic-accent) !important; }
        .moic-accent-bg { background-color: var(--moic-accent) !important; }
        
        /* MOIC Buttons */
        .btn-moic {
            background: var(--moic-gradient);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-moic:hover {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-accent {
            background: var(--moic-gradient-accent);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--moic-accent-light), #ff8d5c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.3);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 1px solid var(--moic-navy);
            color: var(--moic-navy) !important;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* Animated Gradient Header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
            box-shadow: 0 2px 10px rgba(17, 4, 132, 0.15);
        }
        
        /* Logo Container */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 0.375rem;
            padding: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.125rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        /* Grace Period Alert */
        .grace-period-alert {
            background: linear-gradient(135deg, #fef3c7, #fffbeb);
            border-left: 4px solid #f59e0b;
            border-radius: 0.5rem;
        }
        
        /* Card Styling */
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-moic:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Stat Cards */
        .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            padding: 1rem;
            height: 100%;
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        /* Table Styling */
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle;
        }
        
        .table-moic tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-color: #f3f4f6;
        }
        
        .table-moic tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table-moic tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Rating Select Styling */
        .rating-select {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .rating-select:focus {
            border-color: var(--moic-navy);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(17, 4, 132, 0.25);
        }
        
        .rating-select.error {
            border-color: var(--danger);
            border-width: 2px;
        }
        
        /* Score Colors */
        .score-excellent { color: #059669 !important; font-weight: 600; }
        .score-good { color: #2563eb !important; font-weight: 600; }
        .score-fair { color: #d97706 !important; font-weight: 600; }
        .score-poor { color: #dc2626 !important; font-weight: 600; }
        
        /* Weight cell styling */
        .weight-cell {
            background-color: #f0f9ff;
            font-weight: 600;
        }
        
        .weight-display {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Progress Bars */
        .progress-moic {
            height: 0.5rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
        }
        
        .progress-bar-moic {
            background: var(--moic-gradient);
            border-radius: 0.25rem;
        }
        
        /* Badge Styles */
        .recruitment-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        .probation-badge {
            background-color: #fef3c7;
            color: #92400e;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        .disciplinary-badge {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        .client-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        /* Comment Preview */
        .comment-preview {
            max-height: 3rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            font-size: 0.8125rem;
            color: #4b5563;
            background-color: #f9fafb;
            padding: 0.375rem;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
        }
        
        /* Rating Legend */
        .rating-legend-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .rating-legend-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .rating-badge {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 700;
            margin-right: 0.75rem;
        }
        
        /* Modal Styling */
        .modal-moic .modal-header {
            background: var(--moic-gradient);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .modal-moic .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        /* Message Container */
        .message-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
            max-width: 400px;
        }
        
        .message {
            margin-bottom: 0.5rem;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }
        
        .message-success { background: linear-gradient(135deg, #10b981, #059669); }
        .message-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .message-info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .message-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        
        .message-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.8;
            cursor: pointer;
            padding: 0;
            margin-left: 0.75rem;
        }
        
        .message-close:hover {
            opacity: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        /* Background utilities */
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-indigo-50 { background-color: #eef2ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }
        .bg-amber-50 { background-color: #fffbeb !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-teal-50 { background-color: #f0fdfa !important; }
        .bg-gray-50 { background-color: #f9fafb !important; }
        
        /* Text utilities */
        .text-green-800 { color: #166534 !important; }
        .text-purple-800 { color: #6b21a8 !important; }
        .text-blue-800 { color: #1e40af !important; }
        .text-yellow-800 { color: #92400e !important; }
        .text-red-800 { color: #991b1b !important; }
        .text-teal-800 { color: #115e59 !important; }
        
        /* Responsive container */
        .container-custom {
            max-width: 90rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .stat-card {
                padding: 0.75rem;
            }
            
            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            .stat-number {
                font-size: 1.25rem;
            }
            
            .desktop-only {
                display: none !important;
            }
            
            .mobile-only {
                display: block !important;
            }
            
            .table-moic {
                font-size: 0.8125rem;
            }
            
            .table-moic td, .table-moic th {
                padding: 0.5rem;
            }
            
            .rating-legend-item {
                padding: 0.375rem;
            }
            
            .rating-badge {
                width: 1.5rem;
                height: 1.5rem;
                font-size: 0.75rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
            
            .desktop-only {
                display: block !important;
            }
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            
            .gradient-header {
                animation: none;
            }
            
            .stat-card:hover,
            .card-moic:hover,
            .rating-legend-item:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    @php
        /**
         * ENHANCED QUARTER FUNCTION WITH GRACE PERIOD
         * A quarter remains OPEN until the 20th of the month following its end.
         */
        function getQuarterInfoWithGrace($year = null, $quarter = null) {
            $now = now();
            $currentYear = $year ?? $now->year;
            $today = $now->copy()->startOfDay();
            
            $quarters = [
                'Q1' => [
                    'name' => 'Quarter 1',
                    'months' => 'January - March',
                    'period_start' => $currentYear . '-01-01',
                    'period_end' => $currentYear . '-03-31',
                    'grace_end' => $currentYear . '-04-20',
                    'due_date_formatted' => 'April 20',
                ],
                'Q2' => [
                    'name' => 'Quarter 2',
                    'months' => 'April - June',
                    'period_start' => $currentYear . '-04-01',
                    'period_end' => $currentYear . '-06-30',
                    'grace_end' => $currentYear . '-07-20',
                    'due_date_formatted' => 'July 20',
                ],
                'Q3' => [
                    'name' => 'Quarter 3',
                    'months' => 'July - September',
                    'period_start' => $currentYear . '-07-01',
                    'period_end' => $currentYear . '-09-30',
                    'grace_end' => $currentYear . '-10-20',
                    'due_date_formatted' => 'October 20',
                ],
                'Q4' => [
                    'name' => 'Quarter 4',
                    'months' => 'October - December',
                    'period_start' => $currentYear . '-10-01',
                    'period_end' => $currentYear . '-12-31',
                    'grace_end' => ($currentYear + 1) . '-01-20',
                    'due_date_formatted' => 'January 20',
                ],
            ];
            
            // If a specific quarter is requested
            if ($quarter && isset($quarters[$quarter])) {
                $q = $quarters[$quarter];
                $graceEndDate = \Carbon\Carbon::parse($q['grace_end']);
                $periodEndDate = \Carbon\Carbon::parse($q['period_end']);
                $isPast = $today->gt($graceEndDate);
                $isCurrent = $today->lte($graceEndDate);
                $isInGrace = $today->gt($periodEndDate) && $today->lte($graceEndDate);
                
                return (object) [
                    'quarter' => $quarter,
                    'quarter_name' => $q['name'],
                    'quarter_months' => $q['months'],
                    'due_date' => $graceEndDate->format('M d'),
                    'due_date_formatted' => $q['due_date_formatted'],
                    'due_date_timestamp' => $graceEndDate->timestamp,
                    'period_end' => $periodEndDate->format('Y-m-d'),
                    'grace_end' => $q['grace_end'],
                    'is_past' => $isPast,
                    'is_current' => $isCurrent,
                    'is_future' => !$isCurrent && !$isPast,
                    'is_in_grace' => $isInGrace,
                    'year' => $currentYear,
                ];
            }
            
            // Determine current quarter based on today's date (grace period aware)
            $currentQuarter = null;
            foreach ($quarters as $qKey => $qData) {
                $graceEnd = \Carbon\Carbon::parse($qData['grace_end']);
                if ($today->lte($graceEnd)) {
                    $currentQuarter = $qKey;
                    break;
                }
            }
            
            // If all quarters are past, default to Q4
            if (!$currentQuarter) {
                $currentQuarter = 'Q4';
            }
            
            return getQuarterInfoWithGrace($currentYear, $currentQuarter);
        }
        
        // Get current quarter info with grace period
        $quarterInfo = getQuarterInfoWithGrace();
        $currentQuarter = $quarterInfo->quarter;
        $currentYear = $quarterInfo->year;
        
        // Determine quarter dates for hidden inputs
        $quarterDates = [
            'Q1' => ['start' => date('Y-01-01'), 'end' => date('Y-03-31')],
            'Q2' => ['start' => date('Y-04-01'), 'end' => date('Y-06-30')],
            'Q3' => ['start' => date('Y-07-01'), 'end' => date('Y-09-30')],
            'Q4' => ['start' => date('Y-10-01'), 'end' => date('Y-12-31')]
        ];
        
        $startDate = $quarterDates[$currentQuarter]['start'];
        $endDate = $quarterDates[$currentQuarter]['end'];
        
        // Check if user has already submitted for this quarter (replace with actual check)
        $hasAnySubmissionThisQuarter = false;
    @endphp

    <!-- Message Container -->
    <div id="messageContainer" class="message-container"></div>

    <!-- Header with Animated Gradient -->
    <div class="gradient-header text-white">
        <div class="container-custom px-3 py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Logo Section -->
                <div class="d-flex align-items-center">
                    <div class="logo-container me-3">
                        <div class="logo-inner">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                    </div>
                                    <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                </div>
                                
                                <div class="position-relative">
                                    <div class="rounded-circle" style="width: 2rem; height: 2rem; background: linear-gradient(135deg, #110484, #e7581c); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-handshake text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="position-absolute top-100 start-50 translate-middle mt-1">
                                        <span class="status-badge bg-white moic-navy">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo">
                                    </div>
                                    <span class="status-badge moic-accent-bg text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center desktop-only">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem;"></div>
                        <div>
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">DBK HR Advisor Performance Appraisal</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">{{ $currentQuarter }} {{ $quarterInfo->year }}</p>
                        </div>
                    </div>
                    
                    <div class="mobile-only ms-2">
                        <h1 class="h6 mb-0 fw-bold">HR Advisor</h1>
                        <p class="mb-0 text-white-50 small">{{ $currentQuarter }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="desktop-only text-end me-2">
                        <div class="fw-medium">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="small text-white-50">HR Advisor</div>
                    </div>
                    <div class="bg-white text-[#110484] rounded-circle d-flex align-items-center justify-content-center" style="width: 2.5rem; height: 2.5rem;">
                        <span class="fw-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container-custom px-3 py-2">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('appraisals.index') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-list me-2"></i>My Appraisals
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </div>
                
                <div class="text-muted small">
                    <i class="fas fa-calendar-alt me-1 moic-accent"></i>
                    Deadline: {{ $quarterInfo->due_date_formatted }}, {{ $quarterInfo->year }}
                </div>
            </div>
        </div>
    </div>

    <!-- Grace Period Alert -->
    @if($quarterInfo->is_in_grace && !$hasAnySubmissionThisQuarter)
    <div class="container-custom px-3 mt-4">
        <div class="grace-period-alert p-3 mb-3 d-flex align-items-center">
            <i class="fas fa-hourglass-half fa-2x text-warning me-3"></i>
            <div>
                <h6 class="fw-bold mb-1 text-warning">Grace Period Active!</h6>
                <p class="mb-0 small">
                    You can still submit your appraisal for <strong>{{ $quarterInfo->quarter_name }} ({{ $quarterInfo->quarter_months }})</strong> 
                    until <strong>{{ $quarterInfo->due_date_formatted }}, {{ $quarterInfo->year }}</strong>. 
                    Don't miss this extended deadline!
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Display -->
    @if($errors->any())
    <div class="container-custom px-3 mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-danger me-3" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h5 class="alert-heading">There were errors with your submission</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <main class="py-4">
        <div class="container-custom px-3">
            <!-- Quarter Info Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-blue-50 me-3">
                                <i class="fas fa-calendar-alt moic-navy"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Appraisal Quarter</p>
                                <p class="fw-semibold mb-0">{{ $currentQuarter }} {{ $quarterInfo->year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-green-50 me-3">
                                <i class="fas fa-clock text-success"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Period</p>
                                <p class="fw-semibold mb-0">{{ $quarterInfo->quarter_months }} {{ $quarterInfo->year }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-amber-50 me-3">
                                <i class="fas fa-hourglass-end moic-accent"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Deadline</p>
                                <p class="fw-semibold mb-0">{{ $quarterInfo->due_date_formatted }}{{ $quarterInfo->is_in_grace ? ' (Grace Period)' : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-purple-50 me-3">
                                <i class="fas fa-user-tie text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Reporting To</p>
                                <p class="fw-semibold mb-0">TKC/MOIC HR Director</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <form id="hrAdvisorForm" action="{{ route('appraisals.store') }}" method="POST" class="card card-moic">
                @csrf
                <input type="hidden" name="status" id="hrAdvisorFormStatus" value="draft">
                <input type="hidden" name="period" value="{{ $currentQuarter }}">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="job_title" value="HR Advisor">

                <!-- Form Header -->
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h2 class="h5 fw-bold moic-navy mb-0">
                            <i class="fas fa-file-alt me-2 moic-accent"></i>Performance Appraisal Form
                        </h2>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary text-white" style="padding: 0.5rem 1rem;">
                                <i class="fas fa-user-cog me-1"></i> HR Advisory
                            </span>
                            <span class="badge bg-info text-white" style="padding: 0.5rem 1rem;">
                                <i class="fas fa-briefcase me-1"></i> Recruitment
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="bg-blue-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-weight-hanging moic-navy me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Total Weight</p>
                                        <p id="hrAdvisorTotalWeightDisplay" class="h4 fw-bold moic-navy mb-0">100%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-green-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-pie text-success me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">KPAs</p>
                                        <p class="h4 fw-bold text-success mb-0">10</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-amber-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star moic-accent me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Max Score</p>
                                        <p class="h4 fw-bold moic-accent mb-0">100%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-purple-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-purple-600 me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Due Date</p>
                                        <p class="h6 fw-bold text-purple-600 mb-0">{{ $quarterInfo->due_date_formatted }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Legend -->
                    <div class="bg-gray-50 p-4 rounded border mb-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle moic-navy me-2"></i>
                            Rating Scale Guide
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-red-100 text-red-800">1</div>
                                    <div>
                                        <p class="fw-semibold mb-0">ND</p>
                                        <p class="small text-muted mb-0">Not Demonstrated</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-yellow-100 text-yellow-800">2</div>
                                    <div>
                                        <p class="fw-semibold mb-0">NS</p>
                                        <p class="small text-muted mb-0">Not Satisfactory</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-blue-100 text-blue-800">3</div>
                                    <div>
                                        <p class="fw-semibold mb-0">S</p>
                                        <p class="small text-muted mb-0">Satisfactory</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-green-100 text-green-800">4</div>
                                    <div>
                                        <p class="fw-semibold mb-0">EX</p>
                                        <p class="small text-muted mb-0">Exemplary</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPA Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-moic mb-0">
                            <thead>
                                <tr>
                                    <th>Post Profile</th>
                                    <th>Result Indicators</th>
                                    <th>KPI Max</th>
                                    <th>Weight %</th>
                                    <th>Rating (Self)</th>
                                    <th>Score %</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Recruitment & Selections - External Recruitment -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-blue-100 text-[#110484] p-2 rounded me-2">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                            <div>
                                                Recruitment & Selections
                                                <small class="text-muted d-block">External Recruitment</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[0][category]" value="Recruitment & Selections">
                                        <input type="hidden" name="kpas[0][kpa]" value="External Recruitment">
                                        <input type="hidden" name="kpas[0][result_indicators]" value="Complete resume screening and provide a shortlist within 5 business days of receiving the Labor Requisition Form, ensuring candidates meet the qualification requirements for the position">
                                    </td>
                                    <td>
                                        <div class="small">Complete resume screening and provide a shortlist within 5 business days of receiving the Labor Requisition Form, ensuring candidates meet the qualification requirements for the position 
                                            <span class="recruitment-badge">RECRUITMENT</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-100 text-blue-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[0][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[0][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[0][self_rating]" required class="rating-select" id="rating0">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell0" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[0][calculated_score]" id="calculatedScore0" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview0" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(0)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[0][comments]" id="commentInput0" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Recruitment & Selections - Internal Recruitment -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-blue-100 text-[#110484] p-2 rounded me-2">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div>
                                                Recruitment & Selections
                                                <small class="text-muted d-block">Internal Recruitment</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[1][category]" value="Recruitment & Selections">
                                        <input type="hidden" name="kpas[1][kpa]" value="Internal Recruitment">
                                        <input type="hidden" name="kpas[1][result_indicators]" value="Complete shortlist within 6 business days of publishing internal advert notifications, accompanied by the employee's misconduct record and most recent performance appraisal result">
                                    </td>
                                    <td>
                                        <div class="small">Complete shortlist within 6 business days of publishing internal advert notifications, accompanied by the employee's misconduct record and most recent performance appraisal result 
                                            <span class="recruitment-badge">INTERNAL</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-100 text-blue-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[1][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[1][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[1][self_rating]" required class="rating-select" id="rating1">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell1" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[1][calculated_score]" id="calculatedScore1" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview1" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(1)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[1][comments]" id="commentInput1" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Induction & Employee File - Employee Induction -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-green-100 text-green-600 p-2 rounded me-2">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div>
                                                Induction & Employee File
                                                <small class="text-muted d-block">Employee Induction</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[2][category]" value="Induction & Employee File">
                                        <input type="hidden" name="kpas[2][kpa]" value="Employee Induction">
                                        <input type="hidden" name="kpas[2][result_indicators]" value="Complete all new employee induction and training within the established timeline, ensuring high-quality induction training covering HSE, probation inception meetings, code of conduct awareness, etc">
                                    </td>
                                    <td>
                                        <div class="small">Complete all new employee induction and training within the established timeline, ensuring high-quality induction training covering HSE, probation inception meetings, code of conduct awareness, etc</div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-green-100 text-green-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[2][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[2][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[2][self_rating]" required class="rating-select" id="rating2">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell2" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[2][calculated_score]" id="calculatedScore2" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview2" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(2)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[2][comments]" id="commentInput2" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Induction & Employee File - Employee File -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-green-100 text-green-600 p-2 rounded me-2">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div>
                                                Induction & Employee File
                                                <small class="text-muted d-block">Employee File</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[3][category]" value="Induction & Employee File">
                                        <input type="hidden" name="kpas[3][kpa]" value="Employee File">
                                        <input type="hidden" name="kpas[3][result_indicators]" value="Ensure that employee files are completed, employee information is updated in Dingtalk and change note is approved within one week of employee onboarding">
                                    </td>
                                    <td>
                                        <div class="small">Ensure that employee files are completed, employee information is updated in Dingtalk and change note is approved within one week of employee onboarding</div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-green-100 text-green-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[3][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[3][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[3][self_rating]" required class="rating-select" id="rating3">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell3" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[3][calculated_score]" id="calculatedScore3" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview3" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(3)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[3][comments]" id="commentInput3" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Probation Review & Performance Management - Probation Review -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-yellow-100 text-yellow-600 p-2 rounded me-2">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <div>
                                                Probation Review & Performance Management
                                                <small class="text-muted d-block">Probation Review</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[4][category]" value="Probation Review & Performance Management">
                                        <input type="hidden" name="kpas[4][kpa]" value="Probation Review">
                                        <input type="hidden" name="kpas[4][result_indicators]" value="Update the probation performance record within one week after employee onboarding or promotion, complete Review 1 (Day 15), Review 2 (Day 45), and Final Review (Day 75) on time, and communicate the probation review results with the employee within 5 days after each review">
                                    </td>
                                    <td>
                                        <div class="small">Update the probation performance record within one week after employee onboarding or promotion, complete Review 1 (Day 15), Review 2 (Day 45), and Final Review (Day 75) on time, and communicate the probation review results with the employee within 5 days after each review 
                                            <span class="probation-badge">PROBATION</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-yellow-100 text-yellow-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[4][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[4][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[4][self_rating]" required class="rating-select" id="rating4">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell4" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[4][calculated_score]" id="calculatedScore4" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview4" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(4)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[4][comments]" id="commentInput4" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Probation Review & Performance Management - Performance Management -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-yellow-100 text-yellow-600 p-2 rounded me-2">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                Probation Review & Performance Management
                                                <small class="text-muted d-block">Performance Management</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[5][category]" value="Probation Review & Performance Management">
                                        <input type="hidden" name="kpas[5][kpa]" value="Performance Management">
                                        <input type="hidden" name="kpas[5][result_indicators]" value="Complete quarterly attendance and code of conduct performance scoring for all employees within the required timeline, support the implementation of PIP and misconduct management procedures">
                                    </td>
                                    <td>
                                        <div class="small">Complete quarterly attendance and code of conduct performance scoring for all employees within the required timeline, support the implementation of PIP and misconduct management procedures</div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-yellow-100 text-yellow-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[5][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[5][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[5][self_rating]" required class="rating-select" id="rating5">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell5" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[5][calculated_score]" id="calculatedScore5" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview5" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(5)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[5][comments]" id="commentInput5" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Disciplinary & Grievance Management -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-red-100 text-red-600 p-2 rounded me-2">
                                                <i class="fas fa-gavel"></i>
                                            </div>
                                            <div>
                                                Disciplinary & Grievance Management
                                                <small class="text-muted d-block">Disciplinary Management</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[6][category]" value="Disciplinary & Grievance Management">
                                        <input type="hidden" name="kpas[6][kpa]" value="Disciplinary Management">
                                        <input type="hidden" name="kpas[6][result_indicators]" value="Adhere strictly to the code of conduct in all misconduct cases, and complete hearing within 5 business days of receiving the report, ensuring case closure with no instances of cases being overturned due to procedural errors">
                                    </td>
                                    <td>
                                        <div class="small">Adhere strictly to the code of conduct in all misconduct cases, and complete hearing within 5 business days of receiving the report, ensuring case closure with no instances of cases being overturned due to procedural errors 
                                            <span class="disciplinary-badge">DISCIPLINARY</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-red-100 text-red-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[6][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[6][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[6][self_rating]" required class="rating-select" id="rating6">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell6" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[6][calculated_score]" id="calculatedScore6" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview6" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(6)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[6][comments]" id="commentInput6" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Leave Management -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-purple-100 text-purple-600 p-2 rounded me-2">
                                                <i class="fas fa-plane"></i>
                                            </div>
                                            <div>
                                                Leave Management
                                                <small class="text-muted d-block">Leave Management</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[7][category]" value="Leave Management">
                                        <input type="hidden" name="kpas[7][kpa]" value="Leave Management">
                                        <input type="hidden" name="kpas[7][result_indicators]" value="Maintain complete and accurate leave records for all employees, ensure that no unapproved or overdue leave situations occur, and ensure that all sick leave is accompanied by the appropriate sick notes">
                                    </td>
                                    <td>
                                        <div class="small">Maintain complete and accurate leave records for all employees, ensure that no unapproved or overdue leave situations occur, and ensure that all sick leave is accompanied by the appropriate sick notes</div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-purple-100 text-purple-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[7][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[7][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[7][self_rating]" required class="rating-select" id="rating7">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell7" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[7][calculated_score]" id="calculatedScore7" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview7" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(7)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[7][comments]" id="commentInput7" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Payroll Management -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-indigo-100 text-indigo-600 p-2 rounded me-2">
                                                <i class="fas fa-money-check-alt"></i>
                                            </div>
                                            <div>
                                                Payroll Management
                                                <small class="text-muted d-block">Payroll Management</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[8][category]" value="Payroll Management">
                                        <input type="hidden" name="kpas[8][kpa]" value="Payroll Management">
                                        <input type="hidden" name="kpas[8][result_indicators]" value="Submit the employee payroll summary to the client for review by the 20th of each month, ensuring that all calculations are accurate, and complete the payroll confirmation process on time">
                                    </td>
                                    <td>
                                        <div class="small">Submit the employee payroll summary to the client for review by the 20th of each month, ensuring that all calculations are accurate, and complete the payroll confirmation process on time</div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-indigo-100 text-indigo-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[8][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[8][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[8][self_rating]" required class="rating-select" id="rating8">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell8" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[8][calculated_score]" id="calculatedScore8" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview8" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(8)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[8][comments]" id="commentInput8" value="">
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Client Relations -->
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-teal-100 text-teal-600 p-2 rounded me-2">
                                                <i class="fas fa-handshake"></i>
                                            </div>
                                            <div>
                                                Client Relations
                                                <small class="text-muted d-block">Client Relations</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[9][category]" value="Client Relations">
                                        <input type="hidden" name="kpas[9][kpa]" value="Client Relations">
                                        <input type="hidden" name="kpas[9][result_indicators]" value="Respond to client requests within 24 hours and resolve client issues promptly, and share the HR monthly report to client within 5 days after the end of each month">
                                    </td>
                                    <td>
                                        <div class="small">Respond to client requests within 24 hours and resolve client issues promptly, and share the HR monthly report to client within 5 days after the end of each month 
                                            <span class="client-badge">CLIENT</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                            <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                            <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-teal-100 text-teal-800 rounded-pill px-3 py-2">4</span>
                                        <input type="hidden" name="kpas[9][kpi]" value="4">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">10%</span>
                                        <input type="hidden" name="kpas[9][weight]" value="10">
                                    </td>
                                    <td>
                                        <select name="kpas[9][self_rating]" required class="rating-select" id="rating9">
                                            <option value="">Select Rating</option>
                                            <option value="1">1 - ND (Not Demonstrated)</option>
                                            <option value="2">2 - NS (Not Satisfactory)</option>
                                            <option value="3">3 - S (Satisfactory)</option>
                                            <option value="4">4 - EX (Exemplary)</option>
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell9" class="fw-bold">0.00%</span>
                                        <input type="hidden" name="kpas[9][calculated_score]" id="calculatedScore9" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview9" class="comment-preview">No comment added</div>
                                            <button type="button" onclick="openCommentModal(9)" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Add Comment
                                            </button>
                                            <input type="hidden" name="kpas[9][comments]" id="commentInput9" value="">
                                        </div>
                                    </td>
                                </tr>

                                <!-- TOTAL ROW -->
                                <tr class="bg-gray-800 text-white fw-bold">
                                    <td colspan="3" class="text-center">TOTAL</td>
                                    <td class="text-center">100%</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center" id="totalScoreCell">0.00%</td>
                                    <td class="text-center"><i class="fas fa-chart-line"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Current Score Summary -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Total Score</span>
                                <div class="h2 fw-bold moic-navy" id="displayTotalScore">0.00</div>
                                <span class="small text-muted">Out of 100</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Overall Percentage</span>
                                <div class="h2 fw-bold moic-navy" id="displayTotalPercentage">0.00%</div>
                                <span class="small text-muted">Based on 100% scale</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Performance Rating</span>
                                <div class="h2 fw-bold" id="performanceRating">-</div>
                                <span class="small text-muted">Based on score</span>
                            </div>
                        </div>
                    </div>

                    <!-- Development Needs & Comments -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-blue-50 p-4 rounded border">
                                <label class="fw-bold mb-2">
                                    <i class="fas fa-chart-line moic-navy me-2"></i>Development Needs
                                </label>
                                <textarea name="development_needs" rows="4" class="form-control" placeholder="List your development needs for the next period..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-green-50 p-4 rounded border">
                                <label class="fw-bold mb-2">
                                    <i class="fas fa-comment text-success me-2"></i>Additional Comments
                                </label>
                                <textarea name="employee_comments" rows="4" class="form-control" placeholder="Any additional comments or feedback..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top">
                        <div class="d-flex align-items-center">
                            <div class="bg-blue-100 text-[#110484] p-2 rounded me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <p class="fw-medium mb-0">Submission Guidelines</p>
                                <p class="small text-muted mb-0">All ratings must be selected and total weight is fixed at 100%</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="saveAsDraft(event)" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="button" onclick="submitForm(event)" class="btn btn-moic">
                                <i class="fas fa-paper-plane me-2"></i>Submit Appraisal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Comment Modal -->
    <div class="modal fade modal-moic" id="commentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalTitle">Add Comment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="commentModalSubtitle"></p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Your Comment</label>
                        <textarea id="modalCommentTextarea" rows="5" class="form-control" placeholder="Enter your comment here..."></textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="small text-muted"><span id="charCount">0</span>/500 characters</span>
                            <button type="button" onclick="clearComment()" class="btn btn-link btn-sm text-muted p-0">
                                <i class="fas fa-trash-alt me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-moic" onclick="saveComment()">Save Comment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-top mt-4 pt-4">
        <div class="container-custom px-3">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-2 rounded me-3">
                            <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                        </div>
                        <div>
                            <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                            <p class="text-muted small">Current Quarter: {{ $currentQuarter }} {{ $quarterInfo->year }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-4">
                        <span class="text-muted small">
                            <i class="fas fa-calendar-check me-1 text-success"></i> Deadline: {{ $quarterInfo->due_date_formatted }}
                        </span>
                        <span class="text-muted small">
                            <i class="fas fa-calendar-alt me-1 moic-navy"></i> Period: {{ $quarterInfo->quarter_months }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==============================================
        // GLOBAL VARIABLES
        // ==============================================
        
        let currentCommentIndex = -1;
        let commentModal = null;
        
        const kpaTitles = [
            "Recruitment & Selections - External Recruitment",
            "Recruitment & Selections - Internal Recruitment",
            "Induction & Employee File - Employee Induction",
            "Induction & Employee File - Employee File",
            "Probation Review & Performance Management - Probation Review",
            "Probation Review & Performance Management - Performance Management",
            "Disciplinary & Grievance Management - Disciplinary Management",
            "Leave Management - Leave Management",
            "Payroll Management - Payroll Management",
            "Client Relations - Client Relations"
        ];
        
        const weights = [10, 10, 10, 10, 10, 10, 10, 10, 10, 10]; // Fixed weights 10% each
        
        // ==============================================
        // SCORE CALCULATION FUNCTIONS
        // ==============================================
        
        function calculateScores() {
            let totalWeightedScore = 0;
            
            for (let i = 0; i < 10; i++) {
                const ratingSelect = document.getElementById(`rating${i}`);
                const rating = parseFloat(ratingSelect?.value) || 0;
                const maxKPI = 4; // All KPAs have max 4 points
                
                // Calculate weighted score: (rating / maxKPI) * weight
                let weightedScore = 0;
                if (rating > 0) {
                    weightedScore = (rating / maxKPI) * weights[i];
                }
                
                // Update individual score cell
                const scoreCell = document.getElementById(`scoreCell${i}`);
                const calculatedScoreInput = document.getElementById(`calculatedScore${i}`);
                
                if (scoreCell) scoreCell.textContent = weightedScore.toFixed(2) + '%';
                if (calculatedScoreInput) calculatedScoreInput.value = weightedScore.toFixed(2);
                
                // Color code individual scores
                if (scoreCell) {
                    scoreCell.classList.remove('score-excellent', 'score-good', 'score-fair', 'score-poor');
                    if (weightedScore >= (weights[i] * 0.9)) {
                        scoreCell.classList.add('score-excellent');
                    } else if (weightedScore >= (weights[i] * 0.7)) {
                        scoreCell.classList.add('score-good');
                    } else if (weightedScore >= (weights[i] * 0.5)) {
                        scoreCell.classList.add('score-fair');
                    } else if (weightedScore > 0) {
                        scoreCell.classList.add('score-poor');
                    }
                }
                
                totalWeightedScore += weightedScore;
            }
            
            // Update total score in table
            const totalScoreCell = document.getElementById('totalScoreCell');
            if (totalScoreCell) totalScoreCell.textContent = totalWeightedScore.toFixed(2) + '%';
            
            // Update display
            const displayTotalScore = document.getElementById('displayTotalScore');
            const displayTotalPercentage = document.getElementById('displayTotalPercentage');
            const performanceRating = document.getElementById('performanceRating');
            
            if (displayTotalScore) displayTotalScore.textContent = totalWeightedScore.toFixed(2);
            if (displayTotalPercentage) displayTotalPercentage.textContent = totalWeightedScore.toFixed(2) + '%';
            
            // Determine performance rating
            let rating = '-';
            let ratingColor = 'moic-navy';
            
            if (totalWeightedScore >= 90) {
                rating = 'Exemplary';
                ratingColor = 'score-excellent';
            } else if (totalWeightedScore >= 70) {
                rating = 'Satisfactory';
                ratingColor = 'score-good';
            } else if (totalWeightedScore >= 50) {
                rating = 'Needs Improvement';
                ratingColor = 'score-fair';
            } else if (totalWeightedScore > 0) {
                rating = 'Unsatisfactory';
                ratingColor = 'score-poor';
            }
            
            if (performanceRating) {
                performanceRating.textContent = rating;
                performanceRating.className = `h2 fw-bold ${ratingColor}`;
            }
            
            // Color code total score cell
            if (totalScoreCell) {
                totalScoreCell.classList.remove('score-excellent', 'score-good', 'score-fair', 'score-poor');
                if (totalWeightedScore >= 90) {
                    totalScoreCell.classList.add('score-excellent');
                } else if (totalWeightedScore >= 70) {
                    totalScoreCell.classList.add('score-good');
                } else if (totalWeightedScore >= 50) {
                    totalScoreCell.classList.add('score-fair');
                } else if (totalWeightedScore > 0) {
                    totalScoreCell.classList.add('score-poor');
                }
            }
            
            return totalWeightedScore;
        }

        // ==============================================
        // COMMENT MODAL FUNCTIONS
        // ==============================================

        function openCommentModal(index) {
            currentCommentIndex = index;
            
            if (!commentModal) {
                commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            }
            
            const textarea = document.getElementById('modalCommentTextarea');
            const charCount = document.getElementById('charCount');
            
            // Set modal title and subtitle
            document.getElementById('commentModalTitle').textContent = `Add Comment for ${kpaTitles[index]}`;
            document.getElementById('commentModalSubtitle').textContent = "Provide feedback or explanation for your rating";
            
            // Load existing comment
            const commentInput = document.getElementById(`commentInput${index}`);
            textarea.value = commentInput?.value || '';
            charCount.textContent = textarea.value.length;
            
            commentModal.show();
        }

        function saveComment() {
            if (currentCommentIndex === -1) return;
            
            const textarea = document.getElementById('modalCommentTextarea');
            const comment = textarea.value.trim();
            const commentInput = document.getElementById(`commentInput${currentCommentIndex}`);
            const commentPreview = document.getElementById(`commentPreview${currentCommentIndex}`);
            
            // Save to hidden input
            if (commentInput) commentInput.value = comment;
            
            // Update preview
            if (commentPreview) {
                if (comment) {
                    commentPreview.textContent = comment;
                    commentPreview.className = 'comment-preview';
                } else {
                    commentPreview.textContent = 'No comment added';
                    commentPreview.className = 'comment-preview text-muted';
                }
            }
            
            commentModal.hide();
        }

        function clearComment() {
            const textarea = document.getElementById('modalCommentTextarea');
            textarea.value = '';
            document.getElementById('charCount').textContent = '0';
            textarea.focus();
        }

        // ==============================================
        // FORM SUBMISSION FUNCTIONS
        // ==============================================

        function validateForm() {
            let allRatingsSelected = true;
            let firstInvalid = null;
            
            for (let i = 0; i < 10; i++) {
                const ratingSelect = document.getElementById(`rating${i}`);
                if (!ratingSelect || !ratingSelect.value) {
                    allRatingsSelected = false;
                    if (ratingSelect) {
                        ratingSelect.classList.add('error');
                        if (!firstInvalid) firstInvalid = ratingSelect;
                    }
                } else if (ratingSelect) {
                    ratingSelect.classList.remove('error');
                }
            }
            
            if (!allRatingsSelected && firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showMessage('Please select a rating for all KPAs.', 'error');
                return false;
            }
            
            return true;
        }

        function saveAsDraft(event) {
            event.preventDefault();
            
            document.getElementById('hrAdvisorFormStatus').value = 'draft';
            
            // Show loading state
            const button = event.currentTarget;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            button.disabled = true;
            
            // Calculate final score
            calculateScores();
            
            showMessage('Appraisal saved as draft successfully!', 'success');
            
            setTimeout(() => {
                document.getElementById('hrAdvisorForm').submit();
            }, 1500);
        }

        function submitForm(event) {
            event.preventDefault();
            
            if (!validateForm()) return;
            
            // Calculate final score
            const totalScore = calculateScores();
            const performanceRating = document.getElementById('performanceRating')?.textContent || '-';
            
            // Confirm submission
            const confirmationMessage = `Your calculated score: ${totalScore.toFixed(2)}/100\nPerformance Rating: ${performanceRating}\n\nAre you sure you want to submit this appraisal? Once submitted, it cannot be edited.`;
            
            if (confirm(confirmationMessage)) {
                document.getElementById('hrAdvisorFormStatus').value = 'submitted';
                
                // Show loading state
                const button = event.currentTarget;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                button.disabled = true;
                
                showMessage('Appraisal submitted successfully!', 'success');
                
                setTimeout(() => {
                    document.getElementById('hrAdvisorForm').submit();
                }, 1500);
            }
        }

        // ==============================================
        // UTILITY FUNCTIONS
        // ==============================================

        function showMessage(message, type = 'info') {
            const messageContainer = document.getElementById('messageContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message message-${type}`;
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle'
            };
            
            messageDiv.innerHTML = `
                <i class="message-icon fas ${icons[type]} me-2"></i>
                <div class="message-content flex-grow-1">${message}</div>
                <button class="message-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            messageContainer.appendChild(messageDiv);
            
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.style.animation = 'fadeOut 0.3s ease forwards';
                    setTimeout(() => {
                        if (messageDiv.parentElement) {
                            messageDiv.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // ==============================================
        // KEYBOARD SHORTCUTS
        // ==============================================

        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save as draft
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                saveAsDraft(e);
            }
            
            // Ctrl + Enter to submit
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                submitForm(e);
            }
        });

        // ==============================================
        // INITIALIZATION
        // ==============================================

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize comment modal
            commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            
            // Initialize comment previews
            for (let i = 0; i < 10; i++) {
                const commentInput = document.getElementById(`commentInput${i}`);
                const commentPreview = document.getElementById(`commentPreview${i}`);
                
                if (commentPreview) {
                    if (commentInput && commentInput.value) {
                        commentPreview.textContent = commentInput.value;
                        commentPreview.className = 'comment-preview';
                    } else {
                        commentPreview.textContent = 'No comment added';
                        commentPreview.className = 'comment-preview text-muted';
                    }
                }
            }
            
            // Add event listeners to all rating selects
            for (let i = 0; i < 10; i++) {
                const ratingSelect = document.getElementById(`rating${i}`);
                if (ratingSelect) {
                    ratingSelect.addEventListener('change', function() {
                        this.classList.remove('error');
                        calculateScores();
                    });
                }
            }
            
            // Character count for comment modal
            const commentTextarea = document.getElementById('modalCommentTextarea');
            if (commentTextarea) {
                commentTextarea.addEventListener('input', function() {
                    const charCount = document.getElementById('charCount');
                    charCount.textContent = this.value.length;
                    
                    if (this.value.length > 500) {
                        this.value = this.value.substring(0, 500);
                        charCount.textContent = '500';
                        charCount.classList.add('text-danger');
                    } else {
                        charCount.classList.remove('text-danger');
                    }
                });
            }
            
            // Calculate initial values
            calculateScores();
            
            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>