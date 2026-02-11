<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Appraisals - MOIC Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .tab-active {
            position: relative;
        }
        .tab-active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #110484, #1a0c9e);
            border-radius: 3px 3px 0 0;
        }
        
        .score-bar {
            transition: width 0.5s ease-in-out;
        }
        
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(17, 4, 132, 0.1);
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="gradient-header text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
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
                    
                    <!-- Title -->
                    <div class="flex items-center">
                        <div class="h-8 w-[1px] bg-white/30 mx-4"></div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">Supervisor Appraisal Details</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">Viewing appraisals for {{ $supervisor->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.appraisals.index') }}" class="text-white hover:text-blue-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Appraisals
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-white/20 text-white px-3 py-1.5 rounded text-sm hover:bg-white/30 transition duration-200 font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Supervisor Info Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6 hover-lift">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center mr-4">
                        <i class="fas fa-user-tie text-[#110484] text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-[#110484]">{{ $supervisor->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <div>
                                <p class="text-sm text-gray-500">Employee ID</p>
                                <p class="font-semibold text-gray-800">{{ $supervisor->employee_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Department</p>
                                <p class="font-semibold text-gray-800">{{ $supervisor->department ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Team Size</p>
                                <p class="font-semibold text-gray-800">{{ $teamMembers->count() }} members</p>
                            </div>
                        </div>
                        
                        <!-- Supervisor contact info -->
                        @if($supervisor->email || $supervisor->phone)
                        <div class="flex flex-wrap gap-3 mt-3">
                            @if($supervisor->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope mr-2 text-[#e7581c]"></i>
                                <span>{{ $supervisor->email }}</span>
                            </div>
                            @endif
                            @if($supervisor->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone mr-2 text-[#e7581c]"></i>
                                <span>{{ $supervisor->phone }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="text-center lg:text-right">
                    <div class="text-sm text-gray-500 mb-1">Total Appraisals Managed</div>
                    <div class="text-3xl font-bold text-[#110484]">{{ $approvedAppraisals->total() + $submittedAppraisals->total() }}</div>
                    <div class="flex items-center justify-center lg:justify-end gap-4 mt-3">
                        <span class="inline-flex items-center text-sm">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span>
                            {{ $approvedAppraisals->total() }} Approved
                        </span>
                        <span class="inline-flex items-center text-sm">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span>
                            {{ $submittedAppraisals->total() }} Pending
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button id="approvedTab" 
                            class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 tab-active"
                            onclick="switchTab('approved')">
                        <i class="fas fa-check-circle mr-2"></i>
                        Approved Appraisals 
                        <span class="ml-1 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">
                            {{ $approvedAppraisals->total() }}
                        </span>
                    </button>
                    <button id="submittedTab" 
                            class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            onclick="switchTab('submitted')">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submitted for Approval
                        <span class="ml-1 bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                            {{ $submittedAppraisals->total() }}
                        </span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Approved Appraisals Table -->
        <div id="approvedSection" class="mb-8">
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden hover-lift">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                Approved Appraisals
                            </h3>
                            <p class="text-sm text-green-600 mt-1">Appraisals that have been reviewed and approved by {{ $supervisor->name }}</p>
                        </div>
                        @if($approvedAppraisals->total() > 0)
                        <span class="text-sm text-green-700">
                            Avg Score: 
                            <span class="font-bold">
                                {{ number_format($approvedAppraisals->avg('final_score') ?? $approvedAppraisals->avg('self_score') ?? 0, 1) }}%
                            </span>
                        </span>
                        @endif
                    </div>
                </div>
                
                @if($approvedAppraisals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($approvedAppraisals as $appraisal)
                            @php
                                $score = $appraisal->final_score ?? $appraisal->self_score ?? 0;
                                $scoreColor = $score >= 70 ? 'text-green-600' : ($score >= 50 ? 'text-yellow-600' : 'text-red-600');
                                $approvedDate = $appraisal->approved_at ?? $appraisal->updated_at;
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center mr-3">
                                            <span class="text-green-600 font-semibold text-sm">
                                                {{ strtoupper(substr($appraisal->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $appraisal->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appraisal->period ?? 'Q4 ' . date('Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $appraisal->form_type ?? 'Annual' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                            <div class="bg-gradient-to-r from-green-400 to-green-500 h-2.5 rounded-full score-bar" 
                                                 style="width: {{ min($score, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $scoreColor }}">
                                            {{ number_format($score, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $approvedDate ? $approvedDate->format('M d, Y') : 'N/A' }}
                                    @if($approvedDate)
                                    <div class="text-xs text-gray-400">
                                        {{ $approvedDate->format('h:i A') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($appraisal->start_date && $appraisal->end_date)
                                        {{ \Carbon\Carbon::parse($appraisal->start_date)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($appraisal->end_date)->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-1">
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                           class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition duration-200"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}?print=true" 
                                           class="bg-gray-50 text-gray-600 p-2 rounded hover:bg-gray-100 transition duration-200"
                                           title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination for Approved Appraisals -->
                    @if($approvedAppraisals->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-700 mb-2 md:mb-0">
                                Showing {{ $approvedAppraisals->firstItem() }} to {{ $approvedAppraisals->lastItem() }} of {{ $approvedAppraisals->total() }} results
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $approvedAppraisals->links('pagination::tailwind') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-12">
                    <div class="inline-block p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-full mb-4">
                        <i class="fas fa-check-circle text-green-400 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Approved Appraisals</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        {{ $supervisor->name }} has not approved any appraisals yet.
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Submitted Appraisals Table (Hidden by Default) -->
        <div id="submittedSection" class="mb-8 hidden">
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden hover-lift">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-800">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Submitted for Approval
                            </h3>
                            <p class="text-sm text-blue-600 mt-1">Appraisals waiting for review and approval by {{ $supervisor->name }}</p>
                        </div>
                        @if($submittedAppraisals->total() > 0)
                        <span class="text-sm text-blue-700">
                            Avg Days Waiting: 
                            <span class="font-bold">
                                {{ number_format($submittedAppraisals->map(function($a) {
                                    $date = $a->submitted_at ?? $a->updated_at;
                                    return $date ? floor(\Carbon\Carbon::parse($date)->diffInDays(now())) : 0;
                                })->avg() ?? 0, 0) }} days
                            </span>
                        </span>
                        @endif
                    </div>
                </div>
                
                @if($submittedAppraisals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Self Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Waiting</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($submittedAppraisals as $appraisal)
                            @php
                                // Use updated_at as fallback if submitted_at is not set
                                $submittedDate = $appraisal->submitted_at ?? $appraisal->updated_at;
                                
                                // Use floor() to get whole days only (no decimals)
                                $daysWaiting = $submittedDate ? floor(\Carbon\Carbon::parse($submittedDate)->diffInDays(now())) : 0;
                                $daysColor = $daysWaiting > 7 ? 'text-red-600' : ($daysWaiting > 3 ? 'text-yellow-600' : 'text-green-600');
                                
                                $score = $appraisal->self_score ?? 0;
                                $scoreColor = $score >= 70 ? 'text-green-600' : ($score >= 50 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ strtoupper(substr($appraisal->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $appraisal->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $appraisal->employee_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $appraisal->period ?? 'Q4 ' . date('Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $appraisal->form_type ?? 'Annual' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                            <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-2.5 rounded-full score-bar" 
                                                 style="width: {{ min($score, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-bold {{ $scoreColor }}">
                                            {{ number_format($score, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $submittedDate ? $submittedDate->format('M d, Y') : 'N/A' }}
                                    @if($submittedDate)
                                    <div class="text-xs text-gray-400">
                                        {{ $submittedDate->format('h:i A') }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="{{ $daysColor }} font-semibold">
                                            {{ $daysWaiting }} day{{ $daysWaiting != 1 ? 's' : '' }}
                                        </span>
                                        @if($daysWaiting > 7)
                                        <div class="text-xs text-red-500 mt-1 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Overdue
                                        </div>
                                        @elseif($daysWaiting > 3)
                                        <div class="text-xs text-yellow-500 mt-1 flex items-center">
                                            <i class="fas fa-clock mr-1"></i> Needs attention
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-1">
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}" 
                                           class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition duration-200"
                                           title="Review Appraisal">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('appraisals.show', $appraisal->id) }}?print=true" 
                                           class="bg-gray-50 text-gray-600 p-2 rounded hover:bg-gray-100 transition duration-200"
                                           title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if(auth()->user()->user_type === 'admin')
                                        <a href="{{ route('supervisor.review', $appraisal->id) }}" 
                                           class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition duration-200"
                                           title="Review as Admin">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Pagination for Submitted Appraisals -->
                    @if($submittedAppraisals->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-700 mb-2 md:mb-0">
                                Showing {{ $submittedAppraisals->firstItem() }} to {{ $submittedAppraisals->lastItem() }} of {{ $submittedAppraisals->total() }} results
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $submittedAppraisals->links('pagination::tailwind') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-12">
                    <div class="inline-block p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full mb-4">
                        <i class="fas fa-inbox text-blue-400 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Appraisals Submitted</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        No appraisals are currently waiting for approval from {{ $supervisor->name }}.
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Team Members Section -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 hover-lift">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-[#110484] mb-2">
                        <i class="fas fa-users mr-2"></i>
                        Team Members ({{ $teamMembers->count() }})
                    </h3>
                    <p class="text-sm text-gray-600">Employees under {{ $supervisor->name }}'s supervision</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        <span class="font-semibold text-[#110484]">{{ $teamMembers->count() }}</span> active team members
                    </div>
                </div>
            </div>
            
            @if($teamMembers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($teamMembers as $member)
                @php
                    $memberAppraisals = $member->appraisals ?? collect([]);
                    $approvedCount = $memberAppraisals->where('status', 'approved')->count();
                    $pendingCount = $memberAppraisals->where('status', 'submitted')->count();
                    $totalCount = $approvedCount + $pendingCount;
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 hover:border-[#110484]/30 hover:shadow-sm transition duration-200 bg-gradient-to-br from-white to-gray-50">
                    <div class="flex items-start mb-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center mr-3">
                            <span class="text-gray-600 font-semibold text-sm">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $member->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $member->employee_number }}</p>
                            <p class="text-xs text-gray-500">{{ $member->job_title ?? 'Employee' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-green-50 rounded p-2 text-center">
                            <div class="text-lg font-bold text-green-600">{{ $approvedCount }}</div>
                            <div class="text-xs text-gray-600">Approved</div>
                        </div>
                        <div class="bg-blue-50 rounded p-2 text-center">
                            <div class="text-lg font-bold text-blue-600">{{ $pendingCount }}</div>
                            <div class="text-xs text-gray-600">Pending</div>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <div class="text-lg font-bold text-gray-600">{{ $totalCount }}</div>
                            <div class="text-xs text-gray-600">Total</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xs">
                        <a href="{{ route('admin.users.show', $member->employee_number) }}" 
                           class="text-[#110484] hover:text-[#0a0369] font-medium flex items-center">
                            <i class="fas fa-user mr-1"></i> Profile
                        </a>
                        <a href="{{ route('appraisals.index', ['employee_number' => $member->employee_number]) }}" 
                           class="text-gray-600 hover:text-gray-900 flex items-center">
                            <i class="fas fa-file-alt mr-1"></i> Appraisals
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-3">
                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">No team members assigned to this supervisor.</p>
            </div>
            @endif
        </div>

        <!-- Back to Top -->
        <div class="mt-6 text-center">
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="inline-flex items-center text-sm text-gray-600 hover:text-[#110484]">
                <i class="fas fa-arrow-up mr-2"></i> Back to Top
            </button>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 pt-6 border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <!-- MOIC Logo in footer -->
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Admin Panel - Supervisor View</p>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.appraisals.index') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-file-alt mr-1"></i> All Appraisals
                    </a>
                    <a href="{{ route('profile.show') }}" class="text-sm text-[#110484] hover:text-[#e7581c] font-medium">
                        <i class="fas fa-user-circle mr-1"></i> My Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Remove active class from all tabs
            document.getElementById('approvedTab').classList.remove('border-[#110484]', 'text-[#110484]', 'tab-active');
            document.getElementById('submittedTab').classList.remove('border-[#110484]', 'text-[#110484]', 'tab-active');
            
            // Add active class to clicked tab
            document.getElementById(tabName + 'Tab').classList.add('border-[#110484]', 'text-[#110484]', 'tab-active');
            
            // Hide all sections
            document.getElementById('approvedSection').classList.add('hidden');
            document.getElementById('submittedSection').classList.add('hidden');
            
            // Show selected section
            document.getElementById(tabName + 'Section').classList.remove('hidden');
            
            // Update URL hash without page reload
            history.pushState(null, null, '#' + tabName);
        }
        
        // Check URL hash on page load
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.substring(1);
            if (hash === 'submitted') {
                switchTab('submitted');
            }
            
            // Animate score bars
            setTimeout(() => {
                const scoreBars = document.querySelectorAll('.score-bar');
                scoreBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 300);
        });
    </script>
</body>
</html>