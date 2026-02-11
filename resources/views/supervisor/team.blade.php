@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">My Team Members</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Appraisals</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($team as $member)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->employee_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->is_primary)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Primary Supervisor
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Rating Supervisor
                                    </span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $member->rating_weight }}% weight</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->department ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($member->pending_count > 0)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $member->pending_count }} pending
                                    </span>
                                    @else
                                    <span class="text-sm text-gray-500">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($member->average_score > 0)
                                    <div class="flex items-center">
                                        <span class="font-semibold 
                                            @if($member->average_score >= 90) text-green-600
                                            @elseif($member->average_score >= 70) text-blue-600
                                            @elseif($member->average_score >= 50) text-yellow-600
                                            @else text-red-600 @endif">
                                            {{ $member->average_score }}%
                                        </span>
                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full
                                                @if($member->average_score >= 90) bg-green-500
                                                @elseif($member->average_score >= 70) bg-blue-500
                                                @elseif($member->average_score >= 50) bg-yellow-500
                                                @else bg-red-500 @endif"
                                                 style="width: {{ min($member->average_score, 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-gray-400">No data</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('appraisals.index') }}?employee_number={{ $member->employee_number }}" 
                                       class="text-blue-600 hover:text-blue-900">View Appraisals</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No team members assigned yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $team->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection