<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave Applications - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('layouts.navigation')
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-[#110484]">My Leave Applications</h1>
                    <p class="text-gray-600 mt-2">View and manage your leave requests</p>
                </div>
                <a href="{{ route('leave.create') }}" 
                   class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-4 py-2.5 rounded hover:shadow transition duration-200 font-medium">
                    <i class="fas fa-plus mr-2"></i> New Leave Application
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
                $stats = [
                    'total' => ['count' => $leaves->total(), 'label' => 'Total Applications', 'color' => 'from-blue-500 to-cyan-600', 'icon' => 'fas fa-calendar-alt'],
                    'pending' => ['count' => $leaves->where('status', 'pending')->count(), 'label' => 'Pending', 'color' => 'from-yellow-500 to-amber-600', 'icon' => 'fas fa-clock'],
                    'approved' => ['count' => $leaves->where('status', 'approved')->count(), 'label' => 'Approved', 'color' => 'from-green-500 to-emerald-600', 'icon' => 'fas fa-check-circle'],
                    'rejected' => ['count' => $leaves->where('status', 'rejected')->count(), 'label' => 'Rejected', 'color' => 'from-red-500 to-pink-600', 'icon' => 'fas fa-times-circle'],
                ];
            @endphp
            
            @foreach($stats as $stat)
            <div class="bg-white rounded-lg shadow border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stat['count'] }}</p>
                    </div>
                    <div class="bg-gradient-to-r {{ $stat['color'] }} text-white p-3 rounded-full">
                        <i class="{{ $stat['icon'] }} text-lg"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Leave Applications Table -->
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-[#110484]">Recent Applications</h3>
            </div>
            
            @if($leaves->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Applied On</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 text-sm text-[#110484] font-medium">#{{ $leave->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $leave->leave_type_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $leave->start_date->format('M d, Y') }} - 
                                {{ $leave->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="font-semibold">{{ $leave->total_days }}</span> days
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded {{ $leave->status_badge }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $leave->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('leave.show', $leave->id) }}" 
                                       class="bg-blue-50 text-[#110484] p-2 rounded hover:bg-blue-100 transition duration-200"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($leave->isPending())
                                    <a href="{{ route('leave.edit', $leave->id) }}" 
                                       class="bg-green-50 text-green-600 p-2 rounded hover:bg-green-100 transition duration-200"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('leave.destroy', $leave->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 transition duration-200"
                                                title="Cancel"
                                                onclick="return confirm('Are you sure you want to cancel this leave request?')">
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
            </div>
            
            <!-- Pagination -->
            @if($leaves->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $leaves->links() }}
            </div>
            @endif
            
            @else
            <div class="text-center py-12">
                <div class="inline-block p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-full mb-4">
                    <i class="fas fa-calendar-alt text-[#110484] text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#110484] mb-2">No leave applications</h3>
                <p class="text-gray-600 mb-4">You haven't submitted any leave applications yet.</p>
                <a href="{{ route('leave.create') }}" class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-4 py-2 rounded hover:shadow transition duration-200 font-medium">
                    <i class="fas fa-plus mr-2"></i> Create First Leave Application
                </a>
            </div>
            @endif
        </div>

        <!-- Upcoming Approved Leaves -->
        @php
            $upcomingLeaves = $leaves->where('status', 'approved')
                                    ->where('start_date', '>=', now())
                                    ->sortBy('start_date')
                                    ->take(3);
        @endphp
        
        @if($upcomingLeaves->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-green-50 to-emerald-50">
                <h3 class="text-lg font-bold text-green-800">
                    <i class="fas fa-calendar-check mr-2"></i> Upcoming Approved Leaves
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($upcomingLeaves as $leave)
                    <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-green-800">{{ $leave->leave_type_name }}</h4>
                            <span class="text-xs font-medium bg-green-200 text-green-800 px-2 py-1 rounded">
                                {{ $leave->total_days }} days
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-green-600"></i>
                            {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            Starts in {{ $leave->start_date->diffForHumans(['parts' => 2]) }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>