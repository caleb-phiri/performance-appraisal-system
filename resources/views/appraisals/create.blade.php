<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Forms - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
        .form-card-selected {
            box-shadow: 0 0 0 3px #110484, 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-color: #110484;
        }
        .form-card-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .form-card-disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Logo container with gradient border */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 8px;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 6px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 12px;
        }
        
        /* Animated gradient for header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
        }
        
        /* Mobile menu animations */
        .mobile-menu-enter {
            animation: slideDown 0.3s ease forwards;
        }
        
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
        
        /* Progress bar styles */
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        /* Quarter status styles */
        .quarter-missed {
            background-color: #fee2e2;
            border-color: #f87171;
            color: #dc2626;
        }
        
        .quarter-completed {
            background-color: #d1fae5;
            border-color: #34d399;
            color: #059669;
        }
        
        .quarter-pending {
            background-color: #fef3c7;
            border-color: #fbbf24;
            color: #d97706;
        }
        
        .quarter-current {
            background-color: #dbeafe;
            border-color: #60a5fa;
            color: #2563eb;
        }
        
        .quarter-future {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
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
            'HR Advisor' => 'hr-advisor', // Add HR Advisor
        ];
        
        $userJobTitle = Auth::user()->job_title ?? 'Employee';
        $employeeNumber = Auth::user()->employee_number;
        
        // Check if user's job title matches any form
        $selectedForm = null;
        $isManualSelection = request()->has('manual_select') || !array_key_exists($userJobTitle, $jobTitleMappings);
        
        if (!$isManualSelection) {
            $selectedForm = $jobTitleMappings[$userJobTitle] ?? null;
        }
        
        // Quarter calculation function with updated deadlines:
        // Q1: April 20, Q2: July 20, Q3: October 20, Q4: January 20 (next year)
        function getQuarterInfo($year = null, $quarter = null) {
            $now = now();
            $currentYear = $year ?? $now->year;
            $currentMonth = $now->month;
            
            $quarterInfo = [
                'current_date' => $now->format('Y-m-d'),
                'year' => $currentYear,
                'quarter' => '',
                'quarter_name' => '',
                'quarter_months' => '',
                'due_date' => '',
                'due_date_formatted' => '',
                'due_date_timestamp' => '',
                'appraisal_start' => '',
                'appraisal_end' => '',
                'review_start' => '',
                'review_end' => '',
                'is_past' => false,
                'is_current' => false,
                'is_future' => false
            ];
            
            // If quarter is specified, use it
            if ($quarter) {
                $quarterInfo['quarter'] = $quarter;
            } else {
                // Determine current quarter based on month
                if ($currentMonth >= 1 && $currentMonth <= 3) {
                    $quarterInfo['quarter'] = 'Q1';
                } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
                    $quarterInfo['quarter'] = 'Q2';
                } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
                    $quarterInfo['quarter'] = 'Q3';
                } else {
                    $quarterInfo['quarter'] = 'Q4';
                }
            }
            
            // Set quarter details based on determined quarter
            switch ($quarterInfo['quarter']) {
                case 'Q1':
                    $quarterInfo['quarter_name'] = 'Quarter 1';
                    $quarterInfo['quarter_months'] = 'January - March';
                    $quarterInfo['due_date'] = date('M d', strtotime("April 20, $currentYear"));
                    $quarterInfo['due_date_formatted'] = "April 20";
                    $quarterInfo['due_date_timestamp'] = strtotime("April 20, $currentYear");
                    $quarterInfo['appraisal_start'] = date('M d', strtotime("January 1, $currentYear"));
                    $quarterInfo['appraisal_end'] = date('M d', strtotime("April 15, $currentYear"));
                    $quarterInfo['review_start'] = date('M d', strtotime("April 16, $currentYear"));
                    $quarterInfo['review_end'] = date('M d', strtotime("April 20, $currentYear"));
                    break;
                    
                case 'Q2':
                    $quarterInfo['quarter_name'] = 'Quarter 2';
                    $quarterInfo['quarter_months'] = 'April - June';
                    $quarterInfo['due_date'] = date('M d', strtotime("July 20, $currentYear"));
                    $quarterInfo['due_date_formatted'] = "July 20";
                    $quarterInfo['due_date_timestamp'] = strtotime("July 20, $currentYear");
                    $quarterInfo['appraisal_start'] = date('M d', strtotime("April 1, $currentYear"));
                    $quarterInfo['appraisal_end'] = date('M d', strtotime("July 15, $currentYear"));
                    $quarterInfo['review_start'] = date('M d', strtotime("July 16, $currentYear"));
                    $quarterInfo['review_end'] = date('M d', strtotime("July 20, $currentYear"));
                    break;
                    
                case 'Q3':
                    $quarterInfo['quarter_name'] = 'Quarter 3';
                    $quarterInfo['quarter_months'] = 'July - September';
                    $quarterInfo['due_date'] = date('M d', strtotime("October 20, $currentYear"));
                    $quarterInfo['due_date_formatted'] = "October 20";
                    $quarterInfo['due_date_timestamp'] = strtotime("October 20, $currentYear");
                    $quarterInfo['appraisal_start'] = date('M d', strtotime("July 1, $currentYear"));
                    $quarterInfo['appraisal_end'] = date('M d', strtotime("October 15, $currentYear"));
                    $quarterInfo['review_start'] = date('M d', strtotime("October 16, $currentYear"));
                    $quarterInfo['review_end'] = date('M d', strtotime("October 20, $currentYear"));
                    break;
                    
                case 'Q4':
                    $quarterInfo['quarter_name'] = 'Quarter 4';
                    $quarterInfo['quarter_months'] = 'October - December';
                    $quarterInfo['due_date'] = date('M d', strtotime("January 20, " . ($currentYear + 1)));
                    $quarterInfo['due_date_formatted'] = "January 20";
                    $quarterInfo['due_date_timestamp'] = strtotime("January 20, " . ($currentYear + 1));
                    $quarterInfo['appraisal_start'] = date('M d', strtotime("October 1, $currentYear"));
                    $quarterInfo['appraisal_end'] = date('M d', strtotime("January 15, " . ($currentYear + 1)));
                    $quarterInfo['review_start'] = date('M d', strtotime("January 16, " . ($currentYear + 1)));
                    $quarterInfo['review_end'] = date('M d', strtotime("January 20, " . ($currentYear + 1)));
                    break;
            }
            
            // Determine if quarter is past, current, or future
            $nowTimestamp = time();
            $appraisalEndTimestamp = strtotime($quarterInfo['appraisal_end'] . ", " . ($quarterInfo['quarter'] === 'Q4' ? $currentYear + 1 : $currentYear));
            $dueDateTimestamp = $quarterInfo['due_date_timestamp'];
            
            if ($nowTimestamp > $dueDateTimestamp) {
                $quarterInfo['is_past'] = true;
                $quarterInfo['is_current'] = false;
                $quarterInfo['is_future'] = false;
            } elseif ($nowTimestamp >= $appraisalEndTimestamp && $nowTimestamp <= $dueDateTimestamp) {
                $quarterInfo['is_past'] = false;
                $quarterInfo['is_current'] = true;
                $quarterInfo['is_future'] = false;
            } else {
                $quarterInfo['is_past'] = false;
                $quarterInfo['is_current'] = false;
                $quarterInfo['is_future'] = true;
            }
            
            return (object) $quarterInfo;
        }
        
        // Get current quarter info
        $quarterInfo = getQuarterInfo();
        $currentQuarter = $quarterInfo->quarter;
        $currentYear = $quarterInfo->year;
        
        // Get user's submission history for the current year
        $userSubmissions = Appraisal::where('employee_number', $employeeNumber)
            ->whereYear('created_at', $currentYear)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all quarters for the current year
        $allQuarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        
        // Track quarter statuses
        $quarterStatuses = [];
        $hasMissedQuarters = false;
        $currentQuarterSubmission = null;
        $canSubmitCurrentQuarter = true;
        $missedQuarters = [];
        $completedQuarters = [];
        
        foreach ($allQuarters as $quarter) {
            $qInfo = getQuarterInfo($currentYear, $quarter);
            $submission = $userSubmissions->first(function($appraisal) use ($quarter, $currentYear) {
                // Check if appraisal was created during this quarter period
                $createdAt = strtotime($appraisal->created_at);
                
                switch ($quarter) {
                    case 'Q1':
                        $start = strtotime("January 1, $currentYear");
                        $end = strtotime("April 20, $currentYear");
                        break;
                    case 'Q2':
                        $start = strtotime("April 1, $currentYear");
                        $end = strtotime("July 20, $currentYear");
                        break;
                    case 'Q3':
                        $start = strtotime("July 1, $currentYear");
                        $end = strtotime("October 20, $currentYear");
                        break;
                    case 'Q4':
                        $start = strtotime("October 1, $currentYear");
                        $end = strtotime("January 20, " . ($currentYear + 1));
                        break;
                }
                
                return $createdAt >= $start && $createdAt <= $end;
            });
            
            $status = 'future';
            if ($submission) {
                $status = 'completed';
                $completedQuarters[] = $quarter;
            } elseif ($qInfo->is_past) {
                $status = 'missed';
                $missedQuarters[] = $quarter;
                $hasMissedQuarters = true;
            } elseif ($qInfo->is_current) {
                $status = 'current';
                if ($submission) {
                    $currentQuarterSubmission = $submission;
                }
            } elseif ($qInfo->is_future) {
                $status = 'future';
            }
            
            $quarterStatuses[$quarter] = [
                'status' => $status,
                'info' => $qInfo,
                'submission' => $submission
            ];
        }
        
        // Check if user can submit for current quarter
        // Rule: If there are missed quarters, user cannot submit for current quarter
        if ($hasMissedQuarters && $quarterInfo->is_current) {
            $canSubmitCurrentQuarter = false;
        }
        
        // For form-specific checks
        $isSelected = (!$isManualSelection && $selectedForm);
        $isDisabled = ($isManualSelection === false && $selectedForm !== null && $selectedForm !== $selectedForm);
        
        // Now also check if user can submit based on quarter rules
        if (!$canSubmitCurrentQuarter && $quarterInfo->is_current) {
            $isDisabled = true;
        }
        
        // Calculate submission statistics
        $totalQuarters = count($allQuarters);
        $completedCount = count($completedQuarters);
        $missedCount = count($missedQuarters);
        $submissionRate = $totalQuarters > 0 ? ($completedCount / $totalQuarters) * 100 : 0;
    @endphp

    <!-- Updated Header with both logos -->
    <header class="gradient-header text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo Section with both logos -->
                <div class="flex items-center space-x-4">
                    <!-- Dual Logo Container -->
                    <div class="logo-container">
                        <div class="logo-inner">
                            <div class="flex items-center space-x-3">
                                <!-- MOIC Logo -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="h-7 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22%23110484%22>MOIC</text></svg>';">
                                    </div>
                                    <span class="status-badge bg-[#110484] text-white">MOIC</span>
                                </div>
                                
                                <!-- Partnership Badge -->
                                <div class="relative">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                                        <i class="fas fa-handshake text-white text-sm"></i>
                                    </div>
                                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                        <span class="status-badge bg-white text-[#110484]">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <!-- TKC Logo -->
                                <div class="flex flex-col items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="h-7 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2220%22 fill=%22%23e7581c%22>TKC</text></svg>';">
                                    </div>
                                    <span class="status-badge bg-[#e7581c] text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appraisal Forms Title -->
                    <div class="flex items-center">
                        <div class="h-8 w-[1px] bg-white/30 mx-4"></div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">Appraisal Forms</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">
                                <i class="fas fa-user mr-1"></i>
                                {{ htmlspecialchars($userJobTitle) }}
                                <span class="ml-3">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $quarterInfo->quarter }} {{ $quarterInfo->year }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="flex items-center space-x-4">
                    <!-- User Info -->
                    <div class="hidden md:flex flex-col items-end">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <span class="text-blue-200 text-sm">{{ Auth::user()->job_title ?? 'Employee' }}</span>
                    </div>
                    
                    <!-- Dashboard Button -->
                    <a href="{{ route('dashboard') }}" 
                       class="bg-white text-[#110484] px-3 py-1.5 rounded text-sm hover:bg-gray-100 transition duration-200 font-medium">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Submission Progress Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-[#110484]">Quarterly Submission Progress - {{ $currentYear }}</h2>
                <div class="text-right">
                    <span class="text-2xl font-bold">{{ $completedCount }}/{{ $totalQuarters }}</span>
                    <span class="text-gray-600 text-sm ml-1">Quarters Completed</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Submission Rate</span>
                    <span class="text-sm font-bold {{ $submissionRate >= 75 ? 'text-green-600' : ($submissionRate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ round($submissionRate) }}%
                    </span>
                </div>
                <div class="progress-bar bg-gray-200">
                    <div class="progress-fill {{ $submissionRate >= 75 ? 'bg-green-500' : ($submissionRate >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                         style="width: {{ $submissionRate }}%"></div>
                </div>
            </div>
            
            <!-- Quarter Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($quarterStatuses as $quarter => $statusData)
                    @php
                        $statusClass = '';
                        $statusIcon = '';
                        $statusText = '';
                        
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
                            case 'future':
                                $statusClass = 'quarter-future';
                                $statusIcon = 'fa-calendar-alt';
                                $statusText = 'Upcoming';
                                break;
                        }
                    @endphp
                    
                    <div class="border rounded-lg p-4 {{ $statusClass }}">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-lg">{{ $quarter }}</span>
                            <i class="fas {{ $statusIcon }} text-lg"></i>
                        </div>
                        <p class="text-sm mb-1">{{ $statusData['info']->quarter_months }}</p>
                        <p class="text-xs font-medium">{{ $statusText }}</p>
                        @if($statusData['status'] === 'completed' && $statusData['submission'])
                            <p class="text-xs mt-1">
                                Submitted: {{ \Carbon\Carbon::parse($statusData['submission']->created_at)->format('M d, Y') }}
                            </p>
                        @elseif($statusData['status'] === 'missed')
                            <p class="text-xs mt-1 font-semibold">Deadline: {{ $statusData['info']->due_date }}</p>
                        @elseif($statusData['status'] === 'current')
                            <p class="text-xs mt-1 font-semibold">Due: {{ $statusData['info']->due_date }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Submission Rules Notice -->
            @if($hasMissedQuarters && $quarterInfo->is_current && !$canSubmitCurrentQuarter)
                <div class="mt-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 px-4 py-3 rounded flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-500 text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-1">Submission Blocked</p>
                        <p class="text-sm">
                            You have missed submissions for 
                            @foreach($missedQuarters as $index => $quarter)
                                {{ $quarter }}{{ $index < count($missedQuarters) - 1 ? ', ' : '' }}
                            @endforeach.
                            You must complete all missed quarters before submitting for {{ $quarterInfo->quarter }}.
                        </p>
                        <p class="text-xs mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Contact your supervisor or HR to request late submission for missed quarters.
                        </p>
                    </div>
                </div>
            @elseif($hasMissedQuarters && !$quarterInfo->is_current)
                <div class="mt-6 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 text-yellow-500 text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-1">Missed Quarters</p>
                        <p class="text-sm">
                            You have missed submissions for 
                            @foreach($missedQuarters as $index => $quarter)
                                {{ $quarter }}{{ $index < count($missedQuarters) - 1 ? ', ' : '' }}
                            @endforeach.
                            These will be marked as "Missed" in your appraisal history.
                        </p>
                    </div>
                </div>
            @elseif($completedCount === $totalQuarters)
                <div class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-4 py-3 rounded flex items-start">
                    <i class="fas fa-check-circle mr-3 text-green-500 text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-1">Excellent! All Quarters Completed</p>
                        <p class="text-sm">
                            You have successfully submitted appraisals for all quarters in {{ $currentYear }}.
                            Keep up the great work!
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-[#110484] mb-2">
                        @if($isManualSelection)
                            Select Your Appraisal Form
                        @else
                            Your Appraisal Form
                        @endif
                    </h2>
                    <div class="flex flex-wrap gap-4">
                        <p class="text-gray-600">
                            <i class="fas fa-calendar-alt mr-1"></i> 
                            Current: <span class="font-semibold">{{ $quarterInfo->quarter_name }} ({{ $quarterInfo->quarter_months }} {{ $quarterInfo->year }})</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-clock mr-1"></i> Deadline: <span class="font-semibold text-[#e7581c]">{{ $quarterInfo->due_date }}</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-calendar-check mr-1"></i> Today: <span class="font-semibold">{{ date('M d, Y') }}</span>
                        </p>
                    </div>
                    
                    <!-- Additional info -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($isManualSelection)
                            <span class="inline-flex items-center bg-gradient-to-r from-yellow-100 to-amber-100 text-[#e7581c] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-exchange-alt mr-1"></i> Manual Selection Mode
                            </span>
                        @else
                            <span class="inline-flex items-center bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-user-tie mr-1"></i> Auto-detected: {{ htmlspecialchars($userJobTitle) }}
                            </span>
                        @endif
                        
                        <span class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-file-alt mr-1"></i> 12 forms available <!-- Updated to 12 -->
                        </span>
                        
                        @if($selectedForm && !$isManualSelection)
                            <span class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Form auto-selected
                            </span>
                        @endif
                        
                        <span class="inline-flex items-center bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-clock mr-1"></i> {{ $quarterInfo->quarter }} {{ $quarterInfo->year }}
                        </span>
                        
                        @if($currentQuarterSubmission)
                            <span class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-paper-plane mr-1"></i> Already Submitted
                            </span>
                        @elseif($canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <span class="inline-flex items-center bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-edit mr-1"></i> Ready to Submit
                            </span>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <span class="inline-flex items-center bg-gradient-to-r from-red-100 to-rose-100 text-red-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-lock mr-1"></i> Submission Blocked
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex space-x-3">
                    @if(!$isManualSelection && $selectedForm)
                        <button onclick="enableManualSelection()" 
                           class="inline-flex items-center bg-gradient-to-r from-yellow-500 to-amber-600 text-white px-4 py-2 rounded hover:shadow transition duration-200 font-medium">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Change Form
                        </button>
                    @endif
                    
                    <a href="{{ route('appraisals.index') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-4 py-2 rounded hover:shadow transition duration-200 font-medium">
                        <i class="fas fa-list mr-2"></i>
                        My Appraisals
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-2 rounded mr-3">
                        <i class="fas fa-file-alt text-[#110484]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Forms</p>
                        <p class="text-xl font-bold text-gray-800">12</p> <!-- Updated to 12 -->
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-2 rounded mr-3">
                        <i class="fas fa-calendar-check text-green-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Current Quarter</p>
                        <p class="text-xl font-bold text-gray-800">{{ $quarterInfo->quarter }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-2 rounded mr-3">
                        <i class="fas fa-clock text-[#e7581c]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Deadline</p>
                        <p class="text-xl font-bold text-gray-800">{{ $quarterInfo->due_date }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-50 to-violet-50 p-2 rounded mr-3">
                        <i class="fas fa-user text-purple-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Your Job</p>
                        <p class="text-xl font-bold text-gray-800 truncate" title="{{ $userJobTitle }}">
                            {{ Str::limit($userJobTitle, 15) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Mode Notice -->
        @if($isManualSelection)
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-exchange-alt mr-3 text-amber-500 text-xl"></i>
                <div>
                    <p class="font-medium">Manual Selection Mode Active</p>
                    <p class="text-sm opacity-90">All forms are now enabled. Please select the appropriate form for your current role.</p>
                </div>
            </div>
        @elseif($selectedForm)
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3 text-emerald-500 text-xl"></i>
                <div>
                    <p class="font-medium">Form Auto-Selected</p>
                    <p class="text-sm opacity-90">Based on your job title "{{ $userJobTitle }}", the appropriate form has been selected for you.</p>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-800 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500 text-xl"></i>
                <div>
                    <p class="font-medium">No Matching Form Found</p>
                    <p class="text-sm opacity-90">Your job title "{{ $userJobTitle }}" doesn't match any standard forms. Please select from the list below.</p>
                </div>
            </div>
        @endif

        <!-- Session Messages -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-500"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Forms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="formsGrid">
            
            <!-- Plaza Manager -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'plaza-manager');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'plaza-manager') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="plaza-manager">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 text-[#110484] mr-3">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Plaza Manager</h3>
                                <span class="text-xs text-gray-500">Management</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Oversee plaza operations and staff management</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.plaza-manager') }}" 
                           class="block w-full bg-gradient-to-r from-[#110484] to-[#1a0c9e] hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Admin Clerk -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'admin-clerk');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'admin-clerk') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="admin-clerk">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-purple-50 to-violet-50 text-purple-600 mr-3">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Admin Clerk</h3>
                                <span class="text-xs text-gray-500">Admin</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-purple-100 to-violet-100 text-purple-800 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Administrative tasks and document management</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.admin-clerk') }}" 
                           class="block w-full bg-gradient-to-r from-purple-500 to-violet-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- E&M Technician -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'em-technician');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'em-technician') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="em-technician">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 text-green-600 mr-3">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">E&M Technician</h3>
                                <span class="text-xs text-gray-500">Technical</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Electrical and mechanical maintenance</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.em-technician') }}" 
                           class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Shift Manager -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'shift-manager');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'shift-manager') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="shift-manager">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-orange-50 to-amber-50 text-[#e7581c] mr-3">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Shift Manager</h3>
                                <span class="text-xs text-gray-500">Supervisory</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-orange-100 to-amber-100 text-[#e7581c] text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Shift operations and staff coordination</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.shift-manager') }}" 
                           class="block w-full bg-gradient-to-r from-orange-500 to-amber-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Senior Toll Collector -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'senior-toll-collector');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'senior-toll-collector') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="senior-toll-collector">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-teal-50 to-cyan-50 text-teal-600 mr-3">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Senior Toll Collector</h3>
                                <span class="text-xs text-gray-500">Senior</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-teal-100 to-cyan-100 text-teal-800 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Toll collection with team support</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.senior-toll-collector') }}" 
                           class="block w-full bg-gradient-to-r from-teal-500 to-cyan-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Toll Collector -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'toll-collector');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'toll-collector') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="toll-collector">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-blue-50 to-sky-50 text-blue-700 mr-3">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Toll Collector</h3>
                                <span class="text-xs text-gray-500">Frontline</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-blue-100 to-sky-100 text-blue-700 text-xs px-2 py-1 rounded font-medium mb-1">7 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Frontline toll collection and service</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.toll-collector') }}" 
                           class="block w-full bg-gradient-to-r from-blue-400 to-sky-500 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- TCE Technician -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'tce');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'tce') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="tce">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-cyan-50 to-sky-50 text-cyan-600 mr-3">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">TCE Technician</h3>
                                <span class="text-xs text-gray-500">Technical</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-cyan-100 to-sky-100 text-cyan-800 text-xs px-2 py-1 rounded font-medium mb-1">7 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Technical team and equipment oversight</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.tce') }}" 
                           class="block w-full bg-gradient-to-r from-cyan-500 to-sky-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Route Patrol Driver -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'route-patrol-driver');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'route-patrol-driver') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="route-patrol-driver">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-600 mr-3">
                                <i class="fas fa-truck-pickup"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Route Patrol Driver</h3>
                                <span class="text-xs text-gray-500">Support</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Route patrols and vehicle maintenance</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.route-patrol-driver') }}" 
                           class="block w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Plaza Attendant -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'plaza-attendant');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'plaza-attendant') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="plaza-attendant">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-green-50 to-lime-50 text-green-700 mr-3">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Plaza Attendant</h3>
                                <span class="text-xs text-gray-500">Support</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-green-100 to-lime-100 text-green-700 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Plaza cleaning and maintenance</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.plaza-attendant') }}" 
                           class="block w-full bg-gradient-to-r from-green-400 to-lime-500 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- Lane Attendant -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'lane-attendant');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'lane-attendant') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="lane-attendant">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-lime-50 to-green-50 text-lime-600 mr-3">
                                <i class="fas fa-tree"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">Lane Attendant</h3>
                                <span class="text-xs text-gray-500">Support</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-lime-100 to-green-100 text-lime-800 text-xs px-2 py-1 rounded font-medium mb-1">8 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Lane cleaning and landscaping</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.lane-attendant') }}" 
                           class="block w-full bg-gradient-to-r from-lime-500 to-green-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- HR Assistant -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'hr-assistant');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'hr-assistant') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="hr-assistant">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-600 mr-3">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">HR Assistant</h3>
                                <span class="text-xs text-gray-500">Admin</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 text-xs px-2 py-1 rounded font-medium mb-1">10 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Payroll and HR administration</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.hr-assistant') }}" 
                           class="block w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>

            <!-- HR Advisor -->
            @php
                $isSelected = (!$isManualSelection && $selectedForm === 'hr-advisor');
                $isDisabled = ($isManualSelection === false && $selectedForm !== 'hr-advisor') || 
                              (!$canSubmitCurrentQuarter && $quarterInfo->is_current) ||
                              ($currentQuarterSubmission !== null);
            @endphp
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200 
                        {{ $isSelected ? 'form-card-selected' : '' }} 
                        {{ $isDisabled ? 'form-card-disabled' : '' }}"
                 data-form="hr-advisor">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-pink-50 to-rose-50 text-pink-600 mr-3">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">DBK HR Advisor</h3>
                                <span class="text-xs text-gray-500">HR</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-gradient-to-r from-pink-100 to-rose-100 text-pink-800 text-xs px-2 py-1 rounded font-medium mb-1">10 KPAs</span>
                            @if($isSelected)
                                <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-2 py-1 rounded font-medium">
                                    <i class="fas fa-check mr-1"></i> Selected
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Recruitment, induction, and employee relations</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="far fa-clock mr-1"></i> Due: {{ $quarterInfo->due_date }}</span>
                        @if($currentQuarterSubmission)
                            <span class="text-green-600">
                                <i class="fas fa-check mr-1"></i> Submitted
                            </span>
                        @endif
                    </div>
                    @if($isDisabled)
                        @if($currentQuarterSubmission)
                            <button class="block w-full bg-gradient-to-r from-green-400 to-emerald-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-check mr-2"></i> Already Submitted
                            </button>
                        @elseif(!$canSubmitCurrentQuarter && $quarterInfo->is_current)
                            <button class="block w-full bg-gradient-to-r from-red-400 to-rose-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Submission Blocked
                            </button>
                        @else
                            <button class="block w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white text-center py-2 rounded font-medium cursor-not-allowed" disabled>
                                <i class="fas fa-lock mr-2"></i> Not Available
                            </button>
                        @endif
                    @else
                        <a href="{{ route('appraisals.hr-advisor') }}" 
                           class="block w-full bg-gradient-to-r from-pink-500 to-rose-600 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                           <i class="fas fa-play-circle mr-2"></i> 
                           @if($isSelected) Start Appraisal @else Select Form @endif
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- View Appraisals -->
            <div class="form-card bg-white rounded-lg shadow border border-gray-200 hover:shadow-md transition duration-200">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-r from-gray-50 to-slate-50 text-gray-600 mr-3">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#110484]">My Appraisals</h3>
                                <span class="text-xs text-gray-500">Manage</span>
                            </div>
                        </div>
                        <span class="bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 text-xs px-2 py-1 rounded font-medium">View All</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">View and manage existing appraisals</p>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span><i class="fas fa-history mr-1"></i> All Status</span>
                        <span><i class="fas fa-cog mr-1"></i> Manage</span>
                    </div>
                    <a href="{{ route('appraisals.index') }}" 
                       class="block w-full bg-gradient-to-r from-gray-600 to-slate-700 hover:shadow text-white text-center py-2 rounded font-medium transition duration-200">
                       <i class="fas fa-eye mr-2"></i> View Appraisals
                    </a>
                </div>
            </div>
        </div>

       
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- Dual Logos in footer -->
                    <div class="logo-container">
                        <div class="logo-inner">
                            <div class="flex items-center space-x-2">
                                <!-- MOIC Logo -->
                                <div class="bg-white rounded p-0.5">
                                    <img class="h-5 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23110484%22>MOIC</text></svg>';">
                                </div>
                                
                                <!-- Partnership Symbol -->
                                <div class="text-xs text-[#110484] font-bold">+</div>
                                
                                <!-- TKC Logo -->
                                <div class="bg-white rounded p-0.5">
                                    <img class="h-5 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23e7581c%22>TKC</text></svg>';">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Performance Appraisal System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Current Quarter: {{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('appraisals.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-list mr-1"></i> My Appraisals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to enable manual selection mode
        function enableManualSelection() {
            // Add query parameter to enable manual selection
            window.location.href = window.location.pathname + '?manual_select=true';
        }

        // Add click handlers for manual selection mode
        document.addEventListener('DOMContentLoaded', function() {
            const formCards = document.querySelectorAll('.form-card');
            const isManualSelection = {{ $isManualSelection ? 'true' : 'false' }};
            
            if (isManualSelection) {
                formCards.forEach(card => {
                    card.addEventListener('click', function(e) {
                        // Don't trigger if clicking on a link or disabled button
                        if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
                            return;
                        }
                        
                        // Get the link inside the card
                        const link = this.querySelector('a');
                        if (link && !link.classList.contains('cursor-not-allowed')) {
                            window.location.href = link.href;
                        }
                    });
                });
            }
            
            // Show submission rules modal if user has missed quarters
            @if($hasMissedQuarters && $quarterInfo->is_current && !$canSubmitCurrentQuarter)
                setTimeout(() => {
                    alert('Submission Blocked: You have missed submissions for previous quarters. Please complete all missed quarters before submitting for the current quarter. Contact your supervisor or HR for assistance.');
                }, 1000);
            @endif
        });
    </script>
</body>
</html>