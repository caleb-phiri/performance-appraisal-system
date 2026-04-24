<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PIP Data - MOIC Performance Appraisal System</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/TK.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        .export-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .export-header {
            background: linear-gradient(135deg, var(--moic-navy), #1a0c9e);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }
        
        .btn-export {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        
        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .format-option {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .format-option:hover {
            border-color: var(--moic-accent);
            background-color: #fef3f2;
        }
        
        .format-option.selected {
            border-color: var(--moic-accent);
            background: linear-gradient(135deg, #fef3f2, #fff);
        }
        
        .preview-table {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .preview-table table {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    @include('layouts.navigation')
    
    <div class="container-fluid py-4">
        <div class="container">
            <!-- Header -->
            <div class="export-header mb-4">
                <h2 class="fw-bold mb-2">
                    <i class="fas fa-download me-2"></i>
                    Export Performance Improvement Plans
                </h2>
                <p class="mb-0 opacity-75">
                    Export PIP data to CSV or Excel format for reporting and analysis
                </p>
            </div>
            
            <div class="row">
                <!-- Export Options Panel -->
                <div class="col-md-4">
                    <div class="card export-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-sliders-h me-2" style="color: var(--moic-accent);"></i>
                                Export Options
                            </h5>
                            
                            <form action="{{ route('pip.export.download') }}" method="GET" id="exportForm">
                                <!-- Format Selection -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Export Format</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="format-option text-center" data-format="csv">
                                                <i class="fas fa-file-csv fa-2x mb-2" style="color: #2c9c3e;"></i>
                                                <div class="fw-bold">CSV</div>
                                                <small class="text-muted">Comma Separated</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="format-option text-center" data-format="excel">
                                                <i class="fas fa-file-excel fa-2x mb-2" style="color: #1f724c;"></i>
                                                <div class="fw-bold">Excel</div>
                                                <small class="text-muted">.xlsx format</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="format" id="exportFormat" value="csv">
                                </div>
                                
                                <!-- Filters -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status Filter</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active">Active PIPs</option>
                                        <option value="completed">Completed PIPs</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Department</label>
                                    <select name="department" class="form-select">
                                        <option value="">All Departments</option>
                                        @foreach($departments ?? [] as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date Range</label>
                                    <select name="date_range" class="form-select">
                                        <option value="all">All Time</option>
                                        <option value="this_month">This Month</option>
                                        <option value="last_month">Last Month</option>
                                        <option value="this_quarter">This Quarter</option>
                                        <option value="this_year">This Year</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Include Fields</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="include_kpas" value="1" class="form-check-input" id="includeKpas">
                                        <label class="form-check-label" for="includeKpas">
                                            Include detailed KPA data
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="include_comments" value="1" class="form-check-input" id="includeComments">
                                        <label class="form-check-label" for="includeComments">
                                            Include supervisor comments
                                        </label>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-export">
                                        <i class="fas fa-download me-2"></i>Export Now
                                    </button>
                                    <a href="{{ route('pip.management') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to PIP Management
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Panel -->
                <div class="col-md-8">
                    <div class="card export-card">
                        <div class="card-header bg-white border-bottom-0 pt-3">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-eye me-2" style="color: var(--moic-accent);"></i>
                                Preview (Last 10 PIPs)
                            </h5>
                        </div>
                        <div class="card-body preview-table">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pipsPreview ?? [] as $pip)
                                    @php
                                        $isActive = $pip->pip_end_date >= now();
                                        $totalScore = 0;
                                        if($pip->kpas && count($pip->kpas) > 0) {
                                            foreach($pip->kpas as $kpa) {
                                                $kpi = $kpa->kpi ?? 4;
                                                $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating ?? 0;
                                                $totalScore += ($finalRating / $kpi) * ($kpa->weight ?? 0);
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $pip->user->name ?? $pip->employee_name ?? 'N/A' }}</td>
                                        <td>{{ $pip->user->department ?? $pip->department ?? 'N/A' }}</td>
                                        <td>{{ $pip->pip_start_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>{{ $pip->pip_end_date?->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>
                                            @if($isActive)
                                                <span class="badge bg-danger">Active</span>
                                            @else
                                                <span class="badge bg-success">Completed</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($totalScore, 1) }}%</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-chart-line fa-2x text-muted mb-2 d-block"></i>
                                            No PIP data available for preview
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Showing preview of {{ $pipsPreview->count() ?? 0 }} records. Full export will include all matching PIPs.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Format selection
        document.querySelectorAll('.format-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.format-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                const format = this.dataset.format;
                document.getElementById('exportFormat').value = format;
            });
        });
        
        // Auto-select CSV by default
        document.querySelector('.format-option[data-format="csv"]').classList.add('selected');
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>