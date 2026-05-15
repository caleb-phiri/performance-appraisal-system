<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Quarterly Summary - MOIC Performance Appraisal System</title>
     <!-- FAVICON - Using TK.png -->
  <link rel="icon" type="image/png" href="{{ asset('images/TK.png') }}">
  <link rel="shortcut icon" href="{{ asset('images/TK.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/TK.png') }}">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Fonts & icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Apple Touch Icon (for iOS home screen) -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        body {
            background: #f0f3f8;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .moic-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            color: white;
        }
        .card-moic {
            border: none;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
            background: white;
        }
        .filter-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            border-top: 3px solid var(--moic-accent);
        }
        .btn-download {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            font-weight: 500;
        }
        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }
        .rating-badge {
            display: inline-block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            text-align: center;
            line-height: 32px;
            font-weight: bold;
            color: white;
        }
        .rating-5 { background-color: #10b981; }
        .rating-4 { background-color: #3b82f6; }
        .rating-3 { background-color: #f59e0b; }
        .rating-2 { background-color: #ef4444; }
        .rating-1 { background-color: #6b7280; }
        .stat-number { font-size: 1.5rem; font-weight: 700; }
        
        /* Footer Styles */
        .moic-footer {
            background: linear-gradient(135deg, #110484, #1a0c9e);
            color: white;
            margin-top: auto;
        }
        .moic-footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .moic-footer a:hover {
            color: var(--moic-accent);
        }
        .footer-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1rem 0;
        }
        main {
            flex: 1;
        }
        .summary-stats {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .stat-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        .hover-scale {
            transition: transform 0.2s ease;
        }
        .hover-scale:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header class="moic-header">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded me-3">
                        <img style="height: 2rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold">Quarterly Performance Summary</h1>
                        <p class="mb-0 text-white-50 small">{{ $year }} {{ $quarter !== 'all' ? '- ' . $quarter : '- All Quarters' }}</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        <div class="container-fluid px-4">
            <!-- Small Summary and Statistics Section - USING ACTUAL DATA -->
            
                    
                    

            <!-- Distribution Compliance Card with Dropdown Selector -->
            <div class="card card-moic mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="h5 fw-bold moic-navy mb-0">Distribution Compliance by Workstation</h3>
                    <div style="width: 320px;">
                        <select id="workstationComplianceSelect" class="form-select form-select-sm">
                            <option value="">-- Select Workstation --</option>
                            <option value="HQ">Headquarters (HQ)</option>
                            <option value="Kafulafuta Toll Plaza">Kafulafuta Toll Plaza</option>
                            <option value="Katuba Toll Plaza">Katuba Toll Plaza</option>
                            <option value="Manyumbi Toll Plaza">Manyumbi Toll Plaza</option>
                            <option value="Konkola Toll Plaza">Konkola Toll Plaza</option>
                            <option value="Abram Zayoni Mokola Toll Plaza">Abram Zayoni Mokola Toll Plaza</option>
                        </select>
                    </div>
                </div>
                <div class="card-body" id="complianceContent">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>Select a workstation to view distribution compliance</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div class="me-3"><i class="fas fa-users fa-2x moic-navy"></i></div>
                        <div>
                            <small class="text-muted">Total Users</small>
                            <div class="stat-number">{{ number_format($systemStats['total_users'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div class="me-3"><i class="fas fa-file-alt fa-2x text-success"></i></div>
                        <div>
                            <small class="text-muted">Total Appraisals</small>
                            <div class="stat-number">{{ number_format($systemStats['total_appraisals'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div class="me-3"><i class="fas fa-check-circle fa-2x text-primary"></i></div>
                        <div>
                            <small class="text-muted">With Ratings</small>
                            <div class="stat-number">{{ number_format($systemStats['users_with_ratings'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card d-flex align-items-center">
                        <div class="me-3"><i class="fas fa-clock fa-2x moic-accent"></i></div>
                        <div>
                            <small class="text-muted">No Ratings</small>
                            <div class="stat-number">{{ number_format($systemStats['users_without_ratings'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="filter-card mb-4">
                <form id="downloadForm" method="GET" action="{{ route('appraisals.quarterly-summary.download') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select">
                            @for($y = date('Y'); $y >= date('Y')-2; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quarter</label>
                        <select name="quarter" class="form-select">
                            <option value="all" {{ $quarter == 'all' ? 'selected' : '' }}>All Quarters</option>
                            <option value="Q1" {{ $quarter == 'Q1' ? 'selected' : '' }}>Q1</option>
                            <option value="Q2" {{ $quarter == 'Q2' ? 'selected' : '' }}>Q2</option>
                            <option value="Q3" {{ $quarter == 'Q3' ? 'selected' : '' }}>Q3</option>
                            <option value="Q4" {{ $quarter == 'Q4' ? 'selected' : '' }}>Q4</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Employee Type</label>
                        <select name="employee_type" class="form-select">
                            <option value="all" {{ ($employeeType ?? 'all') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="employee" {{ ($employeeType ?? '') == 'employee' ? 'selected' : '' }}>Employees</option>
                            <option value="supervisor" {{ ($employeeType ?? '') == 'supervisor' ? 'selected' : '' }}>Supervisors</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Workstation</label>
                        <select name="workstation" id="workstationSelect" class="form-select">
                            <option value="">All</option>
                            <option value="HQ" {{ ($workstation ?? '') == 'HQ' ? 'selected' : '' }}>HQ</option>
                            <option value="Kafulafuta Toll Plaza" {{ ($workstation ?? '') == 'Kafulafuta Toll Plaza' ? 'selected' : '' }}>Kafulafuta</option>
                            <option value="Katuba Toll Plaza" {{ ($workstation ?? '') == 'Katuba Toll Plaza' ? 'selected' : '' }}>Katuba</option>
                            <option value="Manyumbi Toll Plaza" {{ ($workstation ?? '') == 'Manyumbi Toll Plaza' ? 'selected' : '' }}>Manyumbi</option>
                            <option value="Konkola Toll Plaza" {{ ($workstation ?? '') == 'Konkola Toll Plaza' ? 'selected' : '' }}>Konkola</option>
                            <option value="Abram Zayoni Mokola Toll Plaza" {{ ($workstation ?? '') == 'Abram Zayoni Mokola Toll Plaza' ? 'selected' : '' }}>Mokola</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-download px-4">
                            <i class="fas fa-download me-2"></i> Download Report
                        </button>
                        <button type="button" onclick="resetFilters()" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Downloads -->
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'HQ', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-primary w-100 py-2">
                        <i class="fas fa-building me-2"></i> HQ
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'Kafulafuta Toll Plaza', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-success w-100 py-2">
                        <i class="fas fa-map-pin me-2"></i> Kafulafuta
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'Katuba Toll Plaza', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-success w-100 py-2">
                        <i class="fas fa-map-pin me-2"></i> Katuba
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'Manyumbi Toll Plaza', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-success w-100 py-2">
                        <i class="fas fa-map-pin me-2"></i> Manyumbi
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'Konkola Toll Plaza', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-success w-100 py-2">
                        <i class="fas fa-map-pin me-2"></i> Konkola
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appraisals.quarterly-summary.download', ['workstation' => 'Abram Zayoni Mokola Toll Plaza', 'year' => $year, 'quarter' => $quarter]) }}" 
                       class="btn btn-outline-success w-100 py-2">
                        <i class="fas fa-map-pin me-2"></i> Mokola
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Rating Adjustment Modal -->
    <div class="modal fade" id="ratingAdjustmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Adjust Rating - HQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="ratingAdjustmentForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning small py-2">
                            <strong>Issue:</strong> {{ $distributionByWorkstation['HQ']['ratings_count'][5] ?? 0 }} employees rated 5, only {{ $distributionByWorkstation['HQ']['limits'][5]['max'] ?? 1 }} allowed. Adjust 1 employee to Rating 3.
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Employee</label>
                            <select class="form-select" id="employeeSelect" required>
                                <option value="">Choose...</option>
                                @if(isset($outstandingEmployees) && count($outstandingEmployees) > 0)
                                    @foreach($outstandingEmployees as $emp)
                                        <option value="{{ $emp['employee_number'] }}" 
                                                data-name="{{ $emp['name'] }}"
                                                data-avg-score="{{ $emp['avg_score'] }}">
                                            {{ $emp['name'] }} ({{ $emp['avg_score'] }}%)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">New Rating</label>
                            <select class="form-select" id="newRatingSelect" required>
                                <option value="4">Excellent (4) - 1.5x</option>
                                <option value="3" selected>Competent (3) - 1.0x</option>
                                <option value="2">Below Average (2) - 0.5x</option>
                                <option value="1">Unsatisfactory (1) - 0x</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason</label>
                            <textarea class="form-control" id="adjustmentReason" rows="2" required placeholder="Reason for adjustment..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Apply Adjustment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="moic-footer mt-4 py-4">
        <div class="container-fluid px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="bg-white p-2 rounded me-3">
                        <img style="height: 1.8rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="mb-0 fw-bold">MOIC Performance Management System</p>
                        <p class="small mb-0 opacity-75">Version 2.1 • Supervisor Portal</p>
                    </div>
                </div>
                <div class="text-center text-md-end">
                    <p class="mb-0 small">{{ now()->format('l, F d, Y') }} • {{ now()->format('h:i A') }}</p>
                    <p class="small mb-0 opacity-75">© {{ date('Y') }} SmartWaveSolutions. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Store distribution data in JavaScript
    const distributionData = @json($distributionByWorkstation);
    
    function renderCompliance(workstationName) {
        const data = distributionData[workstationName];
        if (!data) {
            document.getElementById('complianceContent').innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                    <p>No data available for ${workstationName}</p>
                </div>
            `;
            return;
        }
        
        const isCompliant = data.compliance.is_compliant;
        const teamSize = data.team_size;
        
        let tableRows = '';
        const ratings = [
            { rating: 5, name: 'Outstanding' },
            { rating: 4, name: 'Excellent' },
            { rating: 3, name: 'Competent' },
            { rating: 2, name: 'Below Average' },
            { rating: 1, name: 'Unsatisfactory' }
        ];
        
        ratings.forEach(r => {
            const limit = data.limits[r.rating];
            const current = data.ratings_count[r.rating] || 0;
            const compliance = data.compliance.compliance[r.rating];
            const isCompliantRating = compliance ? compliance.compliant : true;
            
            tableRows += `
                <tr class="${!isCompliantRating ? 'table-danger' : ''}">
                    <td><span class="rating-badge rating-${r.rating}">${r.rating}</span> ${r.name}</td>
                    <td>≤ ${limit.cap_percentage}%</td>
                    <td>${limit.max}</td>
                    <td class="fw-bold ${!isCompliantRating ? 'text-danger' : ''}">${current}</td>
                    <td>
                        ${isCompliantRating ? 
                            '<span class="badge bg-success">✓ Compliant</span>' : 
                            '<span class="badge bg-danger">Exceeds by ' + compliance.excess + '</span>'
                        }
                    </td>
                </tr>
            `;
        });
        
        let actionSection = '';
        if (!isCompliant && workstationName === 'HQ') {
            actionSection = `
                <div class="row mt-3">
                    <div class="col-md-8">
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Rating 5 exceeds cap</strong> - Reduce by 1 employee
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-warning w-100" onclick="showRatingAdjustmentModal()">
                            <i class="fas fa-edit me-1"></i> Adjust Rating
                        </button>
                    </div>
                </div>
            `;
        }
        
        const statusBadge = isCompliant ? 
            '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Compliant</span>' : 
            '<span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Non-Compliant</span>';
        
        document.getElementById('complianceContent').innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i> ${workstationName}
                    <span class="badge bg-secondary ms-2">Team: ${teamSize} employees</span>
                </h5>
                ${statusBadge}
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Rating</th>
                            <th>Cap</th>
                            <th>Max Allowed</th>
                            <th>Current</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
                ${actionSection}
            `;
        }
        
        // Initialize dropdown
        const workstationSelect = document.getElementById('workstationComplianceSelect');
        if (workstationSelect) {
            workstationSelect.addEventListener('change', function() {
                const workstation = this.value;
                if (workstation) {
                    renderCompliance(workstation);
                } else {
                    document.getElementById('complianceContent').innerHTML = `
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-bar fa-3x mb-3"></i>
                            <p>Select a workstation to view distribution compliance</p>
                        </div>
                    `;
                }
            });
        }
        
        function resetFilters() {
            window.location.href = "{{ route('appraisals.quarterly-summary.index') }}";
        }
        
        function showRatingAdjustmentModal() {
            new bootstrap.Modal(document.getElementById('ratingAdjustmentModal')).show();
        }
        
        document.getElementById('ratingAdjustmentForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const employeeNumber = document.getElementById('employeeSelect').value;
            const newRating = document.getElementById('newRatingSelect').value;
            const reason = document.getElementById('adjustmentReason').value;
            
            if (!employeeNumber || !newRating || !reason) {
                Swal.fire('Error', 'Please fill all fields', 'error');
                return;
            }
            
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            try {
                const response = await fetch('{{ route("appraisals.adjust-rating") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_number: employeeNumber,
                        quarter: '{{ $quarter }}',
                        year: {{ $year }},
                        new_rating: newRating,
                        reason: reason
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    Swal.fire('Success', 'Rating adjusted successfully', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.error || 'Failed to adjust');
                }
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Apply Adjustment';
            }
        });
    </script>
</body>
</html>