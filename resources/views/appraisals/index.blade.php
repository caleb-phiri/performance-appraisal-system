<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisals - MOIC Performance Appraisal System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header with MOIC Gradient -->
    <header class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <!-- MOIC Logo with white background -->
                    <div class="bg-white p-1.5 rounded-md mr-3">
                        <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Performance Appraisals</h1>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-user mr-1"></i>
                            {{ auth()->user()->user_type === 'supervisor' ? 'Supervisor' : 'Employee' }}: 
                            <span class="font-semibold">{{ auth()->user()->name ?? 'User' }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-id-badge mr-1"></i>
                            ID: <span class="font-mono font-semibold">{{ auth()->user()->employee_number ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-white">{{ auth()->user()->name ?? 'User' }}</span>
                    <a href="{{ route('dashboard') }}" class="bg-white text-[#110484] px-4 py-2 rounded hover:bg-gray-100 transition font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Supervisor Employee Selection -->
            @if(auth()->user()->user_type === 'supervisor')
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-[#110484] text-white p-2 rounded mr-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-[#110484]">My Assigned Employees</h2>
                                <p class="text-sm text-gray-600">View appraisals for employees assigned to you (primary or secondary)</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('appraisals.index') }}" class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-4 py-2 rounded hover:shadow transition text-sm font-medium">
                                All Assigned Employees
                            </a>
                        </div>
                    </div>
                    
                    <!-- Employee Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Employee:</label>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('appraisals.index') }}" 
                               class="px-3 py-1 rounded font-medium {{ !request()->has('employee_number') ? 'bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                All Employees
                            </a>
                            @foreach($teamMembers ?? [] as $member)
                            @php
                                // Get supervisor relationship info
                                $isPrimary = false;
                                foreach($member->ratingSupervisors as $supervisor) {
                                    if ($supervisor->employee_number === auth()->user()->employee_number) {
                                        $isPrimary = $supervisor->pivot->is_primary ?? false;
                                        break;
                                    }
                                }
                            @endphp
                            <a href="{{ route('appraisals.index', ['employee_number' => $member->employee_number]) }}" 
                               class="px-3 py-1 rounded font-medium {{ request('employee_number') == $member->employee_number ? 'bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                {{ $member->name }} ({{ $member->employee_number }})
                                @if($isPrimary)
                                    <span class="ml-1 text-xs bg-purple-100 text-purple-800 px-1 rounded">Primary</span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Currently Viewing -->
                    @if(request()->has('employee_number') && $employee)
                    <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-[#110484]">
                            <i class="fas fa-eye mr-2"></i>
                            Currently viewing appraisals for: 
                            <span class="font-semibold">{{ $employee->name }}</span> 
                            ({{ $employee->employee_number }})
                            @php
                                $currentSupervisorRole = 'Secondary Supervisor';
                                foreach($employee->ratingSupervisors as $supervisor) {
                                    if ($supervisor->employee_number === auth()->user()->employee_number && $supervisor->pivot->is_primary) {
                                        $currentSupervisorRole = 'Primary Supervisor';
                                        break;
                                    }
                                }
                            @endphp
                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs rounded">{{ $currentSupervisorRole }}</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Employee Profile Card (for individual view) -->
            @if((auth()->user()->user_type === 'employee' && !request()->has('employee_number')) || (auth()->user()->user_type === 'supervisor' && request()->has('employee_number')))
            <div class="mb-6">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 p-4 mr-4">
                            <i class="fas fa-user text-[#110484] text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#110484]">{{ $employee->name ?? auth()->user()->name }}</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                <div>
                                    <p class="text-sm text-gray-500">Employee ID</p>
                                    <p class="font-semibold text-gray-800">{{ $employee->employee_number ?? auth()->user()->employee_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Department</p>
                                    <p class="font-semibold text-gray-800">{{ $employee->department ?? auth()->user()->department ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Job Title</p>
                                    <p class="font-semibold text-gray-800">{{ $employee->job_title ?? auth()->user()->job_title ?? 'Not specified' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Assigned Supervisors</p>
                                    <p class="font-semibold text-gray-800">
                                        @php
                                            $supervisorCount = $employee->ratingSupervisors->count() ?? 0;
                                            echo $supervisorCount . ' supervisor' . ($supervisorCount !== 1 ? 's' : '');
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-500"></i> {{ session('success') }}
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-4 py-3 rounded flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-500"></i> {{ session('error') }}
            </div>
            @endif

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 p-3 mr-3">
                            <i class="fas fa-file-alt text-[#110484] text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Appraisals</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $appraisals->total() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gradient-to-r from-green-50 to-emerald-50 p-3 mr-3">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Submitted</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $submittedCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gradient-to-r from-yellow-50 to-amber-50 p-3 mr-3">
                            <i class="fas fa-edit text-[#e7581c] text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Drafts</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $draftCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gradient-to-r from-purple-50 to-violet-50 p-3 mr-3">
                            <i class="fas fa-star text-purple-500 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Avg Score</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ number_format($averageScore ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appraisal Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex justify-between items-center">
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
                            <h2 class="text-xl font-bold text-[#110484]">{{ $title }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                            
                            @if(auth()->user()->user_type === 'supervisor' && request()->has('employee_number'))
                            <p class="text-xs text-[#110484] mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
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
                        <a href="{{ route('appraisals.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded hover:shadow transition font-medium">
                            <i class="fas fa-plus mr-2"></i> Create New Appraisal
                        </a>
                        @endif
                    </div>
                </div>

                @if($appraisals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white">
                            <tr>
                                @if(auth()->user()->user_type === 'supervisor' && !request()->has('employee_number'))
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i> Employee
                                </th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1"></i> ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-1"></i> Period
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-clock mr-1"></i> Duration
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i> Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-chart-line mr-1"></i> Self Score
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-star mr-1"></i> Final Score
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-user-tie mr-1"></i> Supervisor Ratings
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-calendar-plus mr-1"></i> Created
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-1"></i> Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appraisals as $appraisal)
                            @php
                                $currentUserEmployeeNumber = auth()->user()->employee_number ?? null;
                                $appraisalEmployeeNumber = $appraisal->employee_number ?? null;
                                $status = $appraisal->status ?? 'draft';
                                
                                // Check if appraisal has KPAs loaded
                                $hasKpasLoaded = isset($appraisal->kpas) && $appraisal->kpas->count() > 0;
                                
                                // Initialize scores
                                $calculatedSelfScore = 0;
                                $calculatedFinalScore = 0;
                                $hasSupervisorRatings = false;
                                $currentUserHasRated = false;
                                $ratedSupervisorsCount = 0;
                                $totalSupervisorsCount = 0;
                                
                                // Get employee data
                                $employee = $appraisal->user ?? null;
                                if ($employee) {
                                    $totalSupervisorsCount = $employee->ratingSupervisors->count() ?? 0;
                                    
                                    // Check if current user has rated
                                    if ($hasKpasLoaded && $currentUserEmployeeNumber) {
                                        foreach ($appraisal->kpas as $kpa) {
                                            if ($kpa->hasSupervisorRating($currentUserEmployeeNumber)) {
                                                $currentUserHasRated = true;
                                                break;
                                            }
                                        }
                                        
                                        // Count rated supervisors
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
                                
                                // Calculate scores from KPAs if available
                                if ($hasKpasLoaded) {
                                    $totalWeight = 0;
                                    $selfWeightedSum = 0;
                                    $finalWeightedSum = 0;
                                    
                                    foreach ($appraisal->kpas as $kpa) {
                                        $weight = $kpa->weight ?? 0;
                                        $totalWeight += $weight;
                                        
                                        // Get ratings
                                        $selfRating = $kpa->self_rating ?? 0;
                                        $supervisorRating = $kpa->supervisor_rating ?? 0;
                                        
                                        // KPI scale (default to 4)
                                        $kpi = $kpa->kpi ?? 4;
                                        if ($kpi == 0) $kpi = 4; // Avoid division by zero
                                        
                                        // Calculate self score contribution
                                        if ($selfRating > 0) {
                                            $selfPercentage = ($selfRating / $kpi) * 100;
                                            $selfWeightedSum += ($selfPercentage * $weight) / 100;
                                        }
                                        
                                        // Calculate final score contribution - handle multiple supervisors
                                        $finalRating = $kpa->getFinalSupervisorRatingAttribute() ?? $selfRating;
                                        if ($finalRating > 0) {
                                            $finalPercentage = ($finalRating / $kpi) * 100;
                                            $finalWeightedSum += ($finalPercentage * $weight) / 100;
                                        }
                                        
                                        // Check if has supervisor ratings
                                        if ($supervisorRating > 0 || $kpa->ratedSupervisors()->count() > 0) {
                                            $hasSupervisorRatings = true;
                                        }
                                    }
                                    
                                    // Calculate average scores if we have weight
                                    if ($totalWeight > 0) {
                                        $calculatedSelfScore = $selfWeightedSum > 0 ? min(100, max(0, round($selfWeightedSum, 2))) : 0;
                                        $calculatedFinalScore = $finalWeightedSum > 0 ? min(100, max(0, round($finalWeightedSum, 2))) : 0;
                                    }
                                }
                                
                                // Use database scores if available, otherwise use calculated scores
                                $selfScore = $appraisal->self_score > 0 ? (float) $appraisal->self_score : $calculatedSelfScore;
                                $finalScore = $appraisal->final_score > 0 ? (float) $appraisal->final_score : $calculatedFinalScore;
                                
                                // Determine what to display for final score
                                $displayFinalScore = false;
                                $displayAwaitingRating = false;
                                
                                if (in_array($status, ['approved', 'completed', 'in_review', 'submitted'])) {
                                    if ($hasSupervisorRatings || $finalScore > 0) {
                                        $displayFinalScore = true;
                                    } else {
                                        $displayAwaitingRating = true;
                                    }
                                }
                                
                                // Colors for scores
                                $selfScoreColor = $selfScore >= 70 ? 'text-green-600' : ($selfScore >= 50 ? 'text-yellow-600' : 'text-red-600');
                                $finalScoreColor = $finalScore >= 70 ? 'text-green-600' : ($finalScore >= 50 ? 'text-yellow-600' : 'text-red-600');
                                
                                // Get employee name for supervisor view
                                $employeeName = $appraisal->employee_name ?? 'N/A';
                                
                                // Check if current user is assigned supervisor for this appraisal
                                $isAssignedSupervisor = false;
                                if (auth()->user()->user_type === 'supervisor' && $employee) {
                                    foreach ($employee->ratingSupervisors as $supervisor) {
                                        if ($supervisor->employee_number === auth()->user()->employee_number) {
                                            $isAssignedSupervisor = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                @if(auth()->user()->user_type === 'supervisor' && !request()->has('employee_number'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-[#110484]"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employeeName }}</div>
                                            <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                            <a href="{{ route('appraisals.index', ['employee_number' => $appraisal->employee_number]) }}" 
                                               class="text-xs text-[#110484] hover:text-[#e7581c] font-medium">
                                                View all appraisals →
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-mono font-medium text-[#110484]">#{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-[#110484]">{{ $appraisal->period }}</div>
                                    <div class="text-xs text-gray-500">{{ $appraisal->appraisal_type ?? 'Annual' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            try {
                                                $startDate = \Carbon\Carbon::parse($appraisal->start_date);
                                                $endDate = \Carbon\Carbon::parse($appraisal->end_date);
                                                echo $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
                                            } catch (\Exception $e) {
                                                echo 'Invalid date';
                                            }
                                        @endphp
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-yellow-100 text-yellow-800',
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'in_review' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'archived' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $status = $appraisal->status ?? 'draft';
                                        $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        <i class="fas fa-circle text-xs mr-1 mt-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                            <div class="bg-gradient-to-r from-green-400 to-green-500 h-2.5 rounded-full" style="width: {{ min($selfScore, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $selfScoreColor }}">
                                            {{ number_format($selfScore, 1) }}%
                                        </span>
                                        @if($selfScore == 0 && $hasKpasLoaded)
                                            <span class="text-xs text-gray-400 ml-2">(Not rated)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($displayFinalScore && $finalScore > 0)
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                            <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] h-2.5 rounded-full" style="width: {{ min($finalScore, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $finalScoreColor }}">
                                            {{ number_format($finalScore, 1) }}%
                                        </span>
                                        @if($hasSupervisorRatings)
                                            <span class="text-xs text-green-600 ml-2">(Rated)</span>
                                        @endif
                                    </div>
                                    @elseif($displayAwaitingRating)
                                    <div class="flex items-center text-[#110484]">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="text-sm font-medium">Awaiting Rating</span>
                                    </div>
                                    @else
                                    <div class="flex items-center text-gray-500">
                                        @switch($status)
                                            @case('draft')
                                                <i class="fas fa-edit mr-2"></i>
                                                <span class="text-sm">Draft</span>
                                                @break
                                            @case('submitted')
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                <span class="text-sm">Submitted</span>
                                                @break
                                            @case('in_review')
                                                <i class="fas fa-search mr-2"></i>
                                                <span class="text-sm">In Review</span>
                                                @break
                                            @case('rejected')
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <span class="text-sm">Rejected</span>
                                                @break
                                            @default
                                                <i class="fas fa-minus-circle mr-2"></i>
                                                <span class="text-sm">Not Rated</span>
                                        @endswitch
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($totalSupervisorsCount > 0)
                                        <div class="text-sm min-w-[200px]">
                                            <!-- Supervisor Count Badge -->
                                            <div class="mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                          {{ $ratedSupervisorsCount > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    <i class="fas fa-users mr-1 text-xs"></i>
                                                    {{ $ratedSupervisorsCount }}/{{ $totalSupervisorsCount }} rated
                                                </span>
                                            </div>
                                            
                                            <!-- Supervisor Names List -->
                                            @php
                                                $supervisors = $employee->ratingSupervisors ?? collect();
                                            @endphp
                                            
                                            @if($supervisors->count() > 0)
                                                <div class="space-y-1">
                                                    @foreach($supervisors as $supervisor)
                                                        @php
                                                            $isPrimary = $supervisor->pivot->is_primary ?? false;
                                                            $supervisorHasRated = false;
                                                            
                                                            // Check if this supervisor has rated
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
                                                        
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center">
                                                                <span class="{{ $isCurrentUser ? 'font-semibold text-blue-600' : 'text-gray-700' }} {{ $supervisorHasRated ? 'text-green-600' : '' }} text-xs truncate max-w-[120px]">
                                                                    {{ $supervisor->name }}
                                                                </span>
                                                                @if($isPrimary)
                                                                    <span class="ml-1 text-xs bg-purple-100 text-purple-800 px-1 py-0.5 rounded">P</span>
                                                                @endif
                                                                @if($isCurrentUser)
                                                                    <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1 py-0.5 rounded">You</span>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                @if($supervisorHasRated)
                                                                    <span class="text-xs text-green-600">
                                                                        <i class="fas fa-check-circle"></i>
                                                                    </span>
                                                                @elseif(in_array($status, ['submitted', 'in_review']))
                                                                    <span class="text-xs text-yellow-600">
                                                                        <i class="fas fa-clock"></i>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <!-- Current User Status -->
                                            @if(auth()->user()->user_type === 'supervisor' && $isAssignedSupervisor)
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <div class="text-xs">
                                                        @if($currentUserHasRated)
                                                            <span class="text-green-600">
                                                                <i class="fas fa-check-circle mr-1"></i>You have rated this appraisal
                                                            </span>
                                                        @elseif(in_array($status, ['submitted', 'in_review']))
                                                            <span class="text-yellow-600">
                                                                <i class="fas fa-star mr-1"></i>Your rating required
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Fallback to manager if no rating supervisors -->
                                        @if($employee && $employee->manager)
                                            <div class="text-sm">
                                                <div class="text-xs font-medium text-gray-500 mb-1">Direct Manager:</div>
                                                <div class="flex items-center">
                                                    <span class="font-medium text-gray-700 text-sm">{{ $employee->manager->name }}</span>
                                                    <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">Manager</span>
                                                </div>
                                                @if($hasSupervisorRatings)
                                                    <span class="text-xs text-green-600 mt-1">
                                                        <i class="fas fa-check-circle mr-1"></i>Rated
                                                    </span>
                                                @elseif(in_array($status, ['submitted', 'in_review']))
                                                    <span class="text-xs text-yellow-600 mt-1">
                                                        <i class="fas fa-clock mr-1"></i>Awaiting rating
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No supervisors assigned</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            try {
                                                $createdDate = \Carbon\Carbon::parse($appraisal->created_at);
                                                echo $createdDate->format('M d, Y');
                                                echo '<div class="text-xs text-gray-500">' . $createdDate->format('h:i A') . '</div>';
                                            } catch (\Exception $e) {
                                                echo 'Invalid date';
                                            }
                                        @endphp
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <!-- View Button -->
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                           class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit Button (only for drafts and own appraisals) -->
                                        @if(($status === 'draft') && $currentUserEmployeeNumber && $appraisalEmployeeNumber && $currentUserEmployeeNumber == $appraisalEmployeeNumber)
                                        <a href="{{ route('appraisals.edit', $appraisal->id) }}" 
                                           class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        
                                        <!-- Submit Button (only for drafts and own appraisals) -->
                                        @if(($status === 'draft') && $currentUserEmployeeNumber && $appraisalEmployeeNumber && $currentUserEmployeeNumber == $appraisalEmployeeNumber)
                                        <form action="{{ route('appraisals.submit', $appraisal->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-purple-50 text-purple-600 p-2 rounded hover:bg-purple-100 transition"
                                                    title="Submit"
                                                    onclick="return confirm('Are you sure you want to submit this appraisal?')">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Supervisor Rate Button (for submitted appraisals) -->
                                        @if(auth()->user()->user_type === 'supervisor' && $isAssignedSupervisor && in_array($status, ['submitted', 'in_review']) && $hasKpasLoaded && !$currentUserHasRated)
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                           class="bg-yellow-50 text-yellow-600 p-2 rounded hover:bg-yellow-100 transition"
                                           title="Rate/Review Appraisal"
                                           onclick="return confirm('Go to appraisal details page to rate this submission?')">
                                            <i class="fas fa-star"></i>
                                        </a>
                                        @endif
                                        
                                        <!-- Delete Button (only for drafts and own appraisals) -->
                                        @if(($status === 'draft') && $currentUserEmployeeNumber && $appraisalEmployeeNumber && $currentUserEmployeeNumber == $appraisalEmployeeNumber)
                                        <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 transition"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this appraisal? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Print Button -->
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}?print=true" 
                                           class="bg-gray-50 text-gray-600 p-2 rounded hover:bg-gray-100 transition"
                                           title="Print"
                                           target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    @if($appraisals->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $appraisals->firstItem() }} to {{ $appraisals->lastItem() }} of {{ $appraisals->total() }} results
                                <span class="text-gray-500 ml-2">(Page {{ $appraisals->currentPage() }} of {{ $appraisals->lastPage() }})</span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <!-- Items per page selector -->
                                <div class="flex items-center text-sm text-gray-700">
                                    <span class="mr-2">Show:</span>
                                    <select onchange="window.location.href = this.value" class="border border-gray-300 rounded px-2 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#110484]">
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
                                    <span class="ml-1">entries</span>
                                </div>
                                
                                <!-- Previous Button -->
                                @if($appraisals->onFirstPage())
                                <button disabled class="px-3 py-1 bg-gray-100 text-gray-400 rounded cursor-not-allowed font-medium text-sm">
                                    <i class="fas fa-chevron-left mr-1"></i> Prev
                                </button>
                                @else
                                <a href="{{ $appraisals->previousPageUrl() . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') }}" 
                                   class="px-3 py-1 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded hover:shadow transition font-medium text-sm flex items-center">
                                    <i class="fas fa-chevron-left mr-1"></i> Prev
                                </a>
                                @endif
                                
                                <!-- Page Numbers -->
                                <div class="flex space-x-1">
                                    @php
                                        $currentPage = $appraisals->currentPage();
                                        $lastPage = $appraisals->lastPage();
                                        $startPage = max(1, $currentPage - 1);
                                        $endPage = min($lastPage, $currentPage + 1);
                                        
                                        if ($startPage > 1) {
                                            echo '<a href="' . $appraisals->url(1) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '" class="px-2 py-1 rounded text-sm font-medium ' . ($currentPage == 1 ? 'bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . '">1</a>';
                                            if ($startPage > 2) {
                                                echo '<span class="px-2 py-1 text-gray-500">...</span>';
                                            }
                                        }
                                        
                                        for ($page = $startPage; $page <= $endPage; $page++) {
                                            echo '<a href="' . $appraisals->url($page) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '" class="px-3 py-1 rounded text-sm font-medium ' . ($currentPage == $page ? 'bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . '">' . $page . '</a>';
                                        }
                                        
                                        if ($endPage < $lastPage) {
                                            if ($endPage < $lastPage - 1) {
                                                echo '<span class="px-2 py-1 text-gray-500">...</span>';
                                            }
                                            echo '<a href="' . $appraisals->url($lastPage) . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') . '" class="px-2 py-1 rounded text-sm font-medium ' . ($currentPage == $lastPage ? 'bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200') . '">' . $lastPage . '</a>';
                                        }
                                    @endphp
                                </div>
                                
                                <!-- Next Button -->
                                @if($appraisals->hasMorePages())
                                <a href="{{ $appraisals->nextPageUrl() . (request()->has('employee_number') ? '&employee_number=' . request('employee_number') : '') . (request()->has('per_page') ? '&per_page=' . request('per_page') : '&per_page=5') }}" 
                                   class="px-3 py-1 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded hover:shadow transition font-medium text-sm flex items-center">
                                    Next <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                                @else
                                <button disabled class="px-3 py-1 bg-gray-100 text-gray-400 rounded cursor-not-allowed font-medium text-sm">
                                    Next <i class="fas fa-chevron-right ml-1"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-16">
                    <div class="inline-block p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full mb-4">
                        <i class="fas fa-file-alt text-[#110484] text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-[#110484] mb-3">No appraisals found</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
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
                    <a href="{{ route('appraisals.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg hover:shadow transition inline-flex items-center text-lg font-medium">
                        <i class="fas fa-plus mr-3"></i> Create Your First Appraisal
                    </a>
                    @endif
                    <p class="text-sm text-gray-400 mt-4">Need help? <a href="#" class="text-[#110484] hover:text-[#e7581c] font-medium">View our guide</a></p>
                </div>
                @endif
            </div>
            
            <!-- Quick Tips -->
            @php
                $currentUserEmployeeNumber = auth()->user()->employee_number ?? null;
                $viewingEmployeeNumber = $employeeNumber ?? $currentUserEmployeeNumber;
                $isViewingOwnProfile = $currentUserEmployeeNumber && $viewingEmployeeNumber && $currentUserEmployeeNumber == $viewingEmployeeNumber;
                $isSupervisorViewingTeam = auth()->user()->user_type === 'supervisor' && !request()->has('employee_number');
            @endphp
            @if($isViewingOwnProfile && !$isSupervisorViewingTeam)
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-white p-3 rounded-full shadow-sm mr-3">
                        <i class="fas fa-lightbulb text-[#110484]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-[#110484]">Quick Tips</h3>
                        <p class="text-sm text-gray-600">Best practices for performance appraisals</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-green-100 p-2 rounded mr-3">
                                <i class="fas fa-check text-green-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Submit on Time</h4>
                        </div>
                        <p class="text-sm text-gray-600">Ensure you submit your appraisals before the deadline to avoid penalties.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-blue-100 p-2 rounded mr-3">
                                <i class="fas fa-edit text-blue-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Save Drafts</h4>
                        </div>
                        <p class="text-sm text-gray-600">Save your work as draft if you need to complete it later. Drafts can be edited anytime.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-purple-100 p-2 rounded mr-3">
                                <i class="fas fa-chart-bar text-purple-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Track Progress</h4>
                        </div>
                        <p class="text-sm text-gray-600">Monitor your scores over time to identify areas for improvement.</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Supervisor Tips -->
            @if(auth()->user()->user_type === 'supervisor' && $isSupervisorViewingTeam)
            <div class="mt-8 bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-white p-3 rounded-full shadow-sm mr-3">
                        <i class="fas fa-user-tie text-[#110484]"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-[#110484]">Multiple Supervisor Guidelines</h3>
                        <p class="text-sm text-gray-600">Guidelines for rating with multiple supervisors</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-purple-100 p-2 rounded mr-3">
                                <i class="fas fa-users text-purple-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Independent Ratings</h4>
                        </div>
                        <p class="text-sm text-gray-600">Each assigned supervisor provides independent ratings. Your rating is saved separately from others.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-blue-100 p-2 rounded mr-3">
                                <i class="fas fa-star text-blue-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Weighted Scoring</h4>
                        </div>
                        <p class="text-sm text-gray-600">Final scores are calculated as weighted averages of all supervisor ratings based on assigned weights.</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <div class="flex items-center mb-2">
                            <div class="fas fa-chart-line text-green-500"></i>
                            </div>
                            <h4 class="font-medium text-[#110484]">Primary Supervisor Role</h4>
                        </div>
                        <p class="text-sm text-gray-600">Primary supervisors can approve appraisals, but all assigned supervisors can rate independently.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- MOIC Logo in footer -->
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Performance Appraisal System &copy; {{ date('Y') }} MOIC</p>
                        <p class="text-xs text-gray-400">Version 1.0.0</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-question-circle mr-1"></i> Help Center
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-shield-alt mr-1"></i> Privacy Policy
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-file-contract mr-1"></i> Terms of Service
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Auto-dismiss success messages after 5 seconds
        setTimeout(() => {
            const successMsg = document.querySelector('[class*="bg-green-100"]');
            if (successMsg) successMsg.style.display = 'none';
        }, 5000);
        
        // Tooltip for truncated supervisor names
        document.addEventListener('DOMContentLoaded', function() {
            const supervisorSpans = document.querySelectorAll('.truncate');
            supervisorSpans.forEach(span => {
                if (span.scrollWidth > span.clientWidth) {
                    span.setAttribute('title', span.textContent);
                    span.style.cursor = 'help';
                }
            });
        });
    </script>
</body>
</html>