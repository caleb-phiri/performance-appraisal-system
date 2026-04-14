<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisal Report - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* MOIC Brand Colors */
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #1a0c9e);
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .pip-warning-banner {
            background: linear-gradient(135deg, #fef3c7, #ffedd5);
            border-left: 5px solid #e7581c;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .pip-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: #e7581c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .btn-pip {
            background: #e7581c;
            color: white;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-pip:hover {
            background: #c2410c;
            transform: translateY(-1px);
        }
        
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            padding: 0.75rem 0.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        
        .score-excellent { color: #059669; font-weight: 600; }
        .score-good { color: #2563eb; font-weight: 600; }
        .score-fair { color: #d97706; font-weight: 600; }
        .score-poor { color: #dc2626; font-weight: 600; }
        
        .message-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
            width: 400px;
            max-width: 90%;
        }
        
        .message {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            color: white;
            animation: slideInRight 0.3s ease-out;
        }
        
        .message-success { background: linear-gradient(135deg, #10b981, #059669); }
        .message-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @media (max-width: 768px) {
            .container-custom { padding: 0 0.75rem; }
            .pip-warning-banner { flex-direction: column; }
        }
        
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    @php
        // Calculate total score
        $totalScore = 0;
        $totalWeight = $appraisal->kpas->sum('weight');
        foreach($appraisal->kpas as $kpa) {
            $kpi = $kpa->kpi ?: 4;
            $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
            $totalScore += ($finalRating / $kpi) * $kpa->weight;
        }
        $requiresPIP = $totalScore < 75 && !$appraisal->pip_initiated;
        $user = auth()->user();
        $isSupervisor = $user->user_type === 'supervisor';
    @endphp

    <div id="messageContainer" class="message-container"></div>

    <div id="reportContainer">
        <!-- Header -->
        <div class="gradient-header text-white no-print">
            <div class="container-custom px-3 py-2">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="h6 mb-0 fw-bold">Performance Appraisal Report</h1>
                        <p class="mb-0 text-white-50 small">ID: #{{ str_pad($appraisal->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <span class="small">{{ auth()->user()->name ?? 'User' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white border-bottom shadow-sm no-print">
            <div class="container-custom px-3 py-2">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('appraisals.index') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-list me-2"></i>All Appraisals
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <main class="py-3 py-md-4">
            <div class="container-custom px-2 px-md-3">
                
                <!-- ⭐⭐⭐ PIP WARNING BANNER - NEW FEATURE ⭐⭐⭐ -->
                @if($requiresPIP && $isSupervisor)
                <div class="pip-warning-banner d-flex flex-wrap align-items-center justify-content-between gap-3 no-print">
                    <div class="d-flex align-items-center gap-3">
                        <div class="pip-icon">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold" style="color:#9a3412;">Performance Improvement Plan Required</h5>
                            <p class="mb-0 small text-dark">
                                Final score: <strong>{{ number_format($totalScore, 1) }}%</strong> (below 75% threshold). 
                                Employee requires formal Performance Improvement Plan.
                            </p>
                        </div>
                    </div>
                    <button onclick="initiatePIP()" class="btn btn-pip px-4 py-2">
                        <i class="fas fa-file-signature me-2"></i>Initiate PIP
                    </button>
                </div>
                @elseif($appraisal->pip_initiated)
                <div class="alert alert-warning border-start border-4 border-warning d-flex align-items-center gap-2 no-print">
                    <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                    <div>
                        <strong>PIP Active:</strong> Performance Improvement Plan has been initiated for this employee.
                        @if($appraisal->pip_end_date)
                        <br><small>Period: {{ $appraisal->pip_start_date->format('M d, Y') }} - {{ $appraisal->pip_end_date->format('M d, Y') }}</small>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Performance Summary Card -->
                <div class="card card-moic mb-3 mb-md-4">
                    <div class="card-header bg-white border-bottom py-2 py-md-3">
                        <h2 class="h6 fw-bold moic-navy mb-0">
                            <i class="fas fa-chart-line me-2" style="color: var(--moic-accent);"></i>Performance Summary
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="small text-muted">Total Weight</div>
                                    <div class="h5 fw-bold moic-navy">{{ number_format($totalWeight, 1) }}%</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="small text-muted">Final Score</div>
                                    <div class="h5 fw-bold {{ $totalScore >= 75 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($totalScore, 1) }}%
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="small text-muted">Performance Level</div>
                                    <div class="h5 fw-bold">
                                        @if($totalScore >= 90)
                                            <span class="score-excellent">Excellent</span>
                                        @elseif($totalScore >= 75)
                                            <span class="score-good">Good</span>
                                        @elseif($totalScore >= 50)
                                            <span class="score-fair">Fair</span>
                                        @else
                                            <span class="score-poor">Needs Improvement</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="small text-muted">PIP Status</div>
                                    <div class="h5 fw-bold">
                                        @if($appraisal->pip_initiated)
                                            <span class="text-warning">Active</span>
                                        @elseif($totalScore < 75)
                                            <span class="text-danger">Eligible</span>
                                        @else
                                            <span class="text-success">Not Required</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-medium">Overall Performance</span>
                                <span class="small fw-medium">{{ number_format($totalScore, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 0.6rem;">
                                <div class="progress-bar" style="width: {{ min($totalScore, 100) }}%; background: linear-gradient(90deg, var(--moic-navy), var(--moic-accent));"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPA Table -->
                <div class="card card-moic mb-3 mb-md-4">
                    <div class="card-header bg-white border-bottom py-2 py-md-3">
                        <h2 class="h6 fw-bold moic-navy mb-0">
                            <i class="fas fa-tasks me-2" style="color: var(--moic-accent);"></i>Key Performance Areas (KPAs)
                        </h2>
                    </div>
                    
                    @if($appraisal->kpas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-moic mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>KPA Details</th>
                                    <th>Weight</th>
                                    <th>Self Rating</th>
                                    <th>Supervisor Rating</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appraisal->kpas as $kpa)
                                @php
                                    $kpi = $kpa->kpi ?: 4;
                                    $finalRating = $kpa->supervisor_rating ?? $kpa->self_rating;
                                    $individualScore = ($finalRating / $kpi) * $kpa->weight;
                                @endphp
                                <tr>
                                    <td data-label="Category">{{ $kpa->category }}</td>
                                    <td data-label="KPA Details">
                                        <div class="fw-semibold">{{ $kpa->kpa }}</div>
                                        @if($kpa->description)
                                            <div class="small text-muted">{{ Str::limit($kpa->description, 80) }}</div>
                                        @endif
                                    </td>
                                    <td data-label="Weight">{{ $kpa->weight }}%</td>
                                    <td data-label="Self Rating">{{ $kpa->self_rating }}/{{ $kpi }}</td>
                                    <td data-label="Supervisor Rating">
                                        {{ $kpa->supervisor_rating ?? '—' }}/{{ $kpi }}
                                    </td>
                                    <td data-label="Score" class="fw-bold {{ $individualScore >= ($kpa->weight * 0.9) ? 'score-excellent' : ($individualScore >= ($kpa->weight * 0.7) ? 'score-good' : 'score-fair') }}">
                                        {{ number_format($individualScore, 1) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">Total Score:</td>
                                    <td colspan="2" class="text-success">{{ number_format($totalScore, 1) }}%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="card-body text-center py-4">
                        <p class="text-muted mb-0">No KPAs added to this appraisal yet.</p>
                    </div>
                    @endif
                </div>

                <!-- Comments Section -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card card-moic h-100">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="h6 fw-bold text-primary mb-0">
                                    <i class="fas fa-user me-2"></i>Employee Comments
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($appraisal->employee_comments)
                                    <p class="small">{{ $appraisal->employee_comments }}</p>
                                @else
                                    <p class="text-muted fst-italic small mb-0">No comments provided.</p>
                                @endif
                                
                                @if($appraisal->development_needs)
                                    <hr>
                                    <strong class="small">Development Needs:</strong>
                                    <p class="small mt-1">{{ $appraisal->development_needs }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-moic h-100">
                            <div class="card-header bg-white border-bottom">
                                <h3 class="h6 fw-bold text-success mb-0">
                                    <i class="fas fa-user-tie me-2"></i>Supervisor Comments
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($appraisal->supervisor_comments)
                                    <p class="small">{{ $appraisal->supervisor_comments }}</p>
                                @else
                                    <p class="text-muted fst-italic small mb-0">No supervisor comments yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="border-top pt-3 text-center text-muted small no-print">
                    <p>MOIC Performance Appraisal System © {{ date('Y') }}</p>
                </footer>
            </div>
        </main>
    </div>

    <!-- PIP Initiation Modal -->
    <div class="modal fade" id="pipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #e7581c, #c2410c); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-file-contract me-2"></i>Initiate Performance Improvement Plan (PIP)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="pipForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Employee final score: <strong>{{ number_format($totalScore, 1) }}%</strong> (below required 75% threshold).
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIP Start Date</label>
                            <input type="date" name="pip_start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIP End Date (Review Period)</label>
                            <input type="date" name="pip_end_date" class="form-control" value="{{ date('Y-m-d', strtotime('+90 days')) }}" required>
                            <small class="text-muted">Typically 30-90 days for improvement period</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Improvement Objectives & Action Plan</label>
                            <textarea name="pip_plan" rows="5" class="form-control" required 
                                placeholder="Example:&#10;1. Improve customer service response time from 24hrs to 4hrs&#10;2. Complete mandatory training on CRM system by [date]&#10;3. Weekly progress meetings with supervisor&#10;4. Achieve 90% quality score on monthly audits"></textarea>
                            <small class="text-muted">List specific, measurable goals with clear deadlines</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Supervisor Comments / Expectations</label>
                            <textarea name="pip_supervisor_notes" rows="3" class="form-control" 
                                placeholder="Additional expectations, support provided, meeting schedule, follow-up plan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-pip">
                            <i class="fas fa-save me-2"></i>Submit PIP & Notify Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show message function
        function showMessage(message, type = 'success') {
            const container = document.getElementById('messageContainer');
            const msgDiv = document.createElement('div');
            msgDiv.className = `message message-${type}`;
            msgDiv.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <div><i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>${escapeHtml(message)}</div>
                    <button class="btn-close btn-close-white" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            container.appendChild(msgDiv);
            setTimeout(() => {
                if(msgDiv.parentElement) msgDiv.remove();
            }, 5000);
        }
        
        function escapeHtml(text) {
            if(!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Initiate PIP function
        function initiatePIP() {
            const modal = new bootstrap.Modal(document.getElementById('pipModal'));
            modal.show();
        }
        
        // Handle PIP form submission
        document.addEventListener('DOMContentLoaded', function() {
            const pipForm = document.getElementById('pipForm');
            if(pipForm) {
                pipForm.action = "{{ route('appraisals.initiate-pip', $appraisal->id) }}";
                
                pipForm.addEventListener('submit', async function(e) {
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if(data.success) {
                            showMessage(data.message, 'success');
                            bootstrap.Modal.getInstance(document.getElementById('pipModal')).hide();
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showMessage(data.message || 'Error initiating PIP', 'error');
                        }
                    } catch(error) {
                        console.error('Error:', error);
                        showMessage('Network error. Please try again.', 'error');
                    } finally {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
</body>
</html>