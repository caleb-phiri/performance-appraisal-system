<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('admin.users.index') }}" class="text-white hover:text-blue-200 mr-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="bg-white p-1.5 rounded-md mr-3">
                        <i class="fas fa-user text-[#110484]"></i>
                    </div>
                    <h1 class="text-xl font-bold">User Details</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-blue-200">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-blue-200">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: User Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-16 w-16 rounded-full bg-white flex items-center justify-center mr-4">
                                <span class="text-2xl font-bold text-[#110484]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                                <p class="text-blue-200">{{ $user->employee_number }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Basic Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Job Title</p>
                                        <p class="font-medium">{{ $user->job_title }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">User Type</p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $user->user_type === 'supervisor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Department</p>
                                        <p class="font-medium">{{ $user->department }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Workstation Type</p>
                                        <p class="font-medium">{{ $user->workstation_type }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact & Account -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Contact & Account</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium">{{ $user->email ?? 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Password Status</p>
                                        @if($user->password)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-key mr-1"></i> Password Set
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> No Password
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Manager</p>
                                        <p class="font-medium">
                                            @if($user->manager_id)
                                                {{ \App\Models\User::where('employee_number', $user->manager_id)->first()->name ?? $user->manager_id }}
                                            @else
                                                No manager assigned
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Account Created</p>
                                        <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Additional Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Toll Plaza</p>
                                        <p class="font-medium">{{ $user->toll_plaza ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">HQ Department</p>
                                        <p class="font-medium">{{ $user->hq_department ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Team Members Count</p>
                                        <p class="font-medium">
                                            @if($user->user_type === 'supervisor' && isset($teamMembers))
                                                {{ count($teamMembers) }}
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Last Updated</p>
                                        <p class="font-medium">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Team Members (if supervisor) -->
                @if($user->user_type === 'supervisor' && isset($teamMembers) && count($teamMembers) > 0)
                <div class="mt-6 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Team Members ({{ count($teamMembers) }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Password</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($teamMembers as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.users.show', $member->employee_number) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            {{ $member->employee_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->job_title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->password)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Set</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Not Set</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Actions & Stats -->
            <div>
                <!-- Actions Card -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.reset-password', $user->employee_number) }}" 
                           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition font-medium">
                            <i class="fas fa-key mr-2"></i> Reset Password
                        </a>
                        
                        @if($user->password)
                        <form action="{{ route('admin.users.remove-password', $user->employee_number) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Remove password? User will login with employee number only.')"
                                    class="block w-full text-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white rounded-lg hover:shadow-lg transition font-medium">
                                <i class="fas fa-unlock mr-2"></i> Remove Password
                            </button>
                        </form>
                        @endif
                        
                        <!-- Toggle Status Form -->
                        <form action="{{ route('admin.users.toggle-status', $user->employee_number) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('{{ $user->is_active ? 'Deactivate' : 'Activate' }} this user?')"
                                    class="block w-full text-center px-4 py-3 bg-gradient-to-r {{ $user->is_active ? 'from-red-500 to-orange-600' : 'from-green-500 to-emerald-600' }} text-white rounded-lg hover:shadow-lg transition font-medium">
                                <i class="fas fa-toggle-{{ $user->is_active ? 'on' : 'off' }} mr-2"></i> 
                                {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                            </button>
                        </form>
                        
                        <!-- Reactivate button (only show if user is inactive) -->
                        @if(!$user->is_active)
                        <form action="{{ route('admin.users.reactivate', $user->employee_number) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Reactivate this user?')"
                                    class="block w-full text-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition font-medium">
                                <i class="fas fa-check-circle mr-2"></i> Reactivate User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                
                <!-- Approval Stats (if supervisor) -->
                @if($user->user_type === 'supervisor' && isset($approvalStats))
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Approval Statistics</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Approval Rate</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ isset($approvalStats['total']) && $approvalStats['total'] > 0 ? round(($approvalStats['approved'] / $approvalStats['total']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" 
                                     style="width: {{ isset($approvalStats['total']) && $approvalStats['total'] > 0 ? ($approvalStats['approved'] / $approvalStats['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-center p-3 bg-blue-50 rounded">
                                <p class="text-sm text-gray-600">Pending</p>
                                <p class="text-xl font-bold text-blue-600">{{ $approvalStats['pending'] ?? 0 }}</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded">
                                <p class="text-sm text-gray-600">Approved</p>
                                <p class="text-xl font-bold text-green-600">{{ $approvalStats['approved'] ?? 0 }}</p>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded">
                                <p class="text-sm text-gray-600">Rejected</p>
                                <p class="text-xl font-bold text-red-600">{{ $approvalStats['rejected'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- User Status & Quick Info -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">User Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full {{ $user->is_active ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center mr-3">
                                <i class="fas {{ $user->is_active ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }}"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Account Status</p>
                                <p class="font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <i class="fas fa-id-card text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Employee Number</p>
                                <p class="font-medium">{{ $user->employee_number }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user-tag text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Account Type</p>
                                <p class="font-medium">{{ ucfirst($user->user_type) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Member Since</p>
                                <p class="font-medium">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                        
                        @if($user->deactivated_at)
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-times text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Deactivated On</p>
                                <p class="font-medium">{{ $user->deactivated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->left_reason)
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Left Reason</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $user->left_reason)) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmation for actions
        document.addEventListener('DOMContentLoaded', function() {
            const actionForms = document.querySelectorAll('form');
            actionForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const confirmMessage = this.querySelector('button[onclick]')?.getAttribute('onclick')?.match(/confirm\('([^']+)'\)/)?.[1];
                    if (confirmMessage && !confirm(confirmMessage)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>