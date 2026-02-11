<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management - MOIC Supervisor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.1);
        }
        
        .moic-navy { color: #110484; }
        .moic-accent { color: #e7581c; }
        .bg-moic-navy { background-color: #110484; }
        .bg-moic-accent { background-color: #e7581c; }
        .border-moic-navy { border-color: #110484; }
        .border-moic-accent { border-color: #e7581c; }
        
        .gradient-moic-navy {
            background: linear-gradient(135deg, #0a0463 0%, #110484 50%, #2a1d9e 100%);
        }
        
        .gradient-moic-accent {
            background: linear-gradient(135deg, #cc4a15 0%, #e7581c 50%, #ff6b2c 100%);
        }
        
        .form-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            border-color: #e7581c;
            box-shadow: 0 0 0 3px rgba(231, 88, 28, 0.1);
            outline: none;
        }
        
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
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-approved { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #fee2e2; color: #991b1b; }
        .status-cancelled { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <nav class="gradient-moic-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-white p-2.5 rounded-lg shadow-sm">
                        <img class="h-9 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Leave Management</h1>
                        <p class="text-sm text-blue-100">Supervisor Portal</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
<p class="text-xs text-blue-200">{{ Auth::user()->employee_number }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('supervisor.dashboard') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
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
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold moic-navy">Team Leave Management</h2>
                    <p class="text-gray-600">Review and manage leave requests from your team members</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="exportLeaves()" class="btn-primary">
                        <i class="fas fa-download mr-2"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Leave Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            <!-- Total Leaves -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Leaves</p>
                        <p class="text-3xl font-bold moic-navy mt-1">{{ $leaveStats['total'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                        <i class="fas fa-calendar-alt text-2xl moic-navy"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">All time records</div>
                </div>
            </div>

            <!-- Pending Leaves -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $leaveStats['pending'] ?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">Awaiting approval</div>
                </div>
            </div>

            <!-- Approved Leaves -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Approved</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $leaveStats['approved']?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">Approved by you</div>
                </div>
            </div>

            <!-- Rejected Leaves -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Rejected</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ $leaveStats['rejected']?? 0 }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl">
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">Not approved</div>
                </div>
            </div>

            <!-- Avg Days -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Avg Days</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ $leaveStats['avg_days'] ?? 0}}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                        <i class="fas fa-calendar-day text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-500">Per approved leave</div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('supervisor.leaves') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="form-input w-full" onchange="this.form.submit()">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Leave Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                        <select name="type" class="form-input w-full" onchange="this.form.submit()">
                            <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>All Types</option>
                            <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                            <option value="sick" {{ request('type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                            <option value="maternity" {{ request('type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                            <option value="paternity" {{ request('type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                            <option value="study" {{ request('type') == 'study' ? 'selected' : '' }}>Study Leave</option>
                            <option value="compassionate" {{ request('type') == 'compassionate' ? 'selected' : '' }}>Compassionate Leave</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="form-input w-full" onchange="this.form.submit()">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="form-input w-full" onchange="this.form.submit()">
                    </div>
                </div>

                <!-- Search -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by employee name, number, or reason..." 
                                   class="form-input w-full pl-10">
                        </div>
                    </div>
                    <button type="submit" class="btn-accent">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('supervisor.leaves') }}" class="btn-primary">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Leaves Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Leave Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date Applied
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-moic-navy flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->user->name ?? 'Unknown Employee' }}</div>
                                        <div class="text-xs text-gray-500">{{ $leave->user->employee_number ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">{{ $leave->user->department ?? 'Unknown Department' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @php
                                        $leaveTypes = [
                                            'annual' => 'Annual Leave',
                                            'sick' => 'Sick Leave',
                                            'maternity' => 'Maternity Leave',
                                            'paternity' => 'Paternity Leave',
                                            'study' => 'Study Leave',
                                            'compassionate' => 'Compassionate Leave',
                                            'other' => 'Other Leave',
                                        ];
                                    @endphp
                                    {{ $leaveTypes[$leave->leave_type] ?? ucfirst($leave->leave_type) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $leave->total_days }} day(s)
                                </div>
                                @if($leave->reason)
                                <div class="text-xs text-gray-400 mt-1 truncate max-w-xs">
                                    "{{ Str::limit($leave->reason, 80) }}"
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($leave->status == 'pending')
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @elseif($leave->status == 'approved')
                                    <span class="status-badge status-approved">
                                        <i class="fas fa-check mr-1"></i> Approved
                                    </span>
                                    @if($leave->approved_at)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y') }}
                                    </div>
                                    @endif
                                @elseif($leave->status == 'rejected')
                                    <span class="status-badge status-rejected">
                                        <i class="fas fa-times mr-1"></i> Rejected
                                    </span>
                                @elseif($leave->status == 'cancelled')
                                    <span class="status-badge status-cancelled">
                                        <i class="fas fa-ban mr-1"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    @if($leave->status == 'pending')
                                        <button onclick="approveLeave({{ $leave->id }})" 
                                                class="text-green-600 hover:text-green-800"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="showRejectModal({{ $leave->id }})" 
                                                class="text-red-600 hover:text-red-800"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($leave->status == 'approved')
                                        <button onclick="showCancelModal({{ $leave->id }})" 
                                                class="text-red-600 hover:text-red-800"
                                                title="Cancel">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                    <button onclick="viewLeaveDetails({{ $leave->id }})" 
                                            class="text-blue-600 hover:text-blue-800"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p class="text-lg font-medium text-gray-500">No leave requests found</p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        @if(request()->hasAny(['status', 'type', 'search', 'start_date', 'end_date']))
                                            Try changing your filters
                                        @else
                                            No leave requests have been submitted yet
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($leaves->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $leaves->withQueryString()->links() }}
            </div>
            @endif
        </div>

        <!-- Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                <h4 class="font-bold text-blue-800 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('supervisor.dashboard') }}" 
                       class="flex items-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Return to Dashboard</span>
                    </a>
                    <button onclick="exportLeaves()" class="flex items-center text-green-600 hover:text-green-800">
                        <i class="fas fa-download mr-3"></i>
                        <span>Export Leave Report</span>
                    </button>
                    <a href="mailto:?subject=Team Leave Report" 
                       class="flex items-center text-purple-600 hover:text-purple-800">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>Email Summary</span>
                    </a>
                </div>
            </div>

            <!-- Leave Calendar Link -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                <h4 class="font-bold text-green-800 mb-4">Team Calendar</h4>
                <p class="text-sm text-gray-600 mb-4">View all approved leaves on a calendar view to plan team coverage.</p>
                <a href="#" class="btn-accent inline-block">
                    <i class="fas fa-calendar-alt mr-2"></i> View Calendar
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div id="successMessage" class="fixed bottom-6 right-6 z-50">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center animate-slide-up max-w-md">
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
            if (successMsg) successMsg.remove();
        }, 5000);
    </script>
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // View leave details
        function viewLeaveDetails(leaveId) {
            window.open(`/leaves/${leaveId}`, '_blank');
        }

        // Approve leave
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
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Reject Leave Request</h3>
                        <p class="text-gray-600 mb-4">Please provide a reason for rejecting this leave request.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Rejection</label>
                            <textarea id="rejectReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
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

        // Reject leave
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
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-red-600 mb-2">Cancel Approved Leave</h3>
                        <p class="text-gray-600 mb-4">Are you sure you want to cancel this approved leave? This action cannot be undone.</p>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Reason for Cancellation</label>
                            <textarea id="cancelReason" rows="4" 
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
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

        // Cancel leave
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

        // Export leaves
        function exportLeaves() {
            showToast('info', 'Export feature coming soon!');
        }

        // Toast notification
        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-xl text-white flex items-center animate-slide-up ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-4">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info-circle'}"></i>
                </div>
                <div>
                    <p class="font-semibold">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-6 hover:opacity-80">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slide-up {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .animate-slide-up { animation: slide-up 0.3s ease-out; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>