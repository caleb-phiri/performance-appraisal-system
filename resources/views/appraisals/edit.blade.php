<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Edit Verification Clerk Appraisal - MOIC Performance Appraisal System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Meta CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* MOIC Brand Colors - Verification Clerk Theme */
        :root {
            --moic-navy: #110484;
            --moic-navy-light: #3328a5;
            --moic-accent: #e7581c;
            --moic-accent-light: #ff6b2d;
            --moic-blue: #1a0c9e;
            --moic-blue-light: #2d1fd1;
            --moic-gradient: linear-gradient(135deg, var(--moic-navy), var(--moic-blue));
            --moic-gradient-accent: linear-gradient(135deg, var(--moic-accent), #ff7c45);
            
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --audit: #8b5cf6;
            --audit-light: #ede9fe;
        }
        
        /* Base styles */
        html {
            font-size: 16px;
        }
        
        body {
            font-size: 0.875rem;
            line-height: 1.5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f9fafb;
        }
        
        /* Custom Color Classes */
        .moic-navy { color: var(--moic-navy) !important; }
        .moic-navy-bg { background-color: var(--moic-navy) !important; }
        .moic-accent { color: var(--moic-accent) !important; }
        .moic-accent-bg { background-color: var(--moic-accent) !important; }
        
        /* MOIC Buttons */
        .btn-moic {
            background: var(--moic-gradient);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-moic:hover {
            background: linear-gradient(135deg, var(--moic-navy-light), var(--moic-blue-light));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 4, 132, 0.3);
        }
        
        .btn-outline-moic {
            background: transparent;
            border: 1px solid var(--moic-navy);
            color: var(--moic-navy) !important;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .btn-outline-moic:hover {
            background: var(--moic-navy);
            color: white !important;
            transform: translateY(-1px);
        }
        
        /* Animated Gradient Header */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e, #110484, #e7581c);
            background-size: 300% 300%;
            animation: gradientShift 15s ease infinite;
            box-shadow: 0 2px 10px rgba(17, 4, 132, 0.15);
        }
        
        /* Logo Container */
        .logo-container {
            position: relative;
            padding: 2px;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #110484, #e7581c);
        }
        
        .logo-inner {
            background: white;
            border-radius: 0.375rem;
            padding: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.125rem 0.5rem;
            border-radius: 0.75rem;
        }
        
        /* Card Styling */
        .card-moic {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-moic:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Table Styling */
        .table-moic thead th {
            background: var(--moic-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            vertical-align: middle;
        }
        
        .table-moic tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-color: #f3f4f6;
        }
        
        .table-moic tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table-moic tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Rating Select Styling */
        .rating-select {
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .rating-select:focus {
            border-color: var(--moic-navy);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(17, 4, 132, 0.25);
        }
        
        .rating-select.error {
            border-color: var(--danger);
            border-width: 2px;
        }
        
        /* Score Colors */
        .score-excellent { color: #059669 !important; font-weight: 600; }
        .score-good { color: #2563eb !important; font-weight: 600; }
        .score-fair { color: #d97706 !important; font-weight: 600; }
        .score-poor { color: #dc2626 !important; font-weight: 600; }
        
        /* Weight cell styling */
        .weight-cell {
            background-color: #f0f9ff;
            font-weight: 600;
        }
        
        .weight-display {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Progress Bars */
        .progress-moic {
            height: 0.5rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
        }
        
        .progress-bar-moic {
            background: var(--moic-gradient);
            border-radius: 0.25rem;
        }
        
        /* Badge Styles */
        .audit-badge {
            background-color: #ede9fe;
            color: #6d28d9;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        .accuracy-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        .deadline-badge {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 0.25rem;
        }
        
        /* Comment Preview */
        .comment-preview {
            max-height: 3rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            font-size: 0.8125rem;
            color: #4b5563;
            background-color: #f9fafb;
            padding: 0.375rem;
            border-radius: 0.25rem;
            border: 1px solid #e5e7eb;
        }
        
        /* Rating Legend */
        .rating-legend-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .rating-legend-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .rating-badge {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 700;
            margin-right: 0.75rem;
        }
        
        /* Message Container */
        .message-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
            max-width: 400px;
        }
        
        .message {
            margin-bottom: 0.5rem;
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }
        
        .message-success { background: linear-gradient(135deg, #10b981, #059669); }
        .message-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .message-info { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .message-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        
        .message-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.8;
            cursor: pointer;
            padding: 0;
            margin-left: 0.75rem;
        }
        
        .message-close:hover {
            opacity: 1;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        /* Container */
        .container-custom {
            max-width: 90rem;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    @php
        $isResubmission = $appraisal->status === 'submitted' && $appraisal->resubmission_count > 0;
    @endphp

    <!-- Message Container -->
    <div id="messageContainer" class="message-container"></div>

    <!-- Header with Animated Gradient -->
    <div class="gradient-header text-white">
        <div class="container-custom px-3 py-2">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Logo Section -->
                <div class="d-flex align-items-center">
                    <div class="logo-container me-3">
                        <div class="logo-inner">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                                    </div>
                                    <span class="status-badge moic-navy-bg text-white">MOIC</span>
                                </div>
                                
                                <div class="position-relative">
                                    <div class="rounded-circle" style="width: 2rem; height: 2rem; background: linear-gradient(135deg, #110484, #e7581c); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-handshake text-white" style="font-size: 0.75rem;"></i>
                                    </div>
                                    <div class="position-absolute top-100 start-50 translate-middle mt-1">
                                        <span class="status-badge bg-white moic-navy">PARTNERS</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-white rounded p-1 mb-1">
                                        <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/TKC.png') }}" alt="TKC Logo">
                                    </div>
                                    <span class="status-badge moic-accent-bg text-white">TKC</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="vr bg-white opacity-25 mx-3" style="height: 1.5rem;"></div>
                        <div>
                            <h1 class="h5 mb-0 fw-bold">Edit Verification Clerk Appraisal</h1>
                            <p class="mb-0 text-white-50 small">{{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="text-end me-2">
                        <span class="badge bg-{{ $appraisal->status === 'approved' ? 'success' : ($appraisal->status === 'submitted' ? 'info' : 'secondary') }} me-2">
                            Status: {{ ucfirst($appraisal->status) }}
                        </span>
                        <div class="fw-medium">{{ Auth::user()->name ?? 'User' }}</div>
                    </div>
                    <div class="bg-white text-[#110484] rounded-circle d-flex align-items-center justify-content-center" style="width: 2.5rem; height: 2.5rem;">
                        <span class="fw-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <div class="bg-white border-bottom shadow-sm">
        <div class="container-custom px-3 py-2">
            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('appraisals.show', $appraisal) }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Appraisal
                    </a>
                    <a href="{{ route('appraisals.index') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-list me-2"></i>My Appraisals
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-moic btn-sm">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </div>
                
                <div class="text-muted small">
                    <i class="fas fa-calendar-alt me-1 moic-accent"></i>
                    Deadline: {{ $quarterInfo->due_date }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-custom px-3">
            
            <!-- RESUBMISSION WARNING -->
            @if($appraisal->status === 'submitted')
                <div class="alert alert-warning mb-4">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-3 mt-1" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="alert-heading">You are editing a submitted appraisal</h5>
                            <p class="mb-0">
                                This appraisal has already been submitted. Any changes you make will create a new version.
                                Your supervisor will be notified of these changes when you resubmit.
                            </p>
                            @if($appraisal->resubmission_count > 0)
                                <p class="mb-0 mt-2">
                                    <strong>Resubmission #{{ $appraisal->resubmission_count + 1 }}</strong>
                                    @if($appraisal->resubmitted_at)
                                        · Last resubmitted: {{ \Carbon\Carbon::parse($appraisal->resubmitted_at)->format('d M Y H:i') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Form -->
            <form id="editAppraisalForm" action="{{ route('appraisals.update', $appraisal) }}" method="POST" class="card card-moic">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="formStatus" value="{{ $appraisal->status }}">

                <!-- Form Header -->
                <div class="card-header bg-white border-bottom bg-gray-50">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h2 class="h5 fw-bold moic-navy mb-0">
                            <i class="fas fa-edit me-2 moic-accent"></i>Edit Appraisal Form
                        </h2>
                        <span class="badge bg-primary text-white" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-star me-1"></i> 7 Key Performance Areas
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="bg-blue-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-weight-hanging moic-navy me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Total Weight</p>
                                        <p id="verificationTotalWeightDisplay" class="h4 fw-bold moic-navy mb-0">100%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-green-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-pie text-success me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">KPAs</p>
                                        <p class="h4 fw-bold text-success mb-0">7</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-amber-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star moic-accent me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Max Score</p>
                                        <p class="h4 fw-bold moic-accent mb-0">100%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-purple-50 p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-purple-600 me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Due Date</p>
                                        <p class="h6 fw-bold text-purple-600 mb-0">{{ $quarterInfo->due_date }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Legend -->
                    <div class="bg-gray-50 p-4 rounded border mb-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-info-circle moic-navy me-2"></i>
                            Rating Scale Guide
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-red-100 text-red-800">1</div>
                                    <div>
                                        <p class="fw-semibold mb-0">ND</p>
                                        <p class="small text-muted mb-0">Not Demonstrated</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-yellow-100 text-yellow-800">2</div>
                                    <div>
                                        <p class="fw-semibold mb-0">NS</p>
                                        <p class="small text-muted mb-0">Not Satisfactory</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-blue-100 text-blue-800">3</div>
                                    <div>
                                        <p class="fw-semibold mb-0">S</p>
                                        <p class="small text-muted mb-0">Satisfactory</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="rating-legend-item">
                                    <div class="rating-badge bg-green-100 text-green-800">4</div>
                                    <div>
                                        <p class="fw-semibold mb-0">EX</p>
                                        <p class="small text-muted mb-0">Exemplary</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KPA Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-moic mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Result Indicators</th>
                                    <th>KPI Max</th>
                                    <th>Weight %</th>
                                    <th>Rating (Self)</th>
                                    <th>Score %</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appraisal->kpas as $index => $kpa)
                                <tr>
                                    <td class="fw-medium">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $iconClass = 'bg-blue-100';
                                                $icon = 'fa-calendar-check';
                                                if($kpa->category === 'CODE OF CONDUCT') {
                                                    $iconClass = 'bg-green-100';
                                                    $icon = 'fa-gavel';
                                                } elseif($kpa->category === 'WORK PERFORMANCE') {
                                                    $iconClass = 'bg-purple-100';
                                                    $icon = 'fa-clock';
                                                } elseif($kpa->category === 'ATTITUDE') {
                                                    $iconClass = 'bg-yellow-100';
                                                    $icon = 'fa-smile';
                                                }
                                            @endphp
                                            <div class="rounded-circle p-2 {{ $iconClass }} me-2" style="color: #110484;">
                                                <i class="fas {{ $icon }}"></i>
                                            </div>
                                            <div>
                                                {{ $kpa->category }}
                                                <small class="text-muted d-block">{{ $kpa->kpa }}</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][category]" value="{{ $kpa->category }}">
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][kpa]" value="{{ $kpa->kpa }}">
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][result_indicators]" value="{{ $kpa->result_indicators }}">
                                    </td>
                                    <td>
                                        <div class="small">{{ $kpa->result_indicators }}</div>
                                        <div class="mt-1">
                                            @if($kpa->category === 'ATTENDANCE')
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Reduced Attendance - 1</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Unscheduled Leave - 2</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Scheduled Leave - 3</span>
                                                <span class="badge bg-gray-100 text-gray-800">Full Attendance - 4</span>
                                            @elseif($kpa->category === 'CODE OF CONDUCT')
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Final Written - 1</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Written - 2</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">Verbal - 3</span>
                                                <span class="badge bg-gray-100 text-gray-800">No Record - 4</span>
                                            @else
                                                <span class="badge bg-gray-100 text-gray-800 me-1">ND - 1</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">NS - 2</span>
                                                <span class="badge bg-gray-100 text-gray-800 me-1">S - 3</span>
                                                <span class="badge bg-gray-100 text-gray-800">EX - 4</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue-100 text-blue-800 rounded-pill px-3 py-2">{{ $kpa->kpi }}</span>
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][kpi]" value="{{ $kpa->kpi }}">
                                    </td>
                                    <td class="weight-cell">
                                        <span class="weight-display">{{ $kpa->weight }}%</span>
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][weight]" value="{{ $kpa->weight }}" class="kpa-weight">
                                    </td>
                                    <td>
                                        <select name="kpas[{{ $kpa->id }}][self_rating]" required class="rating-select" id="rating{{ $index }}" data-weight="{{ $kpa->weight }}">
                                            <option value="">Select Rating</option>
                                            @for($i = 1; $i <= 4; $i++)
                                                <option value="{{ $i }}" {{ $kpa->self_rating == $i ? 'selected' : '' }}>
                                                    {{ $i }} - 
                                                    @if($kpa->category === 'ATTENDANCE')
                                                        {{ ['Reduced Attendance', 'Unscheduled Leave', 'Scheduled Leave', 'Full Attendance'][$i-1] }}
                                                    @elseif($kpa->category === 'CODE OF CONDUCT')
                                                        {{ ['Final Written Warning', 'Written Warning', 'Verbal Warning', 'No Record'][$i-1] }}
                                                    @else
                                                        {{ ['ND', 'NS', 'S', 'EX'][$i-1] }}
                                                    @endif
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td>
                                        <span id="scoreCell{{ $index }}" class="fw-bold score-display">0%</span>
                                        <input type="hidden" name="kpas[{{ $kpa->id }}][calculated_score]" id="calculatedScore{{ $index }}" value="0">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div id="commentPreview{{ $index }}" class="comment-preview">{{ $kpa->comments ?: 'No comment added' }}</div>
                                            <button type="button" onclick="openCommentModal({{ $index }}, '{{ $kpa->comments }}')" class="btn btn-sm btn-outline-moic">
                                                <i class="fas fa-edit me-1"></i>Edit Comment
                                            </button>
                                            <input type="hidden" name="kpas[{{ $kpa->id }}][comments]" id="commentInput{{ $index }}" value="{{ $kpa->comments }}">
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                <!-- TOTAL ROW -->
                                <tr class="bg-gray-800 text-white fw-bold">
                                    <td colspan="3" class="text-center">TOTAL</td>
                                    <td class="text-center">100%</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center" id="totalScoreCell">{{ number_format($appraisal->self_score ?? 0, 2) }}%</td>
                                    <td class="text-center"><i class="fas fa-chart-line"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Weight Summary -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="bg-gray-50 p-4 rounded border">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-chart-pie moic-navy me-2"></i>Weight Distribution Summary
                                </h6>
                                <div class="mb-2 d-flex justify-content-between">
                                    <span class="small">Total Weight:</span>
                                    <span id="verificationTotalWeight" class="fw-bold moic-navy">100%</span>
                                </div>
                                <div class="progress-moic">
                                    <div id="verificationWeightProgress" class="progress-bar-moic" style="width: 100%; height: 100%;"></div>
                                </div>
                                <div id="verificationWeightStatus" class="mt-2 small text-success">
                                    <i class="fas fa-check-circle me-1"></i> Total weight equals 100% - Ready for submission
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Score Summary -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Total Score</span>
                                <div class="h2 fw-bold moic-navy" id="displayTotalScore">{{ number_format($appraisal->self_score ?? 0, 2) }}</div>
                                <span class="small text-muted">Out of 100</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Overall Percentage</span>
                                <div class="h2 fw-bold moic-navy" id="displayTotalPercentage">{{ number_format($appraisal->self_score ?? 0, 2) }}%</div>
                                <span class="small text-muted">Based on 100% scale</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-white p-4 rounded border text-center">
                                <span class="text-muted small">Performance Rating</span>
                                <div class="h2 fw-bold" id="performanceRating">
                                    @php
                                        $score = $appraisal->self_score ?? 0;
                                        if($score >= 90) echo 'Exemplary';
                                        elseif($score >= 70) echo 'Satisfactory';
                                        elseif($score >= 50) echo 'Needs Improvement';
                                        else echo 'Unsatisfactory';
                                    @endphp
                                </div>
                                <span class="small text-muted">Based on score</span>
                            </div>
                        </div>
                    </div>

                    <!-- Agreement Section -->
                    <div class="bg-light p-4 rounded border mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-file-signature moic-navy me-2"></i>Agreement
                        </h6>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Employee Name & Surname</label>
                                <input type="text" name="employee_name" class="form-control" value="{{ $appraisal->employee_name ?? Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Job Title</label>
                                <input type="text" name="employee_job_title" class="form-control" value="Verification Clerk" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="small text-muted mb-2">I hereby confirm that my performance review was done in a fair and sufficient manner through discussion with my superior. My signature hereto means that:</p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="agreement_option" id="agreeOption" value="agree" {{ $appraisal->agreement_option === 'agree' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="agreeOption">
                                    a. I am in agreement with the results of my performance review
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="agreement_option" id="notAgreeOption" value="not_agree" {{ $appraisal->agreement_option === 'not_agree' ? 'checked' : '' }}>
                                <label class="form-check-label" for="notAgreeOption">
                                    b. I have been advised of my performance status and does not necessarily imply that I agree with the evaluation
                                </label>
                            </div>
                        </div>
                        
                        <div id="managerReasonSection" class="mb-3 {{ $appraisal->agreement_option === 'not_agree' ? '' : 'd-none' }}">
                            <label class="form-label fw-medium">Manager's Reason (if option b selected):</label>
                            <textarea name="manager_reason" rows="2" class="form-control" placeholder="Please provide reason for disagreement...">{{ $appraisal->manager_reason }}</textarea>
                        </div>
                    </div>

                    <!-- Development Needs & Career Aspirations -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="bg-blue-50 p-4 rounded border">
                                <label class="fw-bold mb-2">
                                    <i class="fas fa-chart-line moic-navy me-2"></i>Development Needs
                                </label>
                                <textarea name="development_needs" rows="4" class="form-control" placeholder="What skills do you need to develop?">{{ $appraisal->development_needs }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-green-50 p-4 rounded border">
                                <label class="fw-bold mb-2">
                                    <i class="fas fa-rocket text-success me-2"></i>Career Aspirations
                                </label>
                                <textarea name="career_aspirations" rows="4" class="form-control" placeholder="Record your career aspirations...">{{ $appraisal->career_aspirations }}</textarea>
                                <div class="mt-2 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Your aspirations will be reviewed by your appraiser
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-4 border-top">
                        <div class="d-flex align-items-center">
                            <div class="bg-blue-100 text-[#110484] p-2 rounded me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <p class="fw-medium mb-0">Submission Guidelines</p>
                                <p class="small text-muted mb-0">All ratings must be selected and total weight is fixed at 100%</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('appraisals.show', $appraisal) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="button" onclick="saveAsDraft(event)" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Save as Draft
                            </button>
                            <button type="button" onclick="submitForm(event)" class="btn btn-moic">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ $appraisal->status === 'submitted' ? 'Resubmit' : 'Submit' }} Appraisal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Comment Modal -->
    <div class="modal fade modal-moic" id="commentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalTitle">Edit Comment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Your Comment</label>
                        <textarea id="modalCommentTextarea" rows="5" class="form-control" placeholder="Enter your comment here..."></textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="small text-muted"><span id="charCount">0</span>/500 characters</span>
                            <button type="button" onclick="clearComment()" class="btn btn-link btn-sm text-muted p-0">
                                <i class="fas fa-trash-alt me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-moic" onclick="saveComment()">Save Comment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-top mt-4 pt-4">
        <div class="container-custom px-3">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-2 rounded me-3">
                            <img class="img-fluid" style="height: 1.5rem;" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                        </div>
                        <div>
                            <p class="text-muted small mb-0">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                            <p class="text-muted small">Current Quarter: {{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-4">
                        <span class="text-muted small">
                            <i class="fas fa-calendar-check me-1 text-success"></i> Deadline: {{ $quarterInfo->due_date }}
                        </span>
                        <span class="text-muted small">
                            <i class="fas fa-calendar-alt me-1 moic-navy"></i> Period: {{ $quarterInfo->quarter_months }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // ==============================================
    // GLOBAL VARIABLES
    // ==============================================
    
    let currentCommentIndex = -1;
    let commentModal = null;
    const weights = [10, 25, 20, 20, 15, 5, 5];
    
    // ==============================================
    // SCORE CALCULATION FUNCTIONS
    // ==============================================
    
    function calculateScores() {
        let totalWeightedScore = 0;
        
        for (let i = 0; i < 7; i++) {
            const ratingSelect = document.getElementById(`rating${i}`);
            const rating = parseFloat(ratingSelect?.value) || 0;
            const maxKPI = 4;
            
            let weightedScore = 0;
            if (rating > 0) {
                weightedScore = (rating / maxKPI) * weights[i];
            }
            
            const scoreCell = document.getElementById(`scoreCell${i}`);
            const calculatedScoreInput = document.getElementById(`calculatedScore${i}`);
            
            if (scoreCell) scoreCell.textContent = weightedScore.toFixed(2) + '%';
            if (calculatedScoreInput) calculatedScoreInput.value = weightedScore.toFixed(2);
            
            totalWeightedScore += weightedScore;
        }
        
        const totalScoreCell = document.getElementById('totalScoreCell');
        if (totalScoreCell) totalScoreCell.textContent = totalWeightedScore.toFixed(2) + '%';
        
        const displayTotalScore = document.getElementById('displayTotalScore');
        const displayTotalPercentage = document.getElementById('displayTotalPercentage');
        const performanceRating = document.getElementById('performanceRating');
        
        if (displayTotalScore) displayTotalScore.textContent = totalWeightedScore.toFixed(2);
        if (displayTotalPercentage) displayTotalPercentage.textContent = totalWeightedScore.toFixed(2) + '%';
        
        let rating = '-';
        if (totalWeightedScore >= 90) rating = 'Exemplary';
        else if (totalWeightedScore >= 70) rating = 'Satisfactory';
        else if (totalWeightedScore >= 50) rating = 'Needs Improvement';
        else if (totalWeightedScore > 0) rating = 'Unsatisfactory';
        
        if (performanceRating) performanceRating.textContent = rating;
        
        return totalWeightedScore;
    }

    // ==============================================
    // COMMENT MODAL FUNCTIONS
    // ==============================================

    function openCommentModal(index, currentComment) {
        currentCommentIndex = index;
        
        if (!commentModal) {
            commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
        }
        
        const textarea = document.getElementById('modalCommentTextarea');
        const charCount = document.getElementById('charCount');
        
        textarea.value = currentComment || '';
        charCount.textContent = textarea.value.length;
        
        commentModal.show();
    }

    function saveComment() {
        if (currentCommentIndex === -1) return;
        
        const textarea = document.getElementById('modalCommentTextarea');
        const comment = textarea.value.trim();
        const commentInput = document.getElementById(`commentInput${currentCommentIndex}`);
        const commentPreview = document.getElementById(`commentPreview${currentCommentIndex}`);
        
        if (commentInput) commentInput.value = comment;
        
        if (commentPreview) {
            commentPreview.textContent = comment || 'No comment added';
            commentPreview.className = comment ? 'comment-preview' : 'comment-preview text-muted';
        }
        
        commentModal.hide();
    }

    function clearComment() {
        const textarea = document.getElementById('modalCommentTextarea');
        textarea.value = '';
        document.getElementById('charCount').textContent = '0';
        textarea.focus();
    }

    // ==============================================
    // FORM SUBMISSION FUNCTIONS
    // ==============================================

    function validateForm() {
        let allRatingsSelected = true;
        let firstInvalid = null;
        
        for (let i = 0; i < 7; i++) {
            const ratingSelect = document.getElementById(`rating${i}`);
            if (!ratingSelect || !ratingSelect.value) {
                allRatingsSelected = false;
                if (ratingSelect) {
                    ratingSelect.classList.add('error');
                    if (!firstInvalid) firstInvalid = ratingSelect;
                }
            } else if (ratingSelect) {
                ratingSelect.classList.remove('error');
            }
        }
        
        const agreeOption = document.getElementById('agreeOption');
        const notAgreeOption = document.getElementById('notAgreeOption');
        
        if (!agreeOption.checked && !notAgreeOption.checked) {
            showMessage('Please select an agreement option.', 'error');
            return false;
        }
        
        if (!allRatingsSelected && firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            showMessage('Please select a rating for all KPAs.', 'error');
            return false;
        }
        
        return true;
    }

    function saveAsDraft(event) {
        event.preventDefault();
        document.getElementById('formStatus').value = 'draft';
        
        const button = event.currentTarget;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        button.disabled = true;
        
        calculateScores();
        showMessage('Appraisal saved as draft successfully!', 'success');
        
        setTimeout(() => {
            document.getElementById('editAppraisalForm').submit();
        }, 1500);
    }

    function submitForm(event) {
        event.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        const totalScore = calculateScores();
        const performanceRating = document.getElementById('performanceRating')?.textContent || '-';
        const action = '{{ $appraisal->status === 'submitted' ? 'resubmit' : 'submit' }}';
        
        const confirmationMessage = `Your calculated score: ${totalScore.toFixed(2)}/100\nPerformance Rating: ${performanceRating}\n\nAre you sure you want to ${action} this appraisal?`;
        
        if (confirm(confirmationMessage)) {
            document.getElementById('formStatus').value = 'submitted';
            
            const button = event.currentTarget;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            button.disabled = true;
            
            showMessage('Appraisal submitted successfully!', 'success');
            
            setTimeout(() => {
                document.getElementById('editAppraisalForm').submit();
            }, 1500);
        }
    }

    // ==============================================
    // UTILITY FUNCTIONS
    // ==============================================

    function showMessage(message, type = 'info') {
        const messageContainer = document.getElementById('messageContainer');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message message-${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };
        
        messageDiv.innerHTML = `
            <i class="message-icon fas ${icons[type]} me-2"></i>
            <div class="message-content flex-grow-1">${message}</div>
            <button class="message-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        messageContainer.appendChild(messageDiv);
        
        setTimeout(() => {
            if (messageDiv.parentElement) {
                messageDiv.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    if (messageDiv.parentElement) messageDiv.remove();
                }, 300);
            }
        }, 5000);
    }

    // ==============================================
    // AGREEMENT OPTION HANDLER
    // ==============================================

    document.addEventListener('DOMContentLoaded', function() {
        const agreeOption = document.getElementById('agreeOption');
        const notAgreeOption = document.getElementById('notAgreeOption');
        const managerReasonSection = document.getElementById('managerReasonSection');
        
        if (agreeOption && notAgreeOption) {
            agreeOption.addEventListener('change', function() {
                if (this.checked) managerReasonSection.classList.add('d-none');
            });
            
            notAgreeOption.addEventListener('change', function() {
                if (this.checked) managerReasonSection.classList.remove('d-none');
            });
        }
        
        // Initialize rating selects
        for (let i = 0; i < 7; i++) {
            const ratingSelect = document.getElementById(`rating${i}`);
            if (ratingSelect) {
                ratingSelect.addEventListener('change', function() {
                    this.classList.remove('error');
                    calculateScores();
                });
            }
        }
        
        // Character count for comment modal
        const commentTextarea = document.getElementById('modalCommentTextarea');
        if (commentTextarea) {
            commentTextarea.addEventListener('input', function() {
                const charCount = document.getElementById('charCount');
                charCount.textContent = this.value.length;
                
                if (this.value.length > 500) {
                    this.value = this.value.substring(0, 500);
                    charCount.textContent = '500';
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }
            });
        }
        
        commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
        calculateScores();
    });
    </script>
</body>
</html>