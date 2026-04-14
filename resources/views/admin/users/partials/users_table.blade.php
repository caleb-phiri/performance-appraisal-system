<div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                @foreach($users->take(10) as $user)
                <tr class="user-row hover:bg-gray-50 transition-all duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user->employee_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->employee_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->department ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->user_type === 'admin')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-crown mr-1 text-xs"></i> Admin
                            </span>
                        @elseif($user->user_type === 'supervisor')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-user-shield mr-1 text-xs"></i> Supervisor
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-user mr-1 text-xs"></i> Employee
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->left_company_at)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-user-slash mr-1 text-xs"></i> Left Company
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1 text-xs" style="font-size: 8px;"></i> Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-2">
                            @if($user->left_company_at)
                                <!-- Reactivate Button -->
                                <button onclick="openReactivateModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}')"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-xs flex items-center">
                                    <i class="fas fa-user-check mr-1"></i> Reactivate
                                </button>
                            @else
                                <!-- Make Supervisor Button (Only for non-supervisors) -->
                                @if($user->user_type !== 'supervisor' && $user->user_type !== 'admin')
                                <button onclick="openMakeSupervisorModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}', '{{ $user->user_type }}')"
                                        class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-xs flex items-center">
                                    <i class="fas fa-user-shield mr-1"></i> Make Supervisor
                                </button>
                                @endif
                                
                                <!-- Mark as Left Button -->
                                <button onclick="openMarkAsLeftModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}')"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs flex items-center">
                                    <i class="fas fa-user-slash mr-1"></i> Mark as Left
                                </button>
                                
                                <!-- View Details Button -->
                                <a href="{{ route('admin.users.show', $user->employee_number) }}" 
                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs flex items-center">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
            <!-- Hidden rows for remaining users (for load more functionality) -->
            @if($users->count() > 10)
                <tbody id="remainingUsers" class="hidden">
                    @foreach($users->skip(10) as $user)
                    <tr class="user-row hover:bg-gray-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->employee_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->department ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->user_type === 'admin')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-crown mr-1 text-xs"></i> Admin
                                </span>
                            @elseif($user->user_type === 'supervisor')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-user-shield mr-1 text-xs"></i> Supervisor
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-user mr-1 text-xs"></i> Employee
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->left_company_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-user-slash mr-1 text-xs"></i> Left Company
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-circle mr-1 text-xs" style="font-size: 8px;"></i> Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                @if($user->left_company_at)
                                    <button onclick="openReactivateModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}')"
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-xs flex items-center">
                                        <i class="fas fa-user-check mr-1"></i> Reactivate
                                    </button>
                                @else
                                    @if($user->user_type !== 'supervisor' && $user->user_type !== 'admin')
                                    <button onclick="openMakeSupervisorModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}', '{{ $user->user_type }}')"
                                            class="px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-xs flex items-center">
                                        <i class="fas fa-user-shield mr-1"></i> Make Supervisor
                                    </button>
                                    @endif
                                    
                                    <button onclick="openMarkAsLeftModal('{{ $user->employee_number }}', '{{ addslashes($user->name) }}')"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs flex items-center">
                                        <i class="fas fa-user-slash mr-1"></i> Mark as Left
                                    </button>
                                    
                                    <a href="{{ route('admin.users.show', $user->employee_number) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs flex items-center">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>
    
    <!-- Load More Controls -->
    @if($users->count() > 10)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-700">
            Showing <span id="showingCount">10</span> of {{ $users->count() }} users
        </div>
        <div class="flex space-x-3">
            <button id="loadMoreBtn" 
                    onclick="loadMoreUsers()"
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center text-sm">
                <i class="fas fa-plus mr-2"></i> Load More
            </button>
            <button id="showAllBtn" 
                    onclick="showAllUsers()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center text-sm">
                <i class="fas fa-list mr-2"></i> Show All
            </button>
        </div>
    </div>
    @endif
</div>