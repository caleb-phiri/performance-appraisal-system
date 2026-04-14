<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Forms - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
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
        }
        
     body {
    background: #f0f3f8;
    background-image: 
        radial-gradient(circle at 0% 0%, rgba(17, 4, 132, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 100% 100%, rgba(231, 88, 28, 0.03) 0%, transparent 50%),
        repeating-linear-gradient(45deg, rgba(0,0,0,0.01) 0px, rgba(0,0,0,0.01) 1px, transparent 1px, transparent 10px);
    min-height: 100vh;
}

/* Add depth to cards */
.card-moic {
    border: none;
    box-shadow: 
        0 5px 10px rgba(0, 0, 0, 0.05),
        0 15px 25px rgba(0, 0, 0, 0.02),
        inset 0 1px 1px rgba(255, 255, 255, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-moic:hover {
    box-shadow: 
        0 10px 20px rgba(17, 4, 132, 0.08),
        0 20px 30px rgba(0, 0, 0, 0.05),
        inset 0 1px 1px rgba(255, 255, 255, 0.8);
    transform: translateY(-2px);
}

/* Optional: Add subtle pattern to cards for depth */
.card-moic {
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(2px);
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
        
        /* Form Card Styling */
        .form-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .form-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(17, 4, 132, 0.1);
        }
        
        .form-card-selected {
            border: 2px solid var(--moic-navy);
            box-shadow: 0 0 0 3px rgba(17, 4, 132, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .form-card-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .form-card-disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Progress Bar */
        .progress-bar-custom {
            height: 6px;
            border-radius: 3px;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        /* Quarter Status */
        .quarter-completed {
            background-color: #d1fae5;
            border-color: #34d399;
            color: #065f46;
        }
        
        .quarter-missed {
            background-color: #fee2e2;
            border-color: #f87171;
            color: #991b1b;
        }
        
        .quarter-current {
            background-color: #dbeafe;
            border-color: #60a5fa;
            color: #1e40af;
        }
        
        .quarter-future {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #4b5563;
        }
        
        /* Responsive container */
        .container-custom {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Form Icon Colors */
        .icon-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
        .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }
        .icon-green { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .icon-orange { background: linear-gradient(135deg, #f97316, #ea580c); color: white; }
        .icon-teal { background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; }
        .icon-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; }
        .icon-indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; }
        .icon-pink { background: linear-gradient(135deg, #ec4899, #db2777); color: white; }
        
        /* Badge Colors */
        .badge-blue { background-color: #dbeafe !important; color: #1e40af !important; }
        .badge-purple { background-color: #e9d5ff !important; color: #6b21a8 !important; }
        .badge-green { background-color: #d1fae5 !important; color: #065f46 !important; }
        .badge-orange { background-color: #fed7aa !important; color: #9a3412 !important; }
        .badge-teal { background-color: #99f6e4 !important; color: #115e59 !important; }
        .badge-cyan { background-color: #cffafe !important; color: #155e75 !important; }
        .badge-pink { background-color: #fce7f3 !important; color: #9d174d !important; }
        
        /* Background Colors */
        .bg-blue-50 { background-color: #eff6ff !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-orange-50 { background-color: #fff7ed !important; }
        .bg-purple-50 { background-color: #faf5ff !important; }
        .bg-cyan-50 { background-color: #ecfeff !important; }
        .bg-pink-50 { background-color: #fdf2f8 !important; }
        .bg-gray-100 { background-color: #f3f4f6 !important; }
        
        /* Text Colors */
        .text-cyan-800 { color: #155e75 !important; }
        .text-pink-800 { color: #9d174d !important; }
        
        /* Mobile menu animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .mobile-menu-enter {
            animation: slideDown 0.3s ease forwards;
        }
        
        /* Avatar Styles */
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
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container-custom {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .form-card {
                margin-bottom: 1rem;
            }
        }
        /* Prevent flex overflow */
.min-w-0{
    min-width:0;
}

/* Premium stat cards */
.stat-card{
    border-radius:14px;
    border:none;
    box-shadow:0 4px 14px rgba(0,0,0,0.04);
    transition:.25s;
}

.stat-card:hover{
    transform:translateY(-6px);
}

/* Icon container */
.stat-icon{
    width:48px;
    height:48px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-right:12px;
}

.stat-icon i{
    font-size:20px;
}

/* Numbers */
.stat-number{
    font-size:22px;
    font-weight:700;
    white-space:nowrap;
}

/* MOBILE OPTIMIZATION */
@media (max-width:576px){

    .stat-card .card-body{
        padding:12px;
    }

    .stat-number{
        font-size:18px;
    }

    .stat-icon{
        width:38px;
        height:38px;
    }

    .stat-icon i{
        font-size:16px;
    }
}
.icon-pink { 
    background: linear-gradient(135deg, #ec4899, #db2777); 
    color: white; 
}
.badge-pink { 
    background-color: #fce7f3 !important; 
    color: #9d174d !important; 
}

/* Button group styling */
.btn-group-flex {
    display: flex;
    gap: 0.5rem;
}

.btn-group-flex .btn {
    flex: 1;
}
/* Grace Period Quarter Status */
.quarter-grace {
    background-color: #fef3c7;
    border-color: #fbbf24;
    color: #92400e;
}
    </style>
</head>
<body class="bg-gray-50">
   @php
    use App\Models\Appraisal;
    
    // Define job title mappings to forms
    $jobTitleMappings = [
        'Plaza Manager' => 'plaza-manager',
        'Admin Clerk' => 'admin-clerk',
        'E&M Technician' => 'em-technician',
        'Shift Manager' => 'shift-manager',
        'Senior Toll Collector' => 'senior-toll-collector',
        'Toll Collector' => 'toll-collector',
        'TCE Technician' => 'tce',
        'Route Patrol Driver' => 'route-patrol-driver',
        'Plaza Attendant' => 'plaza-attendant',
        'Lane Attendant' => 'lane-attendant',
        'HR Assistant' => 'hr-assistant',
        'HR Advisor' => 'hr-advisor',
        'Verification Clerk' => 'verification-clerk',
        'Admin Manager' => 'admin-manager',
        'Trainer' => 'trainer',
        'Senior Trainer' => 'senior-trainer',
        'Senior TCE' => 'senior-tce',
        'Media and Customer Coordinator' => 'media-coordinator',
    ];
    
    $user = Auth::user();
    $userJobTitle = $user->job_title ?? 'Employee';
    $employeeNumber = $user->employee_number;
    
    // Check if user's job title matches any form
    $selectedForm = null;
    $isManualSelection = request()->has('manual_select') || !array_key_exists($userJobTitle, $jobTitleMappings);
    
    if (!$isManualSelection) {
        $selectedForm = $jobTitleMappings[$userJobTitle] ?? null;
    }
    
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
            'due_date_display' => 'Apr 20',
            'due_date_full' => 'April 20',
        ],
        'Q2' => [
            'name' => 'Quarter 2',
            'months' => 'April - June',
            'period_start' => $currentYear . '-04-01',
            'period_end' => $currentYear . '-06-30',
            'grace_end' => $currentYear . '-07-20',
            'due_date_display' => 'Jul 20',
            'due_date_full' => 'July 20',
        ],
        'Q3' => [
            'name' => 'Quarter 3',
            'months' => 'July - September',
            'period_start' => $currentYear . '-07-01',
            'period_end' => $currentYear . '-09-30',
            'grace_end' => $currentYear . '-10-20',
            'due_date_display' => 'Oct 20',
            'due_date_full' => 'October 20',
        ],
        'Q4' => [
            'name' => 'Quarter 4',
            'months' => 'October - December',
            'period_start' => $currentYear . '-10-01',
            'period_end' => $currentYear . '-12-31',
            'grace_end' => ($currentYear + 1) . '-01-20',
            'due_date_display' => 'Jan 20',
            'due_date_full' => 'January 20',
        ],
    ];
    
    // If a specific quarter is requested
    if ($quarter && isset($quarters[$quarter])) {
        $q = $quarters[$quarter];
        $graceEndDate = \Carbon\Carbon::parse($q['grace_end']);
        $periodEndDate = \Carbon\Carbon::parse($q['period_end']);
        $periodStartDate = \Carbon\Carbon::parse($q['period_start']);
        
        $isPast = $today->gt($graceEndDate);
        // A quarter is current ONLY if today is between period start and grace end
        $isCurrent = $today->gte($periodStartDate) && $today->lte($graceEndDate);
        $isFuture = $today->lt($periodStartDate);
        $isInGrace = $today->gt($periodEndDate) && $today->lte($graceEndDate);
        
        return (object) [
            'quarter' => $quarter,
            'quarter_name' => $q['name'],
            'quarter_months' => $q['months'],
            'due_date' => $q['due_date_display'],
            'due_date_formatted' => $q['due_date_full'],
            'due_date_timestamp' => $graceEndDate->timestamp,
            'period_start' => $periodStartDate->format('Y-m-d'),
            'period_end' => $periodEndDate->format('Y-m-d'),
            'grace_end' => $q['grace_end'],
            'is_past' => $isPast,
            'is_current' => $isCurrent,
            'is_future' => $isFuture,
            'is_in_grace' => $isInGrace,
            'year' => $currentYear,
        ];
    }
    
    // Determine current quarter based on today's date (grace period aware)
    // Find the FIRST quarter where today is between period start and grace end
    $currentQuarter = null;
    foreach ($quarters as $qKey => $qData) {
        $periodStart = \Carbon\Carbon::parse($qData['period_start']);
        $graceEnd = \Carbon\Carbon::parse($qData['grace_end']);
        
        if ($today->gte($periodStart) && $today->lte($graceEnd)) {
            $currentQuarter = $qKey;
            break;
        }
    }
    
    // If all quarters are past (after Q4 grace end), default to Q4
    if (!$currentQuarter) {
        $currentQuarter = 'Q4';
    }
    
    return getQuarterInfoWithGrace($currentYear, $currentQuarter);
}
    
    // Get current quarter info with grace period
    $quarterInfo = getQuarterInfoWithGrace();
    $currentQuarter = $quarterInfo->quarter;
    $currentYear = $quarterInfo->year;
    
    // Get all quarters with their grace period info
    $allQuartersWithInfo = [];
    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $q) {
        $allQuartersWithInfo[$q] = getQuarterInfoWithGrace($currentYear, $q);
    }
    
    // Get user's submission history for the current year
    $userSubmissions = Appraisal::where('employee_number', $employeeNumber)
        ->whereYear('created_at', $currentYear)
        ->orderBy('created_at', 'desc')
        ->get();

    // Track quarter statuses with grace period awareness
$quarterStatuses = [];
$hasMissedQuarters = false;
$currentQuarterSubmission = null;
$missedQuarters = [];
$completedQuarters = [];
$gracePeriodQuarters = [];

// Track submissions by quarter explicitly
$submissionsByQuarter = [];

foreach ($allQuartersWithInfo as $quarter => $qInfo) {
    // Find submission for this quarter - using period_start instead of subtracting months
    $submission = null;
    $quarterStart = \Carbon\Carbon::parse($qInfo->period_start);
    $quarterEnd = \Carbon\Carbon::parse($qInfo->grace_end);
    
    foreach ($userSubmissions as $appraisal) {
        $createdAt = \Carbon\Carbon::parse($appraisal->created_at);
        // Check if appraisal was created during this quarter's active period (from quarter start to grace end)
        if ($createdAt->between($quarterStart, $quarterEnd)) {
            $submission = $appraisal;
            $submissionsByQuarter[$quarter] = $submission;
            break;
        }
    }
    
    $status = 'future';
    if ($submission) {
        $status = 'completed';
        $completedQuarters[] = $quarter;
        // Only set as current quarter submission if this is the current quarter
        if ($quarter === $currentQuarter) {
            $currentQuarterSubmission = $submission;
        }
    } elseif ($qInfo->is_past) {
        $status = 'missed';
        $missedQuarters[] = $quarter;
        $hasMissedQuarters = true;
    } elseif ($qInfo->is_current) {
        $status = $qInfo->is_in_grace ? 'grace' : 'current';
        if ($status === 'grace') {
            $gracePeriodQuarters[] = $quarter;
        }
    } else {
        $status = 'future';
    }
    
    $quarterStatuses[$quarter] = [
        'status' => $status,
        'info' => $qInfo,
        'submission' => $submission
    ];
}

// Calculate submission statistics
$totalQuarters = count(['Q1', 'Q2', 'Q3', 'Q4']);
$completedCount = count($completedQuarters);
$missedCount = count($missedQuarters);
$submissionRate = $totalQuarters > 0 ? ($completedCount / $totalQuarters) * 100 : 0;

// Determine if user can submit for current quarter - check if they have NOT submitted for current quarter specifically
$hasSubmittedCurrentQuarter = isset($submissionsByQuarter[$currentQuarter]);
$hasAnySubmissionThisQuarter = $hasSubmittedCurrentQuarter;
$canSubmitForCurrentQuarter = !$hasSubmittedCurrentQuarter && $quarterInfo->is_current;
    
    // Function to check if user can edit an appraisal
    function canEditAppraisal($appraisal, $user) {
        if (!$appraisal) return false;
        
        // Can't edit approved appraisals
        if ($appraisal->status === 'approved') return false;
        
        // Employees can only edit their own appraisals
        if ($user->user_type === 'employee') {
            return $appraisal->employee_number === $user->employee_number;
        }
        
        // Supervisors can edit appraisals of their team members
        if ($user->user_type === 'supervisor') {
            return true;
        }
        
        return false;
    }
@endphp

    <!-- Header with Animated Gradient -->
    <div class="gradient-header text-white">
        <div class="container-custom px-3 py-2">
            <div class="d-flex justify-content-between align-items-center">
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
                                    <div class="rounded-circle bg-gradient-to-br from-[#110484] to-[#e7581c]" style="width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center;">
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
                    
                    <div class="d-flex align-items-center">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem;"></div>
                        <div>
                            <h1 class="h5 mb-0 fw-bold" style="font-size: 1rem;">Appraisal Forms</h1>
                            <p class="mb-0 text-white-50" style="font-size: 0.75rem;">
                                {{ $quarterInfo->quarter }} {{ $quarterInfo->year }} • {{ $userJobTitle }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="d-flex align-items-center gap-2">
                    <div class="d-none d-lg-block">
                        <a href="{{ route('dashboard') }}" class="btn btn-accent btn-sm">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </div>
                    
                    <div class="d-none d-md-flex flex-column align-items-end">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-success rounded-circle me-1" style="width: 0.5rem; height: 0.5rem;"></div>
                            <span class="fw-medium">{{ $user->name }}</span>
                        </div>
                        <span class="text-white-50" style="font-size: 0.75rem;">{{ $user->job_title ?? 'Employee' }}</span>
                    </div>
                    
                    <div class="dropdown d-md-none">
                        <button class="btn btn-outline-light btn-sm" type="button" id="mobileMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mobile-menu" aria-labelledby="mobileMenu">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-gradient me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->employee_number }}</div>
                                        <div class="small text-muted">{{ $user->job_title ?? 'Employee' }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home me-2 moic-navy"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('appraisals.index') }}">
                                    <i class="fas fa-list me-2 text-primary"></i> My Appraisals
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-none d-md-block">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-custom px-3">
            <!-- Progress Card -->
            <div class="card card-moic mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <!-- Grace Period Alert -->
@if($quarterInfo->is_in_grace && !$hasAnySubmissionThisQuarter)
    <div class="alert alert-warning mb-4 border-0 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="fas fa-hourglass-half fa-2x me-3 text-warning"></i>
            <div>
                <h5 class="mb-1 fw-bold">Grace Period Active!</h5>
                <p class="mb-0">
                    You can still submit your appraisal for <strong>{{ $quarterInfo->quarter_name }} ({{ $quarterInfo->quarter_months }})</strong> 
                    until <strong>{{ $quarterInfo->due_date_formatted }}, {{ $quarterInfo->year }}</strong>. 
                    Don't miss this extended deadline!
                </p>
            </div>
        </div>
    </div>
@endif
                            <h2 class="h5 fw-bold moic-navy mb-1">Quarterly Submission Progress - {{ $currentYear }}</h2>
                            <p class="text-muted small mb-0">{{ $completedCount }} of {{ $totalQuarters }} quarters completed</p>
                        </div>
                        <div class="text-end">
                            <span class="h3 fw-bold {{ $submissionRate >= 75 ? 'text-success' : ($submissionRate >= 50 ? 'text-warning' : 'text-danger') }}">
                                {{ round($submissionRate) }}%
                            </span>
                            <p class="text-muted small mb-0">Completion Rate</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Submission Progress</span>
                            <span class="text-muted small">{{ round($submissionRate) }}%</span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill {{ $submissionRate >= 75 ? 'bg-success' : ($submissionRate >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                 style="width: {{ $submissionRate }}%"></div>
                        </div>
                    </div>
                    
                  <div class="row g-3">
    @foreach($quarterStatuses as $quarter => $statusData)
        @php
            $statusClass = '';
            $statusIcon = '';
            $statusText = '';
            
            // Determine status display based on status type
            switch($statusData['status']) {
                case 'completed':
                    $statusClass = 'quarter-completed';
                    $statusIcon = 'fa-check-circle';
                    $statusText = 'Completed';
                    break;
                case 'missed':
                    $statusClass = 'quarter-missed';
                    $statusIcon = 'fa-times-circle';
                    $statusText = 'Missed';
                    break;
                case 'current':
                    $statusClass = 'quarter-current';
                    $statusIcon = 'fa-clock';
                    $statusText = 'In Progress';
                    break;
                case 'grace':
                    $statusClass = 'quarter-grace';
                    $statusIcon = 'fa-hourglass-half';
                    $statusText = 'Grace Period';
                    break;
                case 'future':
                    $statusClass = 'quarter-future';
                    $statusIcon = 'fa-calendar-alt';
                    $statusText = 'Upcoming';
                    break;
                default:
                    $statusClass = 'quarter-future';
                    $statusIcon = 'fa-calendar-alt';
                    $statusText = 'Upcoming';
                    break;
            }
        @endphp
        
        <div class="col-6 col-md-3">
            <div class="border rounded p-3 {{ $statusClass }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">{{ $quarter }}</span>
                    <i class="fas {{ $statusIcon }}"></i>
                </div>
                <p class="small mb-1">{{ $statusData['info']->quarter_months }}</p>
                <p class="small fw-medium mb-1">{{ $statusText }}</p>
                @if($statusData['status'] === 'completed' && $statusData['submission'])
                    <p class="small text-muted mt-1">
                        {{ \Carbon\Carbon::parse($statusData['submission']->created_at)->format('M d') }}
                    </p>
                @elseif($statusData['status'] === 'missed')
                    <p class="small fw-medium mt-1">Deadline: {{ $statusData['info']->due_date }}</p>
                @elseif($statusData['status'] === 'current')
                    <p class="small fw-medium mt-1">Due: {{ $statusData['info']->due_date }}</p>
                @elseif($statusData['status'] === 'grace')
                    <p class="small fw-medium mt-1">Final Deadline: {{ $statusData['info']->due_date }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>                    
                    @if($hasMissedQuarters && $quarterInfo->is_current)
                        <div class="alert alert-warning mt-4">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-circle me-3 mt-1"></i>
                                <div>
                                    <p class="fw-bold mb-1">Missed Quarter Notice</p>
                                    <p class="small mb-0">
                                        You have missed submissions for 
                                        @foreach($missedQuarters as $index => $quarter)
                                            {{ $quarter }}{{ $index < count($missedQuarters) - 1 ? ', ' : '' }}
                                        @endforeach.
                                        <strong>You can still submit for {{ $quarterInfo->quarter }}.</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="card card-moic mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="h4 fw-bold moic-navy mb-3">
                                @if($isManualSelection)
                                    Select Your Appraisal Form
                                @else
                                    Your Appraisal Form
                                @endif
                            </h2>
                            
                            <div class="row mb-3">
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-user-tie me-2 text-muted"></i>
                                        <span class="text-muted">Job Title:</span>
                                        <span class="fw-bold ms-1">{{ $userJobTitle }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                        <span class="text-muted">Current Quarter:</span>
                                        <span class="fw-bold ms-1">{{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</span>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <p class="mb-1">
                                        <i class="fas fa-clock me-2 text-muted"></i>
                                        <span class="text-muted">Deadline:</span>
                                        <span class="fw-bold text-danger ms-1">{{ $quarterInfo->due_date }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                @if($isManualSelection)
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-exchange-alt me-1"></i> Manual Selection
                                    </span>
                                @elseif($selectedForm)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-check-circle me-1"></i> Auto-selected
                                    </span>
                                @endif
                                
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-file-alt me-1"></i> 13 Forms Available
                                </span>
                                
                                @if($currentQuarterSubmission)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-paper-plane me-1"></i> Already Submitted
                                    </span>
                                @elseif($quarterInfo->is_current)
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-edit me-1"></i> Ready to Submit
                                    </span>
                                @endif
                                
                                @if($hasMissedQuarters)
                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-exclamation-circle me-1"></i> Missed Quarters
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            @if(!$isManualSelection && $selectedForm)
                                <a href="?manual_select=true" class="btn btn-warning btn-sm">
                                    <i class="fas fa-exchange-alt me-1"></i> Change Form
                                </a>
                            @endif
                            
                            <a href="{{ route('appraisals.index') }}" class="btn btn-moic btn-sm">
                                <i class="fas fa-list me-1"></i> My Appraisals
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-2 g-md-3 mb-4">
                <div class="col-6 col-sm-6 col-md-3">
                    <div class="card card-moic h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-blue-50">
                                <i class="fas fa-file-alt moic-navy"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-muted small mb-1">Total Forms</p>
                                <p class="stat-number mb-0">13</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-md-3">
                    <div class="card card-moic h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-green-50">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-muted small mb-1">Current Quarter</p>
                                <p class="stat-number mb-0">{{ $quarterInfo->quarter }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-md-3">
                    <div class="card card-moic h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-orange-50">
                                <i class="fas fa-clock moic-accent"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-muted small mb-1">Deadline</p>
                                <p class="stat-number mb-0">{{ $quarterInfo->due_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-6 col-sm-6 col-md-3">
                    <div class="card card-moic h-100 stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-purple-50">
                                <i class="fas fa-user text-purple"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-muted small mb-1">Your Role</p>
                                <p class="stat-number text-truncate mb-0" title="{{ $userJobTitle }}">
                                    {{ $userJobTitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         <!-- Forms Grid - COMPLETE with all 18 forms -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="formsGrid">
    
  <!-- Plaza Manager -->
@php
    $isSelected = (!$isManualSelection && $selectedForm === 'plaza-manager');
    // Check if user has already submitted for the CURRENT quarter with THIS job title
    $hasSubmittedForCurrentQuarterWithThisForm = $hasSubmittedCurrentQuarter && 
        $currentQuarterSubmission && 
        $currentQuarterSubmission->job_title === 'Plaza Manager';
    // Check if user has submitted for current quarter with ANY form
    $hasAnySubmissionForCurrentQuarter = $hasSubmittedCurrentQuarter;
    
    // Form is disabled if:
    // 1. Auto-selection is on and this isn't the matched form, OR
    // 2. User has already submitted for current quarter with a DIFFERENT form
    $isDisabled = ($isManualSelection === false && $selectedForm !== 'plaza-manager') || 
                  ($hasAnySubmissionForCurrentQuarter && !$hasSubmittedForCurrentQuarterWithThisForm);
    $isClickable = !$isDisabled && !$hasSubmittedForCurrentQuarterWithThisForm;
@endphp
<div class="col">
    <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
         data-form="plaza-manager">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3 icon-blue">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold moic-navy mb-0">Plaza Manager</h5>
                        <span class="text-muted small">Management</span>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <span class="badge badge-blue mb-1">8 KPAs</span>
                    @if($isSelected)
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check me-1"></i> Selected
                        </span>
                    @endif
                </div>
            </div>
            <p class="text-muted small mb-3">Oversee plaza operations and staff management</p>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                </span>
                @if($hasSubmittedForCurrentQuarterWithThisForm)
                    <span class="badge bg-success text-white">
                        <i class="fas fa-check me-1"></i> Submitted
                    </span>
                @elseif($hasAnySubmissionForCurrentQuarter)
                    <span class="badge bg-info text-white">
                        <i class="fas fa-info-circle me-1"></i> Other Form Submitted for {{ $currentQuarter }}
                    </span>
                @endif
            </div>
            
            @if($hasSubmittedForCurrentQuarterWithThisForm)
                <div class="d-flex gap-2">
                    @if($currentQuarterSubmission->status !== 'approved')
                        <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                           class="btn btn-warning flex-grow-1">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                            {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                        </span>
                    @else
                        <button class="btn btn-success w-100" disabled>
                            <i class="fas fa-check-circle me-2"></i> Approved
                        </button>
                    @endif
                </div>
            @elseif($hasAnySubmissionForCurrentQuarter)
                <button class="btn btn-secondary w-100" disabled>
                    <i class="fas fa-lock me-2"></i> Already Submitted for {{ $currentQuarter }}
                </button>
            @else
                <a href="{{ route('appraisals.plaza-manager') }}" class="btn btn-moic w-100">
                    <i class="fas fa-play-circle me-2"></i>
                    @if($isSelected) Start Appraisal for {{ $currentQuarter }} @else Select Form @endif
                </a>
            @endif
        </div>
    </div>
</div>

   <!-- Admin Clerk -->
@php
    $isSelected = (!$isManualSelection && $selectedForm === 'admin-clerk');
    // Check if user has already submitted for the CURRENT quarter with THIS job title
    $hasSubmittedForCurrentQuarterWithThisForm = $hasSubmittedCurrentQuarter && 
        $currentQuarterSubmission && 
        $currentQuarterSubmission->job_title === 'Admin Clerk';
    // Check if user has submitted for current quarter with ANY form
    $hasAnySubmissionForCurrentQuarter = $hasSubmittedCurrentQuarter;
    
    // Form is disabled if:
    // 1. Auto-selection is on and this isn't the matched form, OR
    // 2. User has already submitted for current quarter with a DIFFERENT form
    $isDisabled = ($isManualSelection === false && $selectedForm !== 'admin-clerk') || 
                  ($hasAnySubmissionForCurrentQuarter && !$hasSubmittedForCurrentQuarterWithThisForm);
    $isClickable = !$isDisabled && !$hasSubmittedForCurrentQuarterWithThisForm;
@endphp
<div class="col">
    <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
         data-form="admin-clerk">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3 icon-purple">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold moic-navy mb-0">Admin Clerk</h5>
                        <span class="text-muted small">Admin</span>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <span class="badge badge-purple mb-1">8 KPAs</span>
                    @if($isSelected)
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check me-1"></i> Selected
                        </span>
                    @endif
                </div>
            </div>
            <p class="text-muted small mb-3">Administrative tasks and document management</p>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">
                    <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                </span>
                @if($hasSubmittedForCurrentQuarterWithThisForm)
                    <span class="badge bg-success text-white">
                        <i class="fas fa-check me-1"></i> Submitted
                    </span>
                @elseif($hasAnySubmissionForCurrentQuarter)
                    <span class="badge bg-info text-white">
                        <i class="fas fa-info-circle me-1"></i> Other Form Submitted for {{ $currentQuarter }}
                    </span>
                @endif
            </div>
            
            @if($hasSubmittedForCurrentQuarterWithThisForm)
                <div class="d-flex gap-2">
                    @if($currentQuarterSubmission->status !== 'approved')
                        <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                           class="btn btn-warning flex-grow-1">
                            <i class="fas fa-edit me-2"></i> Edit
                        </a>
                        <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                            {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                        </span>
                    @else
                        <button class="btn btn-success w-100" disabled>
                            <i class="fas fa-check-circle me-2"></i> Approved
                        </button>
                    @endif
                </div>
            @elseif($hasAnySubmissionForCurrentQuarter)
                <button class="btn btn-secondary w-100" disabled>
                    <i class="fas fa-lock me-2"></i> Already Submitted for {{ $currentQuarter }}
                </button>
            @else
                <a href="{{ route('appraisals.admin-clerk') }}" class="btn btn-moic w-100">
                    <i class="fas fa-play-circle me-2"></i>
                    @if($isSelected) Start Appraisal for {{ $currentQuarter }} @else Select Form @endif
                </a>
            @endif
        </div>
    </div>
</div>

    <!-- E&M Technician -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'em-technician');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'E&M Technician';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'em-technician') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="em-technician">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-green">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">E&M Technician</h5>
                            <span class="text-muted small">Technical</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-green mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Electrical and mechanical maintenance</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.em-technician') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Shift Manager -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'shift-manager');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Shift Manager';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'shift-manager') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="shift-manager">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-orange">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Shift Manager</h5>
                            <span class="text-muted small">Supervisory</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-orange mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Shift operations and staff coordination</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.shift-manager') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Senior Toll Collector -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'senior-toll-collector');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Senior Toll Collector';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'senior-toll-collector') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="senior-toll-collector">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-teal">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Senior Toll Collector</h5>
                            <span class="text-muted small">Senior</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-teal mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Toll collection with team support</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.senior-toll-collector') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Toll Collector -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'toll-collector');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Toll Collector';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'toll-collector') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="toll-collector">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-cyan">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Toll Collector</h5>
                            <span class="text-muted small">Frontline</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-cyan mb-1">7 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Frontline toll collection and service</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.toll-collector') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- TCE Technician -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'tce');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'TCE Technician';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'tce') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="tce">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-cyan">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">TCE Technician</h5>
                            <span class="text-muted small">Technical</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-cyan mb-1">7 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Technical team and equipment oversight</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.tce') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Route Patrol Driver -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'route-patrol-driver');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Route Patrol Driver';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'route-patrol-driver') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="route-patrol-driver">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-green">
                            <i class="fas fa-truck-pickup"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Route Patrol Driver</h5>
                            <span class="text-muted small">Support</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-green mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Route patrols and vehicle maintenance</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.route-patrol-driver') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Plaza Attendant -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'plaza-attendant');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Plaza Attendant';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'plaza-attendant') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="plaza-attendant">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-green">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Plaza Attendant</h5>
                            <span class="text-muted small">Support</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-green mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Plaza cleaning and maintenance</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.plaza-attendant') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Lane Attendant -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'lane-attendant');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Lane Attendant';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'lane-attendant') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="lane-attendant">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-green">
                            <i class="fas fa-tree"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Lane Attendant</h5>
                            <span class="text-muted small">Support</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-green mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Lane cleaning and landscaping</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.lane-attendant') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- HR Assistant -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'hr-assistant');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'HR Assistant';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'hr-assistant') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="hr-assistant">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-indigo">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">HR Assistant</h5>
                            <span class="text-muted small">Admin</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-purple mb-1">10 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Payroll and HR administration</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.hr-assistant') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- HR Advisor -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'hr-advisor');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'DBK HR Advisor';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'hr-advisor') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="hr-advisor">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3 icon-pink">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">DBK HR Advisor</h5>
                            <span class="text-muted small">HR</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-pink mb-1">10 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Recruitment, induction, and employee relations</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.hr-advisor') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Verification Clerk -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'verification-clerk');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Verification Clerk';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'verification-clerk') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="verification-clerk">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Verification Clerk</h5>
                            <span class="text-muted small">Audit & Compliance</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge" style="background-color: #ede9fe; color: #6d28d9; padding: 0.35rem 0.65rem; border-radius: 0.375rem; font-weight: 600;">7 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Transaction verification, audit accuracy, and correction follow-up</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.verification-clerk') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ========== NEW FORMS - MISSING JOB TITLES ========== -->
    
    <!-- Admin Manager -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'admin-manager');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Admin Manager';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'admin-manager') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="admin-manager">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Admin Manager</h5>
                            <span class="text-muted small">Management</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge" style="background-color: #e9d5ff; color: #6b21a8;">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Strategic planning, budget management, staff development, and procurement oversight</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.admin-manager') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Trainer -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'trainer');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Trainer';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'trainer') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="trainer">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Trainer</h5>
                            <span class="text-muted small">Training & Development</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-green mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Training needs analysis, program development, delivery, and evaluation</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.trainer') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Senior Trainer -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'senior-trainer');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Senior Trainer';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'senior-trainer') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="senior-trainer">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                            <i class="fas fa-person-chalkboard"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Senior Trainer</h5>
                            <span class="text-muted small">Training Leadership</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-purple mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Training strategy, curriculum development, trainer coaching, and quality assurance</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.senior-trainer') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Senior TCE -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'senior-tce');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Senior TCE';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'senior-tce') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="senior-tce">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #f97316, #ea580c); color: white;">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Senior TCE</h5>
                            <span class="text-muted small">Technical Leadership</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-orange mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Technical team leadership, equipment maintenance oversight, and emergency response management</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.senior-tce') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Media and Customer Coordinator -->
    @php
        $isSelected = (!$isManualSelection && $selectedForm === 'media-coordinator');
        $hasSubmittedCurrent = $currentQuarterSubmission && $currentQuarterSubmission->job_title === 'Media and Customer Coordinator';
        $isDisabled = ($isManualSelection === false && $selectedForm !== 'media-coordinator') || 
                      ($currentQuarterSubmission !== null && !$hasSubmittedCurrent);
        $isClickable = !$isDisabled && !$hasSubmittedCurrent;
    @endphp
    <div class="col">
        <div class="form-card h-100 {{ $isSelected ? 'form-card-selected' : '' }} {{ !$isClickable ? 'form-card-disabled' : '' }}"
             data-form="media-coordinator">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #06b6d4, #0891b2); color: white;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold moic-navy mb-0">Media & Customer Coordinator</h5>
                            <span class="text-muted small">Communications</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-cyan mb-1">8 KPAs</span>
                        @if($isSelected)
                            <span class="badge bg-success bg-opacity-10 text-success mt-1">
                                <i class="fas fa-check me-1"></i> Selected
                            </span>
                        @endif
                    </div>
                </div>
                <p class="text-muted small mb-3">Customer service management, media relations, complaint resolution, and social media management</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted small">
                        <i class="far fa-clock me-1"></i> Due: {{ $quarterInfo->due_date }}
                    </span>
                    @if($hasSubmittedCurrent)
                        <span class="badge bg-success text-white">
                            <i class="fas fa-check me-1"></i> Submitted
                        </span>
                    @elseif($currentQuarterSubmission)
                        <span class="badge bg-info text-white">
                            <i class="fas fa-info-circle me-1"></i> Other Form Submitted
                        </span>
                    @endif
                </div>
                
                @if($hasSubmittedCurrent)
                    <div class="d-flex gap-2">
                        @if($currentQuarterSubmission->status !== 'approved')
                            <a href="{{ route('appraisals.edit', $currentQuarterSubmission->id) }}" 
                               class="btn btn-warning flex-grow-1">
                                <i class="fas fa-edit me-2"></i> Edit
                            </a>
                            <span class="badge {{ $currentQuarterSubmission->status === 'submitted' ? 'bg-info' : 'bg-secondary' }} d-flex align-items-center px-3">
                                {{ $currentQuarterSubmission->status === 'submitted' ? 'Submitted' : 'Draft' }}
                            </span>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check-circle me-2"></i> Approved
                            </button>
                        @endif
                    </div>
                @elseif($currentQuarterSubmission)
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-lock me-2"></i> Another Form Submitted
                    </button>
                @else
                    <a href="{{ route('appraisals.media-coordinator') }}" class="btn btn-moic w-100">
                        <i class="fas fa-play-circle me-2"></i>
                        @if($isSelected) Start Appraisal @else Select Form @endif
                    </a>
                @endif
            </div>
        </div>
    </div>
</div> <!-- Close forms grid -->
 
            <!-- View Appraisals Card -->
            <div class="row mt-4">
                <div class="col">
                    <div class="card card-moic">
                        <div class="card-body text-center p-5">
                            <div class="d-inline-flex p-3 mb-3 bg-gray-100 rounded-circle">
                                <i class="fas fa-list-alt fa-2x text-gray-600"></i>
                            </div>
                            <h4 class="fw-bold moic-navy mb-2">Manage Your Appraisals</h4>
                            <p class="text-muted mb-4">View and manage all your existing appraisals in one place</p>
                            <a href="{{ route('appraisals.index') }}" class="btn btn-moic">
                                <i class="fas fa-eye me-2"></i> View All Appraisals
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-2 rounded me-3">
                                <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                            </div>
                            <div>
                                <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                                <p class="text-muted small">Version 1.0.0 powered by SmartWave Solutions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap justify-content-lg-end gap-4">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                            <a href="{{ route('appraisals.index') }}" class="text-decoration-none text-muted small">
                                <i class="fas fa-list me-1"></i> My Appraisals
                            </a>
                            <a href="{{ route('profile.show') }}" class="text-decoration-none text-muted small">
                                <i class="fas fa-user-circle me-1"></i> Profile
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Handle mobile menu
            const mobileMenuBtn = document.getElementById('mobileMenu');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    const dropdown = new bootstrap.Dropdown(mobileMenuBtn);
                });
            }
        });
    </script>
</body>
</html>