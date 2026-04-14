@php
    $member = $memberData['user'];
    $children = $memberData['children'];
    $level = $memberData['level'];
    $isSupervisor = $member->user_type === 'supervisor';
    
    // Get member's appraisal stats
    use App\Models\Appraisal;
    $approvedCount = Appraisal::where('employee_number', $member->employee_number)
        ->where('status', 'approved')->count();
    $pendingCount = Appraisal::where('employee_number', $member->employee_number)
        ->where('status', 'submitted')->count();
@endphp

<div class="team-member-node ml-{{ $level * 4 }} mb-3">
    <div class="bg-white border rounded-xl p-4 hover:shadow-lg transition-all duration-300 cursor-pointer
                {{ $isSupervisor ? 'border-l-4 border-moic-accent' : 'border-l-4 border-moic-navy' }}"
         data-employee-number="{{ $member->employee_number }}"
         onclick="viewMemberDetails('{{ $member->employee_number }}', '{{ $member->name }}')">
        
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-4 flex-1">
                <!-- Avatar with level indicator -->
                <div class="relative">
                    <div class="w-12 h-12 rounded-xl {{ $isSupervisor ? 'bg-gradient-to-r from-moic-accent to-orange-500' : 'gradient-moic-navy' }} flex items-center justify-center">
                        <i class="fas {{ $isSupervisor ? 'fa-user-tie' : 'fa-user' }} text-white text-lg"></i>
                    </div>
                    @if($level > 0)
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-700 border-2 border-white">
                        {{ $level }}
                    </div>
                    @endif
                </div>
                
                <!-- Member Info -->
                <div class="flex-1">
                    <div class="flex items-center">
                        <h4 class="font-bold text-gray-900">{{ $member->name }}</h4>
                        @if($isSupervisor)
                            <span class="ml-2 text-xs bg-gradient-to-r from-orange-100 to-orange-200 text-orange-700 px-2 py-0.5 rounded-full">
                                Supervisor
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3 mt-1 text-sm">
                        <span class="text-gray-600">{{ $member->employee_number }}</span>
                        <span class="text-gray-400">•</span>
                        <span class="text-gray-600">{{ $member->job_title ?? 'Employee' }}</span>
                    </div>
                    
                    <!-- Stats Badges -->
                    <div class="flex items-center space-x-2 mt-2">
                        @if($approvedCount > 0)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i> {{ $approvedCount }} approved
                        </span>
                        @endif
                        @if($pendingCount > 0)
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">
                            <i class="fas fa-clock mr-1"></i> {{ $pendingCount }} pending
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-2" onclick="event.stopPropagation();">
                <button onclick="viewMemberAppraisals('{{ $member->employee_number }}', '{{ $member->name }}')"
                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View All Appraisals">
                    <i class="fas fa-eye"></i>
                </button>
                @if($pendingCount > 0)
                <a href="{{ route('appraisals.index', ['employee' => $member->employee_number, 'status' => 'submitted']) }}"
                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="View Pending">
                    <i class="fas fa-clock"></i>
                </a>
                @endif
                <a href="{{ route('appraisals.create', ['employee' => $member->employee_number]) }}"
                   class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Create New Appraisal">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Render children recursively -->
    @if(count($children) > 0)
        <div class="ml-8 mt-2 space-y-2 border-l-2 border-dashed border-gray-300 pl-4">
            @foreach($children as $child)
                @include('partials.team-member-node', ['memberData' => $child, 'level' => $child['level']])
            @endforeach
        </div>
    @endif
</div>