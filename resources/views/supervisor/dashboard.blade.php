<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add Chart.js for performance charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.1);
        }
        .performance-bar {
            transition: width 1s ease-in-out;
        }
        .active-tab {
            border-bottom: 3px solid #e7581c !important;
            font-weight: 700 !important;
            color: white !important;
        }
        .tab-badge {
            position: relative;
            top: -2px;
            margin-left: 6px;
        }
        
        /* Enhanced MOIC Colors */
        .moic-navy { color: #110484; }
        .moic-navy-light { color: #2a1d9e; }
        .moic-navy-dark { color: #0a0463; }
        .moic-accent { color: #e7581c; }
        .moic-accent-light { color: #ff6b2c; }
        .moic-accent-dark { color: #cc4a15; }
        
        .bg-moic-navy { background-color: #110484; }
        .bg-moic-navy-light { background-color: #2a1d9e; }
        .bg-moic-navy-dark { background-color: #0a0463; }
        .bg-moic-accent { background-color: #e7581c; }
        .bg-moic-accent-light { background-color: #ff6b2c; }
        .bg-moic-accent-dark { background-color: #cc4a15; }
        
        .border-moic-navy { border-color: #110484; }
        .border-moic-navy-light { border-color: #2a1d9e; }
        .border-moic-accent { border-color: #e7581c; }
        .border-moic-accent-light { border-color: #ff6b2c; }
        
        /* Tab text colors */
        #teamTab span:not(.tab-badge),
        #pendingTab span:not(.tab-badge),
        #performanceTab span:not(.tab-badge),
        #approvedTab span:not(.tab-badge),
        #leaveTab span:not(.tab-badge) {
            color: rgb(219, 234, 254); /* blue-100 */
        }
        
        #teamTab.active-tab span:not(.tab-badge),
        #pendingTab.active-tab span:not(.tab-badge),
        #performanceTab.active-tab span:not(.tab-badge),
        #approvedTab.active-tab span:not(.tab-badge),
        #leaveTab.active-tab span:not(.tab-badge) {
            color: white !important;
            font-weight: 700 !important;
        }
        
        /* Active tab icon styling */
        .active-tab div:first-child {
            background: rgba(255, 255, 255, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Gradient backgrounds */
        .gradient-moic-navy {
            background: linear-gradient(135deg, #0a0463 0%, #110484 50%, #2a1d9e 100%);
        }
        
        .gradient-moic-accent {
            background: linear-gradient(135deg, #cc4a15 0%, #e7581c 50%, #ff6b2c 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        /* Highlight effect for selected member */
        .member-highlight {
            border: 2px solid #e7581c !important;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.15) !important;
        }
        
        /* Modal animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #110484;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #2a1d9e;
        }
        
        /* Form styling */
        .form-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .form-input:focus {
            border-color: #e7581c;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 16px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        .form-select:focus {
            border-color: #e7581c;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
        /* Button styles */
        .btn-primary {
            background: linear-gradient(135deg, #110484 0%, #2a1d9e 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0a0463 0%, #110484 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.2);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #e7581c 0%, #ff6b2c 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, #cc4a15 0%, #e7581c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(231, 88, 28, 0.2);
        }
        
        /* Chart container styling */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        /* Performance rating colors */
        .rating-excellent { background-color: #10b981; }
        .rating-good { background-color: #3b82f6; }
        .rating-fair { background-color: #f59e0b; }
        .rating-poor { background-color: #ef4444; }
        
        /* Hover states for tabs */
        #teamTab:hover span:not(.tab-badge),
        #pendingTab:hover span:not(.tab-badge),
        #performanceTab:hover span:not(.tab-badge),
        #approvedTab:hover span:not(.tab-badge),
        #leaveTab:hover span:not(.tab-badge) {
            color: white !important;
        }

        /* Line clamp for text */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Enhanced Header with MOIC Colors -->
    <nav class="gradient-moic-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <!-- MOIC Logo with enhanced styling -->
                    <div class="bg-white p-2.5 rounded-lg shadow-sm">
                        <img class="h-9 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Supervisor Portal</h1>
                        <p class="text-sm text-blue-100">Team Management Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-white">{{ $supervisor->name }}</p>
                        <p class="text-xs text-blue-200">{{ $supervisor->employee_number }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-exchange-alt mr-2"></i> Switch View
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="bg-white/10 hover:bg-white/20 text-white p-2.5 rounded-lg transition-all duration-200">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Quick Stats with MOIC Colors -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Team Members Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Team Members</p>
                        <p class="text-3xl font-bold moic-navy mt-1">{{ $stats['team_size'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                        <i class="fas fa-users text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-user-plus mr-2 text-moic-accent"></i>
                        <span>Managing {{ $stats['team_size'] }} employees</span>
                    </div>
                </div>
            </div>

            <!-- Pending Review Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingAppraisals->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i>
                        <span>Action required</span>
                    </div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Approved</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $approvedAppraisals->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-history mr-2 text-green-500"></i>
                        <span>Completed reviews</span>
                    </div>
                </div>
            </div>

            <!-- Avg Score Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Avg Score</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ number_format($stats['avg_final_score'], 1) }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-trend-up mr-2 text-purple-500"></i>
                        <span>Team performance</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Container -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">

    <!-- Responsive Tab Navigation -->
    <div class="gradient-moic-navy">
        <div class="flex gap-2 md:gap-6 px-3 md:px-8 overflow-x-auto custom-scrollbar">

            <!-- My Team -->
            <button id="teamTab"
                class="py-3 md:py-5 px-4 md:px-2 font-semibold active-tab whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[120px] md:min-w-0"
                onclick="switchTab('team')">

                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>

                <span class="text-xs sm:text-sm md:text-base">My Team</span>

                <span class="tab-badge bg-white/30 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                    {{ $stats['team_size'] }}
                </span>
            </button>

            <!-- Pending -->
            <button id="pendingTab"
                class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[140px]"
                onclick="switchTab('pending')">

                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>

                <span class="text-xs sm:text-sm md:text-base">Pending Review</span>

                @if($pendingAppraisals->count() > 0)
                <span class="tab-badge bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                    {{ $pendingAppraisals->count() }}
                </span>
                @endif
            </button>

            <!-- Performance -->
            <button id="performanceTab"
                class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[130px]"
                onclick="switchTab('performance')">

                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                    <i class="fas fa-chart-bar text-white text-sm"></i>
                </div>

                <span class="text-xs sm:text-sm md:text-base">Performance</span>
            </button>

            <!-- Approved -->
            <button id="approvedTab"
                class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[120px]"
                onclick="switchTab('approved')">

                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>

                <span class="text-xs sm:text-sm md:text-base">Approved</span>

                <span class="tab-badge bg-green-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                    {{ $approvedAppraisals->count() }}
                </span>
            </button>

            <!-- Leave -->
            <button id="leaveTab"
                class="py-3 md:py-5 px-4 md:px-2 font-semibold whitespace-nowrap flex flex-col sm:flex-row items-center min-w-[160px]"
                onclick="switchTab('leave')">

                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center sm:mr-3 mb-1 sm:mb-0">
                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                </div>

                <span class="text-xs sm:text-sm md:text-base">Leave Management</span>

                @if(isset($leaveStats) && $leaveStats['pending'] > 0)
                <span class="tab-badge bg-red-500 text-white text-xs px-2 py-0.5 rounded-full sm:ml-2 mt-1 sm:mt-0 font-bold">
                    {{ $leaveStats['pending'] }}
                </span>
                @endif
            </button>

        </div>
    </div>
</div>


            <!-- Team Tab Content -->
            <div id="teamContent" class="p-8">
                <!-- Enhanced Search and Filter Section -->
                <div class="flex flex-col lg:flex-row justify-between items-center mb-8 space-y-4 lg:space-y-0">
                    <div class="w-full lg:w-2/5">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="teamSearch" placeholder="Search team members by name or employee number..." 
                                   class="form-input w-full pl-12 pr-4 bg-gray-50"
                                   onkeyup="filterTeam()">
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full lg:w-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-building text-gray-400 text-sm"></i>
                            </div>
                            <select id="departmentFilter" onchange="filterTeam()"
                                    class="form-select pl-10 pr-10 w-full sm:w-auto bg-gray-50">
                                <option value="">All Departments</option>
                                <option value="operations">Operations</option>
                                <option value="finance">Finance</option>
                                <option value="hr">Human Resources</option>
                                <option value="it">IT</option>
                                <option value="marketing">Marketing</option>
                                <option value="sales">Sales</option>
                            </select>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-filter text-gray-400 text-sm"></i>
                            </div>
                            <select id="statusFilter" onchange="filterTeam()"
                                    class="form-select pl-10 pr-10 w-full sm:w-auto bg-gray-50">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending Review</option>
                                <option value="high-performer">High Performer</option>
                                <option value="needs-improvement">Needs Improvement</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Team Grid - Enhanced Cards with MOIC Colors -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="teamGrid">
                    @forelse($team as $member)
                    @php
                        // Get all approved appraisals for this member
                        $approvedMemberAppraisals = App\Models\Appraisal::with('kpas')
                            ->where('employee_number', $member->employee_number)
                            ->where('status', 'approved')
                            ->orderBy('updated_at', 'desc')
                            ->get();
                        
                        $latestAppraisal = $approvedMemberAppraisals->first();
                        
                        $finalScore = 0;
                        $appraisalCount = $approvedMemberAppraisals->count();
                        $totalScore = 0;
                        
                        if ($appraisalCount > 0) {
                            foreach ($approvedMemberAppraisals as $appraisal) {
                                $score = 0;
                                $totalSupervisorScore = 0;
                                $totalWeight = 0;
                                foreach ($appraisal->kpas as $kpa) {
                                    $rating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                    $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                                    $totalWeight += $kpa->weight;
                                }
                                $score = $totalWeight > 0 ? $totalSupervisorScore : 0;
                                $totalScore += $score;
                            }
                            $finalScore = $totalScore / $appraisalCount;
                        }
                        
                        $hasPending = App\Models\Appraisal::where('employee_number', $member->employee_number)
                            ->where('status', 'submitted')
                            ->exists();
                        
                        // Get pending appraisals for this member
                        $memberPendingAppraisals = App\Models\Appraisal::where('employee_number', $member->employee_number)
                            ->where('status', 'submitted')
                            ->get();
                        
                        // Get member's pending count
                        $memberPendingCount = $memberPendingAppraisals->count();
                    @endphp
                    <div class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 team-member cursor-pointer"
                         data-department="{{ strtolower($member->department ?? '') }}"
                         data-status="{{ $hasPending ? 'pending' : 'active' }}"
                         data-employee-number="{{ $member->employee_number }}"
                         onclick="viewMemberDetails('{{ $member->employee_number }}', '{{ $member->name }}')">
                        <!-- Member Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 rounded-xl gradient-moic-navy flex items-center justify-center">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg moic-navy">{{ $member->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $member->employee_number }}</p>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <span class="bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            {{ $member->job_title ?? 'Employee' }}
                                        </span>
                                        @if($appraisalCount > 0)
                                        <span class="bg-gradient-to-r from-green-50 to-green-100 text-green-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> {{ $appraisalCount }}
                                        </span>
                                        @endif
                                        @if($hasPending)
                                        <span class="bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-700 text-xs px-2.5 py-1.5 rounded-full font-medium">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Performance Score -->
                            @if($finalScore > 0)
                            <div class="text-right">
                                <div class="text-2xl font-bold 
                                    @if($finalScore >= 90) text-green-600
                                    @elseif($finalScore >= 70) text-blue-600
                                    @elseif($finalScore >= 50) text-yellow-600
                                    @else text-red-600 @endif">
                                    {{ number_format($finalScore, 1) }}%
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Avg Score</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Performance Bar -->
                        @if($finalScore > 0)
                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-medium text-gray-600 mb-2">
                                <span>Performance Rating</span>
                                <span>{{ number_format($finalScore, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="performance-bar h-2.5 rounded-full
                                    @if($finalScore >= 90) bg-green-500
                                    @elseif($finalScore >= 70) bg-blue-500
                                    @elseif($finalScore >= 50) bg-yellow-500
                                    @else bg-red-500 @endif"
                                     style="width: {{ min($finalScore, 100) }}%">
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Enhanced Action Buttons -->
                        <div class="flex justify-between pt-6 border-t border-gray-200" onclick="event.stopPropagation();">
                            <button onclick="event.stopPropagation(); viewMemberAppraisals('{{ $member->employee_number }}', '{{ $member->name }}')"
                               class="flex items-center text-sm font-semibold moic-navy hover:text-moic-navy-light transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-eye text-blue-600 text-sm"></i>
                                </div>
                                View All
                            </button>
                            @if($hasPending)
                            <button onclick="event.stopPropagation(); viewMemberPending('{{ $member->employee_number }}', '{{ $member->name }}', {{ $memberPendingCount }})"
                               class="flex items-center text-sm font-semibold moic-accent hover:text-moic-accent-light transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-exclamation-circle text-yellow-600 text-sm"></i>
                                </div>
                                Review Now
                            </button>
                            @endif
                            @if($appraisalCount > 0)
                            <button onclick="event.stopPropagation(); viewMemberApproved('{{ $member->employee_number }}', '{{ $member->name }}', {{ $appraisalCount }})"
                               class="flex items-center text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center mr-2">
                                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                </div>
                                Approved
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-16">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users-slash text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold moic-navy mb-2">No Team Members Assigned</h3>
                        <p class="text-gray-500 mb-6">You don't have any team members assigned to your supervision yet.</p>
                        <button class="btn-primary">
                            <i class="fas fa-user-plus mr-2"></i> Request Team Members
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Tab Content -->
            <div id="pendingContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-50 to-yellow-100 flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            Appraisals Pending Your Review
                        </h3>
                    </div>
                    <span class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm px-4 py-2 rounded-full font-semibold">
                        <span id="pendingCount">{{ $pendingAppraisals->count() }}</span> waiting
                    </span>
                </div>
                
                <div class="mb-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="pendingMemberSearch" placeholder="Search by employee name or number..." 
                               class="form-input w-full pl-12 pr-4 bg-gray-50"
                               onkeyup="filterPending()">
                    </div>
                </div>
                
                @if($pendingAppraisals->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Employee
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-chart-bar mr-2"></i>Self Score
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-briefcase mr-2"></i>Job Title
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Submitted
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="pendingTable">
                                @foreach($pendingAppraisals as $appraisal)
                                <tr class="pending-row hover:bg-gray-50 transition-colors duration-150" 
                                    data-employee="{{ strtolower($appraisal->user->name ?? '') }}"
                                    data-emp-number="{{ strtolower($appraisal->employee_number) }}">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl gradient-moic-navy flex items-center justify-center mr-4">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold moic-navy">{{ $appraisal->user->name ?? 'Employee' }}</div>
                                                <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->period }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="mr-4">
                                                <span class="text-lg font-bold 
                                                    @if($appraisal->self_score >= 4.0) text-green-600
                                                    @elseif($appraisal->self_score >= 3.0) text-blue-600
                                                    @elseif($appraisal->self_score >= 2.0) text-yellow-600
                                                    @else text-red-600 @endif">
                                                    {{ number_format($appraisal->self_score ?? 0, 2) }}%
                                                </span>
                                            </div>
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full
                                                    @if($appraisal->self_score >= 4.0) bg-green-500
                                                    @elseif($appraisal->self_score >= 3.0) bg-blue-500
                                                    @elseif($appraisal->self_score >= 2.0) bg-yellow-500
                                                    @else bg-red-500 @endif"
                                                     style="width: {{ min($appraisal->self_score ?? 0 * 25, 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-full">
                                            {{ $appraisal->user->job_title ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->updated_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appraisal->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}"
                                               class="btn-accent text-sm px-4 py-2">
                                                <i class="fas fa-eye mr-2"></i> Review
                                            </a>
                                            
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-600 mb-2 md:mb-0">
                                Showing <span id="visiblePendingCount" class="font-semibold">{{ $pendingAppraisals->count() }}</span> pending appraisal<span id="pendingPlural">{{ $pendingAppraisals->count() !== 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-sm">
                                    <span class="text-gray-600">Avg Self Score:</span>
                                    <span class="font-bold ml-1 text-yellow-600">
                                        {{ number_format($pendingAppraisals->avg('self_score') ?? 0, 2) }}%
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">Earliest:</span>
                                    <span class="font-medium ml-1">
                                        {{ $pendingAppraisals->min('updated_at')?->format('M d') ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats for Pending -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                    <div class="bg-gradient-to-br from-white to-yellow-50 border border-yellow-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-100 to-yellow-200 flex items-center justify-center mr-4">
                                <i class="fas fa-user-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Oldest Pending</p>
                                <p class="font-bold text-gray-800">
                                    {{ $pendingAppraisals->sortBy('updated_at')->first()?->updated_at?->diffForHumans() ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Employees Waiting</p>
                                <p class="font-bold text-gray-800">
                                    {{ $pendingAppraisals->pluck('employee_number')->unique()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-green-50 border border-green-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center mr-4">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Avg Score</p>
                                <p class="font-bold text-gray-800">
                                    {{ number_format($pendingAppraisals->avg('self_score') ?? 0, 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gradient-moic-navy text-white rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center mr-4">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Action Needed</p>
                                <p class="font-bold text-white">Review & Approve Now</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-circle text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold moic-navy mb-2">All Caught Up!</h3>
                    <p class="text-gray-500 mb-6">No pending appraisals to review at the moment.</p>
                    <p class="text-sm text-gray-400">Check back later for new submissions</p>
                </div>
                @endif
            </div>

            <!-- Performance Tab Content - COMPLETELY IMPLEMENTED -->
            <div id="performanceContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-chart-bar text-purple-600"></i>
                            </div>
                            Team Performance Analytics
                        </h3>
                    </div>
                    <div class="flex space-x-3">
                        <select id="performancePeriod" class="form-select bg-gray-50" onchange="updateCharts()">
                            <option value="yearly">Yearly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <select id="performanceMetric" class="form-select bg-gray-50" onchange="updateCharts()">
                            <option value="score">Average Score</option>
                            <option value="completion">Completion Rate</option>
                            <option value="growth">Performance Growth</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Team Performance Overview Chart -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold moic-navy">Team Performance Overview</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Current Period</span>
                                <span class="text-sm font-semibold text-purple-600">{{ date('Y') }}</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['avg_final_score'], 1) }}%</div>
                                    <div class="text-xs text-gray-500">Avg Score</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $approvedAppraisals->count() }}</div>
                                    <div class="text-xs text-gray-500">Completed</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $pendingAppraisals->count() }}</div>
                                    <div class="text-xs text-gray-500">Pending</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ $stats['team_size'] }}</div>
                                    <div class="text-xs text-gray-500">Team Size</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Performers Section -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold moic-navy">Top Performers</h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Ranking</span>
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            @php
                                // Calculate scores for all team members
                                $memberScores = [];
                                foreach($team as $member) {
                                    $approvedMemberAppraisals = App\Models\Appraisal::with('kpas')
                                        ->where('employee_number', $member->employee_number)
                                        ->where('status', 'approved')
                                        ->get();
                                    
                                    $finalScore = 0;
                                    $appraisalCount = $approvedMemberAppraisals->count();
                                    $totalScore = 0;
                                    
                                    if ($appraisalCount > 0) {
                                        foreach ($approvedMemberAppraisals as $appraisal) {
                                            $score = 0;
                                            $totalSupervisorScore = 0;
                                            $totalWeight = 0;
                                            foreach ($appraisal->kpas as $kpa) {
                                                $rating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                                $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                                                $totalWeight += $kpa->weight;
                                            }
                                            $score = $totalWeight > 0 ? $totalSupervisorScore : 0;
                                            $totalScore += $score;
                                        }
                                        $finalScore = $totalScore / $appraisalCount;
                                        
                                        if ($finalScore > 0) {
                                            $memberScores[] = [
                                                'member' => $member,
                                                'score' => $finalScore,
                                                'appraisal_count' => $appraisalCount
                                            ];
                                        }
                                    }
                                }
                                
                                // Sort by score descending and take top 5
                                usort($memberScores, function($a, $b) {
                                    return $b['score'] <=> $a['score'];
                                });
                                $topPerformers = array_slice($memberScores, 0, 5);
                            @endphp
                            
                            @forelse($topPerformers as $index => $data)
                            @php
                                $member = $data['member'];
                                $finalScore = $data['score'];
                                $appraisalCount = $data['appraisal_count'];
                            @endphp
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg gradient-moic-navy flex items-center justify-center mr-4 relative">
                                        <i class="fas fa-user text-white"></i>
                                        @if($index < 3)
                                        <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center text-xs font-bold text-white">
                                            {{ $index + 1 }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">{{ $member->job_title ?? 'Employee' }}</span>
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                                {{ $appraisalCount }} appr.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full
                                                @if($finalScore >= 90) bg-green-500
                                                @elseif($finalScore >= 70) bg-blue-500
                                                @elseif($finalScore >= 50) bg-yellow-500
                                                @else bg-red-500 @endif"
                                                 style="width: {{ min($finalScore, 100) }}%">
                                            </div>
                                        </div>
                                        <span class="text-lg font-bold 
                                            @if($finalScore >= 90) text-green-600
                                            @elseif($finalScore >= 70) text-blue-600
                                            @elseif($finalScore >= 50) text-yellow-600
                                            @else text-red-600 @endif">
                                            {{ number_format($finalScore, 1) }}%
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Performance Score</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-chart-line text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-semibold moic-navy mb-2">No Performance Data</h4>
                                <p class="text-gray-500 mb-4">Approve more appraisals to see performance analytics</p>
                                <a href="{{ route('appraisals.index', ['status' => 'submitted']) }}" class="btn-primary text-sm px-4 py-2">
                                    <i class="fas fa-clock mr-2"></i>Review Pending
                                </a>
                            </div>
                            @endforelse
                        </div>
                        
                        @if(count($topPerformers) > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Highest Score</div>
                                    <div class="text-xl font-bold text-green-600">
                                        @if(count($topPerformers) > 0)
                                        {{ number_format(max(array_column($topPerformers, 'score')), 1) }}%
                                        @else
                                        0%
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Avg Top 5</div>
                                    <div class="text-xl font-bold text-blue-600">
                                        @if(count($topPerformers) > 0)
                                        {{ number_format(array_sum(array_column($topPerformers, 'score')) / count($topPerformers), 1) }}%
                                        @else
                                        0%
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-500">Team Avg</div>
                                    <div class="text-xl font-bold text-purple-600">{{ number_format($stats['avg_final_score'], 1) }}%</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Performance Trends Chart -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-bold moic-navy">Performance Trends</h4>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-xs text-gray-600">Team Average</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-xs text-gray-600">Top Performer</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="trendsChart"></canvas>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm text-gray-500">Trend Analysis</div>
                                <div class="text-lg font-semibold moic-navy">Quarter-over-Quarter Performance</div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Current Trend</div>
                                    <div class="text-lg font-bold text-green-600">+2.4% <i class="fas fa-arrow-up"></i></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Compared to Last Year</div>
                                    <div class="text-lg font-bold text-blue-600">+5.8% <i class="fas fa-arrow-up"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Performance Distribution -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h4 class="font-bold moic-navy mb-6">Performance Distribution</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full gradient-success flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-star text-white text-2xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600">35%</div>
                            <div class="text-sm font-semibold text-gray-700">Excellent</div>
                            <div class="text-xs text-gray-500">(90-100%)</div>
                        </div>
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-thumbs-up text-white text-2xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-blue-600">45%</div>
                            <div class="text-sm font-semibold text-gray-700">Good</div>
                            <div class="text-xs text-gray-500">(70-89%)</div>
                        </div>
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-chart-line text-white text-2xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-yellow-600">15%</div>
                            <div class="text-sm font-semibold text-gray-700">Fair</div>
                            <div class="text-xs text-gray-500">(50-69%)</div>
                        </div>
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-red-600">5%</div>
                            <div class="text-sm font-semibold text-gray-700">Needs Improvement</div>
                            <div class="text-xs text-gray-500">(Below 50%)</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Tab Content -->
            <div id="approvedContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            Approved Appraisals
                        </h3>
                    </div>
                    <span class="bg-gradient-to-r from-green-500 to-green-600 text-white text-sm px-4 py-2 rounded-full font-semibold">
                        {{ $approvedAppraisals->count() }} approved
                    </span>
                </div>
                
                <div class="mb-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="approvedSearch" placeholder="Search approved appraisals..." 
                               class="form-input w-full pl-12 pr-4 bg-gray-50"
                               onkeyup="filterApproved()">
                    </div>
                </div>
                
                @if($approvedAppraisals->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Employee
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-chart-bar mr-2"></i>Final Score
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-briefcase mr-2"></i>Job Title
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-check mr-2"></i>Approval Date
                                    </th>
                                    <th scope="col" class="px-8 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="approvedTable">
                                @foreach($approvedAppraisals as $appraisal)
                                <tr class="approved-row hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 rounded-xl gradient-moic-navy flex items-center justify-center mr-4">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold moic-navy">{{ $appraisal->user->name ?? 'Employee' }}</div>
                                                <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->period }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="mr-4">
                                                <span class="text-lg font-bold 
                                                    @if($appraisal->final_score >= 4.0) text-green-600
                                                    @elseif($appraisal->final_score >= 3.0) text-blue-600
                                                    @elseif($appraisal->final_score >= 2.0) text-yellow-600
                                                    @else text-red-600 @endif">
                                                    {{ number_format($appraisal->final_score ?? 0, 2) }}%
                                                </span>
                                            </div>
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full
                                                    @if($appraisal->final_score >= 4.0) bg-green-500
                                                    @elseif($appraisal->final_score >= 3.0) bg-blue-500
                                                    @elseif($appraisal->final_score >= 2.0) bg-yellow-500
                                                    @else bg-red-500 @endif"
                                                     style="width: {{ min($appraisal->final_score ?? 0 * 25, 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 rounded-full">
                                            {{ $appraisal->user->job_title ?? 'Not specified' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $appraisal->updated_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $appraisal->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}"
                                               class="btn-primary text-sm px-4 py-2">
                                                <i class="fas fa-eye mr-2"></i> View
                                            </a>
                                            <button onclick="downloadAppraisal({{ $appraisal->id }})"
                                               class="bg-gradient-to-r from-purple-500 to-purple-600 text-white text-sm px-4 py-2 rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-200">
                                                <i class="fas fa-download mr-2"></i> PDF
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Footer -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-600 mb-2 md:mb-0">
                                Showing {{ $approvedAppraisals->count() }} approved appraisal{{ $approvedAppraisals->count() !== 1 ? 's' : '' }}
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-sm">
                                    <span class="text-gray-600">Avg Final Score:</span>
                                    <span class="font-bold ml-1 text-green-600">
                                        {{ number_format($approvedAppraisals->avg('final_score') ?? 0, 2) }}%
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <span class="text-gray-600">Latest Approval:</span>
                                    <span class="font-medium ml-1">
                                        {{ $approvedAppraisals->max('updated_at')?->format('M d') ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats for Approved -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                    <div class="bg-gradient-to-br from-white to-green-50 border border-green-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center mr-4">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Average Score</p>
                                <p class="font-bold text-gray-800">
                                    {{ number_format($approvedAppraisals->avg('final_score') ?? 0, 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Employees Rated</p>
                                <p class="font-bold text-gray-800">
                                    {{ $approvedAppraisals->pluck('employee_number')->unique()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-white to-purple-50 border border-purple-200 rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Appraisal Periods</p>
                                <p class="font-bold text-gray-800">
                                    {{ $approvedAppraisals->pluck('period')->unique()->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="gradient-moic-navy text-white rounded-xl p-5 card-hover">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center mr-4">
                                <i class="fas fa-history text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-100">Recent Activity</p>
                                <p class="font-bold text-white">Last: {{ $approvedAppraisals->max('updated_at')?->diffForHumans() ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold moic-navy mb-2">No Approved Appraisals</h3>
                    <p class="text-gray-500 mb-6">No appraisals have been approved yet.</p>
                    <p class="text-sm text-gray-400">Approve pending reviews to see them here</p>
                </div>
                @endif
            </div>

           <!-- Team Tab Content -->
<!-- [Previous teamContent code remains the same until the end] -->

            <!-- Leave Management Tab Content -->
            <div id="leaveContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold moic-navy flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            Team Leave Management
                        </h3>
                        <p class="text-gray-600 mt-2">Review and manage leave requests from your team members</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('supervisor.leaves') }}" 
                           class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2.5 rounded hover:shadow transition duration-200 font-medium">
                            <i class="fas fa-external-link-alt mr-2"></i> Full Leave Management
                        </a>
                    </div>
                </div>
                
                <!-- Leave Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <!-- Pending Leaves Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Pending Leaves</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $leaveStats['pending'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl">
                                <i class="fas fa-clock text-2xl text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i>
                                <span>Awaiting your approval</span>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Leaves Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Approved Leaves</p>
                                <p class="text-3xl font-bold text-green-600 mt-1">{{ $leaveStats['approved'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                                <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                <span>Approved by you</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Leaves Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Rejected Leaves</p>
                                <p class="text-3xl font-bold text-red-600 mt-1">{{ $leaveStats['rejected'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl">
                                <i class="fas fa-times-circle text-2xl text-red-600"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-ban mr-2 text-red-500"></i>
                                <span>Not approved</span>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Leave Days Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Avg Leave Days</p>
                                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $leaveStats['avg_days'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                                <i class="fas fa-calendar-day text-2xl text-purple-600"></i>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                                <span>Per approved leave</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Pending Leaves Section -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                            <div class="flex justify-between items-center">
                                <h4 class="font-bold moic-navy">Pending Approval</h4>
                                <span class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-xs px-3 py-1 rounded-full font-bold">
                                    {{ ($pendingLeaves ?? collect())->count() }} requests
                                </span>
                            </div>
                        </div>
                        
                        @if(($pendingLeaves ?? collect())->count() > 0)
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @foreach($pendingLeaves ?? [] as $leave)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg gradient-moic-navy flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $leave->employee_name ?? 'Employee' }}</div>
                                            <div class="text-sm text-gray-500">{{ $leave->department ?? 'Department' }}</div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold 
                                        @if($leave->leave_type == 'annual') bg-blue-100 text-blue-800
                                        @elseif($leave->leave_type == 'sick') bg-green-100 text-green-800
                                        @elseif($leave->leave_type == 'maternity') bg-pink-100 text-pink-800
                                        @elseif($leave->leave_type == 'paternity') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-800 @endif rounded-full">
                                        {{ $leave->leave_type_name ?? $leave->leave_type ?? 'Leave' }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        {{ isset($leave->start_date) ? $leave->start_date->format('M d') : 'N/A' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-clock mr-2 text-green-500"></i>
                                        {{ $leave->total_days ?? 0 }} working days
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $leave->reason ?? 'No reason provided' }}</p>
                                
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        Applied: {{ isset($leave->created_at) ? $leave->created_at->diffForHumans() : 'N/A' }}
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="viewLeaveDetails({{ $leave->id ?? 0 }})"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </button>
                                        <button onclick="approveLeave({{ $leave->id ?? 0 }})"
                                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            <i class="fas fa-check mr-1"></i>Approve
                                        </button>
                                        <button onclick="showRejectModal({{ $leave->id ?? 0 }})"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i>Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="px-6 py-4 border-t bg-gray-50">
                            <a href="{{ route('supervisor.leaves') }}?status=pending" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                                View all pending leaves
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-3xl text-green-500"></i>
                            </div>
                            <h4 class="font-bold moic-navy mb-2">All Caught Up!</h4>
                            <p class="text-gray-500 mb-4">No pending leave requests to approve.</p>
                            <p class="text-sm text-gray-400">Team members will notify you when they apply for leave</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Upcoming Leaves Section -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                            <div class="flex justify-between items-center">
                                <h4 class="font-bold moic-navy">Upcoming Approved Leaves</h4>
                                <span class="bg-gradient-to-r from-green-500 to-green-600 text-white text-xs px-3 py-1 rounded-full font-bold">
                                    {{ ($upcomingLeaves ?? collect())->count() }} upcoming
                                </span>
                            </div>
                        </div>
                        
                        @if(($upcomingLeaves ?? collect())->count() > 0)
                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @foreach($upcomingLeaves ?? [] as $leave)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg gradient-moic-navy flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $leave->employee_name ?? 'Employee' }}</div>
                                            <div class="text-sm text-gray-500">{{ $leave->department ?? 'Department' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500 mb-1">Starts in</div>
                                        <div class="text-sm font-semibold text-green-600">
                                            {{ isset($leave->start_date) ? $leave->start_date->diffForHumans(['parts' => 2]) : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="flex items-center text-sm text-gray-600 mb-1">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        {{ isset($leave->start_date) ? $leave->start_date->format('M d') : 'N/A' }} - {{ isset($leave->end_date) ? $leave->end_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-clock mr-2 text-green-500"></i>
                                        {{ $leave->total_days ?? 0 }} working days
                                    </div>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs text-gray-500">
                                            Approved: {{ isset($leave->approved_at) ? $leave->approved_at->format('M d') : 'N/A' }}
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="viewLeaveDetails({{ $leave->id ?? 0 }})"
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button onclick="showCancelModal({{ $leave->id ?? 0 }})"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                <i class="fas fa-times mr-1"></i>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="px-6 py-4 border-t bg-gray-50">
                            <a href="{{ route('supervisor.leaves') }}?status=approved" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                                View all approved leaves
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-alt text-3xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold moic-navy mb-2">No Upcoming Leaves</h4>
                            <p class="text-gray-500 mb-4">No approved leaves scheduled for the near future.</p>
                            <p class="text-sm text-gray-400">Team members will appear here when they have approved leaves</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div> <!-- Closing div for leaveContent -->
                <!-- Quick Actions -->
                <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-blue-800 mb-2">Leave Management Tools</h4>
                            <p class="text-sm text-blue-700">Quick actions and settings for managing team leaves</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('supervisor.leaves') }}" 
                               class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                                <i class="fas fa-list-alt mr-2"></i>Full Leave List
                            </a>
                            <button onclick="exportLeaves()"
                                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-200">
                                <i class="fas fa-download mr-2"></i>Export Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Actions with MOIC Colors -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h4 class="text-xl font-bold moic-navy">Quick Actions</h4>
                    <p class="text-gray-500">Common tasks and shortcuts</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">Last updated: {{ date('h:i A') }}</span>
                    <button onclick="refreshDashboard()" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-sync-alt text-gray-600"></i>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- New Appraisal Card -->
                <a href="{{ route('appraisals.create') }}"
                   class="bg-gradient-to-br from-white to-green-50 border border-green-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg gradient-success flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-plus-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-700">New Appraisal</p>
                            <p class="text-sm text-green-600 opacity-80">Create review</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Initiate a new performance evaluation for team members</p>
                </a>
                
                <!-- All Appraisals Card -->
                <a href="{{ route('appraisals.index') }}"
                   class="bg-gradient-to-br from-white to-blue-50 border border-blue-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg gradient-moic-navy flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-list-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg moic-navy">All Appraisals</p>
                            <p class="text-sm moic-navy-light opacity-80">View all reviews</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Browse through all completed and ongoing appraisals</p>
                </a>
                
                <!-- Pending Reviews Card -->
                <a href="{{ route('appraisals.index', ['status' => 'submitted']) }}"
                   class="bg-gradient-to-br from-white to-yellow-50 border border-yellow-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-yellow-700">Pending Reviews</p>
                            <p class="text-sm text-yellow-600 opacity-80">{{ $pendingAppraisals->count() }} waiting</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Review and approve submitted performance evaluations</p>
                </a>
                
                <!-- Switch View Card -->
                <a href="{{ route('dashboard') }}"
                   class="bg-gradient-to-br from-white to-purple-50 border border-purple-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-exchange-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-purple-700">Switch View</p>
                            <p class="text-sm text-purple-600 opacity-80">Employee dashboard</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Switch to employee view for personal tasks</p>
                </a>
            </div>
        </div>

        <!-- Enhanced Footer -->
        <div class="mt-10 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <div class="bg-white p-2 rounded-lg border border-gray-200">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="text-sm font-medium moic-navy">MOIC Performance Management System</p>
                        <p class="text-xs text-gray-500">Version 2.1 • Supervisor Portal</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-600">{{ date('l, F d, Y') }} • {{ date('h:i A') }}</p>
                    <p class="text-xs text-gray-500 mt-1">© {{ date('Y') }} Ministry of Investment &amp; Commerce. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Success Message -->
    @if(session('success'))
    <div id="successMessage" class="fixed bottom-6 right-6 z-50">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center modal-slide-up max-w-md">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                <i class="fas fa-check text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Success!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
            <button onclick="document.getElementById('successMessage').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                successMsg.classList.add('opacity-0');
                successMsg.classList.add('transition-opacity');
                successMsg.classList.add('duration-500');
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <script>
        // Tab switching functionality
        function switchTab(tab) {
            console.log('Switching to tab:', tab);
            
            // Hide all content
            const tabs = ['team', 'pending', 'performance', 'approved', 'leave']; // Added 'leave'
            tabs.forEach(t => {
                const content = document.getElementById(t + 'Content');
                const tabBtn = document.getElementById(t + 'Tab');
                
                if (content) {
                    content.classList.add('hidden');
                }
                if (tabBtn) {
                    tabBtn.classList.remove('active-tab');
                    // Reset text color for non-active tabs
                    const textSpan = tabBtn.querySelector('span:not(.tab-badge)');
                    if (textSpan) {
                        textSpan.style.color = '';
                        textSpan.classList.remove('text-white', 'font-bold');
                    }
                    // Reset icon container background
                    const iconDiv = tabBtn.querySelector('div:first-child');
                    if (iconDiv) {
                        iconDiv.style.background = '';
                        iconDiv.style.border = '';
                    }
                }
            });
            
            // Show selected content and activate tab
            const selectedContent = document.getElementById(tab + 'Content');
            const selectedTab = document.getElementById(tab + 'Tab');
            
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }
            if (selectedTab) {
                selectedTab.classList.add('active-tab');
                // Force white text for active tab
                const textSpan = selectedTab.querySelector('span:not(.tab-badge)');
                if (textSpan) {
                    textSpan.style.color = 'white';
                    textSpan.classList.add('text-white', 'font-bold');
                }
                // Highlight icon container for active tab
                const iconDiv = selectedTab.querySelector('div:first-child');
                if (iconDiv) {
                    iconDiv.style.background = 'rgba(255, 255, 255, 0.3)';
                    iconDiv.style.border = '1px solid rgba(255, 255, 255, 0.5)';
                }
            }
            
            // Scroll to top of content
            if (selectedContent) {
                selectedContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            
            // Clear any member-specific filters when switching tabs
            clearMemberFilters();
            
            // Initialize charts when performance tab is selected
            if (tab === 'performance' && !window.chartsInitialized) {
                setTimeout(() => {
                    initializeCharts();
                    window.chartsInitialized = true;
                }, 100);
            }
        }

        // Team filtering functionality
        function filterTeam() {
            const searchTerm = document.getElementById('teamSearch').value.toLowerCase();
            const departmentFilter = document.getElementById('departmentFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            const teamMembers = document.querySelectorAll('.team-member');
            
            teamMembers.forEach(member => {
                const name = member.querySelector('h3').textContent.toLowerCase();
                const empNumber = member.querySelector('p.text-sm').textContent.toLowerCase();
                const department = member.getAttribute('data-department');
                const status = member.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm) || empNumber.includes(searchTerm);
                const matchesDepartment = !departmentFilter || department === departmentFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                if (matchesSearch && matchesDepartment && matchesStatus) {
                    member.style.display = 'block';
                } else {
                    member.style.display = 'none';
                }
            });
        }

        // Filter pending appraisals by member
        function filterPending() {
            const searchTerm = document.getElementById('pendingMemberSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.pending-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const employeeName = row.getAttribute('data-employee');
                const empNumber = row.getAttribute('data-emp-number');
                
                const matchesSearch = employeeName.includes(searchTerm) || empNumber.includes(searchTerm);
                
                if (matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update count display
            document.getElementById('visiblePendingCount').textContent = visibleCount;
            document.getElementById('pendingCount').textContent = visibleCount;
            document.getElementById('pendingPlural').textContent = visibleCount !== 1 ? 's' : '';
        }

        // Filter approved appraisals
        function filterApproved() {
            const searchTerm = document.getElementById('approvedSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.approved-row');
            
            rows.forEach(row => {
                const employeeName = row.querySelector('.text-sm.font-bold').textContent.toLowerCase();
                const empNumber = row.querySelector('.text-xs.text-gray-500').textContent.toLowerCase();
                
                const matchesSearch = employeeName.includes(searchTerm) || empNumber.includes(searchTerm);
                
                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // View member details (click on card)
        function viewMemberDetails(employeeNumber, employeeName) {
            // Remove highlight from all cards first
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.classList.remove('member-highlight');
                member.style.border = '';
                member.style.boxShadow = '';
            });
            
            // Find and highlight the clicked member's card
            teamMembers.forEach(member => {
                const memberEmpNumber = member.getAttribute('data-employee-number');
                if (memberEmpNumber === employeeNumber) {
                    member.classList.add('member-highlight');
                    
                    // Focus on this member by dimming others
                    teamMembers.forEach(m => {
                        if (m.getAttribute('data-employee-number') !== employeeNumber) {
                            m.style.opacity = '0.4';
                            m.style.transform = 'scale(0.98)';
                        } else {
                            m.style.opacity = '1';
                            m.style.transform = 'scale(1)';
                        }
                    });
                    
                    // Show a modal with detailed information
                    showMemberModal(employeeNumber, employeeName);
                }
            });
        }

        // Show member modal with detailed information
        function showMemberModal(employeeNumber, employeeName) {
            // Create modal overlay
            const modalOverlay = document.createElement('div');
            modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal-fade-in';
            modalOverlay.id = 'memberModal';
            modalOverlay.onclick = function(event) {
                if (event.target === modalOverlay) {
                    closeMemberModal();
                }
            };
            
            // Create modal content
            modalOverlay.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden modal-slide-up">
                    <div class="bg-gradient-to-r from-moic-navy to-blue-800 text-white p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold">${employeeName}</h2>
                                <p class="opacity-90">${employeeNumber}</p>
                            </div>
                            <button onclick="closeMemberModal()" class="text-white hover:text-gray-200 text-2xl">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 200px)">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-chart-line text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Average Score</p>
                                        <p id="avgScore" class="text-2xl font-bold text-blue-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-green-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Approved Appraisals</p>
                                        <p id="approvedCount" class="text-2xl font-bold text-green-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 p-3 rounded-lg mr-3">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pending Reviews</p>
                                        <p id="pendingCountModal" class="text-2xl font-bold text-yellow-700">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold moic-navy">Recent Appraisals</h3>
                                <div class="flex space-x-2">
                                    <button onclick="viewMemberAppraisals('${employeeNumber}', '${employeeName}')"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        <i class="fas fa-list mr-2"></i>View All
                                    </button>
                                    <button onclick="viewMemberPending('${employeeNumber}', '${employeeName}')"
                                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                                        <i class="fas fa-clock mr-2"></i>Pending Reviews
                                    </button>
                                </div>
                            </div>
                            <div id="memberAppraisalsList" class="text-center py-8">
                                <div class="inline-block">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    <p class="mt-2 text-gray-500">Loading appraisals...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t p-4 bg-gray-50 flex justify-end space-x-3">
                        <button onclick="closeMemberModal()"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Close
                        </button>
                        <button onclick="viewMemberAppraisals('${employeeNumber}', '${employeeName}')"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            View All Appraisals
                        </button>
                        <button onclick="window.location.href='{{ route('appraisals.create') }}?employee=${employeeNumber}'"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>New Appraisal
                        </button>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.appendChild(modalOverlay);
            
            // Load member data via AJAX
            loadMemberData(employeeNumber);
            
            // Prevent scrolling on body
            document.body.style.overflow = 'hidden';
        }

        // Close member modal
        function closeMemberModal() {
            const modal = document.getElementById('memberModal');
            if (modal) {
                modal.remove();
            }
            
            // Reset team member display
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.style.opacity = '1';
                member.style.transform = 'scale(1)';
                member.classList.remove('member-highlight');
            });
            
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        // Load member data via AJAX (mock function - you need to implement the actual API)
        function loadMemberData(employeeNumber) {
            // Simulate API call delay
            setTimeout(() => {
                // Mock data - replace with actual API call
                const mockData = {
                    avg_score: 87.5,
                    approved_count: 3,
                    pending_count: 1,
                    recent_appraisals: [
                        {
                            id: 1,
                            period: "Q1 2024",
                            status: "approved",
                            score: 88.5,
                            date: "Mar 15, 2024"
                        },
                        {
                            id: 2,
                            period: "Q4 2023",
                            status: "approved",
                            score: 86.0,
                            date: "Dec 20, 2023"
                        },
                        {
                            id: 3,
                            period: "Q3 2023",
                            status: "approved",
                            score: 85.0,
                            date: "Sep 30, 2023"
                        },
                        {
                            id: 4,
                            period: "Q2 2024",
                            status: "submitted",
                            score: 90.0,
                            date: "Jun 10, 2024"
                        }
                    ]
                };
                
                // Update modal with data
                document.getElementById('avgScore').textContent = `${mockData.avg_score.toFixed(1)}%`;
                document.getElementById('approvedCount').textContent = mockData.approved_count;
                document.getElementById('pendingCountModal').textContent = mockData.pending_count;
                
                // Update appraisals list
                const appraisalsList = document.getElementById('memberAppraisalsList');
                if (mockData.recent_appraisals && mockData.recent_appraisals.length > 0) {
                    let html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead class="bg-gray-50"><tr>';
                    html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>';
                    html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>';
                    html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>';
                    html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>';
                    html += '<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>';
                    html += '</tr></thead><tbody class="bg-white divide-y divide-gray-200">';
                    
                    mockData.recent_appraisals.forEach(appraisal => {
                        const statusColor = appraisal.status === 'approved' ? 'green' : 
                                           appraisal.status === 'submitted' ? 'yellow' : 'gray';
                        const scoreColor = appraisal.score >= 90 ? 'green' :
                                          appraisal.score >= 70 ? 'blue' :
                                          appraisal.score >= 50 ? 'yellow' : 'red';
                        
                        html += `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">${appraisal.period}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800 rounded-full">
                                        ${appraisal.status.charAt(0).toUpperCase() + appraisal.status.slice(1)}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-bold text-${scoreColor}-600 mr-2">${appraisal.score}%</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-${scoreColor}-500 h-1.5 rounded-full" style="width: ${Math.min(appraisal.score, 100)}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${appraisal.date}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                    <a href="/appraisals/${appraisal.id}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += '</tbody></table></div>';
                    appraisalsList.innerHTML = html;
                } else {
                    appraisalsList.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No appraisals found for this member</p>
                            <button onclick="window.location.href='{{ route('appraisals.create') }}?employee=${employeeNumber}'"
                                    class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i>Create First Appraisal
                            </button>
                        </div>
                    `;
                }
            }, 500); // Simulate network delay
        }

        // View member's appraisals
        function viewMemberAppraisals(employeeNumber, employeeName) {
            window.location.href = `{{ route('appraisals.index') }}?employee=${employeeNumber}`;
        }

        // View member's pending appraisals
        function viewMemberPending(employeeNumber, employeeName, count = null) {
            const url = `{{ route('appraisals.index') }}?employee=${employeeNumber}&status=submitted`;
            window.location.href = url;
        }

        // View member's approved appraisals
        function viewMemberApproved(employeeNumber, employeeName, count = null) {
            const url = `{{ route('appraisals.index') }}?employee=${employeeNumber}&status=approved`;
            window.location.href = url;
        }

        // Clear member-specific filters
        function clearMemberFilters() {
            const teamMembers = document.querySelectorAll('.team-member');
            teamMembers.forEach(member => {
                member.style.display = 'block';
                member.style.opacity = '1';
                member.style.transform = 'scale(1)';
                member.classList.remove('member-highlight');
            });
            
            // Reset search inputs
            document.getElementById('teamSearch').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('pendingMemberSearch').value = '';
            document.getElementById('approvedSearch').value = '';
            
            // Reset filter displays
            filterPending();
            filterApproved();
        }

        // Download appraisal as PDF
        function downloadAppraisal(appraisalId) {
            // Implement PDF download functionality
            console.log('Downloading appraisal:', appraisalId);
            // In a real implementation, this would call your backend to generate a PDF
            window.open(`/appraisals/${appraisalId}/download`, '_blank');
        }

        // Refresh dashboard
        function refreshDashboard() {
            const refreshBtn = event.target.closest('button');
            refreshBtn.classList.add('animate-spin');
            setTimeout(() => {
                refreshBtn.classList.remove('animate-spin');
                location.reload();
            }, 1000);
        }

        // Chart instances
        let performanceChart = null;
        let trendsChart = null;

        // Initialize performance charts
        function initializeCharts() {
            // Destroy existing charts if they exist
            if (performanceChart) {
                performanceChart.destroy();
            }
            if (trendsChart) {
                trendsChart.destroy();
            }
            
            // Team Performance Overview Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Excellent', 'Good', 'Fair', 'Needs Improvement'],
                    datasets: [{
                        label: 'Team Members',
                        data: [35, 45, 15, 5],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(59, 130, 246)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Performance Trends Chart
            const trendsCtx = document.getElementById('trendsChart').getContext('2d');
            trendsChart = new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Q1 2023', 'Q2 2023', 'Q3 2023', 'Q4 2023', 'Q1 2024', 'Q2 2024'],
                    datasets: [
                        {
                            label: 'Team Average',
                            data: [78, 82, 85, 83, 87, 89],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Top Performer',
                            data: [85, 88, 90, 92, 94, 96],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'nearest'
                    }
                }
            });
        }

        // Update charts based on filters
        function updateCharts() {
            const period = document.getElementById('performancePeriod').value;
            const metric = document.getElementById('performanceMetric').value;
            
            // Update chart data based on selections
            if (performanceChart && trendsChart) {
                // Simulate data changes based on selections
                let performanceData, trendLabels, trendData1, trendData2;
                
                switch(period) {
                    case 'monthly':
                        trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                        break;
                    case 'quarterly':
                        trendLabels = ['Q1', 'Q2', 'Q3', 'Q4'];
                        break;
                    case 'yearly':
                    default:
                        trendLabels = ['2020', '2021', '2022', '2023', '2024'];
                        break;
                }
                
                switch(metric) {
                    case 'completion':
                        performanceData = [40, 50, 8, 2];
                        trendData1 = [65, 70, 75, 80, 85, 90];
                        trendData2 = [75, 80, 85, 90, 95, 98];
                        break;
                    case 'growth':
                        performanceData = [30, 40, 20, 10];
                        trendData1 = [70, 75, 78, 82, 85, 88];
                        trendData2 = [80, 83, 86, 89, 92, 95];
                        break;
                    case 'score':
                    default:
                        performanceData = [35, 45, 15, 5];
                        trendData1 = [78, 82, 85, 83, 87, 89];
                        trendData2 = [85, 88, 90, 92, 94, 96];
                        break;
                }
                
                // Update performance chart
                performanceChart.data.datasets[0].data = performanceData;
                performanceChart.update();
                
                // Update trends chart
                trendsChart.data.labels = trendLabels.slice(-6); // Last 6 periods
                trendsChart.data.datasets[0].data = trendData1.slice(-6);
                trendsChart.data.datasets[1].data = trendData2.slice(-6);
                trendsChart.update();
                
                // Update chart titles based on metric
                const metricText = metric === 'score' ? 'Average Score' : 
                                 metric === 'completion' ? 'Completion Rate' : 'Performance Growth';
                const periodText = period === 'monthly' ? 'Monthly' : 
                                 period === 'quarterly' ? 'Quarterly' : 'Yearly';
                
                // You could update chart titles here if needed
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMemberModal();
            }
        });

        // Animate performance bars on load
        document.addEventListener('DOMContentLoaded', function() {
            const performanceBars = document.querySelectorAll('.performance-bar');
            performanceBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
            
            // Set initial active tab
            switchTab('team');
            
            // Add smooth scrolling for tab content
            const tabButtons = document.querySelectorAll('[onclick^="switchTab"]');
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabName = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                    switchTab(tabName);
                });
            });
            
            // Initialize charts flag
            window.chartsInitialized = false;
        });

        // View leave details
        function viewLeaveDetails(leaveId) {
            window.open(`/leaves/${leaveId}`, '_blank');
        }

        // Approve leave function
        function approveLeave(leaveId) {
            if (confirm('Are you sure you want to approve this leave request?')) {
                fetch(`/supervisor/leaves/${leaveId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while approving the leave.');
                });
            }
        }

        // Show reject modal
        function showRejectModal(leaveId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.id = 'rejectModal';
            
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md modal-slide-up">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Reject Leave Request</h3>
                        <p class="text-gray-600 mb-4">Please provide a reason for rejecting this leave request.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Rejection</label>
                            <textarea id="rejectReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Enter the reason for rejecting this leave request..."
                                      required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="document.getElementById('rejectModal').remove()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                                Cancel
                            </button>
                            <button onclick="rejectLeave(${leaveId})"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Reject Leave
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Reject leave function
        function rejectLeave(leaveId) {
            const reason = document.getElementById('rejectReason').value;
            
            if (!reason.trim()) {
                alert('Please provide a reason for rejection.');
                return;
            }
            
            fetch(`/supervisor/leaves/${leaveId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ remarks: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    document.getElementById('rejectModal').remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while rejecting the leave.');
            });
        }

        // Show cancel modal
        function showCancelModal(leaveId) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.id = 'cancelModal';
            
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md modal-slide-up">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Cancel Approved Leave</h3>
                        <p class="text-gray-600 mb-4">Are you sure you want to cancel this approved leave? This action cannot be undone.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Cancellation</label>
                            <textarea id="cancelReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Enter the reason for cancelling this leave..."
                                      required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="document.getElementById('cancelModal').remove()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">
                                Keep Leave
                            </button>
                            <button onclick="cancelLeave(${leaveId})"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Cancel Leave
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Cancel leave function
        function cancelLeave(leaveId) {
            const reason = document.getElementById('cancelReason').value;
            
            if (!reason.trim()) {
                alert('Please provide a reason for cancellation.');
                return;
            }
            
            fetch(`/supervisor/leaves/${leaveId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ remarks: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    document.getElementById('cancelModal').remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while cancelling the leave.');
            });
        }

        // Export leaves function
        function exportLeaves() {
            // Implement export functionality
            showToast('info', 'Export feature coming soon!');
            // window.open('/supervisor/leaves/export', '_blank');
        }

        // Toast notification function
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white flex items-center modal-slide-up ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-4">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info-circle'} text-white"></i>
                </div>
                <div>
                    <p class="font-semibold">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-6 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    </script>
</body>
</html>