<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    /* Loading spinner styles */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Button transition styles */
.smooth-transition {
    transition: all 0.3s ease;
}

/* Success animation */
@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.success-pulse {
    animation: successPulse 0.5s ease;
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

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
                <p class="text-gray-600 mt-1">Manage all system users and their permissions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.export') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export
                </a>
                <a href="{{ route('admin.users.inactive') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-user-slash mr-2"></i> Inactive Accounts
                    @if(($stats['left_company_users'] ?? 0) > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1" id="inactiveBadge">
                            {{ $stats['left_company_users'] ?? 0 }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <!-- User Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Users</p>
                        <p class="text-3xl font-bold text-[#110484] mt-2" id="totalUsersStat">{{ $stats['total_users'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                        <i class="fas fa-users text-[#110484] text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-[#110484] hover:underline font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Users</p>
                        <p class="text-3xl font-bold text-green-600 mt-2" id="activeUsersStat">{{ $stats['active_users'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Left Company</p>
                        <p class="text-3xl font-bold text-red-600 mt-2" id="leftCompanyStat">{{ $stats['left_company_users'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-red-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.inactive') }}" class="text-sm text-red-600 hover:underline font-medium">
                        View Inactive <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Supervisors</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2" id="supervisorsStat">{{ $stats['supervisors'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
            <div class="flex">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="searchInput"
                           value="{{ request('search') }}"
                           placeholder="Search by name, employee number, department..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                           autocomplete="off">
                    <div id="searchLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none hidden">
                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                    </div>
                </div>
                <button type="button" 
                        id="searchButton"
                        onclick="performSearch()"
                        class="ml-3 px-6 py-3 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </div>
            <div id="searchError" class="mt-2 hidden">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="errorMessage">Search failed. Please try again.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table Container -->
        <div id="usersTableContainer">
            @include('admin.users.partials.users_table', ['users' => $users])
        </div>
    </div>

    <!-- Mark as Left Modal -->
    <div id="markAsLeftModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg transform transition-all">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 rounded-xl bg-gradient-to-r from-red-500 to-pink-500">
                        <i class="fas fa-user-slash text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Mark as Left Company</h3>
                        <p class="text-gray-600 mt-1">Remove employee from active system</p>
                    </div>
                </div>
                
                <form id="markAsLeftForm" method="POST">
                    @csrf
                    <input type="hidden" id="employee_number" name="employee_number">
                    
                    <div class="mb-6">
                        <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                            <p class="text-sm text-gray-600">You are about to mark as left:</p>
                            <p id="employee_name" class="text-lg font-bold text-gray-800 mt-1"></p>
                            <p class="text-xs text-red-500 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                This will remove their access to the system.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="left_reason" class="block text-sm font-medium text-gray-700 mb-3">Reason for leaving:</label>
                        <select name="left_reason" id="left_reason" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Select a reason</option>
                            <option value="resignation">Resignation</option>
                            <option value="termination">Termination</option>
                            <option value="retirement">Retirement</option>
                            <option value="end_of_contract">End of Contract</option>
                            <option value="transfer">Transfer to Another Company</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="left_notes" class="block text-sm font-medium text-gray-700 mb-3">Additional Notes (Optional):</label>
                        <textarea name="left_notes" id="left_notes" 
                                  rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                  placeholder="Any additional information..."></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                onclick="closeMarkAsLeftModal()"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Confirm & Mark as Left
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reactivate Modal -->
<div id="reactivateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg transform transition-all">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="p-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500">
                    <i class="fas fa-user-check text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Reactivate Account</h3>
                    <p class="text-gray-600 mt-1">Bring employee back to active status</p>
                </div>
            </div>
            
            <form id="reactivateForm" method="POST" onsubmit="return handleReactivateSubmit(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="reactivate_employee_number" name="employee_number">
                
                <div class="mb-6">
                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-sm text-gray-600">You are about to reactivate:</p>
                        <p id="reactivate_employee_name" class="text-lg font-bold text-gray-800 mt-1"></p>
                        <p class="text-xs text-green-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            This will restore their access to the system.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeReactivateModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="reactivateSubmitBtn"
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span class="btn-text">Confirm Reactivation</span>
                        <i class="fas fa-spinner fa-spin hidden loading-spinner"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toggle dropdown on click
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Initialize showing count
            initializeShowingCount();
            
            // Initialize search functionality
            initializeSearch();
            
            // Check for existing search on page load
            checkExistingSearch();
        });

        // Search Functionality
        let searchTimeout;
        let isSearching = false;
        
        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
            
            if (searchInput) {
                // Live search as user types (with debounce)
                searchInput.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        if (searchInput.value.length >= 2 || searchInput.value.length === 0) {
                            performSearch();
                        }
                    }, 500); // 500ms delay
                });
                
                // Search on Enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch();
                    }
                });
            }
        }
        
        function checkExistingSearch() {
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam && searchParam.trim() !== '') {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.value = searchParam;
                    // Perform search after a short delay
                    setTimeout(() => performSearch(), 100);
                }
            }
        }
        
        function performSearch() {
            if (isSearching) return;
            
            const searchInput = document.getElementById('searchInput');
            const searchValue = searchInput ? searchInput.value.trim() : '';
            const searchLoading = document.getElementById('searchLoading');
            const searchError = document.getElementById('searchError');
            const searchButton = document.getElementById('searchButton');
            
            // Show loading state
            isSearching = true;
            if (searchLoading) searchLoading.classList.remove('hidden');
            if (searchButton) searchButton.disabled = true;
            if (searchError) searchError.classList.add('hidden');
            
            // Update URL without reloading page
            const url = new URL(window.location);
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            } else {
                url.searchParams.delete('search');
            }
            window.history.pushState({}, '', url);
            
            // Send AJAX request
            $.ajax({
                url: "{{ route('admin.users.search') }}",
                type: 'GET',
                data: { search: searchValue },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Update the table with new data
                        const usersTableContainer = document.getElementById('usersTableContainer');
                        if (usersTableContainer && data.html) {
                            usersTableContainer.innerHTML = data.html;
                        }
                        
                        // Update stats
                        if (data.stats) {
                            updateStats(data.stats);
                        }
                        
                        // Reinitialize functions for new content
                        reinitializeFunctions();
                        
                        // Initialize showing count after search
                        initializeShowingCount();
                        
                        // Show success toast if search was performed
                        if (searchValue) {
                            showToast('Search completed successfully', 'success');
                        }
                    } else {
                        throw new Error(data.message || 'Search failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    
                    // Show error message
                    if (searchError) {
                        searchError.classList.remove('hidden');
                        const errorMessage = document.getElementById('errorMessage');
                        if (errorMessage) {
                            errorMessage.textContent = xhr.responseJSON?.message || 'Search failed. Please try again.';
                        }
                    }
                    
                    // Show error toast
                    showToast('Search failed. Please try again.', 'error');
                },
                complete: function() {
                    // Hide loading state
                    isSearching = false;
                    if (searchLoading) searchLoading.classList.add('hidden');
                    if (searchButton) searchButton.disabled = false;
                }
            });
        }
        
        function updateStats(stats) {
            if (!stats) return;
            
            // Update total users
            const totalUsersStat = document.getElementById('totalUsersStat');
            if (totalUsersStat && stats.total_users !== undefined) {
                totalUsersStat.textContent = stats.total_users;
            }
            
            // Update active users
            const activeUsersStat = document.getElementById('activeUsersStat');
            if (activeUsersStat && stats.active_users !== undefined) {
                activeUsersStat.textContent = stats.active_users;
            }
            
            // Update left company users
            const leftCompanyStat = document.getElementById('leftCompanyStat');
            if (leftCompanyStat && stats.left_company_users !== undefined) {
                leftCompanyStat.textContent = stats.left_company_users;
            }
            
            // Update supervisors count
            const supervisorsStat = document.getElementById('supervisorsStat');
            if (supervisorsStat && stats.supervisors !== undefined) {
                supervisorsStat.textContent = stats.supervisors;
            }
            
            // Update inactive badge
            const inactiveBadge = document.getElementById('inactiveBadge');
            if (inactiveBadge) {
                if (stats.left_company_users !== undefined) {
                    inactiveBadge.textContent = stats.left_company_users;
                    if (stats.left_company_users > 0) {
                        inactiveBadge.style.display = 'flex';
                    } else {
                        inactiveBadge.style.display = 'none';
                    }
                }
            }
        }
        
        function reinitializeFunctions() {
            // Reinitialize showing count
            initializeShowingCount();
            
            // Rebind search functionality if needed
            setTimeout(() => {
                initializeSearch();
            }, 100);
        }
        
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-blue-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            performSearch();
        });

        // Load More Users Functionality
        let showingCount = 0;
        let totalUsers = 0;
        
        function initializeShowingCount() {
            const visibleRows = document.querySelectorAll('#usersTableBody tr');
            const remainingRows = document.querySelectorAll('#remainingUsers tr');
            
            showingCount = visibleRows.length;
            totalUsers = visibleRows.length + remainingRows.length;
            
            updateShowingCount();
            updateLoadMoreButtons(remainingRows.length);
        }
        
        function updateLoadMoreButtons(remainingCount) {
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const showAllBtn = document.getElementById('showAllBtn');
            
            if (loadMoreBtn) {
                if (remainingCount > 0) {
                    loadMoreBtn.style.display = 'inline-flex';
                    loadMoreBtn.innerHTML = `<i class="fas fa-plus mr-2"></i> Load More (${remainingCount} remaining)`;
                } else {
                    loadMoreBtn.style.display = 'none';
                }
            }
            
            if (showAllBtn) {
                if (remainingCount > 10) {
                    showAllBtn.style.display = 'inline-flex';
                } else {
                    showAllBtn.style.display = 'none';
                }
            }
        }
        
        function loadMoreUsers() {
            const remainingRows = document.querySelectorAll('#remainingUsers tr');
            const tableBody = document.getElementById('usersTableBody');
            
            if (remainingRows.length === 0) return;
            
            // Show next 10 users (or all remaining if less than 10)
            const batchSize = 10;
            const nextBatch = Array.from(remainingRows).slice(0, batchSize);
            
            nextBatch.forEach(row => {
                tableBody.appendChild(row);
            });
            
            // Reinitialize showing count
            initializeShowingCount();
        }
        
        function showAllUsers() {
            const remainingRows = document.querySelectorAll('#remainingUsers tr');
            const tableBody = document.getElementById('usersTableBody');
            
            if (remainingRows.length === 0) return;
            
            // Show all remaining users
            remainingRows.forEach(row => {
                tableBody.appendChild(row);
            });
            
            // Update showing count
            initializeShowingCount();
        }
        
        function updateShowingCount() {
            const showingCountElement = document.getElementById('showingCount');
            if (showingCountElement) {
                showingCountElement.textContent = `${showingCount} of ${totalUsers}`;
            }
        }

        // Mark as Left Modal Functions
        function openMarkAsLeftModal(employeeNumber, employeeName) {
            const modal = document.getElementById('markAsLeftModal');
            const nameSpan = document.getElementById('employee_name');
            const numberInput = document.getElementById('employee_number');
            const form = document.getElementById('markAsLeftForm');
            
            nameSpan.textContent = employeeName;
            numberInput.value = employeeNumber;
            form.action = `/admin/users/${employeeNumber}/mark-as-left`;
            modal.classList.remove('hidden');
        }
        
        function closeMarkAsLeftModal() {
            document.getElementById('markAsLeftModal').classList.add('hidden');
        }
        
        // Reactivate Modal Functions
function openReactivateModal(employeeNumber, employeeName) {
    const modal = document.getElementById('reactivateModal');
    const nameSpan = document.getElementById('reactivate_employee_name');
    const numberInput = document.getElementById('reactivate_employee_number');
    const form = document.getElementById('reactivateForm');
    
    nameSpan.textContent = employeeName;
    numberInput.value = employeeNumber;
    form.action = `/admin/users/${employeeNumber}/reactivate`;
    modal.classList.remove('hidden');
}

function closeReactivateModal() {
    document.getElementById('reactivateModal').classList.add('hidden');
    resetReactivateButton();
}

function resetReactivateButton() {
    const submitBtn = document.getElementById('reactivateSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.loading-spinner');
    
    if (btnText) btnText.textContent = 'Confirm Reactivation';
    if (spinner) spinner.classList.add('hidden');
    submitBtn.disabled = false;
}

async function handleReactivateSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = document.getElementById('reactivateSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.loading-spinner');
    const employeeName = document.getElementById('reactivate_employee_name').textContent;
    
    if (!confirm(`Are you sure you want to reactivate ${employeeName}?`)) {
        return false;
    }
    
    // Show loading state
    btnText.textContent = 'Reactivating...';
    spinner.classList.remove('hidden');
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST', // Laravel handles PUT via _method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                _method: 'PUT',
                employee_number: form.querySelector('#reactivate_employee_number').value
            })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Show success message
            showToast(data.message || 'Account reactivated successfully!', 'success');
            
            // Close modal and reload page after delay
            setTimeout(() => {
                closeReactivateModal();
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to reactivate account');
        }
    } catch (error) {
        console.error('Reactivate error:', error);
        showToast(error.message || 'Error reactivating account', 'error');
        resetReactivateButton();
    }
    
    return false;
}

// Function for inline reactivate forms in inactive users page
function confirmReactivate(form, employeeName) {
    if (!confirm(`Are you sure you want to reactivate ${employeeName}?`)) {
        return false;
    }
    
    // Show loading on the button
    const button = form.querySelector('.reactivate-btn');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Submit the form
    form.submit();
    
    return false;
}

// Close modals when clicking outside
document.getElementById('reactivateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReactivateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReactivateModal();
    }
});
        
        // Handle form submissions
        document.getElementById('markAsLeftForm').addEventListener('submit', function(e) {
            const reason = document.getElementById('left_reason').value;
            if (!reason) {
                e.preventDefault();
                showToast('Please select a reason for leaving.', 'error');
                return false;
            }
            
            if (!confirm('Are you sure you want to mark this employee as left company?')) {
                e.preventDefault();
                return false;
            }
        });
        
        document.getElementById('reactivateForm').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to reactivate this account?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>