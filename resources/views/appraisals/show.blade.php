<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Details - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e);
        }
        
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
        
        /* Hide calculation formulas from users */
        .score-formula {
            display: none !important;
        }
        
        /* Message notifications */
        .message-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        .message {
            margin-bottom: 10px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .message-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-left: 4px solid #047857;
        }
        
        .message-error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-left: 4px solid #b91c1c;
        }
        
        .message-info {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-left: 4px solid #1e40af;
        }
        
        .message-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-left: 4px solid #b45309;
        }
        
        .message-icon {
            font-size: 1.25rem;
            margin-right: 12px;
        }
        
        .message-content {
            flex: 1;
        }
        
        .message-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.8;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            font-size: 1.1rem;
        }
        
        .message-close:hover {
            opacity: 1;
        }
        
        @keyframes slideIn {
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
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        
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
        
        /* Supervisor rating badges */
        .supervisor-rating-badge {
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        /* Score colors */
        .score-excellent { color: #059669; }
        .score-good { color: #3b82f6; }
        .score-fair { color: #d97706; }
        .score-poor { color: #dc2626; }
    </style>
</head>

<body class="min-h-screen bg-gray-50">
    <!-- Message Container -->
    <div id="messageContainer" class="message-container"></div>

    <!-- Updated Header with Dual Logo -->
    <div class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <!-- Logo Section -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <!-- MOIC Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="bg-white p-2 border rounded-lg shadow-sm">
                            <img class="h-10 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22%23110484%22>MOIC</text></svg>';">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-[#110484]"></span>
                            <span class="text-xs text-gray-500"></span>
                        </div>
                    </div>

                    <!-- Partnership Separator -->
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                            <i class="fas fa-handshake text-white text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-500 mt-1">Partner</span>
                    </div>

                    <!-- TKC Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="bg-white p-2 border rounded-lg shadow-sm">
                            <img class="h-10 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2220%22 fill=%22%23e7581c%22>TKC</text></svg>';">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-[#e7581c]"></span>
                            <span class="text-xs text-gray-500"></span>
                        </div>
                    </div>
                </div>

                <!-- User Info & Actions -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <span class="font-semibold text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->user_type ?? 'Employee' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-[#110484] text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Appraisal Title and Navigation -->
            <div class="border-t pt-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-file-alt text-[#110484] mr-2"></i>
                            Performance Appraisal Details
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-600">
                            <span class="bg-blue-50 px-3 py-1 rounded-full">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $appraisal->period }}
                            </span>
                            <span class="bg-gray-50 px-3 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i>
                                {{ $appraisal->user->name ?? $appraisal->employee_name }}
                            </span>
                            <span class="bg-gray-50 px-3 py-1 rounded-full">
                                <i class="fas fa-id-badge mr-1"></i>
                                {{ $appraisal->employee_number }}
                            </span>
                            <span class="bg-gray-50 px-3 py-1 rounded-full">
                                <i class="fas fa-clock mr-1"></i>
                                {{ optional($appraisal->start_date)->format('M d, Y') }} - {{ optional($appraisal->end_date)->format('M d, Y') }}
                            </span>
                            @php
                                $employee = $appraisal->user;
                                $ratingSupervisors = $employee->ratingSupervisors ?? collect();
                                $hasMultipleSupervisors = $ratingSupervisors->count() > 1;
                            @endphp
                            @if($hasMultipleSupervisors)
                                <span class="bg-purple-50 px-3 py-1 rounded-full">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $ratingSupervisors->count() }} Supervisors
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('appraisals.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-[#110484] text-[#110484] rounded-lg hover:bg-[#110484] hover:text-white transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            All Appraisals
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#110484] text-white rounded-lg hover:bg-[#0a0369] transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                        @if(auth()->user()->user_type === 'supervisor')
                        <a href="{{ route('supervisor.dashboard') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#e7581c] text-white rounded-lg hover:bg-[#d4490c] transition-colors">
                            <i class="fas fa-chart-line mr-2"></i>
                            Supervisor Panel
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    @php
                        $statusColors = [
                            'draft' => 'bg-yellow-400',
                            'submitted' => 'bg-blue-400',
                            'in_review' => 'bg-purple-400',
                            'approved' => 'bg-green-500',
                            'rejected' => 'bg-red-500',
                            'completed' => 'bg-green-600',
                            'archived' => 'bg-gray-500',
                        ];
                    @endphp
                    
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full {{ $statusColors[$appraisal->status] ?? 'bg-gray-400' }} mr-2 animate-pulse"></div>
                        <span class="font-semibold">STATUS:</span>
                    </div>
                    <span class="text-xl font-bold uppercase tracking-wider">
                        {{ str_replace('_', ' ', $appraisal->status) }}
                    </span>
                    @if($appraisal->approved_at)
                        <span class="text-sm opacity-90 ml-4">
                            <i class="fas fa-check-circle mr-1"></i>
                            Approved {{ $appraisal->approved_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>
                
                <!-- Status Actions for Supervisor -->
                @php
                    $currentUser = auth()->user();
                    $isAssignedSupervisor = false;
                    $isPrimarySupervisor = false;
                    
                    if ($currentUser->user_type === 'supervisor') {
                        $employee = $appraisal->user;
                        if ($employee && $employee->ratingSupervisors) {
                            foreach ($employee->ratingSupervisors as $supervisor) {
                                if ($supervisor->employee_number === $currentUser->employee_number) {
                                    $isAssignedSupervisor = true;
                                    $isPrimarySupervisor = $supervisor->pivot->is_primary ?? false;
                                    break;
                                }
                            }
                        }
                        // Fallback for old system (manager_id)
                        if (!$isAssignedSupervisor && $employee && $employee->manager_id === $currentUser->employee_number) {
                            $isAssignedSupervisor = true;
                            $isPrimarySupervisor = true; // In old system, manager is primary
                        }
                    }
                @endphp
                
                @if($isAssignedSupervisor && $canApprove)
<div class="flex gap-2">
    @if($appraisal->status === 'submitted' || $appraisal->status === 'in_review')
        <button onclick="showRatingModal('{{ $appraisal->employee_number }}')" 
                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-star mr-1.5"></i>
            Quick Rate
        </button>
        
        <button onclick="showReturnModal()" 
                class="inline-flex items-center px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-undo mr-1.5"></i>
            Return for Revision
        </button>
        
        <form action="{{ route('appraisals.approve', $appraisal->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors"
                    onclick="return confirm('Are you sure you want to approve this appraisal?')">
                <i class="fas fa-check-circle mr-1.5"></i>
                Approve Appraisal
            </button>
        </form>
    @endif
    
    @if($appraisal->status === 'draft')
        <form action="{{ route('appraisals.reject', $appraisal->id) }}" method="POST" class="inline">
            @csrf
            @method('POST')
            <button type="submit" 
                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors"
                    onclick="return confirm('Are you sure you want to reject this appraisal?')">
                <i class="fas fa-times-circle mr-1.5"></i>
                Reject
            </button>
        </form>
    @endif
</div>
@endif
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow p-6 mb-6 mt-6">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <i class="fas fa-chart-line mr-2 text-blue-600"></i>Performance Summary
        </h2>
        
        @php
            // Calculate scores
            $totalWeight = $appraisal->kpas->sum('weight');
            
            // Calculate individual KPA scores
            $individualScores = [];
            $totalSelfScore = 0;
            $totalSupervisorScore = 0;
            
            foreach($appraisal->kpas as $kpa) {
                $kpi = $kpa->kpi ?: 4;
                
                // Self score calculation
                $selfScore = ($kpa->self_rating / $kpi) * $kpa->weight;
                $totalSelfScore += $selfScore;
                
                // Supervisor score calculation - handle multiple supervisors
                if($hasMultipleSupervisors && method_exists($kpa, 'getFinalSupervisorRatingAttribute')) {
                    $finalRating = $kpa->getFinalSupervisorRatingAttribute();
                } else {
                    $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                }
                
                $supervisorScore = ($finalRating / $kpi) * $kpa->weight;
                $totalSupervisorScore += $supervisorScore;
                $individualScores[] = $supervisorScore;
            }
            
            // Calculate total score
            $totalScore = $totalSupervisorScore;
            
            $ratedCount = 0;
            $totalCount = $appraisal->kpas->count();
            
            foreach($appraisal->kpas as $kpa) {
                if($hasMultipleSupervisors && method_exists($kpa, 'ratedSupervisors')) {
                    $supervisorRatings = $kpa->ratedSupervisors();
                    $currentSupervisorHasRated = $kpa->hasSupervisorRating(auth()->user()->employee_number);
                    if($currentSupervisorHasRated || $supervisorRatings->count() > 0) {
                        $ratedCount++;
                    }
                } else {
                    if($kpa->supervisor_rating) {
                        $ratedCount++;
                    }
                }
            }
            
            // Determine performance level
            $performanceLevel = '';
            $performanceColor = '';
            if ($totalScore >= 90) {
                $performanceLevel = 'Excellent';
                $performanceColor = 'score-excellent';
            } elseif ($totalScore >= 70) {
                $performanceLevel = 'Good';
                $performanceColor = 'score-good';
            } elseif ($totalScore >= 50) {
                $performanceLevel = 'Fair';
                $performanceColor = 'score-fair';
            } else {
                $performanceLevel = 'Needs Improvement';
                $performanceColor = 'score-poor';
            }
        @endphp
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <!-- Multiple Supervisors Overview -->
            @if($hasMultipleSupervisors)
                <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="flex items-start">
                        <i class="fas fa-users text-purple-600 mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="font-semibold text-purple-700">Multiple Supervisors Assigned</p>
                            <p class="text-sm text-purple-600 mb-3">
                                This employee has {{ $ratingSupervisors->count() }} rating supervisors. 
                                Each supervisor can provide independent ratings.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-{{ min($ratingSupervisors->count(), 4) }} gap-3">
                                @foreach($ratingSupervisors as $supervisor)
                                    @php
                                        $ratedKPAs = 0;
                                        foreach($appraisal->kpas as $kpa) {
                                            if (method_exists($kpa, 'hasSupervisorRating') && 
                                                $kpa->hasSupervisorRating($supervisor->employee_number)) {
                                                $ratedKPAs++;
                                            }
                                        }
                                        $ratingPercentage = $totalCount > 0 
                                            ? round(($ratedKPAs / $totalCount) * 100, 1)
                                            : 0;
                                        $isCurrentSupervisor = auth()->user()->employee_number === $supervisor->employee_number;
                                    @endphp
                                    <div class="bg-white p-3 rounded border {{ $isCurrentSupervisor ? 'border-purple-300 ring-1 ring-purple-200' : 'border-purple-100' }}">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-sm">
                                                {{ $supervisor->name }}
                                                @if($isCurrentSupervisor)
                                                    <span class="ml-1 px-2 py-0.5 bg-purple-600 text-white text-xs rounded">You</span>
                                                @endif
                                                @if($supervisor->pivot->is_primary ?? false)
                                                    <span class="ml-1 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs rounded">Primary</span>
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $supervisor->pivot->rating_weight ?? 100 }}%</span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-600">
                                            <span class="mr-2">{{ $ratedKPAs }}/{{ $totalCount }} rated</span>
                                            <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $ratingPercentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-500">Total Weight</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalWeight }}%</p>
                </div>
                <div class="bg-blue-50 p-4 rounded">
                    <p class="text-gray-500">Employee Self Score</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($totalSelfScore, 2) }}%</p>
                </div>
                <div class="bg-green-50 p-4 rounded">
                    <p class="text-gray-500">{{ $hasMultipleSupervisors ? 'Final Average Score' : 'Supervisor Final Score' }}</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalScore, 2) }}%</p>
                    <p class="text-sm mt-1 font-medium {{ $performanceColor }}">{{ $performanceLevel }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded">
                    <p class="text-gray-500">Progress</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $ratedCount }}/{{ $totalCount }}</p>
                    <p class="text-sm text-gray-600 mt-1">KPAs rated</p>
                </div>
            </div>
            
            <!-- Performance Progress Bar -->
            <div class="mt-6">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Overall Performance</span>
                    <span class="text-sm font-medium {{ $performanceColor }}">{{ $performanceLevel }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ min($totalScore, 100) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>0%</span>
                    <span>{{ number_format($totalScore, 1) }}%</span>
                    <span>100%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPA Table with Supervisor Rating -->
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-6">Key Performance Areas (KPAs)</h2>
        
        @if($isAssignedSupervisor && $appraisal->status === 'submitted')
            <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-blue-700 font-medium">
                    <i class="fas fa-info-circle mr-2"></i>
                    @if($hasMultipleSupervisors)
                        As a rating supervisor, you can provide your independent rating for each KPA. Your rating will be combined with other supervisors' ratings based on weight.
                    @else
                        As a supervisor, you can rate and comment on each KPA below. Click "Rate" next to each KPA to provide your assessment.
                    @endif
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">KPA</th>
                        <th class="px-4 py-3 text-left">Weight</th>
                        <th class="px-4 py-3 text-left">Self Rating</th>
                        
                        @if($hasMultipleSupervisors)
                            <!-- Multiple supervisor columns -->
                            <th class="px-4 py-3 text-left">Final Rating</th>
                            <th class="px-4 py-3 text-left">Score</th>
                            <th class="px-4 py-3 text-left">Ratings Summary</th>
                        @else
                            <!-- Single supervisor columns -->
                            <th class="px-4 py-3 text-left">Supervisor Rating</th>
                            <th class="px-4 py-3 text-left">Score</th>
                            <th class="px-4 py-3 text-left">Comments</th>
                        @endif
                        
                        @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                            <th class="px-4 py-3 text-left">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($appraisal->kpas as $kpa)
                    @php
                        $kpi = $kpa->kpi ?: 4;
                        
                        if($hasMultipleSupervisors && method_exists($kpa, 'getFinalSupervisorRatingAttribute')) {
                            // Get weighted average rating from multiple supervisors
                            $finalRating = $kpa->getFinalSupervisorRatingAttribute();
                            
                            // Get individual supervisor ratings
                            $supervisorRatings = method_exists($kpa, 'ratedSupervisors') ? $kpa->ratedSupervisors() : collect();
                            $currentSupervisorHasRated = method_exists($kpa, 'hasSupervisorRating') 
                                ? $kpa->hasSupervisorRating(auth()->user()->employee_number)
                                : false;
                        } else {
                            // Single supervisor rating
                            $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                        }
                        
                        // Calculate individual score
                        $individualScore = ($finalRating / $kpi) * $kpa->weight;
                        
                        // Score color coding
                        $scoreColor = '';
                        if ($individualScore >= ($kpa->weight * 0.9)) {
                            $scoreColor = 'score-excellent';
                        } elseif ($individualScore >= ($kpa->weight * 0.7)) {
                            $scoreColor = 'score-good';
                        } elseif ($individualScore >= ($kpa->weight * 0.5)) {
                            $scoreColor = 'score-fair';
                        } else {
                            $scoreColor = 'score-poor';
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $kpa->category }}</td>
                        <td class="px-4 py-3">
                            <strong>{{ $kpa->kpa }}</strong>
                            <p class="text-xs text-gray-500">{{ $kpa->result_indicators }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $kpa->weight }}%</td>
                        <td class="px-4 py-3">
                            {{ $kpa->self_rating }}/{{ $kpi }}
                            <span class="text-xs text-gray-500 block">
                                {{ number_format(($kpa->self_rating / $kpi) * 100, 1) }}%
                            </span>
                        </td>
                        
                        @if($hasMultipleSupervisors)
                            <!-- Multiple supervisors view -->
                            <td class="px-4 py-3">
                                <span class="font-bold {{ $scoreColor }}">
                                    {{ number_format($finalRating, 1) }}/{{ $kpi }}
                                </span>
                                <span class="text-xs text-gray-500 block">
                                    Weighted Average
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold {{ $scoreColor }}">
                                    {{ number_format($individualScore, 2) }}%
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($supervisorRatings->isNotEmpty())
                                    <div class="space-y-2">
                                        @foreach($supervisorRatings as $rating)
                                            @php
                                                $ratingColor = $rating->rating >= ($kpi * 0.7) ? 'score-excellent' : 'score-fair';
                                                $isCurrentSupervisor = $rating->supervisor_id === auth()->user()->employee_number;
                                            @endphp
                                            <div class="text-sm">
                                                <div class="flex justify-between">
                                                    <span class="font-medium">
                                                        {{ $rating->supervisor->name ?? 'Supervisor' }}
                                                        @if($isCurrentSupervisor)
                                                            <span class="ml-1 supervisor-rating-badge bg-purple-100 text-purple-800">You</span>
                                                        @endif
                                                    </span>
                                                    <span class="{{ $ratingColor }} font-medium">
                                                        {{ $rating->rating }}/{{ $kpi }}
                                                    </span>
                                                </div>
                                                @if($rating->comments)
                                                    <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-1 rounded">
                                                        {{ Str::limit($rating->comments, 50) }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-sm">No ratings yet</span>
                                @endif
                            </td>
                        @else
                            <!-- Single supervisor view -->
                            <td class="px-4 py-3">
                                @if($kpa->supervisor_rating)
                                    {{ $kpa->supervisor_rating }}/{{ $kpi }}
                                    <span class="text-xs text-gray-500 block">
                                        {{ number_format(($kpa->supervisor_rating / $kpi) * 100, 1) }}%
                                    </span>
                                    <span class="text-xs text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Rated
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">— Not Rated —</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold {{ $scoreColor }}">
                                    {{ number_format($individualScore, 2) }}%
                                </span>
                                <span class="score-formula text-xs text-gray-400">
                                    ({{ $finalRating }}/{{ $kpi }})*{{ $kpa->weight }}%
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($kpa->supervisor_comments)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500">Supervisor:</span>
                                        <p class="text-sm bg-green-50 p-2 rounded">{{ $kpa->supervisor_comments }}</p>
                                    </div>
                                @endif
                                <div class="{{ $kpa->supervisor_comments ? 'mt-2' : '' }}">
                                    <span class="text-xs text-gray-500">Employee:</span>
                                    <p class="text-sm">{{ $kpa->comments ?? '—' }}</p>
                                </div>
                            </td>
                        @endif
                        
                        @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                            <td class="px-4 py-3">
                                @if($hasMultipleSupervisors)
                                    @if($currentSupervisorHasRated)
                                        <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ $kpa->category }}', '{{ $kpa->kpa }}', {{ $kpi }})"
                                                class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded hover:bg-green-200 border border-green-300 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Update
                                        </button>
                                    @else
                                        <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ $kpa->category }}', '{{ $kpa->kpa }}', {{ $kpi }})"
                                                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-star mr-1"></i> Rate
                                        </button>
                                    @endif
                                @else
                                    <!-- Single supervisor rating button -->
                                    <button onclick="showKPARatingModal({{ $kpa->id }}, '{{ $kpa->category }}', '{{ $kpa->kpa }}', {{ $kpi }})"
                                            class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Rate
                                    </button>
                                @endif
                                
                                <!-- View all ratings button for multiple supervisors -->
                                @if($hasMultipleSupervisors && $supervisorRatings->isNotEmpty())
                                    <button onclick="viewAllRatings({{ $kpa->id }})"
                                            class="mt-1 px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-eye mr-1"></i> View All
                                    </button>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        @php
                            $colspan = 7; // Base columns
                            if ($isAssignedSupervisor && $appraisal->status === 'submitted') $colspan++;
                            if ($hasMultipleSupervisors) $colspan++;
                        @endphp
                        <td colspan="{{ $colspan }}" class="text-center py-6 text-gray-500">
                            No KPAs added yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                
                <!-- Total Score Row -->
                @if($appraisal->kpas->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="{{ $hasMultipleSupervisors ? '5' : '4' }}" class="px-4 py-3 text-right font-semibold">
                            Total Score:
                        </td>
                        <td colspan="2" class="px-4 py-3">
                            <span class="font-bold text-lg text-green-600">
                                {{ number_format($totalScore, 2) }}%
                            </span>
                            <span class="score-formula text-xs text-gray-400 ml-2">
                                SUM of all scores
                            </span>
                        </td>
                        @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                            <td class="px-4 py-3"></td>
                        @endif
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Employee Comments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-blue-600">
                <i class="fas fa-user mr-2"></i>Employee Comments
            </h2>
            
            @if($appraisal->development_needs)
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Development Needs:</h3>
                    <p class="bg-gray-50 p-3 rounded">{{ $appraisal->development_needs }}</p>
                </div>
            @endif

            @if($appraisal->employee_comments)
                <div>
                    <h3 class="font-semibold text-gray-700 mb-2">Additional Comments:</h3>
                    <p class="bg-gray-50 p-3 rounded">{{ $appraisal->employee_comments }}</p>
                </div>
            @endif

            @if(!$appraisal->development_needs && !$appraisal->employee_comments)
                <p class="text-gray-500 italic">No comments provided by employee.</p>
            @endif
        </div>

        <!-- Supervisor Comments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4 text-green-600">
                <i class="fas fa-user-tie mr-2"></i>Supervisor Comments
            </h2>
            
            @if($appraisal->supervisor_comments)
                <div class="mb-4">
                    <p class="bg-green-50 p-3 rounded border-l-4 border-green-500">{{ $appraisal->supervisor_comments }}</p>
                    @if($appraisal->approved_by)
                        <p class="text-sm text-gray-500 mt-2">
                            By: {{ $appraisal->approved_by }} 
                            @if($appraisal->approved_at)
                                on {{ $appraisal->approved_at->format('M d, Y h:i A') }}
                            @endif
                        </p>
                    @endif
                </div>
            @endif

            @if($isAssignedSupervisor && $appraisal->status === 'submitted')
                <form action="{{ route('appraisals.add-comment', $appraisal->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Add Overall Comments:</label>
                        <textarea name="supervisor_comments" 
                                  rows="4" 
                                  class="w-full border rounded p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Provide overall feedback, suggestions, or approval comments..."
                                  required></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        <i class="fas fa-comment mr-2"></i>Add Overall Comment
                    </button>
                </form>
            @elseif(!$appraisal->supervisor_comments)
                <p class="text-gray-500 italic">No supervisor comments yet.</p>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @if($isAssignedSupervisor && $appraisal->status === 'submitted')
    <!-- KPA Rating Modal -->
    <div id="kpaRatingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2" id="modalKpaTitle"></h3>
                <p class="text-gray-600 mb-4" id="modalKpaDescription"></p>
                
                <form id="kpaRatingForm">
                    @csrf
                    <input type="hidden" id="kpaId" name="kpa_id">
                    <input type="hidden" id="isMultipleSupervisors" value="{{ $hasMultipleSupervisors ? '1' : '0' }}">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">KPI Scale: 1-<span id="kpiScale">4</span></label>
                        <div class="flex items-center space-x-2 mb-2" id="ratingButtons">
                            <!-- Rating buttons will be dynamically generated here -->
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 px-1">
                            <span>Poor</span>
                            <span>Fair</span>
                            <span>Good</span>
                            <span>Excellent</span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Your Comments:</label>
                        <textarea name="supervisor_comments" id="ratingComments" rows="4" class="w-full border rounded p-3" 
                                  placeholder="Provide specific feedback on this KPA..." required></textarea>
                    </div>
                    
                    @if(!$hasMultipleSupervisors)
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="agree_with_self_rating" id="agreeWithSelfRating" 
                                   class="mr-2" value="1" onchange="toggleSelfRating(this)">
                            <span class="text-gray-700">Agree with employee's self-rating</span>
                        </label>
                    </div>
                    @endif
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeKPAModal()" 
                                class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Save Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View All Ratings Modal -->
    <div id="viewRatingsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-purple-700">All Supervisor Ratings</h3>
                    <button onclick="closeViewRatingsModal()" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="ratingsContent">
                    <!-- Ratings will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Rating Modal -->
    <div id="ratingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-4">Quick Rate Employee</h3>
                <form id="ratingForm">
                    @csrf
                    <input type="hidden" id="rateEmployeeNumber" name="employee_number">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Category:</label>
                        <select name="category" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select Category</option>
                            <option value="quality">Quality of Work</option>
                            <option value="productivity">Productivity</option>
                            <option value="teamwork">Teamwork</option>
                            <option value="initiative">Initiative</option>
                            <option value="communication">Communication</option>
                            <option value="reliability">Reliability</option>
                            <option value="problem_solving">Problem Solving</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Rating (1-5):</label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                    <div class="w-10 h-10 flex items-center justify-center border rounded text-gray-400 peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 transition-colors">
                                        {{ $i }}
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Comments:</label>
                        <textarea name="comments" rows="4" class="w-full border rounded p-3" 
                                  placeholder="Provide detailed feedback..." required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRatingModal()" 
                                class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
                            Submit Rating
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Return for Revision Modal -->
    <div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-4 text-orange-600">Return Appraisal for Revision</h3>
                <form action="{{ route('appraisals.return', $appraisal->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Feedback / Reason for Return:</label>
                        <textarea name="feedback" rows="5" class="w-full border rounded p-3" 
                                  placeholder="Explain what needs to be revised..." required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeReturnModal()" 
                                class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition-colors">
                            Return for Revision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
    // ==============================================
    // KPA RATING MODAL FUNCTIONS
    // ==============================================

    // Load existing rating when opening modal
    function showKPARatingModal(kpaId, category, kpa, kpi) {
        const modal = document.getElementById('kpaRatingModal');
        const hasMultipleSupervisors = document.getElementById('isMultipleSupervisors').value === '1';
        
        // Set modal content
        document.getElementById('modalKpaTitle').textContent = `${category}: ${kpa}`;
        document.getElementById('modalKpaDescription').textContent = `Rate this KPA on a scale of 1-${kpi}`;
        document.getElementById('kpiScale').textContent = kpi;
        document.getElementById('kpaId').value = kpaId;
        
        // Generate rating buttons
        const ratingContainer = document.getElementById('ratingButtons');
        ratingContainer.innerHTML = '';
        
        for(let i = 1; i <= kpi; i++) {
            ratingContainer.innerHTML += `
                <label class="cursor-pointer">
                    <input type="radio" name="supervisor_rating" value="${i}" class="hidden peer" required>
                    <div class="w-12 h-12 flex items-center justify-center border-2 rounded-lg text-gray-400 
                             peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-yellow-500 
                             hover:bg-gray-100 transition-colors">
                        <span class="text-lg font-bold">${i}</span>
                    </div>
                </label>
            `;
        }
        
        // Reset form state
        if (!hasMultipleSupervisors) {
            document.getElementById('agreeWithSelfRating').checked = false;
            toggleSelfRating(document.getElementById('agreeWithSelfRating'));
        }
        
        // For multiple supervisors, check if already rated
        if (hasMultipleSupervisors) {
            fetch(`/appraisals/kpa/${kpaId}/ratings`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find current user's rating
                        const currentUserRating = data.ratings.find(r => 
                            r.supervisor_id === '{{ auth()->user()->employee_number }}'
                        );
                        
                        if (currentUserRating) {
                            // Set existing rating
                            const ratingInput = document.querySelector(`input[name="supervisor_rating"][value="${currentUserRating.rating}"]`);
                            if (ratingInput) {
                                ratingInput.checked = true;
                            }
                            document.getElementById('ratingComments').value = currentUserRating.comments || '';
                            
                            // Update submit button text
                            const submitBtn = modal.querySelector('button[type="submit"]');
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Update Rating';
                        }
                    }
                })
                .catch(error => console.error('Error loading existing rating:', error));
        }
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeKPAModal() {
        document.getElementById('kpaRatingModal').classList.add('hidden');
        document.getElementById('kpaRatingModal').classList.remove('flex');
        document.getElementById('kpaRatingForm').reset();
        const hasMultipleSupervisors = document.getElementById('isMultipleSupervisors').value === '1';
        if (!hasMultipleSupervisors) {
            const agreeCheckbox = document.getElementById('agreeWithSelfRating');
            if (agreeCheckbox) {
                agreeCheckbox.checked = false;
            }
        }
    }

    function toggleSelfRating(checkbox) {
        if (!checkbox) return;
        
        if (checkbox.checked) {
            // If agree with self-rating is checked, disable the rating buttons
            const ratingButtons = document.querySelectorAll('input[name="supervisor_rating"]');
            ratingButtons.forEach(button => {
                button.disabled = true;
                button.required = false;
                button.checked = false;
            });
            
            // Also disable the comments field
            const commentsField = document.getElementById('ratingComments');
            if (commentsField) {
                commentsField.disabled = true;
                commentsField.required = false;
            }
        } else {
            // Re-enable everything if unchecked
            const ratingButtons = document.querySelectorAll('input[name="supervisor_rating"]');
            ratingButtons.forEach(button => {
                button.disabled = false;
                button.required = true;
            });
            
            const commentsField = document.getElementById('ratingComments');
            if (commentsField) {
                commentsField.disabled = false;
                commentsField.required = true;
            }
        }
    }

    // ==============================================
    // VIEW ALL RATINGS MODAL FUNCTIONS
    // ==============================================

    function viewAllRatings(kpaId) {
        const modal = document.getElementById('viewRatingsModal');
        const content = document.getElementById('ratingsContent');
        
        content.innerHTML = `
            <div class="flex justify-center items-center h-40">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
            </div>
        `;
        
        fetch(`/appraisals/kpa/${kpaId}/ratings`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    
                    if (data.ratings && data.ratings.length > 0) {
                        data.ratings.forEach(rating => {
                            const ratingPercentage = (rating.rating / data.kpi) * 100;
                            const ratingColor = ratingPercentage >= 70 ? 'text-green-600' : 
                                              ratingPercentage >= 50 ? 'text-yellow-600' : 'text-red-600';
                            
                            html += `
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-800">
                                                ${rating.supervisor_name || 'Supervisor'}
                                                ${rating.supervisor_id === '{{ auth()->user()->employee_number }}' 
                                                    ? '<span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">You</span>' 
                                                    : ''}
                                            </h4>
                                            <p class="text-sm text-gray-500">${rating.employee_number || ''}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold ${ratingColor}">
                                                ${rating.rating}/${data.kpi}
                                            </span>
                                            <p class="text-sm text-gray-500">${ratingPercentage.toFixed(1)}%</p>
                                        </div>
                                    </div>
                                    ${rating.comments ? `
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <p class="text-sm text-gray-700"><strong>Comments:</strong> ${rating.comments}</p>
                                        </div>
                                    ` : ''}
                                    <div class="mt-2 text-xs text-gray-500">
                                        Rated on: ${new Date(rating.created_at).toLocaleDateString()}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-center text-gray-500 py-4">No ratings available for this KPA.</p>';
                    }
                    
                    content.innerHTML = html;
                } else {
                    content.innerHTML = `<p class="text-center text-red-500 py-4">${data.message || 'Error loading ratings'}</p>`;
                }
            })
            .catch(error => {
                console.error('Error loading ratings:', error);
                content.innerHTML = '<p class="text-center text-red-500 py-4">Error loading ratings. Please try again.</p>';
            });
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeViewRatingsModal() {
        document.getElementById('viewRatingsModal').classList.add('hidden');
        document.getElementById('viewRatingsModal').classList.remove('flex');
    }

    // ==============================================
    // KPA RATING FORM SUBMISSION
    // ==============================================

    // Submit KPA Rating for Multiple Supervisors
    document.getElementById('kpaRatingForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const kpaId = document.getElementById('kpaId').value;
        const hasMultipleSupervisors = document.getElementById('isMultipleSupervisors').value === '1';
        
        // Determine the correct endpoint
        const url = hasMultipleSupervisors 
            ? '{{ route("kpa.rate-multiple") }}' 
            : '{{ route("kpa.rate") }}';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);
        
        // For single supervisor, ensure agree_with_self_rating is included
        if (!hasMultipleSupervisors) {
            const agreeCheckbox = document.getElementById('agreeWithSelfRating');
            if (!agreeCheckbox.checked) {
                formData.set('agree_with_self_rating', '0');
            }
        }
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message || 'Rating submitted successfully!', 'success');
                closeKPAModal();
                
                // Reload the page after 1.5 seconds to show updated ratings
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showMessage(data.message || 'Error submitting rating', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Network error occurred. Please try again.', 'error');
        });
    });

    // ==============================================
    // QUICK RATING MODAL FUNCTIONS
    // ==============================================

    // Quick Rating Modal Functions
    function showRatingModal(employeeNumber) {
        document.getElementById('rateEmployeeNumber').value = employeeNumber;
        document.getElementById('ratingModal').classList.remove('hidden');
        document.getElementById('ratingModal').classList.add('flex');
    }

    function closeRatingModal() {
        document.getElementById('ratingModal').classList.add('hidden');
        document.getElementById('ratingModal').classList.remove('flex');
        document.getElementById('ratingForm').reset();
    }

    // ==============================================
    // RETURN MODAL FUNCTIONS
    // ==============================================

    // Return Modal Functions
    function showReturnModal() {
        document.getElementById('returnModal').classList.remove('hidden');
        document.getElementById('returnModal').classList.add('flex');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
        document.getElementById('returnModal').classList.remove('flex');
    }

    // ==============================================
    // QUICK RATING FORM SUBMISSION
    // ==============================================

    // Submit Quick Rating Form
    document.getElementById('ratingForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', csrfToken);
        
        fetch('{{ route("supervisor.rate-employee") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Rating submitted successfully!', 'success');
                closeRatingModal();
            } else {
                showMessage(data.message || 'Error submitting rating.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Network error occurred. Please try again.', 'error');
        });
    });

    // ==============================================
    // UTILITY FUNCTIONS
    // ==============================================

    // Close modals on outside click
    window.onclick = function(event) {
        const kpaModal = document.getElementById('kpaRatingModal');
        const ratingModal = document.getElementById('ratingModal');
        const returnModal = document.getElementById('returnModal');
        const viewRatingsModal = document.getElementById('viewRatingsModal');
        
        if (event.target === kpaModal) {
            closeKPAModal();
        }
        if (event.target === ratingModal) {
            closeRatingModal();
        }
        if (event.target === returnModal) {
            closeReturnModal();
        }
        if (event.target === viewRatingsModal) {
            closeViewRatingsModal();
        }
    }

    // Message display function
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
            <i class="message-icon fas ${icons[type]}"></i>
            <div class="message-content">${message}</div>
            <button class="message-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        messageContainer.appendChild(messageDiv);
        
        // Auto remove after 5 seconds
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

    // Display calculation formulas in console for debugging (optional)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== Performance Calculation Formulas ===');
        console.log('Total Score Formula: =SUM(All Individual KPA Scores)');
    });
    </script>

</body>
</html>