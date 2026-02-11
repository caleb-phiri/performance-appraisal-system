<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Manager Performance Appraisal - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
            --moic-gradient: linear-gradient(135deg, #110484, #e7581c);
        }
        .rating-select {
            transition: all 0.2s ease;
        }
        .rating-select:focus {
            box-shadow: 0 0 0 3px rgba(17, 4, 132, 0.1);
            border-color: #110484;
        }
        .category-header {
            background: linear-gradient(135deg, #110484 0%, #1a0c9e 100%);
            color: white;
            font-weight: bold;
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        .form-section {
            border-left: 4px solid #110484;
        }
        .weight-input:focus {
            border-color: #110484;
            box-shadow: 0 0 0 3px rgba(17, 4, 132, 0.1);
        }
        .kpa-row:hover {
            background-color: #f8fafc;
        }
        .score-cell {
            background-color: #f8fafc;
            font-weight: 600;
        }
        .weight-cell {
            background-color: #f0f9ff;
        }
        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            z-index: 1001;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal.active, .modal-overlay.active {
            display: block;
        }
        .modal-header {
            background: linear-gradient(135deg, #110484 0%, #1a0c9e 100%);
            color: white;
            padding: 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .comment-preview {
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .critical-badge {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 4px;
        }
        .cash-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 4px;
        }
        .supervisory-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 4px;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    @php
        // Quarter calculation function with updated deadlines
        function getQuarterInfo() {
            $now = now();
            $month = $now->month;
            $year = $now->year;
            
            $quarterInfo = [
                'current_date' => $now->format('Y-m-d'),
                'year' => $year,
                'quarter' => '',
                'quarter_name' => '',
                'quarter_months' => '',
                'due_date' => '',
                'appraisal_start' => '',
                'appraisal_end' => '',
                'review_start' => '',
                'review_end' => ''
            ];
            
            if ($month >= 1 && $month <= 3) {
                $quarterInfo['quarter'] = 'Q1';
                $quarterInfo['quarter_name'] = 'Quarter 1';
                $quarterInfo['quarter_months'] = 'January - March';
                $quarterInfo['due_date'] = date('M d', strtotime("April 20, $year"));
                $quarterInfo['appraisal_start'] = date('M d', strtotime("January 1, $year"));
                $quarterInfo['appraisal_end'] = date('M d', strtotime("April 10, $year"));
                $quarterInfo['review_start'] = date('M d', strtotime("April 11, $year"));
                $quarterInfo['review_end'] = date('M d', strtotime("April 18, $year"));
            } elseif ($month >= 4 && $month <= 6) {
                $quarterInfo['quarter'] = 'Q2';
                $quarterInfo['quarter_name'] = 'Quarter 2';
                $quarterInfo['quarter_months'] = 'April - June';
                $quarterInfo['due_date'] = date('M d', strtotime("July 20, $year"));
                $quarterInfo['appraisal_start'] = date('M d', strtotime("April 1, $year"));
                $quarterInfo['appraisal_end'] = date('M d', strtotime("July 10, $year"));
                $quarterInfo['review_start'] = date('M d', strtotime("July 11, $year"));
                $quarterInfo['review_end'] = date('M d', strtotime("July 18, $year"));
            } elseif ($month >= 7 && $month <= 9) {
                $quarterInfo['quarter'] = 'Q3';
                $quarterInfo['quarter_name'] = 'Quarter 3';
                $quarterInfo['quarter_months'] = 'July - September';
                $quarterInfo['due_date'] = date('M d', strtotime("October 20, $year"));
                $quarterInfo['appraisal_start'] = date('M d', strtotime("July 1, $year"));
                $quarterInfo['appraisal_end'] = date('M d', strtotime("October 10, $year"));
                $quarterInfo['review_start'] = date('M d', strtotime("October 11, $year"));
                $quarterInfo['review_end'] = date('M d', strtotime("October 18, $year"));
            } else {
                $quarterInfo['quarter'] = 'Q4';
                $quarterInfo['quarter_name'] = 'Quarter 4';
                $quarterInfo['quarter_months'] = 'October - December';
                $quarterInfo['due_date'] = date('M d', strtotime("January 20, " . ($year + 1)));
                $quarterInfo['appraisal_start'] = date('M d', strtotime("October 1, $year"));
                $quarterInfo['appraisal_end'] = date('M d', strtotime("January 10, " . ($year + 1)));
                $quarterInfo['review_start'] = date('M d', strtotime("January 11, " . ($year + 1)));
                $quarterInfo['review_end'] = date('M d', strtotime("January 18, " . ($year + 1)));
            }
            
            return (object) $quarterInfo;
        }
        
        $quarterInfo = getQuarterInfo();
        
        // Determine quarter dates for hidden inputs
        $quarterDates = [
            'Q1' => ['start' => date('Y-01-01'), 'end' => date('Y-03-31')],
            'Q2' => ['start' => date('Y-04-01'), 'end' => date('Y-06-30')],
            'Q3' => ['start' => date('Y-07-01'), 'end' => date('Y-09-30')],
            'Q4' => ['start' => date('Y-10-01'), 'end' => date('Y-12-31')]
        ];
        
        $startDate = $quarterDates[$quarterInfo->quarter]['start'];
        $endDate = $quarterDates[$quarterInfo->quarter]['end'];
    @endphp

    <!-- Error Display -->
    @if($errors->any())
    <div class="max-w-7xl mx-auto mb-4">
        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">There were errors with your submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="bg-white p-1.5 rounded-md mr-3">
                                <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold mb-1">Shift Manager - Performance Appraisal</h1>
                                <p class="text-sm opacity-90 flex items-center">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    <span class="font-medium">{{ Auth::user()->name }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-users-cog mr-1"></i>
                                    Shift Manager
                                </p>
                            </div>
                        </div>
                        
                        <!-- Quarter Info -->
                        <div class="flex flex-wrap gap-4 mt-4">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2">
                                <p class="text-xs text-blue-100">Current Quarter</p>
                                <p class="font-semibold text-white">{{ $quarterInfo->quarter_name }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2">
                                <p class="text-xs text-blue-100">Period</p>
                                <p class="font-semibold text-white">{{ $quarterInfo->quarter_months }} {{ $quarterInfo->year }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2">
                                <p class="text-xs text-blue-100">Deadline</p>
                                <p class="font-semibold text-white">{{ $quarterInfo->due_date }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2">
                                <p class="text-xs text-blue-100">Reporting To</p>
                                <p class="font-semibold text-white">Plaza Manager</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('appraisals.index') }}" 
                           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded transition duration-200 flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i> My Appraisals
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white text-[#110484] hover:bg-gray-100 px-4 py-2 rounded transition duration-200 flex items-center justify-center font-medium">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto">
        <form id="shiftManagerForm" action="{{ route('appraisals.store') }}" method="POST" class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            @csrf
            <input type="hidden" name="status" id="shiftFormStatus" value="draft">
            <input type="hidden" name="period" value="{{ $quarterInfo->quarter }}">
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="job_title" value="Shift Manager">

            <!-- Form Header -->
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-[#110484]">Performance Appraisal Form</h2>
                    <p class="text-gray-600 text-sm">Please complete all sections with accurate information</p>
                </div>
                <div class="flex gap-2">
                    <span class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-users-cog mr-1"></i> Supervisory Role
                    </span>
                    <span class="bg-gradient-to-r from-orange-500 to-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-clock mr-1"></i> Shift Operations
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-[#110484] p-2 rounded mr-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Appraisal Quarter</p>
                            <p class="text-lg font-bold text-gray-800">{{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-amber-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-amber-100 text-[#e7581c] p-2 rounded mr-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Submission Deadline</p>
                            <p class="text-lg font-bold text-gray-800">{{ $quarterInfo->due_date }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-emerald-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 text-green-600 p-2 rounded mr-3">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Weight</p>
                            <p id="shiftTotalWeightDisplay" class="text-lg font-bold text-gray-800">100%</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-100 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">KPA Count</p>
                            <p class="text-lg font-bold text-gray-800">8 KPAs</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPA Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-[#110484] to-[#1a0c9e]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-layer-group mr-2"></i>Category
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-list-alt mr-2"></i>Result Indicators
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-trophy mr-2"></i>KPI Max
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-weight-hanging mr-2"></i>Weight %
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-user-edit mr-2"></i>Rating (Self)
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-percentage mr-2"></i>Score %
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                <i class="fas fa-comment-dots mr-2"></i>Comments
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- ATTENDANCE -->
                        <tr class="kpa-row hover:bg-blue-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 text-[#110484] p-2 rounded mr-3">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">ATTENDANCE</span><br>
                                        <span class="text-xs text-gray-500">Attendance</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[0][category]" value="ATTENDANCE">
                                <input type="hidden" name="kpas[0][kpa]" value="Attendance">
                                <input type="hidden" name="kpas[0][result_indicators]" value="Attended work on time throughout the appraisal period without any instances of leave or absenteeism">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Attended work on time throughout the appraisal period without any instances of leave or absenteeism</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Reduced Attendance - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Unscheduled Leave - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Scheduled Leave - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Full Attendance - 4 Points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-[#110484] rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[0][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[0][weight]" value="10">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        10%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[0][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - Reduced Attendance</option>
                                    <option value="2">2 - Unscheduled Leave</option>
                                    <option value="3">3 - Scheduled Leave</option>
                                    <option value="4">4 - Full Attendance</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell0">0%</span>
                                    <input type="hidden" name="kpas[0][calculated_score]" id="calculatedScore0" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview0" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(0)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[0][comments]" id="commentInput0" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- CODE OF CONDUCT -->
                        <tr class="kpa-row hover:bg-green-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-green-100 text-green-600 p-2 rounded mr-3">
                                        <i class="fas fa-gavel"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">CODE OF CONDUCT</span><br>
                                        <span class="text-xs text-gray-500">Code of Conduct</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[1][category]" value="CODE OF CONDUCT">
                                <input type="hidden" name="kpas[1][kpa]" value="Code of Conduct">
                                <input type="hidden" name="kpas[1][result_indicators]" value="Perform well throughout the appraisal period, no disciplinary record on file">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Perform well throughout the appraisal period, no disciplinary record on file</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Final Written Warning - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Written Warning - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">Verbal Warning - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">No Record - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-green-100 text-green-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[1][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[1][weight]" value="25">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        25%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[1][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - Final Written Warning</option>
                                    <option value="2">2 - Written Warning</option>
                                    <option value="3">3 - Verbal Warning</option>
                                    <option value="4">4 - No Record</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell1">0%</span>
                                    <input type="hidden" name="kpas[1][calculated_score]" id="calculatedScore1" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview1" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(1)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[1][comments]" id="commentInput1" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- WORK PERFORMANCE - Shift Supervision -->
                        <tr class="kpa-row hover:bg-purple-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">WORK PERFORMANCE</span><br>
                                        <span class="text-xs text-gray-500">Shift Supervision</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[2][category]" value="WORK PERFORMANCE">
                                <input type="hidden" name="kpas[2][kpa]" value="Shift Supervision">
                                <input type="hidden" name="kpas[2][result_indicators]" value="Effectively supervise shift operations, ensuring all toll lanes are operational and staff are performing their duties">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Effectively supervise shift operations, ensuring all toll lanes are operational and staff are performing their duties 
                                    <span class="supervisory-badge">SUPERVISORY</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[2][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[2][weight]" value="15">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        15%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[2][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell2">0%</span>
                                    <input type="hidden" name="kpas[2][calculated_score]" id="calculatedScore2" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview2" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(2)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[2][comments]" id="commentInput2" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- WORK PERFORMANCE - Cash Management -->
                        <tr class="kpa-row hover:bg-purple-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">WORK PERFORMANCE</span><br>
                                        <span class="text-xs text-gray-500">Cash Management</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[3][category]" value="WORK PERFORMANCE">
                                <input type="hidden" name="kpas[3][kpa]" value="Cash Management">
                                <input type="hidden" name="kpas[3][result_indicators]" value="Oversee cash collection, reconciliation, and secure handling of toll revenue during shift">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Oversee cash collection, reconciliation, and secure handling of toll revenue during shift 
                                    <span class="cash-badge">CASH HANDLING</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[3][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[3][weight]" value="15">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        15%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[3][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell3">0%</span>
                                    <input type="hidden" name="kpas[3][calculated_score]" id="calculatedScore3" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview3" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(3)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[3][comments]" id="commentInput3" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- WORK PERFORMANCE - Staff Coordination -->
                        <tr class="kpa-row hover:bg-purple-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">WORK PERFORMANCE</span><br>
                                        <span class="text-xs text-gray-500">Staff Coordination</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[4][category]" value="WORK PERFORMANCE">
                                <input type="hidden" name="kpas[4][kpa]" value="Staff Coordination">
                                <input type="hidden" name="kpas[4][result_indicators]" value="Coordinate staff breaks, rotations, and ensure adequate coverage during peak traffic hours">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Coordinate staff breaks, rotations, and ensure adequate coverage during peak traffic hours 
                                    <span class="supervisory-badge">TEAM COORDINATION</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[4][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[4][weight]" value="10">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        10%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[4][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell4">0%</span>
                                    <input type="hidden" name="kpas[4][calculated_score]" id="calculatedScore4" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview4" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(4)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[4][comments]" id="commentInput4" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- WORK PERFORMANCE - Incident Management -->
                        <tr class="kpa-row hover:bg-purple-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">WORK PERFORMANCE</span><br>
                                        <span class="text-xs text-gray-500">Incident Management</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[5][category]" value="WORK PERFORMANCE">
                                <input type="hidden" name="kpas[5][kpa]" value="Incident Management">
                                <input type="hidden" name="kpas[5][result_indicators]" value="Handle customer complaints, equipment failures, and emergencies during shift promptly and professionally">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Handle customer complaints, equipment failures, and emergencies during shift promptly and professionally 
                                    <span class="critical-badge">CRITICAL</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[5][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[5][weight]" value="10">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        10%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[5][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell5">0%</span>
                                    <input type="hidden" name="kpas[5][calculated_score]" id="calculatedScore5" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview5" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(5)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[5][comments]" id="commentInput5" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- WORK PERFORMANCE - Reporting -->
                        <tr class="kpa-row hover:bg-purple-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 text-purple-600 p-2 rounded mr-3">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">WORK PERFORMANCE</span><br>
                                        <span class="text-xs text-gray-500">Reporting</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[6][category]" value="WORK PERFORMANCE">
                                <input type="hidden" name="kpas[6][kpa]" value="Reporting">
                                <input type="hidden" name="kpas[6][result_indicators]" value="Complete accurate shift reports including cash collections, incidents, and staffing issues">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Complete accurate shift reports including cash collections, incidents, and staffing issues</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 text-purple-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[6][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[6][weight]" value="10">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        10%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[6][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell6">0%</span>
                                    <input type="hidden" name="kpas[6][calculated_score]" id="calculatedScore6" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview6" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(6)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[6][comments]" id="commentInput6" value="">
                                </div>
                            </td>
                        </tr>
                        
                        <!-- ATTITUDE -->
                        <tr class="kpa-row hover:bg-yellow-50/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 text-yellow-600 p-2 rounded mr-3">
                                        <i class="fas fa-smile"></i>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900">ATTITUDE</span><br>
                                        <span class="text-xs text-gray-500">Attitude</span>
                                    </div>
                                </div>
                                <input type="hidden" name="kpas[7][category]" value="ATTITUDE">
                                <input type="hidden" name="kpas[7][kpa]" value="Attitude">
                                <input type="hidden" name="kpas[7][result_indicators]" value="Exhibit a proactive attitude, take initiative in tasks, and foster positive interactions with colleagues">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">Exhibit a proactive attitude, take initiative in tasks, and foster positive interactions with colleagues</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">ND - 1 point</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">NS - 2 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">S - 3 points</span>
                                    <span class="inline-block px-2 py-1 bg-gray-100 rounded">EX - 4 points</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full font-bold">
                                    4
                                </span>
                                <input type="hidden" name="kpas[7][kpi]" value="4">
                            </td>
                            <td class="px-6 py-4 weight-cell">
                                <div class="relative">
                                    <input type="hidden" name="kpas[7][weight]" value="5">
                                    <div class="w-20 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-center font-semibold text-gray-700">
                                        5%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <select name="kpas[7][self_rating]" required 
                                        class="rating-select w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                                    <option value="">Select Rating</option>
                                    <option value="1">1 - ND (Not Demonstrated)</option>
                                    <option value="2">2 - NS (Not Satisfactory)</option>
                                    <option value="3">3 - S (Satisfactory)</option>
                                    <option value="4">4 - EX (Exemplary)</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 score-cell">
                                <div class="text-center font-semibold">
                                    <span id="scoreCell7">0%</span>
                                    <input type="hidden" name="kpas[7][calculated_score]" id="calculatedScore7" value="0">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <div id="commentPreview7" class="comment-preview text-sm text-gray-600">
                                        <!-- Comment preview will be shown here -->
                                    </div>
                                    <button type="button" onclick="openCommentModal(7)" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg text-sm font-medium transition duration-200">
                                        <i class="fas fa-edit mr-1.5"></i> Add/Edit Comment
                                    </button>
                                    <input type="hidden" name="kpas[7][comments]" id="commentInput7" value="">
                                </div>
                            </td>
                        </tr>

                        <!-- TOTAL ROW -->
                        <tr class="bg-gradient-to-r from-gray-800 to-gray-900 text-white font-bold">
                            <td class="px-6 py-4 text-center" colspan="3">
                                <i class="fas fa-calculator mr-2"></i>TOTAL
                            </td>
                            <td class="px-6 py-4 text-center">
                                100%
                            </td>
                            <td class="px-6 py-4 text-center">
                                -
                            </td>
                            <td class="px-6 py-4 text-center" id="totalScoreCell">
                                0%
                            </td>
                            <td class="px-6 py-4 text-center">
                                <i class="fas fa-chart-line"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Weight Summary -->
            <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-[#110484] mr-2"></i>
                    Weight Distribution Summary
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">Total Weight Percentage:</span>
                            <span id="shiftTotalWeight" class="text-lg font-bold text-[#110484]">100%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 progress-bar">
                            <div id="shiftWeightProgress" class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-500" style="width: 100%"></div>
                        </div>
                    </div>
                    <div id="shiftWeightStatus" class="flex items-center text-sm font-semibold text-green-600">
                        <i class="fas fa-check-circle mr-2"></i> 
                        <span>Total weight equals 100% - Ready for submission</span>
                    </div>
                </div>
            </div>

            <!-- Current Score Summary -->
            <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calculator text-[#110484] mr-2"></i>
                    Score Calculation
                </h3>
                
                <!-- Score Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#110484]" id="displayTotalScore">0.00</div>
                            <div class="text-sm text-gray-600 mt-1">Total Score</div>
                            <div class="text-xs text-gray-500">Out of 100</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#110484]" id="displayTotalPercentage">0%</div>
                            <div class="text-sm text-gray-600 mt-1">Overall Percentage</div>
                            <div class="text-xs text-gray-500">Based on 100% scale</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#110484]" id="performanceRating">-</div>
                            <div class="text-sm text-gray-600 mt-1">Performance Rating</div>
                            <div class="text-xs text-gray-500">Based on score</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Development Needs & Comments -->
            <div class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-section bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-chart-line text-[#110484] mr-2"></i>
                            Development Needs
                        </label>
                        <textarea name="development_needs" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent" 
                                  placeholder="List your development needs for the next period..."></textarea>
                    </div>
                    
                    <div class="form-section bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-comment text-green-600 mr-2"></i>
                            Additional Comments
                        </label>
                        <textarea name="employee_comments" rows="4" 
                                  class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent" 
                                  placeholder="Any additional comments or feedback..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="bg-blue-100 text-[#110484] p-2 rounded mr-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="font-medium">Submission Guidelines</p>
                            <p class="text-xs">All ratings must be selected and total weight is fixed at 100%</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="saveAsDraft()" 
                                class="bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Save as Draft
                        </button>
                        <button type="button" onclick="submitForm()" 
                                class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] hover:from-[#1a0c9e] hover:to-[#110484] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Appraisal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Comment Modal -->
    <div id="commentModalOverlay" class="modal-overlay"></div>
    <div id="commentModal" class="modal">
        <div class="modal-header">
            <h3 class="text-lg font-bold" id="modalTitle">Add Comment</h3>
            <p class="text-sm text-blue-100 mt-1" id="modalSubtitle"></p>
        </div>
        <div class="modal-body">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment-dots mr-2"></i>Your Comment
                </label>
                <textarea id="modalCommentTextarea" rows="5" 
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent" 
                          placeholder="Enter your comment here..."></textarea>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-500">
                        <span id="charCount">0</span>/500 characters
                    </span>
                    <button type="button" onclick="clearComment()" 
                            class="text-xs text-gray-500 hover:text-red-600 transition duration-200">
                        <i class="fas fa-trash-alt mr-1"></i> Clear
                    </button>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCommentModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                    Cancel
                </button>
                <button type="button" onclick="saveComment()" 
                        class="px-4 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow transition duration-200 font-medium">
                    Save Comment
                </button>
            </div>
        </div>
    </div>

    <!-- Quarter Info Footer -->
    <div class="max-w-7xl mx-auto mt-8">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="bg-white p-1 rounded-md mr-3">
                        <img class="h-6 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">MOIC Performance Appraisal System © {{ date('Y') }}</p>
                        <p class="text-xs text-gray-400">Current Quarter: {{ $quarterInfo->quarter }} {{ $quarterInfo->year }}</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                        Deadline: {{ $quarterInfo->due_date }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-user-clock mr-1 text-blue-500"></i>
                        Period: {{ $quarterInfo->quarter_months }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal State
        let currentCommentIndex = -1;
        const kpaTitles = [
            "ATTENDANCE - Attendance",
            "CODE OF CONDUCT - Code of Conduct",
            "WORK PERFORMANCE - Shift Supervision",
            "WORK PERFORMANCE - Cash Management",
            "WORK PERFORMANCE - Staff Coordination",
            "WORK PERFORMANCE - Incident Management",
            "WORK PERFORMANCE - Reporting",
            "ATTITUDE - Attitude"
        ];
        
        // Modal Functions
        function openCommentModal(index) {
            currentCommentIndex = index;
            const modal = document.getElementById('commentModal');
            const overlay = document.getElementById('commentModalOverlay');
            const textarea = document.getElementById('modalCommentTextarea');
            const charCount = document.getElementById('charCount');
            
            // Set modal title
            document.getElementById('modalTitle').textContent = `Add Comment for ${kpaTitles[index]}`;
            document.getElementById('modalSubtitle').textContent = "Provide feedback or explanation for your rating";
            
            // Load existing comment
            const commentInput = document.getElementById(`commentInput${index}`);
            textarea.value = commentInput.value || '';
            charCount.textContent = textarea.value.length;
            
            // Show modal
            modal.classList.add('active');
            overlay.classList.add('active');
            
            // Focus textarea
            setTimeout(() => {
                textarea.focus();
            }, 100);
        }
        
        function closeCommentModal() {
            const modal = document.getElementById('commentModal');
            const overlay = document.getElementById('commentModalOverlay');
            modal.classList.remove('active');
            overlay.classList.remove('active');
            currentCommentIndex = -1;
        }
        
        function saveComment() {
            if (currentCommentIndex === -1) return;
            
            const textarea = document.getElementById('modalCommentTextarea');
            const comment = textarea.value.trim();
            const commentInput = document.getElementById(`commentInput${currentCommentIndex}`);
            const commentPreview = document.getElementById(`commentPreview${currentCommentIndex}`);
            
            // Save to hidden input
            commentInput.value = comment;
            
            // Update preview
            if (comment) {
                commentPreview.textContent = comment;
                commentPreview.classList.remove('text-gray-400');
                commentPreview.classList.add('text-gray-600');
            } else {
                commentPreview.textContent = 'No comment added';
                commentPreview.classList.remove('text-gray-600');
                commentPreview.classList.add('text-gray-400');
            }
            
            closeCommentModal();
        }
        
        function clearComment() {
            const textarea = document.getElementById('modalCommentTextarea');
            textarea.value = '';
            document.getElementById('charCount').textContent = '0';
            textarea.focus();
        }
        
        // Character count
        document.getElementById('modalCommentTextarea').addEventListener('input', function() {
            const charCount = document.getElementById('charCount');
            charCount.textContent = this.value.length;
            
            if (this.value.length > 500) {
                this.value = this.value.substring(0, 500);
                charCount.textContent = '500';
                charCount.classList.add('text-red-600');
            } else {
                charCount.classList.remove('text-red-600');
            }
        });
        
        // Close modal on overlay click
        document.getElementById('commentModalOverlay').addEventListener('click', closeCommentModal);
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCommentModal();
            }
        });
        
        // Score Calculation Functions (G9/E9*F9 and SUM formulas)
        function calculateScores() {
            const form = document.getElementById('shiftManagerForm');
            const ratingSelects = form.querySelectorAll('select[name*="[self_rating]"]');
            
            let totalWeightedScore = 0;
            const weights = [10, 25, 15, 15, 10, 10, 10, 5]; // Fixed weights for Shift Manager
            
            ratingSelects.forEach((select, index) => {
                const rating = parseFloat(select.value) || 0;
                const maxKPI = 4; // All KPAs have max 4 points
                
                // Calculate weighted score: G9/E9*F9
                let weightedScore = 0;
                if (rating > 0) {
                    weightedScore = (rating / maxKPI) * weights[index];
                }
                
                // Update individual score cell
                const scoreCell = document.getElementById(`scoreCell${index}`);
                const calculatedScoreInput = document.getElementById(`calculatedScore${index}`);
                
                scoreCell.textContent = weightedScore.toFixed(2) + '%';
                calculatedScoreInput.value = weightedScore.toFixed(2);
                
                // Color code individual scores
                if (weightedScore >= (weights[index] * 0.9)) {
                    scoreCell.classList.remove('text-red-600', 'text-yellow-600');
                    scoreCell.classList.add('text-green-600');
                } else if (weightedScore >= (weights[index] * 0.7)) {
                    scoreCell.classList.remove('text-red-600', 'text-green-600');
                    scoreCell.classList.add('text-yellow-600');
                } else {
                    scoreCell.classList.remove('text-green-600', 'text-yellow-600');
                    scoreCell.classList.add('text-red-600');
                }
                
                totalWeightedScore += weightedScore;
            });
            
            // Update total score in table
            const totalScoreCell = document.getElementById('totalScoreCell');
            totalScoreCell.textContent = totalWeightedScore.toFixed(2) + '%';
            
            // Update display
            const displayTotalScore = document.getElementById('displayTotalScore');
            const displayTotalPercentage = document.getElementById('displayTotalPercentage');
            const performanceRating = document.getElementById('performanceRating');
            
            displayTotalScore.textContent = totalWeightedScore.toFixed(2);
            displayTotalPercentage.textContent = totalWeightedScore.toFixed(2) + '%';
            
            // Determine performance rating
            let rating = '-';
            let ratingColor = 'text-[#110484]';
            
            if (totalWeightedScore >= 90) {
                rating = 'Exemplary';
                ratingColor = 'text-green-600';
            } else if (totalWeightedScore >= 70) {
                rating = 'Satisfactory';
                ratingColor = 'text-blue-600';
            } else if (totalWeightedScore >= 50) {
                rating = 'Needs Improvement';
                ratingColor = 'text-yellow-600';
            } else if (totalWeightedScore > 0) {
                rating = 'Unsatisfactory';
                ratingColor = 'text-red-600';
            }
            
            performanceRating.textContent = rating;
            performanceRating.className = `text-2xl font-bold ${ratingColor}`;
            
            // Color code total score cell
            if (totalWeightedScore >= 90) {
                totalScoreCell.classList.remove('text-red-300', 'text-yellow-300');
                totalScoreCell.classList.add('text-green-300');
            } else if (totalWeightedScore >= 70) {
                totalScoreCell.classList.remove('text-red-300', 'text-green-300');
                totalScoreCell.classList.add('text-yellow-300');
            } else {
                totalScoreCell.classList.remove('text-green-300', 'text-yellow-300');
                totalScoreCell.classList.add('text-red-300');
            }
            
            return totalWeightedScore;
        }

        // Weight Calculation Functions
        function calculateWeightTotal() {
            const totalWeight = 100; // Fixed at 100% for Shift Manager
            
            // Update all displays
            const weightDisplay = document.getElementById('shiftTotalWeight');
            const weightStatsDisplay = document.getElementById('shiftTotalWeightDisplay');
            const progressBar = document.getElementById('shiftWeightProgress');
            const statusDiv = document.getElementById('shiftWeightStatus');
            
            weightDisplay.textContent = totalWeight.toFixed(0) + '%';
            weightStatsDisplay.textContent = totalWeight.toFixed(0) + '%';
            progressBar.style.width = Math.min(totalWeight, 100) + '%';
            
            if (totalWeight === 100) {
                weightDisplay.classList.remove('text-red-600');
                weightDisplay.classList.add('text-[#110484]');
                progressBar.classList.remove('bg-gradient-to-r', 'from-red-500', 'to-rose-500');
                progressBar.classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-500');
                statusDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i> <span>Total weight equals 100% - Ready for submission</span>';
                statusDiv.classList.remove('text-red-600');
                statusDiv.classList.add('text-green-600');
            } else {
                weightDisplay.classList.remove('text-[#110484]');
                weightDisplay.classList.add('text-red-600');
                progressBar.classList.remove('bg-gradient-to-r', 'from-green-500', 'to-emerald-500');
                progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-rose-500');
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> <span>Total weight must equal 100% - Current: ' + totalWeight + '%</span>';
                statusDiv.classList.remove('text-green-600');
                statusDiv.classList.add('text-red-600');
            }
            
            return totalWeight;
        }

        // Form Submission Functions
        function saveAsDraft() {
            document.getElementById('shiftFormStatus').value = 'draft';
            
            // Calculate final score
            const totalScore = calculateScores();
            
            // Show loading state
            const button = event.currentTarget;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            button.disabled = true;
            
            document.getElementById('shiftManagerForm').submit();
        }

        function submitForm() {
            // Check if all ratings are selected
            const form = document.getElementById('shiftManagerForm');
            const ratingSelects = form.querySelectorAll('select[name*="[self_rating]"]');
            let allRatingsSelected = true;
            
            ratingSelects.forEach(select => {
                if (!select.value) {
                    allRatingsSelected = false;
                    select.classList.add('border-red-500', 'border-2');
                    
                    // Scroll to first missing rating
                    if (allRatingsSelected) {
                        select.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    select.classList.remove('border-red-500', 'border-2');
                }
            });
            
            if (!allRatingsSelected) {
                alert('Please select a rating for all KPAs before submitting.');
                return;
            }
            
            // Check weight total
            const totalWeight = calculateWeightTotal();
            if (totalWeight !== 100) {
                alert('Total weight must equal 100% before submitting. Current total: ' + totalWeight + '%');
                return;
            }
            
            // Calculate final score
            const totalScore = calculateScores();
            const performanceRating = document.getElementById('performanceRating').textContent;
            
            // Confirm submission with score summary
            const confirmationMessage = `Your calculated score: ${totalScore.toFixed(2)}/100\nPerformance Rating: ${performanceRating}\n\nAre you sure you want to submit this appraisal? Once submitted, it cannot be edited.`;
            
            if (confirm(confirmationMessage)) {
                document.getElementById('shiftFormStatus').value = 'submitted';
                
                // Show loading state
                const button = event.currentTarget;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                button.disabled = true;
                
                document.getElementById('shiftManagerForm').submit();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize comment previews
            for (let i = 0; i < 8; i++) {
                const commentInput = document.getElementById(`commentInput${i}`);
                const commentPreview = document.getElementById(`commentPreview${i}`);
                
                if (commentInput.value) {
                    commentPreview.textContent = commentInput.value;
                    commentPreview.classList.add('text-gray-600');
                } else {
                    commentPreview.textContent = 'No comment added';
                    commentPreview.classList.add('text-gray-400');
                }
            }
            
            // Add event listeners to all rating selects
            document.querySelectorAll('select[name*="[self_rating]"]').forEach(select => {
                select.addEventListener('change', function() {
                    this.classList.remove('border-red-500', 'border-2');
                    calculateScores();
                });
            });
            
            // Calculate initial values
            calculateWeightTotal();
            calculateScores();
        });
    </script>
</body>
</html>