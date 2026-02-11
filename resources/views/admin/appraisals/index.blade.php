<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Appraisal Management | MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
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
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 12px;
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
            background: linear-gradient(135deg, #110484, #e7581c);
            border-radius: 10px;
        }
        
        /* Hover effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(17, 4, 132, 0.1);
        }
        
        /* Table row hover */
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(17, 4, 132, 0.02) 0%, rgba(231, 88, 28, 0.02) 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-header text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo Section -->
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
                            <h1 class="text-xl font-bold tracking-tight">Performance Appraisal System</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">Admin Panel - Appraisal Management</p>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="flex items-center space-x-3">
                    <!-- User Profile (Desktop) -->
                    <div class="hidden md:flex flex-col items-end">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                        </div>
                        <span class="text-blue-200 text-sm">{{ auth()->user()->job_title ?? 'Administrator' }}</span>
                        <a href="{{ route('admin.dashboard') }}" class="text-xs text-blue-300 hover:text-white mt-0.5 transition duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-1"></i> Admin Dashboard
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
                                        <i class="fas fa-user-cog text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-600">{{ auth()->user()->job_title ?? 'Administrator' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-id-badge mr-1"></i>{{ auth()->user()->employee_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="py-2">
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-tachometer-alt mr-2 text-[#110484]"></i> Dashboard
                                </a>
                                
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-users mr-2 text-[#110484]"></i> User Management
                                </a>
                                
                                <a href="{{ route('admin.appraisals.index') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-file-alt mr-2 text-green-600"></i> Appraisals
                                </a>
                                
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-user-circle mr-2 text-[#e7581c]"></i> My Profile
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
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#110484] mb-2">Appraisal Management</h2>
                    <p class="text-gray-600">Monitor and manage all employee performance appraisals</p>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.appraisals.export') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded text-sm hover:shadow transition duration-200 font-medium">
                        <i class="fas fa-file-export mr-2"></i> Export Data
                    </a>
                    <a href="{{ route('admin.appraisals.report') }}" 
                       class="inline-flex items-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded text-sm hover:shadow transition duration-200 font-medium">
                        <i class="fas fa-chart-bar mr-2"></i> View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 hover-lift">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-2 rounded mr-3">
                        <i class="fas fa-file-alt text-[#110484]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Appraisals</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 hover-lift">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-2 rounded mr-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 hover-lift">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-2 rounded mr-3">
                        <i class="fas fa-paper-plane text-blue-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Submitted</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['submitted'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 hover-lift">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-2 rounded mr-3">
                        <i class="fas fa-edit text-[#e7581c]"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Drafts</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['draft'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-4 hover-lift">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 p-2 rounded mr-3">
                        <i class="fas fa-times-circle text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Rejected</p>
                        <p class="text-xl font-bold text-gray-800">{{ $stats['rejected'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6 hover-lift">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search Form -->
                <div class="flex-1">
                    <form action="{{ route('admin.appraisals.search') }}" method="GET" class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Search by employee name, ID, or appraisal details..." 
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#110484] focus:border-[#110484] transition duration-200">
                            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                        <button type="submit" 
                                class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-5 py-2.5 rounded-lg hover:shadow transition duration-200 font-medium">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.appraisals.index') }}" 
                               class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
                
                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <select onchange="window.location.href = this.value" 
                            class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#110484] focus:border-[#110484] transition duration-200 bg-white">
                        <option value="{{ route('admin.appraisals.index') }}" {{ request('status') ? '' : 'selected' }}>All Status</option>
                        <option value="{{ route('admin.appraisals.index') }}?status=approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="{{ route('admin.appraisals.index') }}?status=submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="{{ route('admin.appraisals.index') }}?status=draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Drafts</option>
                        <option value="{{ route('admin.appraisals.index') }}?status=rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    
                    <select onchange="window.location.href = this.value" 
                            class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#110484] focus:border-[#110484] transition duration-200 bg-white">
                        <option value="{{ route('admin.appraisals.index') }}" {{ request('period') ? '' : 'selected' }}>All Periods</option>
                        <option value="{{ route('admin.appraisals.index') }}?period=quarterly" {{ request('period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="{{ route('admin.appraisals.index') }}?period=annual" {{ request('period') == 'annual' ? 'selected' : '' }}>Annual</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Appraisals Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-[#110484]">All Appraisals</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Showing {{ $appraisals->firstItem() ?? 0 }} to {{ $appraisals->lastItem() ?? 0 }} of {{ $appraisals->total() }} entries
                        </p>
                    </div>
                    <div class="text-sm text-gray-600">
                        Page {{ $appraisals->currentPage() }} of {{ $appraisals->lastPage() }}
                    </div>
                </div>
            </div>

            @if($appraisals->count() > 0)
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Appraisal ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Form Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Supervisor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($appraisals as $appraisal)
                        <tr class="table-row-hover transition duration-150">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-[#110484]">#{{ $appraisal->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold text-xs">
                                            {{ strtoupper(substr($appraisal->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $appraisal->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $appraisal->employee_number }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $appraisal->form_type }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ ucfirst($appraisal->period) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusColor = $statusColors[$appraisal->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                    {{ ucfirst($appraisal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $appraisal->supervisor->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $appraisal->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-1">
                                    <a href="{{ route('appraisals.show', $appraisal) }}" 
                                       class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition duration-200"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('supervisor.review', $appraisal) }}" 
                                       class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition duration-200"
                                       title="Review">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                    @if($appraisal->status == 'submitted')
                                        <form action="{{ route('appraisals.approve', $appraisal) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition duration-200"
                                                    title="Approve"
                                                    onclick="return confirm('Approve this appraisal?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('appraisals.reject', $appraisal) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 transition duration-200"
                                                    title="Reject"
                                                    onclick="return confirm('Reject this appraisal?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                @if($appraisals->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-gray-600 mb-2 md:mb-0">
                            Showing {{ $appraisals->firstItem() }} to {{ $appraisals->lastItem() }} of {{ $appraisals->total() }} results
                        </p>
                        <div class="flex items-center space-x-2">
                            {{ $appraisals->links('pagination::tailwind') }}
                        </div>
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
                    No appraisals match your current filters.
                    @if(request('search') || request('status') || request('period'))
                        <a href="{{ route('admin.appraisals.index') }}" class="text-[#110484] hover:text-[#e7581c] font-medium ml-1">
                            Clear filters
                        </a>
                    @endif
                </p>
            </div>
            @endif
        </div>

        <!-- Supervisor List -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mb-6 hover-lift">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-[#110484]">Appraisals by Supervisor</h3>
                <p class="text-sm text-gray-600 mt-1">Overview of appraisals managed by each supervisor</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $supervisors = App\Models\User::where('user_type', 'supervisor')->get();
                    @endphp
                    @foreach($supervisors as $supervisor)
                        @php
                            $count = App\Models\Appraisal::whereHas('user', function($query) use ($supervisor) {
                                $query->where('manager_id', $supervisor->employee_number);
                            })->count();
                        @endphp
                        <a href="{{ route('admin.appraisals.supervisor', $supervisor) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:border-[#110484]/30 hover:shadow-md transition duration-200 bg-gradient-to-br from-white to-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-violet-600 flex items-center justify-center mr-3">
                                            <i class="fas fa-user-tie text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $supervisor->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $supervisor->employee_number }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users mr-1"></i>
                                        <span>{{ $supervisor->subordinates()->count() }} team members</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-bold text-[#110484]">{{ $count }}</div>
                                    <div class="text-xs text-gray-500">Appraisals</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

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
                        <p class="text-xs text-gray-400">Admin Panel v1.0.0</p>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-users mr-1"></i> Users
                    </a>
                    <a href="{{ route('admin.appraisals.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-file-alt mr-1"></i> Appraisals
                    </a>
                    <a href="{{ route('profile.show') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-user-circle mr-1"></i> Profile
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
            
            // Add confirmation for all forms with confirm attribute
            const confirmForms = document.querySelectorAll('form[onsubmit*="confirm"]');
            confirmForms.forEach(form => {
                form.onsubmit = function(e) {
                    const message = this.getAttribute('onsubmit').match(/confirm\('([^']+)'\)/)[1];
                    return confirm(message);
                };
            });
        });
    </script>
</body>
</html>