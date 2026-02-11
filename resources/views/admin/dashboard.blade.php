<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        #dashboardTab span:not(.tab-badge),
        #usersTab span:not(.tab-badge),
        #appraisalsTab span:not(.tab-badge),
        #analyticsTab span:not(.tab-badge) {
            color: rgb(219, 234, 254); /* blue-100 */
        }
        
        #dashboardTab.active-tab span:not(.tab-badge),
        #usersTab.active-tab span:not(.tab-badge),
        #appraisalsTab.active-tab span:not(.tab-badge),
        #analyticsTab.active-tab span:not(.tab-badge) {
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
                        <h1 class="text-xl font-bold text-white">Administrator Portal</h1>
                        <p class="text-sm text-blue-100">System Management Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-200">{{ auth()->user()->employee_number }}</p>
                    </div>
                    <div class="relative group">
                        <button class="flex items-center focus:outline-none">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <span class="font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <i class="fas fa-chevron-down ml-2 text-sm text-white"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 hidden group-hover:block">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-exchange-alt mr-2"></i> Switch to User View
                            </a>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Quick Stats with MOIC Colors -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Appraisals Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-moic-navy">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Appraisals</p>
                        <p class="text-3xl font-bold moic-navy mt-1">{{ $stats['total_appraisals'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                        <i class="fas fa-file-alt text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-chart-line mr-2 text-moic-accent"></i>
                        <span>Across all users</span>
                    </div>
                </div>
            </div>

            <!-- Pending Review Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Approval</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['pending_approvals'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i>
                        <span>Awaiting supervisor review</span>
                    </div>
                </div>
            </div>

            <!-- Approved Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Approved Appraisals</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['approved_appraisals'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-history mr-2 text-green-500"></i>
                        <span>Completed evaluations</span>
                    </div>
                </div>
            </div>

            <!-- Avg Score Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Completion Rate</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['completion_rate'] ?? 0 }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                        <i class="fas fa-percentage text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-trend-up mr-2 text-purple-500"></i>
                        <span>Of assigned appraisals</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Container with MOIC Styling -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <!-- Enhanced Tab Navigation with MOIC Colors -->
            <div class="gradient-moic-navy">
                <div class="flex space-x-8 px-8 overflow-x-auto custom-scrollbar">
                    <button id="dashboardTab" class="py-5 font-semibold active-tab whitespace-nowrap flex items-center" 
                            onclick="switchTab('dashboard')">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                            <i class="fas fa-tachometer-alt text-white"></i>
                        </div>
                        <span>Dashboard</span>
                    </button>
                    <button id="usersTab" class="py-5 font-semibold whitespace-nowrap flex items-center"
                            onclick="switchTab('users')">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <span>All Users</span>
                        <span class="tab-badge bg-white/30 text-white text-xs px-2.5 py-1 rounded-full ml-2 font-bold">
                            {{ $stats['total_users'] ?? 0 }}
                        </span>
                    </button>
                    <button id="appraisalsTab" class="py-5 font-semibold whitespace-nowrap flex items-center"
                            onclick="switchTab('appraisals')">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <span>Monitor Appraisals</span>
                        @if(($stats['pending_approvals'] ?? 0) > 0)
                        <span class="tab-badge bg-yellow-500 text-white text-xs px-2.5 py-1 rounded-full ml-2 font-bold">
                            {{ $stats['pending_approvals'] ?? 0 }}
                        </span>
                        @endif
                    </button>
                    <button id="analyticsTab" class="py-5 font-semibold whitespace-nowrap flex items-center"
                            onclick="switchTab('analytics')">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mr-3">
                            <i class="fas fa-chart-pie text-white"></i>
                        </div>
                        <span>Analytics</span>
                    </button>
                </div>
            </div>

            <!-- Dashboard Tab Content -->
            <div id="dashboardContent" class="p-8">
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-moic-navy to-blue-800 flex items-center justify-center mr-3">
                            <i class="fas fa-bolt text-white"></i>
                        </div>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-moic-navy hover:shadow-lg transition-all duration-300 group bg-white">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-moic-navy text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">View All Users</h4>
                                <p class="text-sm text-gray-500">Manage system users</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.appraisals.index') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-lg transition-all duration-300 group bg-white">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-line text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Monitor Appraisals</h4>
                                <p class="text-sm text-gray-500">View all appraisals</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.users.export') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-purple-500 hover:shadow-lg transition-all duration-300 group bg-white">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-export text-purple-600 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Export Users</h4>
                                <p class="text-sm text-gray-500">Download user data</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.appraisals.report') }}" 
                           class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-orange-500 hover:shadow-lg transition-all duration-300 group bg-white">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                <i class="fas fa-chart-pie text-orange-600 text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Analytics</h4>
                                <p class="text-sm text-gray-500">View detailed reports</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Appraisal Monitoring Section -->
                @if(isset($supervisors) && count($supervisors) > 0)
                <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-line text-blue-600"></i>
                                </div>
                                Appraisal Monitoring
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">Overview of all submitted and approved appraisals across supervisors</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.appraisals.index') }}" 
                               class="btn-primary px-4 py-2">
                                <i class="fas fa-expand mr-2"></i> Full View
                            </a>
                        </div>
                    </div>

                    <!-- Supervisor Statistics Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        @foreach($supervisors as $supervisor)
                            @php
                                $approvedCount = $supervisor->approved_count ?? 0;
                                $submittedCount = $supervisor->submitted_count ?? 0;
                                $totalTeamAppraisals = $approvedCount + $submittedCount;
                            @endphp
                            <div class="border border-gray-200 rounded-xl p-6 hover:border-moic-navy hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                                <div class="flex items-start mb-4">
                                    <div class="h-12 w-12 rounded-xl gradient-moic-navy flex items-center justify-center mr-4">
                                        <i class="fas fa-user-tie text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800">{{ $supervisor->name }}</h4>
                                        <p class="text-xs text-gray-500">{{ $supervisor->employee_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $supervisor->department ?? 'No department' }}</p>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                                            <i class="fas fa-users mr-2"></i> Team: {{ $supervisor->team_size ?? 0 }} members
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-green-50 rounded-lg p-3 text-center border border-green-100">
                                        <div class="text-xl font-bold text-green-600">{{ $approvedCount }}</div>
                                        <div class="text-xs text-gray-600 font-medium">Approved</div>
                                    </div>
                                    <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-100">
                                        <div class="text-xl font-bold text-blue-600">{{ $submittedCount }}</div>
                                        <div class="text-xs text-gray-600 font-medium">Submitted</div>
                                    </div>
                                </div>
                                
                                <div class="text-center pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.appraisals.supervisor', $supervisor->employee_number) }}" 
                                       class="text-sm font-semibold moic-navy hover:text-moic-navy-light transition-colors inline-flex items-center">
                                        View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Appraisals Table -->
                @if(isset($recentAppraisals) && count($recentAppraisals) > 0)
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-history text-purple-600"></i>
                        </div>
                        Recent Appraisals
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="gradient-moic-navy">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Employee
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-user-tie mr-2"></i>Supervisor
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-calendar-alt mr-2"></i>Period
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-2"></i>Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-chart-bar mr-2"></i>Score
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Date
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentAppraisals as $appraisal)
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-yellow-100 text-yellow-800',
                                            'submitted' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'in_review' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800'
                                        ];
                                        $statusColor = $statusColors[$appraisal->status] ?? 'bg-gray-100 text-gray-800';
                                        
                                        $score = $appraisal->overall_score ?? $appraisal->supervisor_score ?? $appraisal->self_score ?? 0;
                                        $scoreColor = $score >= 70 ? 'text-green-600' : ($score >= 50 ? 'text-yellow-600' : 'text-red-600');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-xl gradient-moic-navy flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $appraisal->user->name ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $appraisal->supervisor->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $appraisal->period }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $appraisal->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="bg-gradient-to-r from-green-400 to-blue-500 h-2 rounded-full performance-bar" 
                                                         style="width: {{ min($score, 100) }}%"></div>
                                                </div>
                                                <span class="text-sm font-bold {{ $scoreColor }}">
                                                    {{ number_format($score, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ optional($appraisal->updated_at)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                               class="text-moic-navy hover:text-moic-navy-dark mr-4" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('appraisals.show', $appraisal->id) }}?print=true" 
                                               class="text-gray-600 hover:text-gray-900" target="_blank" title="Print">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Users Tab Content -->
            <div id="usersContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            All Users Management
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Manage all system users and their permissions</p>
                    </div>
                    
                </div>

                <!-- Users Management Content -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <p class="text-gray-600 mb-6">User management interface will be displayed here. This tab is currently a placeholder for the full users management view.</p>
                    <div class="text-center py-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-semibold moic-navy mb-2">Users Management</h4>
                        <p class="text-gray-500 mb-6">Click the button below to access the full users management system</p>
                        <a href="{{ route('admin.users.index') }}" class="btn-primary px-6 py-3">
                            <i class="fas fa-external-link-alt mr-2"></i> Go to Users Management
                        </a>
                    </div>
                </div>
            </div>

            <!-- Appraisals Tab Content -->
            <div id="appraisalsContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            Appraisal Monitoring
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Monitor and manage all appraisals across the organization</p>
                    </div>
                    <a href="{{ route('admin.appraisals.index') }}" class="btn-primary px-4 py-2">
                        <i class="fas fa-external-link-alt mr-2"></i> Full Monitoring View
                    </a>
                </div>

                <!-- Appraisals Monitoring Content -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <p class="text-gray-600 mb-6">Comprehensive appraisal monitoring interface will be displayed here. This tab is currently a placeholder for the full monitoring view.</p>
                    <div class="text-center py-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-semibold moic-navy mb-2">Appraisal Monitoring</h4>
                        <p class="text-gray-500 mb-6">Click the button below to access the full appraisal monitoring system</p>
                        <a href="{{ route('admin.appraisals.index') }}" class="btn-primary px-6 py-3">
                            <i class="fas fa-external-link-alt mr-2"></i> Go to Monitoring Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Analytics Tab Content -->
            <div id="analyticsContent" class="p-8 hidden">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-chart-pie text-purple-600"></i>
                            </div>
                            System Analytics
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Comprehensive analytics and reporting dashboard</p>
                    </div>
                    <div class="flex space-x-3">
                        <select class="form-select bg-gray-50">
                            <option>Last 30 Days</option>
                            <option>Last Quarter</option>
                            <option>Last Year</option>
                            <option>All Time</option>
                        </select>
                        <button class="btn-accent px-4 py-2">
                            <i class="fas fa-download mr-2"></i> Export Report
                        </button>
                    </div>
                </div>

                <!-- Analytics Content -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- System Performance Chart -->
                        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6">
                            <h4 class="font-bold moic-navy mb-6">System Performance Overview</h4>
                            <div class="chart-container">
                                <canvas id="systemChart"></canvas>
                            </div>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $stats['completion_rate'] ?? 0 }}%</div>
                                        <div class="text-xs text-gray-500">Completion Rate</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $stats['active_users'] ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Active Users</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-purple-600">{{ $stats['total_appraisals'] ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Total Appraisals</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Performance Distribution -->
                        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6">
                            <h4 class="font-bold moic-navy mb-6">Performance Distribution</h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center mx-auto mb-3">
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
                    
                    <!-- Additional Analytics -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6">
                        <h4 class="font-bold moic-navy mb-6">System Status Overview</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="border border-gray-200 rounded-lg p-5 bg-white">
                                <div class="flex items-center mb-3">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Active Users</h4>
                                        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_users'] ?? 0 }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">Users logged in last 30 days</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-5 bg-white">
                                <div class="flex items-center mb-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Appraisals This Month</h4>
                                        <p class="text-2xl font-bold text-gray-800">{{ $stats['monthly_appraisals'] ?? 0 }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">Appraisals created this month</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-5 bg-white">
                                <div class="flex items-center mb-3">
                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-percentage text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Completion Rate</h4>
                                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completion_rate'] ?? 0 }}%</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">Of assigned appraisals</p>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-5 bg-white">
                                <div class="flex items-center mb-3">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-chart-bar text-orange-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Avg. Score</h4>
                                        <p class="text-2xl font-bold text-gray-800">{{ $stats['average_score'] ?? 0 }}%</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">Average appraisal score</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status & Quick Links -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- System Status -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mr-3">
                        <i class="fas fa-server text-gray-600"></i>
                    </div>
                    System Status
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700">Database Connection</span>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700">Application Server</span>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Running</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-sync-alt text-blue-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700">Background Jobs</span>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Processing</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-700">Email Service</span>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-moic-navy to-blue-800 flex items-center justify-center mr-3">
                        <i class="fas fa-link text-white"></i>
                    </div>
                    Quick Links
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.supervisor-assignments') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-moic-navy hover:shadow-sm transition-all duration-200 group">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-users-cog text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Supervisor Assignments</h4>
                            <p class="text-xs text-gray-500">Manage team assignments</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.appraisals.report') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-purple-500 hover:shadow-sm transition-all duration-200 group">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Detailed Reports</h4>
                            <p class="text-xs text-gray-500">View analytics</p>
                        </div>
                    </a>
                    @if(auth()->user()->user_type === 'supervisor')
                    <a href="{{ route('admin.team-members') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-orange-500 hover:shadow-sm transition-all duration-200 group">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-friends text-orange-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">My Team</h4>
                            <p class="text-xs text-gray-500">View your team</p>
                        </div>
                    </a>
                    @endif
                    <a href="{{ route('admin.users.export') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-sm transition-all duration-200 group">
                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-file-export text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Export Data</h4>
                            <p class="text-xs text-gray-500">Download reports</p>
                        </div>
                    </a>
                </div>
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
                        <p class="text-xs text-gray-500">Version 2.1 • Admin Portal</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-600">{{ date('l, F d, Y') }} • {{ date('h:i A') }}</p>
                    <p class="text-xs text-gray-500 mt-1">© {{ date('Y') }} Ministry of Investment &amp; Commerce. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
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

    @if(session('error'))
    <div id="errorMessage" class="fixed bottom-6 right-6 z-50">
        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center modal-slide-up max-w-md">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold">Error!</p>
                <p class="text-sm opacity-90">{{ session('error') }}</p>
            </div>
            <button onclick="document.getElementById('errorMessage').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) {
                errorMsg.classList.add('opacity-0');
                errorMsg.classList.add('transition-opacity');
                errorMsg.classList.add('duration-500');
                setTimeout(() => errorMsg.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    <script>
        // Tab switching functionality
        function switchTab(tab) {
            // Hide all content
            const tabs = ['dashboard', 'users', 'appraisals', 'analytics'];
            tabs.forEach(t => {
                const content = document.getElementById(t + 'Content');
                const tabBtn = document.getElementById(t + 'Tab');
                
                if (content) {
                    content.classList.add('hidden');
                }
                if (tabBtn) {
                    tabBtn.classList.remove('active-tab');
                    const textSpan = tabBtn.querySelector('span:not(.tab-badge)');
                    if (textSpan) {
                        textSpan.style.color = '';
                        textSpan.classList.remove('text-white', 'font-bold');
                    }
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
                const textSpan = selectedTab.querySelector('span:not(.tab-badge)');
                if (textSpan) {
                    textSpan.style.color = 'white';
                    textSpan.classList.add('text-white', 'font-bold');
                }
                const iconDiv = selectedTab.querySelector('div:first-child');
                if (iconDiv) {
                    iconDiv.style.background = 'rgba(255, 255, 255, 0.3)';
                    iconDiv.style.border = '1px solid rgba(255, 255, 255, 0.5)';
                }
            }
            
            // Initialize charts when analytics tab is selected
            if (tab === 'analytics' && !window.chartsInitialized) {
                setTimeout(() => {
                    initializeCharts();
                    window.chartsInitialized = true;
                }, 100);
            }
        }

        // Initialize charts
        let systemChart = null;

        function initializeCharts() {
            if (systemChart) {
                systemChart.destroy();
            }
            
            const systemCtx = document.getElementById('systemChart').getContext('2d');
            systemChart = new Chart(systemCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Appraisals Created',
                            data: [65, 78, 90, 82, 105, 120, 115],
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Approved Appraisals',
                            data: [45, 58, 70, 62, 85, 95, 90],
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        }

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
            switchTab('dashboard');
            
            // Initialize charts flag
            window.chartsInitialized = false;
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[class*="bg-"]');
                alerts.forEach(alert => {
                    if (alert.classList.contains('bg-green-50') || alert.classList.contains('bg-red-50')) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.5s';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);
        });

        // Toggle dropdown on click
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.querySelector('.group button');
            const dropdown = document.querySelector('.group .hidden');
            
            if (profileButton && dropdown) {
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    dropdown.classList.add('hidden');
                });
                
                // Prevent dropdown from closing when clicking inside
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</html>