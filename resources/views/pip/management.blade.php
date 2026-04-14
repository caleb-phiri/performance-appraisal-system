<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIP Management - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .pip-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        
        .pip-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #110484 0%, #4b71a2 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            color: white;
        }
        
        .stat-card.pip-active {
            background: linear-gradient(135deg, #0a0368 0%, #1b1ee7 100%);
        }
        
        .stat-card.pip-completed {
            background: linear-gradient(135deg, #c44a17 0%, #fe8700 100%);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .badge-pip-active {
            background-color: #dc2626;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
        }
        
        .badge-pip-completed {
            background-color: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
        }
        
        .progress-pip {
            height: 0.5rem;
            border-radius: 0.25rem;
        }
        
        .progress-pip-bar {
            background: linear-gradient(90deg, var(--moic-navy), var(--moic-accent));
        }
        
        .table-pip {
            vertical-align: middle;
        }
        
        .table-pip tbody tr {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .table-pip tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        .filter-section {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .btn-pip {
            background: linear-gradient(135deg, var(--moic-navy), var(--moic-accent));
            color: white;
            border: none;
        }
        
        .btn-pip:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .duration-whole {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        /* Access Denied Page Styles */
        .access-denied-container {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fc 0%, #f1f5f9 100%);
            border-radius: 1.5rem;
            margin: 2rem 0;
        }
        
        .access-denied-card {
            background: white;
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        
        .access-denied-icon {
            font-size: 5rem;
            color: #dc2626;
            margin-bottom: 1.5rem;
        }
        
        .access-denied-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--moic-navy);
            margin-bottom: 1rem;
        }
        
        .access-denied-message {
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        /* Print styles */
        @media print {
            .filter-section, .btn-pip, .btn-outline-success, .btn-outline-secondary, .modal, .actions-column {
                display: none !important;
            }
            .stat-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .table-pip tbody tr {
                break-inside: avoid;
            }
        }
        
        /* Role badge styling */
        .role-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 2rem;
            margin-left: 0.5rem;
        }
        
        .role-badge.admin {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: white;
        }
        
        .role-badge.supervisor {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Include your navigation bar here -->
   
    
    <div class="container-fluid py-4">
        <div class="container-custom">
            
            @php
                // ROLE-BASED ACCESS CONTROL CONDITION
                // Allowed roles: 'admin' and 'supervisor'
                // Get user role from session/auth - this should be dynamically set by your Laravel auth system
                $userRole = session('user_role', Auth::user()->role ?? 'employee'); // Fallback to 'employee' if not set
                $allowedRoles = ['admin', 'supervisor', 'Administrator', 'Supervisor', 'ADMIN', 'SUPERVISOR'];
                $isAuthorized = in_array(strtolower($userRole), array_map('strtolower', $allowedRoles));
                
                // For demonstration in static HTML, we'll check a session variable or auth check
                // In a real Laravel blade, you would use: @auth @if(auth()->user()->hasRole(['admin', 'supervisor']))
                // This implementation supports both server-side role check and client-side fallback
                if(!isset($isAuthorized) || $isAuthorized === false) {
                    // Additional check for common auth patterns
                    if(isset($currentUser) && in_array($currentUser->role ?? '', ['admin', 'supervisor'])) {
                        $isAuthorized = true;
                    } elseif(isset($authUser) && in_array($authUser->role ?? '', ['admin', 'supervisor'])) {
                        $isAuthorized = true;
                    } elseif(isset($loggedInUser) && in_array($loggedInUser->role ?? '', ['admin', 'supervisor'])) {
                        $isAuthorized = true;
                    }
                }
            @endphp
            
            @if($isAuthorized ?? false)
            <!-- AUTHORIZED CONTENT - Only visible to Supervisors and Admins -->
            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold" style="color: var(--moic-navy);">
                        <i class="fas fa-chart-line me-2" style="color: var(--moic-accent);"></i>
                        Performance Improvement Plans
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-shield-alt me-1"></i> 
                        Supervisor & Admin Access Only
                        @if(isset($userRole))
                        <span class="role-badge {{ strtolower($userRole) == 'admin' ? 'admin' : 'supervisor' }} ms-2">
                            <i class="fas {{ strtolower($userRole) == 'admin' ? 'fa-crown' : 'fa-user-tie' }} me-1"></i>
                            {{ ucfirst($userRole) }}
                        </span>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('pip.export') }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-download me-2"></i>Export Report
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75">Total PIPs</p>
                                <h2 class="stat-number">{{ $stats['total'] ?? 0 }}</h2>
                                <small class="opacity-75">All time</small>
                            </div>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card pip-active">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75">Active PIPs</p>
                                <h2 class="stat-number">{{ $stats['active'] ?? 0 }}</h2>
                                <small class="opacity-75">In progress</small>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card pip-completed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 opacity-75">Completed PIPs</p>
                                <h2 class="stat-number">{{ $stats['completed'] ?? 0 }}</h2>
                                <small class="opacity-75">Successfully completed</small>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('pip.management') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    @if(isset($departments) && count($departments) > 0)
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Department</label>
                        <select name="department" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Search</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Employee name or number..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-pip w-100">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- PIP Table -->
            <div class="card pip-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-pip mb-0">
                            <thead style="background: linear-gradient(135deg, var(--moic-navy), #1a0c9e); color: white;">
                                <tr>
                                    <th class="px-3 py-3">Employee</th>
                                    <th class="px-3 py-3">Department</th>
                                    <th class="px-3 py-3">PIP Period</th>
                                    <th class="px-3 py-3">Duration (Days)</th>
                                    <th class="px-3 py-3">Final Score</th>
                                    <th class="px-3 py-3">Status</th>
                                    <th class="px-3 py-3">Progress</th>
                                    <th class="px-3 py-3">Initiated By</th>
                                    <th class="px-3 py-3 text-center actions-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pips ?? [] as $pip)
                                @php
                                    $isActive = $pip->pip_end_date >= now();
                                    $startDate = \Carbon\Carbon::parse($pip->pip_start_date);
                                    $endDate = \Carbon\Carbon::parse($pip->pip_end_date);
                                    $totalDurationDays = (int) $startDate->diffInDays($endDate);
                                    $elapsedDays = $startDate->diffInDays(now());
                                    $progress = ($totalDurationDays > 0) ? ($elapsedDays / $totalDurationDays) * 100 : 0;
                                    $progress = min(100, max(0, round($progress)));
                                    
                                    $totalScore = 0;
                                    if(isset($pip->kpas) && count($pip->kpas) > 0) {
                                        foreach($pip->kpas as $kpa) {
                                            $kpi = $kpa->kpi ?? 4;
                                            $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating ?? 0;
                                            $totalScore += ($finalRating / $kpi) * ($kpa->weight ?? 0);
                                        }
                                    }
                                    $totalScore = $totalScore > 0 ? $totalScore : ($pip->final_score ?? 0);
                                @endphp
                                <tr onclick="window.location='{{ route('appraisals.show', $pip->id) }}'" style="cursor: pointer;">
                                    <td class="px-3 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 35px; height: 35px; background: linear-gradient(135deg, var(--moic-navy), var(--moic-accent)) !important;">
                                                {{ substr($pip->user->name ?? $pip->employee_name ?? 'N/A', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $pip->user->name ?? $pip->employee_name ?? 'N/A' }}</div>
                                                <small class="text-muted">ID: {{ $pip->employee_number ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">{{ $pip->user->department ?? $pip->department ?? 'N/A' }}</td>
                                    <td class="px-3 py-3">
                                        <div>{{ $pip->pip_start_date?->format('M d, Y') ?? 'N/A' }}</div>
                                        <small class="text-muted">to {{ $pip->pip_end_date?->format('M d, Y') ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-3 py-3 duration-whole">
                                        @if($totalDurationDays > 0)
                                            <span class="fw-semibold" style="color: var(--moic-navy);">
                                                <i class="far fa-calendar-alt me-1"></i>{{ number_format($totalDurationDays, 0) }} days
                                            </span>
                                            @if($isActive && $elapsedDays <= $totalDurationDays)
                                                <div class="small text-muted mt-1">
                                                    ({{ number_format($totalDurationDays - $elapsedDays, 0) }} left)
                                                </div>
                                            @elseif(!$isActive)
                                                <div class="small text-success mt-1">
                                                    <i class="fas fa-check-circle"></i> Completed
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                     </td>
                                    <td class="px-3 py-3">
                                        <span class="fw-bold {{ $totalScore >= 75 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalScore, 1) }}%
                                        </span>
                                     </td>
                                    <td class="px-3 py-3">
                                        @if($isActive)
                                            <span class="badge-pip-active">
                                                <i class="fas fa-play-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="badge-pip-completed">
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            </span>
                                        @endif
                                     </td>
                                    <td class="px-3 py-3" style="width: 150px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="small fw-bold">{{ $progress }}%</span>
                                            <div class="progress progress-pip flex-grow-1">
                                                <div class="progress-bar progress-pip-bar" 
                                                     style="width: {{ $progress }}%;"
                                                     role="progressbar"></div>
                                            </div>
                                        </div>
                                     </td>
                                    <td class="px-3 py-3">
                                        <div class="small">
                                            <div>{{ $pip->pipInitiator?->name ?? ($pip->initiated_by_name ?? 'System') }}</div>
                                            <small class="text-muted">{{ $pip->pip_initiated_at?->format('M d, Y') ?? ($pip->created_at?->format('M d, Y') ?? '—') }}</small>
                                        </div>
                                     </td>
                                    <td class="px-3 py-3 text-center actions-column">
                                        <a href="{{ route('appraisals.show', $pip->id) }}" 
                                           class="btn btn-sm btn-outline-primary me-1"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($isActive)
                                        <button onclick="event.stopPropagation(); updatePipStatus({{ $pip->id }})" 
                                                class="btn btn-sm btn-outline-success"
                                                title="Mark Complete or Extend">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                     </td>
                                 </tr>
                                @empty
                                32行
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">No Performance Improvement Plans Found</h5>
                                        <p class="text-muted">When appraisals score below 75%, PIPs will appear here.</p>
                                     </td>
                                 </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(isset($pips) && $pips->hasPages())
                <div class="card-footer bg-white">
                    {{ $pips->links() }}
                </div>
                @endif
            </div>
            
            @else
            <!-- ACCESS DENIED - For non-supervisor and non-admin users -->
            <div class="access-denied-container">
                <div class="access-denied-card">
                    <div class="access-denied-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="access-denied-title">Access Restricted</h3>
                    <div class="access-denied-message">
                        <p><i class="fas fa-shield-alt me-2" style="color: var(--moic-accent);"></i> 
                        This section is only available to <strong>Supervisors</strong> and <strong>Administrators</strong>.</p>
                        <p class="mb-0">Please contact your HR department or system administrator if you believe you need access to Performance Improvement Plans.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ url('/dashboard') }}" class="btn btn-pip">
                            <i class="fas fa-arrow-left me-2"></i>Return to Dashboard
                        </a>
                        <a href="{{ url('/appraisals') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-chart-simple me-2"></i>My Appraisals
                        </a>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Current Role: 
                            <span class="fw-semibold">{{ ucfirst($userRole ?? 'Employee') }}</span>
                        </small>
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
    
    <!-- Update PIP Status Modal (Only shown if authorized) -->
    @if($isAuthorized ?? false)
    <div class="modal fade" id="updatePipModal" tabindex="-1" aria-labelledby="updatePipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                    <h5 class="modal-title" id="updatePipModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Update PIP Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updatePipForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Supervisor Action:</strong> Mark as completed or extend the PIP duration.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Action</label>
                            <select name="status" class="form-select" required>
                                <option value="completed">Completed - Successfully finished</option>
                                <option value="extended">Extended - Need more time (adjust end date)</option>
                            </select>
                            <div class="form-text">Choose 'Extended' to set a new end date for the PIP.</div>
                        </div>
                        
                        <div class="mb-3" id="extendDateField" style="display: none;">
                            <label class="form-label fw-bold">New End Date <span class="text-danger">*</span></label>
                            <input type="date" name="new_end_date" class="form-control" min="{{ date('Y-m-d') }}">
                            <small class="text-muted">Select the new end date for the extended PIP (must be at least tomorrow).</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Completion / Extension Notes</label>
                            <textarea name="completion_notes" rows="3" class="form-control" 
                                      placeholder="Add detailed notes about performance improvement, achievements, or reasons for extension..."></textarea>
                            <div class="form-text">Provide clear documentation for HR and management records.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Only initialize PIP management scripts if user is authorized
        @if($isAuthorized ?? false)
        let currentPipId = null;
        let updateModal = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('updatePipModal');
            if (modalElement) {
                updateModal = new bootstrap.Modal(modalElement);
            }
            
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    const extendField = document.getElementById('extendDateField');
                    const newEndDateInput = document.querySelector('input[name="new_end_date"]');
                    if (this.value === 'extended') {
                        extendField.style.display = 'block';
                        if (newEndDateInput) {
                            newEndDateInput.required = true;
                            const tomorrow = new Date();
                            tomorrow.setDate(tomorrow.getDate() + 1);
                            newEndDateInput.min = tomorrow.toISOString().split('T')[0];
                        }
                    } else {
                        extendField.style.display = 'none';
                        if (newEndDateInput) newEndDateInput.required = false;
                    }
                });
            }
        });
        
        function updatePipStatus(pipId) {
            if (!pipId) return;
            currentPipId = pipId;
            const form = document.getElementById('updatePipForm');
            if (form) {
                form.action = `/pip/${pipId}/update-status`;
                form.reset();
                const extendField = document.getElementById('extendDateField');
                if (extendField) extendField.style.display = 'none';
            }
            if (updateModal) updateModal.show();
        }
        
        document.getElementById('updatePipForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            submitBtn.disabled = true;
            
            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: formData
                });
                
                if (!response.ok) throw new Error('Server error');
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message || 'PIP status updated successfully!');
                    if (updateModal) updateModal.hide();
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    alert(data.message || 'Error updating PIP status.');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
        
        document.querySelectorAll('.table-pip .btn').forEach(btn => {
            btn.addEventListener('click', function(e) { e.stopPropagation(); });
        });
        @endif
    </script>
</body>
</html>