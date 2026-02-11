<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
        
        /* Password setup modal z-index fix */
        .z-modal {
            z-index: 1000;
        }
        
        /* New Logo container with gradient border */
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Calculate counts based on user type -->
    @php
        // For supervisors: Get appraisals from their subordinates
        // For regular employees: Get only their own appraisals
        if ($user->user_type === 'supervisor') {
            // Get subordinate employee numbers
            $subordinateNumbers = $user->subordinates->pluck('employee_number')->toArray();
            // Include supervisor's own appraisals too
            $subordinateNumbers[] = $user->employee_number;
            
            // Get appraisals for supervisor and their subordinates
            $userAppraisals = \App\Models\Appraisal::whereIn('employee_number', $subordinateNumbers)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular employees only see their own appraisals
            $userAppraisals = \App\Models\Appraisal::where('employee_number', $user->employee_number)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $totalAppraisals = $userAppraisals->count() ?? 0;
        $draftCount = $userAppraisals->where('status', 'draft')->count() ?? 0;
        $submittedCount = $userAppraisals->where('status', 'submitted')->count() ?? 0;
        $approvedCount = $userAppraisals->where('status', 'approved')->count() ?? 0;
        $rejectedCount = $userAppraisals->where('status', 'rejected')->count() ?? 0;
        
        // Get only first 5 appraisals for dashboard
        $recentAppraisals = $userAppraisals->take(5);
    @endphp

    <!-- Updated Header with both logos -->
    <div class="gradient-header text-white shadow-lg">
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
                    
                    <!-- Dashboard Title -->
                    <div class="flex items-center">
                        <div class="h-8 w-[1px] bg-white/30 mx-4"></div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">Performance Appraisal System Dashboard</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">Welcome</p>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="flex items-center space-x-3">
                    <!-- Supervisor Dashboard Link -->
                    @if($user->user_type === 'supervisor')
                    <div class="hidden lg:block">
                        <a href="{{ route('supervisor.dashboard') }}" 
                           class="bg-[#e7581c] text-white px-3 py-1.5 rounded text-sm hover:bg-[#d54d18] transition duration-200">
                            <i class="fas fa-user-tie mr-1"></i> Supervisor Dashboard
                        </a>
                    </div>
                    @endif
                    
                    <!-- User Profile (Desktop) -->
                    <div class="hidden md:flex flex-col items-end">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                        <span class="text-blue-200 text-sm">{{ $user->job_title ?? 'Employee' }}</span>
                        <a href="{{ route('profile.show') }}" class="text-xs text-blue-300 hover:text-white mt-0.5 transition duration-200 flex items-center">
                            <i class="fas fa-user-circle mr-1"></i> My Profile
                        </a>
                    </div>
                    
                    <!-- Mobile User Menu -->
                    <div class="md:hidden relative">
                        <button id="mobileUserMenu" class="bg-white/20 p-2.5 rounded-lg hover:bg-white/30 transition-colors">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg py-3 z-50 border border-gray-100 mobile-menu-enter">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $user->job_title ?? 'Employee' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-id-badge mr-1"></i>{{ $user->employee_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="py-2">
                                @if($user->user_type === 'supervisor')
                                <a href="{{ route('supervisor.dashboard') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-user-tie mr-2 text-[#e7581c]"></i> Supervisor Panel
                                </a>
                                @endif
                                
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-user-circle mr-2 text-[#110484]"></i> My Profile
                                </a>
                                
                                <a href="{{ route('appraisals.create') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-plus mr-2 text-green-600"></i> New Appraisal
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="bg-white text-[#110484] px-3 py-1.5 rounded text-sm hover:bg-gray-100 transition duration-200 font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold text-[#110484] mb-2">Welcome, {{ $user->name }}!</h2>
                    <div class="flex flex-wrap gap-4">
                        <p class="text-gray-600">
                            <i class="fas fa-id-badge mr-1"></i> Employee ID: <span class="font-semibold">{{ $user->employee_number }}</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-building mr-1"></i> Department: <span class="font-semibold">{{ $user->department ?? 'Not specified' }}</span>
                        </p>
                        <p class="text-gray-600">
                            <i class="fas fa-briefcase mr-1"></i> Job Title: <span class="font-semibold">{{ $user->job_title ?? 'Not specified' }}</span>
                        </p>
                    </div>
                    
                    <!-- Additional user info -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($user->user_type === 'supervisor')
                            <span class="inline-block bg-gradient-to-r from-purple-100 to-violet-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-user-tie mr-1"></i> Supervisor
                            </span>
                            @php
                                $subordinateCount = $user->subordinates()->count();
                            @endphp
                            <span class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-users mr-1"></i> {{ $subordinateCount }} Team Members
                            </span>
                        @endif
                        
                        @if($user->workstation_type === 'hq')
                            <span class="inline-block bg-gradient-to-r from-blue-100 to-indigo-100 text-[#110484] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-building mr-1"></i> Headquarters
                            </span>
                        @elseif($user->workstation_type === 'toll_plaza')
                            <span class="inline-block bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-road mr-1"></i> Toll Plaza
                            </span>
                        @endif
                        
                        @if($user->toll_plaza)
                            <span class="inline-block bg-gradient-to-r from-orange-100 to-amber-100 text-[#e7581c] text-xs px-3 py-1 rounded-full">
                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $user->toll_plaza }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Profile completion indicator -->
                @if(!$user->onboarded)
                    <a href="{{ route('profile.show') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-[#e7581c] to-orange-500 text-white px-4 py-2 rounded text-sm hover:shadow transition duration-200 font-medium">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Complete Profile
                    </a>
                @else
                    <div class="flex flex-col items-end">
                        <a href="{{ route('profile.show') }}" 
                           class="inline-flex items-center bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 px-3 py-1 rounded-full text-sm hover:bg-green-200 transition duration-200 mb-2">
                            <i class="fas fa-check-circle mr-1"></i>
                            Profile Complete
                        </a>
                        @if($user->last_login_at)
                            <span class="text-xs text-gray-500">
                                Last login: {{ $user->last_login_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-2 rounded mr-3">
                        <i class="fas fa-file-alt text-[#110484]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">
                            @if($user->user_type === 'supervisor')
                                Team Appraisals
                            @else
                                My Appraisals
                            @endif
                        </p>
                        <p class="text-xl font-bold text-gray-800">{{ $totalAppraisals }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-2 rounded mr-3">
                        <i class="fas fa-edit text-[#e7581c]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Drafts</p>
                        <p class="text-xl font-bold text-gray-800">{{ $draftCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-2 rounded mr-3">
                        <i class="fas fa-paper-plane text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Submitted</p>
                        <p class="text-xl font-bold text-gray-800">{{ $submittedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-2 rounded mr-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-xl font-bold text-gray-800">{{ $approvedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 p-2 rounded mr-3">
                        <i class="fas fa-times-circle text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rejected</p>
                        <p class="text-xl font-bold text-gray-800">{{ $rejectedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

     <!-- Quick Actions -->
<div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-[#110484] mb-4">Quick Actions</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

        <a href="{{ route('appraisals.create') }}" 
           class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-plus text-lg mb-2 block"></i>
            <p class="font-medium">New Appraisal</p>
            <p class="text-sm opacity-90 mt-1">Start a new performance review</p>
        </a>

        <a href="{{ route('appraisals.index') }}" 
           class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-list text-lg mb-2 block"></i>
            <p class="font-medium">My Appraisals</p>
            <p class="text-sm opacity-90 mt-1">View all your appraisals</p>
        </a>

        @if($user->user_type === 'supervisor')
        <a href="{{ route('supervisor.dashboard') }}" 
           class="bg-gradient-to-r from-purple-500 to-violet-600 text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-user-tie text-lg mb-2 block"></i>
            <p class="font-medium">Team Dashboard</p>
            <p class="text-sm opacity-90 mt-1">Manage team appraisals</p>
        </a>
        @else
        <a href="{{ route('profile.show') }}" 
           class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-user-edit text-lg mb-2 block"></i>
            <p class="font-medium">Update Profile</p>
            <p class="text-sm opacity-90 mt-1">Edit your information</p>
        </a>
        @endif

        <a href="{{ route('profile.password') }}" 
           class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-key text-lg mb-2 block"></i>
            <p class="font-medium">Password</p>
            <p class="text-sm opacity-90 mt-1">Change password settings</p>
        </a>

        <!-- ✅ Leave Card (NOW INSIDE GRID) -->
        <a href="{{ route('leave.index') }}" 
           class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white p-4 rounded text-center hover:shadow-lg hover:-translate-y-1 transition duration-300">
            <i class="fas fa-calendar-alt text-lg mb-2 block"></i>
            <p class="font-medium">Apply Leave</p>
            <p class="text-sm opacity-90 mt-1">Submit leave request</p>
        </a>

    </div>
</div>


        <!-- Recent Appraisals Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-[#110484]">
                            @if($user->user_type === 'supervisor')
                                My Team's Appraisals
                                @if($user->subordinates()->count() > 0)
                                    <span class="text-sm font-normal text-gray-600 ml-2">
                                        ({{ $user->subordinates()->count() }} team members)
                                    </span>
                                @endif
                            @else
                                My Recent Appraisals
                            @endif
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Showing {{ min(5, $totalAppraisals) }} of {{ $totalAppraisals }} appraisals
                        </p>
                    </div>
                    <a href="{{ route('appraisals.index') }}" class="text-[#110484] hover:text-[#e7581c] font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            @if($recentAppraisals && $recentAppraisals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">ID</th>
                            @if($user->user_type === 'supervisor')
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase">Employee</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Job Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                           
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentAppraisals as $appraisal)
                        @php
                            // Get the user who owns this appraisal
                            $appraisalUser = \App\Models\User::where('employee_number', $appraisal->employee_number)->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 text-sm text-[#110484] font-medium">#{{ $appraisal->id }}</td>
                            
                            @if($user->user_type === 'supervisor')
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($appraisalUser)
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-3">
                                            <span class="text-white font-semibold text-xs">
                                                {{ strtoupper(substr($appraisalUser->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $appraisalUser->name ?? 'Unknown User' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if($appraisalUser && $appraisalUser->employee_number === $user->employee_number)
                                                <span class="text-[#e7581c] font-medium">(You)</span>
                                            @else
                                                {{ $appraisal->employee_number }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @endif
                            
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $appraisal->period ?? 'Q4 ' . date('Y') }}
                                <br>
                                <span class="text-xs text-gray-500">
                                    {{ $appraisal->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $appraisalUser->job_title ?? ($appraisal->job_title ?? 'N/A') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $statusColor = $statusColors[$appraisal->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $statusColor }}">
                                    {{ ucfirst($appraisal->status) }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                       class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition duration-200"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($appraisal->status === 'draft' && $appraisal->employee_number == $user->employee_number)
                                    <a href="{{ route('appraisals.edit', $appraisal->id) }}" 
                                       class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('appraisals.submit', $appraisal->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-purple-50 text-purple-600 p-2 rounded hover:bg-purple-100 transition duration-200"
                                                title="Submit"
                                                onclick="return confirm('Are you sure you want to submit this appraisal? This action cannot be undone.')">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('appraisals.destroy', $appraisal->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 transition duration-200"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this draft? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($user->user_type === 'supervisor' && in_array($appraisal->status, ['submitted', 'pending']) && $appraisal->employee_number != $user->employee_number)
                                    <a href="{{ route('supervisor.review', $appraisal->id) }}" 
                                       class="bg-orange-50 text-[#e7581c] p-2 rounded hover:bg-orange-100 transition duration-200"
                                       title="Review">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Show message if there are more appraisals -->
                @if($totalAppraisals > 5)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            Showing 5 of {{ $totalAppraisals }} appraisals
                        </p>
                        <a href="{{ route('appraisals.index') }}" 
                           class="text-sm text-[#110484] hover:text-[#e7581c] font-medium flex items-center">
                            View all {{ $totalAppraisals }} appraisals
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="text-center py-12">
                <div class="inline-block p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full mb-4">
                    <i class="fas fa-file-alt text-[#110484] text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#110484] mb-2">No appraisals found</h3>
                <p class="text-gray-600">
                    @if($user->user_type === 'supervisor')
                        No appraisals have been created by your team members yet.
                    @else
                        Start by creating your first appraisal
                    @endif
                </p>
                <p class="text-sm text-gray-500 mt-2">Track your performance and growth</p>
                @if($user->user_type !== 'supervisor' || ($user->user_type === 'supervisor' && $user->subordinates()->count() == 0))
                <a href="{{ route('appraisals.create') }}" class="mt-4 inline-block bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-4 py-2 rounded hover:shadow transition duration-200 font-medium">
                    Create First Appraisal
                </a>
                @endif
            </div>
            @endif
        </div>
        <!-- Leave Statistics (Optional) -->
@php
    $userLeaves = \App\Models\Leave::where('employee_number', $user->employee_number)->get();
    $pendingLeaves = $userLeaves->where('status', 'pending')->count();
    $upcomingApprovedLeaves = $userLeaves->where('status', 'approved')
                                          ->where('start_date', '>=', now())
                                          ->count();
@endphp

@if($pendingLeaves > 0 || $upcomingApprovedLeaves > 0)
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border border-blue-200">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-[#110484]">
            <i class="fas fa-calendar-alt mr-2"></i> Leave Status
        </h3>
        <a href="{{ route('leave.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
            Manage Leaves <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @if($pendingLeaves > 0)
        <div class="bg-white rounded-lg p-4 border border-yellow-200 shadow-sm">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-3">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending Leave Requests</p>
                    <p class="text-xl font-bold text-gray-800">{{ $pendingLeaves }}</p>
                </div>
            </div>
        </div>
        @endif
        
        @if($upcomingApprovedLeaves > 0)
        <div class="bg-white rounded-lg p-4 border border-green-200 shadow-sm">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-3">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Upcoming Approved Leaves</p>
                    <p class="text-xl font-bold text-gray-800">{{ $upcomingApprovedLeaves }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- MOIC Logo in footer -->
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Version 1.0.0 powered by SmartWave Solutions</p>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('profile.show') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-user-circle mr-1"></i> Profile
                    </a>
                    <!-- Add to footer links -->
<a href="{{ route('leave.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
    <i class="fas fa-calendar mr-1"></i> Leave
</a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-question-circle mr-1"></i> Help
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-book mr-1"></i> Guide
                    </a>
                    <a href="#" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-cog mr-1"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div id="successMessage" class="fixed bottom-4 right-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center z-50">
        <i class="fas fa-check-circle mr-3"></i> 
        <div>
            <p class="font-medium">{{ session('success') }}</p>
            @if(session('info'))
            <p class="text-xs opacity-90 mt-1">{{ session('info') }}</p>
            @endif
        </div>
        <button onclick="document.getElementById('successMessage').remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) successMsg.style.display = 'none';
        }, 5000);
    </script>
    @endif

    <!-- Error Message -->
    @if($errors->any())
    <div id="errorMessage" class="fixed bottom-4 right-4 bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center z-50">
        <i class="fas fa-exclamation-triangle mr-3"></i> 
        <div>
            <p class="font-medium">Please fix the following errors:</p>
            <ul class="text-xs opacity-90 mt-1">
                @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="document.getElementById('errorMessage').remove()" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) errorMsg.style.display = 'none';
        }, 8000);
    </script>
    @endif

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileUserMenu');
            const userDropdown = document.getElementById('userDropdown');
            
            if (mobileMenuBtn && userDropdown) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    userDropdown.classList.toggle('mobile-menu-enter');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>