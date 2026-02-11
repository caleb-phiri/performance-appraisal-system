<div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Employee Details
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Position & Department
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            User Type
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100" id="usersTableBody">
                    @php
                        $initialUsers = $users->take(10);
                        $remainingUsers = $users->skip(10);
                    @endphp
                    
                    @foreach($initialUsers as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $user->left_company ? 'bg-red-50' : '' }}">
                        <!-- Employee Details -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->employee_number }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $user->email ?? 'No email' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Position & Department -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->job_title }}</div>
                            <div class="text-sm text-gray-600">{{ $user->department }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $user->workstation_type }}</div>
                        </td>
                        
                        <!-- User Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->user_type === 'supervisor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                <i class="{{ $user->user_type === 'supervisor' ? 'fas fa-user-shield' : 'fas fa-user-tie' }} mr-1"></i>
                                {{ ucfirst($user->user_type) }}
                            </span>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->left_company)
                                <div class="flex items-center">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-user-slash mr-1"></i> Left Company
                                    </span>
                                    @if($user->left_at)
                                        <div class="ml-2 text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($user->left_at)->format('M d') }}
                                        </div>
                                    @endif
                                </div>
                            @elseif($user->password)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-key mr-1"></i> Active
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> No Password
                                </span>
                            @endif
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <!-- View Button -->
                                <a href="{{ route('admin.users.show', $user->employee_number) }}" 
                                   class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Reset Password Button -->
                                <a href="{{ route('admin.users.reset-password', $user->employee_number) }}" 
                                   class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200"
                                   title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </a>
                                
                                @if(!$user->left_company)
                                    <!-- Mark as Left Button -->
                                    <button type="button" 
                                            onclick="openMarkAsLeftModal('{{ $user->employee_number }}', '{{ $user->name }}')"
                                            class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors duration-200"
                                            title="Mark as Left Company">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                @else
                                    <!-- Reactivate Button -->
                                    <button type="button" 
                                            onclick="openReactivateModal('{{ $user->employee_number }}', '{{ $user->name }}')"
                                            class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200"
                                            title="Reactivate Account">
                                        <i class="fas fa-user-check"></i>
                                    </button>
                                @endif
                                
                              
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    <!-- Hidden rows for remaining users -->
                    <div id="remainingUsers" style="display: none;">
                        @foreach($remainingUsers as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $user->left_company ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->employee_number }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $user->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->job_title }}</div>
                                <div class="text-sm text-gray-600">{{ $user->department }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $user->workstation_type }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $user->user_type === 'supervisor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    <i class="{{ $user->user_type === 'supervisor' ? 'fas fa-user-shield' : 'fas fa-user-tie' }} mr-1"></i>
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->left_company)
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-user-slash mr-1"></i> Left Company
                                        </span>
                                        @if($user->left_at)
                                            <div class="ml-2 text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($user->left_at)->format('M d') }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($user->password)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-key mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> No Password
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->employee_number) }}" 
                                       class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.users.reset-password', $user->employee_number) }}" 
                                       class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200"
                                       title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    
                                    @if(!$user->left_company)
                                        <button type="button" 
                                                onclick="openMarkAsLeftModal('{{ $user->employee_number }}', '{{ $user->name }}')"
                                                class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors duration-200"
                                                title="Mark as Left Company">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    @else
                                        <button type="button" 
                                                onclick="openReactivateModal('{{ $user->employee_number }}', '{{ $user->name }}')"
                                                class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200"
                                                title="Reactivate Account">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('admin.users.edit', $user->employee_number) }}" 
                                       class="p-2 rounded-lg bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors duration-200"
                                       title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </div>
                </tbody>
            </table>
        </div>
        
        <!-- User Count -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing <span id="showingCount">{{ $initialUsers->count() }}</span> of {{ $users->total() }} users
                </div>
                <div>
                    @if($users->count() > 10)
                        <!-- Load More Button -->
                        <button id="loadMoreBtn" 
                                onclick="loadMoreUsers()"
                                class="px-4 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> Load More ({{ $remainingUsers->count() }} remaining)
                        </button>
                        
                        <!-- Show All Button -->
                        <button id="showAllBtn" 
                                onclick="showAllUsers()"
                                class="ml-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:shadow transition flex items-center"
                                style="display: none;">
                            <i class="fas fa-list mr-2"></i> Show All Users
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    Page {{ $users->currentPage() }} of {{ $users->lastPage() }}
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
        
    @else
        <!-- Empty State -->
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-users text-3xl text-blue-600"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No Users Found</h3>
            <p class="text-gray-500 mb-6">No users match your search criteria.</p>
            <div class="flex justify-center space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Reset Search
                </a>
                
            </div>
        </div>
    @endif
</div>