<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supervisor Assignments - MOIC Appraisal System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        .supervisor-checkbox:checked + div {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        input[name="primary_supervisor"]:checked + div {
            border-color: #3b82f6;
        }
        
        input[name="primary_supervisor"]:checked + div div {
            opacity: 1;
        }
        
        .supervisor-checkbox:checked + div i {
            opacity: 1;
        }
        
        .supervisor-checkbox:not(:checked) + div i {
            opacity: 0;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white p-2 border rounded-lg shadow-sm">
                                <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22%23110484%22>MOIC</text></svg>';">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[#110484] text-sm">MOIC</span>
                                <span class="text-xs text-gray-500">Appraisal System</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.supervisor-assignments') }}" 
                           class="{{ request()->routeIs('admin.supervisor-assignments') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-users-cog mr-2"></i>
                            Supervisor Assignments
                        </a>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-users-cog text-blue-600 mr-3"></i>
                    Assign Multiple Supervisors
                </h2>
                
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3"></i>
                            <div>
                                <p class="font-medium text-red-800">Error</p>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Instructions -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">How to use this tool:</p>
                            <ol class="list-decimal ml-5 mt-2 text-sm text-blue-700 space-y-1">
                                <li>Search for an employee by name or employee number</li>
                                <li>Select the employee from search results</li>
                                <li>Assign supervisors by checking the "Rate" checkbox</li>
                                <li>Set rating weight for each supervisor (default: 100)</li>
                                <li>Select one supervisor as "Primary" (can approve appraisals)</li>
                                <li>Click "Save Assignments" to save changes</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <!-- Search Employee -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-search text-gray-500 mr-2"></i>
                        <label class="block text-sm font-medium text-gray-700">Search Employee</label>
                    </div>
                    <div class="relative">
                        <input type="text" id="employeeSearch" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Type name or employee number (e.g., MOIC-00123)...">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <div id="searchResults" class="mt-2 hidden max-h-64 overflow-y-auto border border-gray-200 rounded-lg shadow-sm"></div>
                </div>
                
                <!-- Selected Employee Info -->
                <div id="employeeInfo" class="hidden mb-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg text-blue-800 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            Selected Employee
                        </h3>
                        <button onclick="clearSelection()" class="text-sm text-red-600 hover:text-red-800 flex items-center">
                            <i class="fas fa-times mr-1"></i> Clear
                        </button>
                    </div>
                    <div id="employeeDetails" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                </div>
                
                <!-- Assign Supervisors -->
                <div id="supervisorAssignment" class="hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-gray-800 flex items-center">
                            <i class="fas fa-user-tie mr-2"></i>
                            Assign Supervisors
                        </h3>
                        <div class="text-sm text-gray-600">
                            <span id="selectedCount">0</span> supervisors selected
                        </div>
                    </div>
                    
                    <!-- Current Supervisors Summary -->
                    <div id="currentSupervisors" class="mb-4 hidden">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-list mr-2"></i>
                            Current Supervisors:
                        </h4>
                        <div id="currentSupervisorsList" class="space-y-2"></div>
                    </div>
                    
                    <!-- Supervisors List -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="space-y-3">
                            @forelse($availableSupervisors as $supervisor)
                                <div class="supervisor-item flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user-tie text-blue-600"></i>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-800 block">{{ $supervisor->name }}</span>
                                            <div class="flex items-center text-sm text-gray-600 space-x-3">
                                                <span><i class="fas fa-id-badge mr-1"></i>{{ $supervisor->employee_number }}</span>
                                                <span><i class="fas fa-building mr-1"></i>{{ $supervisor->department }}</span>
                                                @if($supervisor->job_title)
                                                    <span><i class="fas fa-briefcase mr-1"></i>{{ $supervisor->job_title }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <!-- Rate Checkbox -->
                                        <label class="flex items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" value="{{ $supervisor->employee_number }}" 
                                                       class="supervisor-checkbox sr-only" 
                                                       onchange="toggleSupervisor(this)">
                                                <div class="w-6 h-6 border-2 border-gray-300 rounded flex items-center justify-center transition-all duration-200">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-gray-700">Rate</span>
                                        </label>
                                        
                                        <!-- Weight Input -->
                                        <div class="flex items-center">
                                            <label class="mr-2 text-sm font-medium text-gray-700">Weight:</label>
                                            <div class="relative">
                                                <input type="number" min="0" max="100" value="100" 
                                                       class="supervisor-weight w-20 px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                       disabled
                                                       onchange="validateWeight(this)">
                                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-xs">%</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Primary Radio -->
                                        <label class="flex items-center cursor-pointer">
                                            <div class="relative">
                                                <input type="radio" name="primary_supervisor" 
                                                       value="{{ $supervisor->employee_number }}" 
                                                       class="sr-only"
                                                       onchange="updatePrimarySupervisor(this)">
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center transition-all duration-200">
                                                    <div class="w-2.5 h-2.5 bg-blue-600 rounded-full opacity-0 transition-opacity duration-200"></div>
                                                </div>
                                            </div>
                                            <span class="ml-2 text-sm font-medium text-gray-700">Primary</span>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500">
                                    <i class="fas fa-user-slash text-3xl mb-3"></i>
                                    <p class="font-medium">No supervisors available</p>
                                    <p class="text-sm mt-1">Add supervisors in the user management section first.</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Total Weight Warning -->
                        <div id="weightWarning" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                                <div>
                                    <p class="font-medium text-yellow-800" id="warningTitle">Warning</p>
                                    <p class="text-sm text-yellow-700" id="warningMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-lightbulb mr-1"></i>
                            <span id="instructions">
                                Select supervisors, set weights, and choose one as primary.
                            </span>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="clearSelection()" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </button>
                            <button onclick="saveSupervisorAssignments()" 
                                    id="saveButton"
                                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                                    disabled>
                                <i class="fas fa-save mr-2"></i>
                                Save Assignments
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Success!</h3>
                <p id="successMessage" class="text-gray-600 mb-6"></p>
                <button onclick="closeSuccessModal()" 
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors w-full">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"></div>
                <p class="text-gray-700 font-medium">Processing...</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        let selectedEmployee = null;
        let totalWeight = 0;

       // Search employee with better error handling
document.getElementById('employeeSearch').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('searchResults');
    
    if (query.length < 2) {
        resultsDiv.innerHTML = '';
        resultsDiv.classList.add('hidden');
        return;
    }
    
    // Show loading
    resultsDiv.innerHTML = `
        <div class="p-4 text-center">
            <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mb-2"></div>
            <p class="text-sm text-gray-600">Searching...</p>
        </div>
    `;
    resultsDiv.classList.remove('hidden');
    
    // Debounce search
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        // Use a simpler URL for testing
        const url = `/admin/users/search?q=${encodeURIComponent(query)}&type=employee`;
        console.log('Searching with URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const contentType = response.headers.get("content-type");
            console.log('Response status:', response.status, 'Content-Type:', contentType);
            
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                const text = await response.text();
                console.error('Expected JSON, got:', text.substring(0, 200));
                throw new Error('Server returned non-JSON response');
            }
        })
        .then(data => {
            console.log('Search response:', data);
            resultsDiv.innerHTML = '';
            
            if (data.success && data.users && data.users.length > 0) {
                data.users.forEach(user => {
                    const div = document.createElement('div');
                    div.className = 'p-3 border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition-colors';
                    div.innerHTML = `
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">${user.name || 'Unknown'}</div>
                                <div class="text-xs text-gray-600">
                                    ${user.employee_number || 'No ID'} • ${user.job_title || 'Employee'} • ${user.department || 'No Dept'}
                                </div>
                            </div>
                        </div>
                    `;
                    div.onclick = () => selectEmployee(user);
                    resultsDiv.appendChild(div);
                });
                resultsDiv.classList.remove('hidden');
            } else {
                const message = data.message || `No employees found for "${query}"`;
                resultsDiv.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-search text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500">${message}</p>
                    </div>
                `;
                resultsDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            resultsDiv.innerHTML = `
                <div class="p-4 text-center text-red-600">
                    <i class="fas fa-exclamation-circle mb-2"></i>
                    <p>Error searching employees</p>
                    <p class="text-xs mt-1">${error.message}</p>
                    <div class="mt-3">
                        <button onclick="testDebugSearch()" class="text-xs bg-blue-500 text-white px-3 py-1 rounded">
                            Test Debug Endpoint
                        </button>
                    </div>
                </div>
            `;
            resultsDiv.classList.remove('hidden');
        });
    }, 500);
});

// Debug function to test the endpoint
function testDebugSearch() {
    console.log('Testing debug search...');
    fetch('/debug/search-test?q=test')
        .then(res => res.json())
        .then(data => console.log('Debug test:', data))
        .catch(err => console.error('Debug test error:', err));
}
        function selectEmployee(employee) {
            selectedEmployee = employee;
            
            // Clear search results
            document.getElementById('searchResults').innerHTML = '';
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('employeeSearch').value = '';
            
            // Update employee info
            document.getElementById('employeeDetails').innerHTML = `
                <div>
                    <div class="text-xs text-gray-500 font-medium mb-1">FULL NAME</div>
                    <div class="font-medium text-gray-800">${employee.name}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium mb-1">EMPLOYEE NUMBER</div>
                    <div class="font-medium text-gray-800">${employee.employee_number}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium mb-1">JOB TITLE</div>
                    <div class="font-medium text-gray-800">${employee.job_title || 'Not specified'}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium mb-1">DEPARTMENT</div>
                    <div class="font-medium text-gray-800">${employee.department}</div>
                </div>
            `;
            
            // Show sections
            document.getElementById('employeeInfo').classList.remove('hidden');
            document.getElementById('supervisorAssignment').classList.remove('hidden');
            
            // Reset UI
            resetSupervisorSelection();
            
            // Load existing supervisor assignments
            loadExistingAssignments(employee.employee_number);
            
            // Update instructions
            document.getElementById('instructions').textContent = `Assigning supervisors for ${employee.name}`;
        }

        function clearSelection() {
            selectedEmployee = null;
            document.getElementById('employeeInfo').classList.add('hidden');
            document.getElementById('supervisorAssignment').classList.add('hidden');
            document.getElementById('searchResults').classList.add('hidden');
            document.getElementById('employeeSearch').value = '';
            document.getElementById('currentSupervisors').classList.add('hidden');
            document.getElementById('instructions').textContent = 'Select supervisors, set weights, and choose one as primary.';
            document.getElementById('saveButton').disabled = true;
            resetSupervisorSelection();
        }

        function resetSupervisorSelection() {
            // Uncheck all checkboxes
            document.querySelectorAll('.supervisor-checkbox').forEach(cb => {
                cb.checked = false;
                const weightInput = cb.closest('.supervisor-item').querySelector('.supervisor-weight');
                weightInput.disabled = true;
                weightInput.value = 100;
            });
            
            // Uncheck all primary radios
            document.querySelectorAll('input[name="primary_supervisor"]').forEach(rb => {
                rb.checked = false;
            });
            
            // Reset counters
            updateSelectionCount();
            calculateTotalWeight();
        }

        function toggleSupervisor(checkbox) {
            const supervisorItem = checkbox.closest('.supervisor-item');
            const weightInput = supervisorItem.querySelector('.supervisor-weight');
            weightInput.disabled = !checkbox.checked;
            
            if (!checkbox.checked) {
                const primaryRadio = supervisorItem.querySelector('input[name="primary_supervisor"]');
                if (primaryRadio) primaryRadio.checked = false;
            }
            
            updateSelectionCount();
            calculateTotalWeight();
            validateWeights();
        }

        function updatePrimarySupervisor(radio) {
            // Ensure only one primary is selected
            document.querySelectorAll('input[name="primary_supervisor"]').forEach(rb => {
                if (rb !== radio) {
                    rb.checked = false;
                }
            });
            
            // Ensure the supervisor is also checked for rating
            const checkbox = radio.closest('.supervisor-item').querySelector('.supervisor-checkbox');
            if (!checkbox.checked) {
                checkbox.checked = true;
                toggleSupervisor(checkbox);
            }
        }

        function validateWeight(input) {
            let value = parseInt(input.value);
            if (isNaN(value) || value < 0) value = 0;
            if (value > 100) value = 100;
            input.value = value;
            
            calculateTotalWeight();
            validateWeights();
        }

        function calculateTotalWeight() {
            totalWeight = 0;
            let checkedCount = 0;
            
            document.querySelectorAll('.supervisor-checkbox:checked').forEach(checkbox => {
                const weightInput = checkbox.closest('.supervisor-item').querySelector('.supervisor-weight');
                totalWeight += parseInt(weightInput.value) || 0;
                checkedCount++;
            });
            
            return { totalWeight, checkedCount };
        }

        function validateWeights() {
            const { totalWeight, checkedCount } = calculateTotalWeight();
            const weightWarning = document.getElementById('weightWarning');
            const saveButton = document.getElementById('saveButton');
            
            if (checkedCount === 0) {
                weightWarning.classList.add('hidden');
                saveButton.disabled = true;
                return false;
            }
            
            if (totalWeight !== 100) {
                document.getElementById('warningTitle').textContent = 'Total weight not 100%';
                document.getElementById('warningMessage').textContent = `Total weight is ${totalWeight}%. Please adjust weights so they total 100%.`;
                weightWarning.classList.remove('hidden');
                saveButton.disabled = true;
                return false;
            }
            
            // Check if primary supervisor is selected
            const primarySelected = document.querySelector('input[name="primary_supervisor"]:checked');
            if (!primarySelected) {
                document.getElementById('warningTitle').textContent = 'No primary supervisor selected';
                document.getElementById('warningMessage').textContent = 'Please select one supervisor as primary.';
                weightWarning.classList.remove('hidden');
                saveButton.disabled = true;
                return false;
            }
            
            weightWarning.classList.add('hidden');
            saveButton.disabled = false;
            return true;
        }

        function updateSelectionCount() {
            const checkedCount = document.querySelectorAll('.supervisor-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = checkedCount;
        }

        async function loadExistingAssignments(employeeNumber) {
            try {
                showLoading(true);
                
                const response = await fetch(`/admin/employee/${employeeNumber}/supervisors`);
                if (!response.ok) throw new Error('Failed to load supervisors');
                
                const data = await response.json();
                const currentDiv = document.getElementById('currentSupervisors');
                const listDiv = document.getElementById('currentSupervisorsList');
                
                if (data.supervisors && data.supervisors.length > 0) {
                    listDiv.innerHTML = '';
                    data.supervisors.forEach(supervisor => {
                        listDiv.innerHTML += `
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="font-medium">${supervisor.name}</span>
                                        <div class="text-xs text-gray-600">
                                            Weight: ${supervisor.rating_weight}% • 
                                            ${supervisor.is_primary ? 'Primary' : 'Secondary'} • 
                                            ${supervisor.relationship_type}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    ${supervisor.can_approve_appraisal ? 'Can approve' : 'View only'}
                                </div>
                            </div>
                        `;
                        
                        // Check the supervisor in the list
                        const checkbox = document.querySelector(`.supervisor-checkbox[value="${supervisor.supervisor_id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                            const weightInput = checkbox.closest('.supervisor-item').querySelector('.supervisor-weight');
                            weightInput.disabled = false;
                            weightInput.value = supervisor.rating_weight || 100;
                            
                            if (supervisor.is_primary) {
                                const primaryRadio = document.querySelector(`input[name="primary_supervisor"][value="${supervisor.supervisor_id}"]`);
                                if (primaryRadio) primaryRadio.checked = true;
                            }
                        }
                    });
                    currentDiv.classList.remove('hidden');
                } else {
                    currentDiv.classList.add('hidden');
                }
                
                updateSelectionCount();
                calculateTotalWeight();
                validateWeights();
                
            } catch (error) {
                console.error('Error loading assignments:', error);
                document.getElementById('currentSupervisors').classList.add('hidden');
            } finally {
                showLoading(false);
            }
        }

        async function saveSupervisorAssignments() {
            if (!selectedEmployee) return;
            
            if (!validateWeights()) {
                alert('Please fix the validation errors before saving.');
                return;
            }
            
            const assignments = [];
            document.querySelectorAll('.supervisor-checkbox:checked').forEach(checkbox => {
                const supervisorId = checkbox.value;
                const weightInput = checkbox.closest('.supervisor-item').querySelector('.supervisor-weight');
                const primaryRadio = document.querySelector(`input[name="primary_supervisor"][value="${supervisorId}"]`);
                
                assignments.push({
                    supervisor_id: supervisorId,
                    rating_weight: parseInt(weightInput.value) || 100,
                    is_primary: primaryRadio ? primaryRadio.checked : false,
                    can_view_appraisal: true,
                    can_approve_appraisal: primaryRadio ? primaryRadio.checked : false,
                    relationship_type: 'direct',
                    notes: ''
                });
            });
            
            // Show loading
            showLoading(true);
            
            try {
                const response = await fetch(`/admin/employee/${selectedEmployee.employee_number}/supervisors`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ supervisors: assignments })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccessModal('Supervisor assignments saved successfully!');
                    
                    // Reload existing assignments
                    setTimeout(() => {
                        loadExistingAssignments(selectedEmployee.employee_number);
                    }, 500);
                } else {
                    throw new Error(data.message || 'Failed to save assignments');
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('Error saving assignments: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function showSuccessModal(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
        }

        function showLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            if (show) {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            } else {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        }

        // Close modals on outside click
        document.addEventListener('click', function(event) {
            const successModal = document.getElementById('successModal');
            if (event.target === successModal) {
                closeSuccessModal();
            }
            
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (event.target === loadingOverlay) {
                showLoading(false);
            }
        });

        // Enable enter key in search
        document.getElementById('employeeSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length >= 2) {
                    this.dispatchEvent(new Event('input'));
                }
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Supervisor assignments page loaded');
        });
    </script>
</body>
</html>