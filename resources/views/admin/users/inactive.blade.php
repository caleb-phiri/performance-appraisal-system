<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inactive Users - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .modal-show {
            animation: fadeIn 0.3s ease-out;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
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
            background: #888;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Smooth transitions */
        .smooth-transition {
            transition: all 0.2s ease-in-out;
        }
        
        /* Table row hover effect */
        .table-row-hover:hover {
            background-color: #f8fafc;
        }
        
        /* Status badge animations */
        .status-badge {
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Animation for flash messages */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Highlight effect without modifying HTML */
        .highlighted-row {
            background-color: #fffacd !important;
            border-left: 4px solid #ffeb3b;
        }
        
        /* Pagination active page */
        .pagination-active {
            background-color: #110484;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-white p-1.5 rounded-md mr-3">
                        <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <h1 class="text-xl font-bold">Admin Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ auth()->user()->name }} ({{ auth()->user()->employee_number }})</span>
                    <div class="relative group">
                        <button class="flex items-center focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <span class="font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <i class="fas fa-chevron-down ml-1 text-sm"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden group-hover:block">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-home mr-2"></i> Main Dashboard
                            </a>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex space-x-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="py-3 border-b-2 font-medium text-sm {{ request()->routeIs('admin.dashboard') ? 'border-[#110484] text-[#110484]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="py-3 border-b-2 font-medium text-sm {{ request()->routeIs('admin.users.index') ? 'border-[#110484] text-[#110484]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-users mr-2"></i> All Users
                </a>
                <a href="{{ route('admin.users.inactive') }}" 
                   class="py-3 border-b-2 font-medium text-sm {{ request()->routeIs('admin.users.inactive') ? 'border-[#110484] text-[#110484]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-user-slash mr-2"></i> Inactive Users
                </a>
                @if(auth()->user()->user_type === 'supervisor')
                <a href="{{ route('admin.team-members') }}" 
                   class="py-3 border-b-2 font-medium text-sm {{ request()->routeIs('admin.team-members') ? 'border-[#110484] text-[#110484]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fas fa-user-friends mr-2"></i> My Team
                </a>
                @endif
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Flash Messages -->
        <div id="flashMessages">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex justify-between items-center animate-slideIn">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 flex justify-between items-center animate-slideIn">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Inactive User Accounts</h2>
                <p class="text-gray-600 mt-1">Manage employees who have left the company</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportData()" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-users mr-2"></i> View Active Users
                </a>
            </div>
        </div>

        <!-- SEARCH AND FILTER SECTION -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Users</label>
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search by name, employee number, department, or job title..." 
                               class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                               onkeyup="searchUsers()">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <button onclick="clearSearch()" 
                                class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 hidden"
                                id="clearSearchBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Left Reason Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Left Reason</label>
                    <select id="reasonFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                            onchange="filterUsers()">
                        <option value="all">All Reasons</option>
                        <option value="resignation">Resignation</option>
                        <option value="termination">Termination</option>
                        <option value="retirement">Retirement</option>
                        <option value="end_of_contract">End of Contract</option>
                        <option value="transfer">Transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Left Date</label>
                    <select id="dateFilter" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                            onchange="filterUsers()">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            
            <!-- Advanced Search Options -->
            <div class="mt-4 pt-4 border-t border-gray-200 hidden" id="advancedSearch">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="departmentFilter" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                                onchange="filterUsers()">
                            <option value="all">All Departments</option>
                            @php
                                $departments = \App\Models\User::where('left_company', true)
                                    ->distinct()
                                    ->pluck('department')
                                    ->filter()
                                    ->sort();
                            @endphp
                            @foreach($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User Type</label>
                        <select id="userTypeFilter" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                                onchange="filterUsers()">
                            <option value="all">All Types</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select id="sortFilter" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                                onchange="sortUsers()">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="date_asc">Left Date (Oldest)</option>
                            <option value="date_desc">Left Date (Newest)</option>
                            <option value="employee_asc">Employee No (Asc)</option>
                            <option value="employee_desc">Employee No (Desc)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rows per page</label>
                        <select id="rowsPerPage" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 smooth-transition"
                                onchange="changeRowsPerPage()">
                            <option value="5">5 rows</option>
                            <option value="10" selected>10 rows</option>
                            <option value="25">25 rows</option>
                            <option value="50">50 rows</option>
                            <option value="100">100 rows</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Search Controls -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-500" id="searchResultsInfo">
                    Showing {{ $inactiveUsers->count() }} of {{ $inactiveUsers->total() }} users
                </div>
                <div class="flex space-x-2">
                    <button onclick="toggleAdvancedSearch()" 
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-sliders-h mr-1"></i>
                        <span id="advancedSearchText">Advanced Search</span>
                    </button>
                    <button onclick="resetFilters()" 
                            class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                        <i class="fas fa-redo mr-1"></i>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Inactive Users Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Inactive</p>
                        <p class="text-3xl font-bold text-red-600 mt-2" id="totalInactive">{{ $inactiveUsers->total() }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-red-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">This Month</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">
                            @php
                                $thisMonthCount = \App\Models\User::where('left_company', true)
                                    ->whereMonth('left_at', now()->month)
                                    ->whereYear('left_at', now()->year)
                                    ->count();
                            @endphp
                            {{ $thisMonthCount }}
                        </p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-yellow-100 to-orange-100 flex items-center justify-center">
                        <i class="fas fa-calendar-times text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Supervisors</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">
                            @php
                                $supervisorCount = \App\Models\User::where('left_company', true)
                                    ->where('user_type', 'supervisor')
                                    ->count();
                            @endphp
                            {{ $supervisorCount }}
                        </p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Can Reactivate</p>
                        <p class="text-3xl font-bold text-green-600 mt-2" id="canReactivate">{{ $inactiveUsers->count() }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            @if($inactiveUsers->count() > 0)
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Left Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Reason
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                            @foreach($inactiveUsers as $user)
                            <tr class="table-row-hover smooth-transition user-row" 
                                data-name="{{ strtolower($user->name) }}"
                                data-employee="{{ strtolower($user->employee_number) }}"
                                data-department="{{ strtolower($user->department) }}"
                                data-job-title="{{ strtolower($user->job_title) }}"
                                data-user-type="{{ $user->user_type }}"
                                data-left-reason="{{ $user->left_reason }}"
                                data-left-date="{{ $user->left_at ? \Carbon\Carbon::parse($user->left_at)->format('Y-m-d') : '' }}"
                                data-left-notes="{{ strtolower($user->left_notes) }}">
                                <!-- Employee -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->employee_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Details -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->job_title }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->department }}</div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $user->user_type === 'supervisor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </div>
                                </td>
                                
                                <!-- Left Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($user->left_at)
                                            {{ \Carbon\Carbon::parse($user->left_at)->format('M d, Y') }}
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($user->left_at)->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-gray-500 italic">Not recorded</span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Reason -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->left_reason)
                                        @php
                                            $reasonColors = [
                                                'resignation' => 'bg-purple-100 text-purple-800',
                                                'termination' => 'bg-red-100 text-red-800',
                                                'retirement' => 'bg-yellow-100 text-yellow-800',
                                                'end_of_contract' => 'bg-indigo-100 text-indigo-800',
                                                'transfer' => 'bg-blue-100 text-blue-800',
                                                'other' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $color = $reasonColors[$user->left_reason] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                            @switch($user->left_reason)
                                                @case('resignation')
                                                    <i class="fas fa-sign-out-alt mr-1"></i>Resignation
                                                    @break
                                                @case('termination')
                                                    <i class="fas fa-times-circle mr-1"></i>Termination
                                                    @break
                                                @case('retirement')
                                                    <i class="fas fa-user-clock mr-1"></i>Retirement
                                                    @break
                                                @case('end_of_contract')
                                                    <i class="fas fa-file-contract mr-1"></i>End of Contract
                                                    @break
                                                @case('transfer')
                                                    <i class="fas fa-exchange-alt mr-1"></i>Transfer
                                                    @break
                                                @default
                                                    <i class="fas fa-question-circle mr-1"></i>Other
                                            @endswitch
                                        </span>
                                        
                                        @if($user->left_notes)
                                            <div class="text-xs text-gray-500 mt-1 max-w-xs truncate" title="{{ $user->left_notes }}">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                {{ $user->left_notes }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-500 italic">No reason specified</span>
                                    @endif
                                </td>
                                
                                <!-- Actions column in the table -->
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex space-x-3">
        <a href="{{ route('admin.users.show', $user->employee_number) }}" 
           class="text-blue-600 hover:text-blue-900"
           title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        <form action="{{ route('admin.users.reactivate', $user->employee_number) }}" 
              method="POST" 
              class="inline reactivate-form"
              onsubmit="return confirmReactivate(this, '{{ addslashes($user->name) }}')">
            @csrf
            @method('PUT')
            <button type="submit" 
                    class="text-green-600 hover:text-green-900 reactivate-btn"
                    title="Reactivate Account"
                    data-employee-name="{{ $user->name }}">
                <i class="fas fa-user-check"></i>
            </button>
        </form>
        <button onclick="showUserDetails('{{ $user->employee_number }}', '{{ addslashes($user->name) }}')"
                class="text-purple-600 hover:text-purple-900"
                title="View Quick Details">
            <i class="fas fa-info-circle"></i>
        </button>
    </div>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6" id="paginationControls">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                        <!-- Items per page selector -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-700">Show</span>
                            <select id="pageSizeSelect" 
                                    class="text-sm border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                    onchange="changePageSize(this.value)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-700">entries</span>
                        </div>
                        
                        <!-- Page info -->
                        <div class="text-sm text-gray-700" id="pageInfo">
                            Showing <span id="startIndex">1</span> to <span id="endIndex">{{ $inactiveUsers->count() }}</span> of 
                            <span id="totalItems">{{ $inactiveUsers->total() }}</span> entries
                        </div>
                        
                        <!-- Pagination buttons -->
                        <div class="flex items-center space-x-2" id="paginationButtons">
                            <button onclick="goToFirstPage()" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="firstPageBtn">
                                <i class="fas fa-angle-double-left"></i>
                            </button>
                            <button onclick="goToPrevPage()" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="prevPageBtn">
                                <i class="fas fa-angle-left"></i>
                            </button>
                            
                            <!-- Page numbers will be generated here -->
                            <div class="flex space-x-1" id="pageNumbers"></div>
                            
                            <button onclick="goToNextPage()" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="nextPageBtn">
                                <i class="fas fa-angle-right"></i>
                            </button>
                            <button onclick="goToLastPage()" 
                                    class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="lastPageBtn">
                                <i class="fas fa-angle-double-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-user-check text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Inactive Users Found</h3>
                    <p class="text-gray-500 mb-6">All user accounts are currently active in the system.</p>
                    <div class="flex justify-center space-x-3">
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-users mr-2"></i> View Active Users
                        </a>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- No Results Message -->
        <div id="noResults" class="hidden mt-6">
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-6 rounded-lg text-center">
                <i class="fas fa-search text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold mb-2">No Users Found</h3>
                <p class="mb-4">No inactive users match your search criteria. Try adjusting your filters.</p>
                <button onclick="resetFilters()" 
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    Reset All Filters
                </button>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div id="userDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white modal-show">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalUserName"></h3>
                    <button onclick="hideUserDetails()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="userDetailsContent" class="space-y-4">
                    <!-- Content will be loaded via AJAX or fallback -->
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button onclick="hideUserDetails()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Store original user data for filtering
        let originalUsers = [];
        let filteredUsers = [];
        
        // Pagination variables
        let currentPage = 1;
        let pageSize = 10; // Default page size
        let totalPages = 1;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Store original user data
            const rows = document.querySelectorAll('#usersTableBody tr.user-row');
            originalUsers = Array.from(rows).map(row => {
                return {
                    element: row,
                    name: row.dataset.name,
                    employee: row.dataset.employee,
                    department: row.dataset.department,
                    jobTitle: row.dataset.jobTitle,
                    userType: row.dataset.userType,
                    leftReason: row.dataset.leftReason,
                    leftDate: row.dataset.leftDate,
                    leftNotes: row.dataset.leftNotes
                };
            });
            
            filteredUsers = [...originalUsers];
            
            // Initialize pagination
            updatePagination();
            
            // Toggle dropdown on click
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
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(flash => {
                    flash.style.opacity = '0';
                    flash.style.transition = 'opacity 0.5s';
                    setTimeout(() => flash.remove(), 500);
                });
            }, 5000);
            
            // Initialize with all users visible
            updateSearchUI(originalUsers.length);
        });
        
        // Search and Filter Functions
        function searchUsers() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const clearBtn = document.getElementById('clearSearchBtn');
            
            // Show/hide clear button
            if (searchTerm.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
            
            filterUsers();
        }
        
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('clearSearchBtn').classList.add('hidden');
            filterUsers();
        }
        
        function filterUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const reasonFilter = document.getElementById('reasonFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const departmentFilter = document.getElementById('departmentFilter')?.value || 'all';
            const userTypeFilter = document.getElementById('userTypeFilter')?.value || 'all';
            
            // Reset all rows first
            originalUsers.forEach(user => {
                user.element.classList.remove('highlighted-row');
                user.element.style.display = '';
            });
            
            filteredUsers = originalUsers.filter(user => {
                let show = true;
                
                // Search filter
                if (searchTerm) {
                    const matches = 
                        user.name.includes(searchTerm) ||
                        user.employee.includes(searchTerm) ||
                        user.department.includes(searchTerm) ||
                        user.jobTitle.includes(searchTerm) ||
                        (user.leftNotes && user.leftNotes.includes(searchTerm));
                    
                    if (!matches) {
                        show = false;
                    } else {
                        // Highlight the row instead of modifying HTML
                        user.element.classList.add('highlighted-row');
                    }
                }
                
                // Reason filter
                if (reasonFilter !== 'all' && user.leftReason !== reasonFilter) {
                    show = false;
                }
                
                // Date filter
                if (dateFilter !== 'all' && user.leftDate) {
                    const leftDate = new Date(user.leftDate);
                    const now = new Date();
                    let withinRange = false;
                    
                    switch(dateFilter) {
                        case 'today':
                            withinRange = leftDate.toDateString() === now.toDateString();
                            break;
                        case 'week':
                            const weekAgo = new Date();
                            weekAgo.setDate(weekAgo.getDate() - 7);
                            withinRange = leftDate >= weekAgo;
                            break;
                        case 'month':
                            withinRange = leftDate.getMonth() === now.getMonth() && 
                                         leftDate.getFullYear() === now.getFullYear();
                            break;
                        case 'year':
                            withinRange = leftDate.getFullYear() === now.getFullYear();
                            break;
                    }
                    
                    if (!withinRange) show = false;
                }
                
                // Department filter (only if advanced search is visible)
                if (departmentFilter !== 'all' && user.department !== departmentFilter) {
                    show = false;
                }
                
                // User type filter (only if advanced search is visible)
                if (userTypeFilter !== 'all' && user.userType !== userTypeFilter) {
                    show = false;
                }
                
                return show;
            });
            
            // Reset to first page when filtering
            currentPage = 1;
            
            // Update UI based on results
            updateSearchUI(filteredUsers.length);
            
            // Sort users if needed
            sortUsers();
            
            // Update pagination
            updatePagination();
        }
        
        function sortUsers() {
            const sortFilter = document.getElementById('sortFilter')?.value || 'name_asc';
            
            // Sort filteredUsers array
            filteredUsers.sort((a, b) => {
                let aValue, bValue;
                
                switch(sortFilter) {
                    case 'name_asc':
                        aValue = a.name;
                        bValue = b.name;
                        return aValue.localeCompare(bValue);
                        
                    case 'name_desc':
                        aValue = a.name;
                        bValue = b.name;
                        return bValue.localeCompare(aValue);
                        
                    case 'date_asc':
                        aValue = a.leftDate || '9999-12-31';
                        bValue = b.leftDate || '9999-12-31';
                        return new Date(aValue) - new Date(bValue);
                        
                    case 'date_desc':
                        aValue = a.leftDate || '0001-01-01';
                        bValue = b.leftDate || '0001-01-01';
                        return new Date(bValue) - new Date(aValue);
                        
                    case 'employee_asc':
                        aValue = a.employee;
                        bValue = b.employee;
                        return aValue.localeCompare(bValue, undefined, {numeric: true});
                        
                    case 'employee_desc':
                        aValue = a.employee;
                        bValue = b.employee;
                        return bValue.localeCompare(aValue, undefined, {numeric: true});
                        
                    default:
                        return 0;
                }
            });
            
            // Update pagination after sorting
            updatePagination();
        }
        
        // Pagination Functions
        function updatePagination() {
            // Calculate total pages
            totalPages = Math.ceil(filteredUsers.length / pageSize);
            
            // Ensure current page is valid
            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            }
            if (currentPage < 1) {
                currentPage = 1;
            }
            
            // Calculate start and end indices
            const startIndex = (currentPage - 1) * pageSize;
            const endIndex = Math.min(startIndex + pageSize, filteredUsers.length);
            
            // Update page info
            document.getElementById('startIndex').textContent = filteredUsers.length > 0 ? startIndex + 1 : 0;
            document.getElementById('endIndex').textContent = endIndex;
            document.getElementById('totalItems').textContent = filteredUsers.length;
            
            // Show/hide all rows based on current page
            originalUsers.forEach((user, index) => {
                const isVisible = filteredUsers.includes(user);
                const isInCurrentPage = isVisible && 
                    filteredUsers.indexOf(user) >= startIndex && 
                    filteredUsers.indexOf(user) < endIndex;
                
                user.element.style.display = isInCurrentPage ? '' : 'none';
            });
            
            // Update pagination buttons
            updatePaginationButtons();
            
            // Update search UI
            updateSearchUI(filteredUsers.length);
        }
        
        function updatePaginationButtons() {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            pageNumbersDiv.innerHTML = '';
            
            // Calculate which page numbers to show
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            // Adjust if we're near the beginning
            if (currentPage <= 3) {
                endPage = Math.min(5, totalPages);
            }
            
            // Adjust if we're near the end
            if (currentPage >= totalPages - 2) {
                startPage = Math.max(1, totalPages - 4);
            }
            
            // Add page number buttons
            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.className = `px-3 py-1 border rounded text-sm ${i === currentPage ? 'bg-[#110484] text-white border-[#110484]' : 'border-gray-300 text-gray-700 hover:bg-gray-50'}`;
                button.textContent = i;
                button.onclick = () => goToPage(i);
                pageNumbersDiv.appendChild(button);
            }
            
            // Update button states
            document.getElementById('firstPageBtn').disabled = currentPage === 1;
            document.getElementById('prevPageBtn').disabled = currentPage === 1;
            document.getElementById('nextPageBtn').disabled = currentPage === totalPages || totalPages === 0;
            document.getElementById('lastPageBtn').disabled = currentPage === totalPages || totalPages === 0;
            
            // Update page size selector
            document.getElementById('pageSizeSelect').value = pageSize;
            document.getElementById('rowsPerPage').value = pageSize;
        }
        
        function goToPage(page) {
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                updatePagination();
            }
        }
        
        function goToFirstPage() {
            goToPage(1);
        }
        
        function goToPrevPage() {
            goToPage(currentPage - 1);
        }
        
        function goToNextPage() {
            goToPage(currentPage + 1);
        }
        
        function goToLastPage() {
            goToPage(totalPages);
        }
        
        function changePageSize(newSize) {
            pageSize = parseInt(newSize);
            currentPage = 1; // Reset to first page when changing page size
            updatePagination();
        }
        
        function changeRowsPerPage() {
            const newSize = document.getElementById('rowsPerPage').value;
            changePageSize(newSize);
        }
        
        function updateSearchUI(visibleCount) {
            const totalCount = originalUsers.length;
            const noResultsDiv = document.getElementById('noResults');
            const tableDiv = document.querySelector('.bg-white.rounded-lg.shadow.border.border-gray-200.overflow-hidden');
            const resultsInfo = document.getElementById('searchResultsInfo');
            const paginationControls = document.getElementById('paginationControls');
            
            if (visibleCount === 0 && totalCount > 0) {
                noResultsDiv.classList.remove('hidden');
                tableDiv.classList.add('hidden');
                paginationControls.classList.add('hidden');
                resultsInfo.textContent = `No users found`;
            } else {
                noResultsDiv.classList.add('hidden');
                tableDiv.classList.remove('hidden');
                paginationControls.classList.remove('hidden');
                
                // Update search results info
                const startIndex = (currentPage - 1) * pageSize + 1;
                const endIndex = Math.min(startIndex + pageSize - 1, visibleCount);
                const displayText = visibleCount === totalCount ? 
                    `Showing ${startIndex} to ${endIndex} of ${visibleCount} users` :
                    `Showing ${startIndex} to ${endIndex} of ${visibleCount} users (filtered from ${totalCount})`;
                
                resultsInfo.textContent = displayText;
                
                // Update stats
                document.getElementById('canReactivate').textContent = visibleCount;
            }
        }
        
        function toggleAdvancedSearch() {
            const advancedSearch = document.getElementById('advancedSearch');
            const advancedSearchText = document.getElementById('advancedSearchText');
            
            if (advancedSearch.classList.contains('hidden')) {
                advancedSearch.classList.remove('hidden');
                advancedSearchText.textContent = 'Hide Advanced';
                // Trigger filter to apply advanced filters
                filterUsers();
            } else {
                advancedSearch.classList.add('hidden');
                advancedSearchText.textContent = 'Advanced Search';
                // Reset advanced filters and re-filter
                document.getElementById('departmentFilter').value = 'all';
                document.getElementById('userTypeFilter').value = 'all';
                filterUsers();
            }
        }
        
        function resetFilters() {
            // Reset all filter inputs
            document.getElementById('searchInput').value = '';
            document.getElementById('reasonFilter').value = 'all';
            document.getElementById('dateFilter').value = 'all';
            
            // Reset advanced filters if visible
            const advancedSearch = document.getElementById('advancedSearch');
            if (!advancedSearch.classList.contains('hidden')) {
                document.getElementById('departmentFilter').value = 'all';
                document.getElementById('userTypeFilter').value = 'all';
                document.getElementById('sortFilter').value = 'name_asc';
                document.getElementById('rowsPerPage').value = '10';
            } else {
                // If advanced search is hidden, make sure sort is reset
                document.getElementById('sortFilter').value = 'name_asc';
            }
            
            document.getElementById('clearSearchBtn').classList.add('hidden');
            
            // Hide advanced search
            if (!advancedSearch.classList.contains('hidden')) {
                advancedSearch.classList.add('hidden');
                document.getElementById('advancedSearchText').textContent = 'Advanced Search';
            }
            
            // Reset pagination
            currentPage = 1;
            pageSize = 10;
            
            // Remove highlights
            originalUsers.forEach(user => {
                user.element.classList.remove('highlighted-row');
            });
            
            // Reset filtered users
            filteredUsers = [...originalUsers];
            
            // Update UI
            updateSearchUI(originalUsers.length);
            document.getElementById('canReactivate').textContent = originalUsers.length;
            
            // Reset sort and update pagination
            sortUsers();
            updatePagination();
        }
        
        function exportData() {
            // Simple export functionality - can be enhanced later
            alert('Export functionality would be implemented here. This could export the filtered data to CSV or Excel.');
        }
        
        // User Details Modal Functions
        function showUserDetails(employeeNumber, userName) {
            const modal = document.getElementById('userDetailsModal');
            const userNameSpan = document.getElementById('modalUserName');
            const contentDiv = document.getElementById('userDetailsContent');
            
            userNameSpan.textContent = `${userName} (${employeeNumber})`;
            
            // Show loading
            contentDiv.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner spinner text-2xl text-blue-600 mb-3"></i>
                    <p class="text-gray-600">Loading user details...</p>
                </div>
            `;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // SIMPLE SOLUTION: Show basic details without AJAX
            // Find the user in the current table
            const userRow = document.querySelector(`tr[data-employee="${employeeNumber.toLowerCase()}"]`);
            
            if (userRow) {
                // Extract data from the table row
                const name = userRow.querySelector('td:first-child .text-gray-900').textContent;
                const employeeNum = userRow.querySelector('td:first-child .text-gray-500').textContent;
                const jobTitle = userRow.querySelector('td:nth-child(2) .text-gray-900').textContent;
                const department = userRow.querySelector('td:nth-child(2) .text-gray-500').textContent;
                const userType = userRow.querySelector('td:nth-child(2) span').textContent;
                const leftDate = userRow.querySelector('td:nth-child(3) .text-gray-900').textContent;
                const leftReasonSpan = userRow.querySelector('td:nth-child(4) span');
                const leftReason = leftReasonSpan ? leftReasonSpan.textContent.trim() : 'Not specified';
                const leftNotesDiv = userRow.querySelector('td:nth-child(4) .text-xs');
                const leftNotes = leftNotesDiv ? leftNotesDiv.textContent.replace('', '').trim() : '';
                
                // Format and display the details
                contentDiv.innerHTML = formatUserDetailsSimple({
                    name: name,
                    employee_number: employeeNum,
                    job_title: jobTitle,
                    department: department,
                    user_type: userType.toLowerCase(),
                    left_date: leftDate,
                    left_reason: leftReason.toLowerCase().includes('resignation') ? 'resignation' :
                                leftReason.toLowerCase().includes('termination') ? 'termination' :
                                leftReason.toLowerCase().includes('retirement') ? 'retirement' :
                                leftReason.toLowerCase().includes('end of contract') ? 'end_of_contract' :
                                leftReason.toLowerCase().includes('transfer') ? 'transfer' : 'other',
                    left_notes: leftNotes
                }, employeeNumber);
            } else {
                // Fallback if user row not found
                contentDiv.innerHTML = `
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Quick details unavailable</strong>
                        </div>
                        <p class="mt-2">Showing quick details is not available. Please use the View button for full information.</p>
                        <div class="mt-4">
                            <a href="/admin/users/${employeeNumber}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 smooth-transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                View Full Profile
                            </a>
                        </div>
                    </div>
                `;
            }
        }
        
        function formatUserDetailsSimple(user, employeeNumber) {
            return `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Job Title</label>
                            <p class="text-gray-900">${user.job_title || 'Not specified'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                            <p class="text-gray-900">${user.department || 'Not specified'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">User Type</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                ${user.user_type === 'supervisor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                                ${user.user_type ? user.user_type.charAt(0).toUpperCase() + user.user_type.slice(1) : 'Not specified'}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Employee Number</label>
                            <p class="text-gray-900">${user.employee_number || 'Not specified'}</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Left Date</label>
                                <p class="text-gray-900">${user.left_date || 'Not recorded'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Left Reason</label>
                                <p class="text-gray-900 font-medium">${user.left_reason ? user.left_reason.replace('_', ' ').toUpperCase() : 'Not specified'}</p>
                            </div>
                        </div>
                    </div>
                    
                    ${user.left_notes ? `
                    <div class="border-t pt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Additional Notes</label>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-gray-700">${user.left_notes}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="border-t pt-4">
                        <div class="flex space-x-3">
                            <form action="/admin/users/${employeeNumber}/reactivate" method="POST" class="inline">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" 
                                        onclick="return confirm('Reactivate ${user.name}?')"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 smooth-transition">
                                    <i class="fas fa-user-check mr-2"></i> Reactivate Account
                                </button>
                            </form>
                            <a href="/admin/users/${employeeNumber}" 
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 smooth-transition">
                                <i class="fas fa-external-link-alt mr-2"></i> View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function hideUserDetails() {
            document.getElementById('userDetailsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.getElementById('userDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideUserDetails();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideUserDetails();
            }
        });
        
        // Auto-focus search input on page load
        setTimeout(() => {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.focus();
            }
        }, 100);
    </script>
</body>
</html>