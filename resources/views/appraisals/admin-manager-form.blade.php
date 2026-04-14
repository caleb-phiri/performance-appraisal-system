<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manager Performance Appraisal - MOIC</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .rating-option {
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s ease;
        }
        .nd { background: #fee2e2; color: #dc2626; }
        .ns { background: #fef3c7; color: #d97706; }
        .s { background: #dbeafe; color: #2563eb; }
        .ex { background: #dcfce7; color: #16a34a; }
        .rating-option.selected {
            border: 2px solid currentColor;
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .grace-period-alert {
            background: linear-gradient(135deg, #fef3c7, #fffbeb);
            border-left: 4px solid #f59e0b;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    @php
        /**
         * ENHANCED QUARTER FUNCTION WITH GRACE PERIOD
         * A quarter remains OPEN until the 20th of the month following its end.
         */
        function getQuarterInfoWithGrace($year = null, $quarter = null) {
            $now = now();
            $currentYear = $year ?? $now->year;
            $today = $now->copy()->startOfDay();
            
            $quarters = [
                'Q1' => [
                    'name' => 'Quarter 1',
                    'months' => 'January - March',
                    'period_start' => $currentYear . '-01-01',
                    'period_end' => $currentYear . '-03-31',
                    'grace_end' => $currentYear . '-04-20',
                    'due_date_formatted' => 'April 20',
                ],
                'Q2' => [
                    'name' => 'Quarter 2',
                    'months' => 'April - June',
                    'period_start' => $currentYear . '-04-01',
                    'period_end' => $currentYear . '-06-30',
                    'grace_end' => $currentYear . '-07-20',
                    'due_date_formatted' => 'July 20',
                ],
                'Q3' => [
                    'name' => 'Quarter 3',
                    'months' => 'July - September',
                    'period_start' => $currentYear . '-07-01',
                    'period_end' => $currentYear . '-09-30',
                    'grace_end' => $currentYear . '-10-20',
                    'due_date_formatted' => 'October 20',
                ],
                'Q4' => [
                    'name' => 'Quarter 4',
                    'months' => 'October - December',
                    'period_start' => $currentYear . '-10-01',
                    'period_end' => $currentYear . '-12-31',
                    'grace_end' => ($currentYear + 1) . '-01-20',
                    'due_date_formatted' => 'January 20',
                ],
            ];
            
            // If a specific quarter is requested
            if ($quarter && isset($quarters[$quarter])) {
                $q = $quarters[$quarter];
                $graceEndDate = \Carbon\Carbon::parse($q['grace_end']);
                $periodEndDate = \Carbon\Carbon::parse($q['period_end']);
                $isPast = $today->gt($graceEndDate);
                $isCurrent = $today->lte($graceEndDate);
                $isInGrace = $today->gt($periodEndDate) && $today->lte($graceEndDate);
                
                return (object) [
                    'quarter' => $quarter,
                    'quarter_name' => $q['name'],
                    'quarter_months' => $q['months'],
                    'due_date' => $graceEndDate->format('M d'),
                    'due_date_formatted' => $q['due_date_formatted'],
                    'due_date_timestamp' => $graceEndDate->timestamp,
                    'period_end' => $periodEndDate->format('Y-m-d'),
                    'grace_end' => $q['grace_end'],
                    'is_past' => $isPast,
                    'is_current' => $isCurrent,
                    'is_future' => !$isCurrent && !$isPast,
                    'is_in_grace' => $isInGrace,
                    'year' => $currentYear,
                ];
            }
            
            // Determine current quarter based on today's date (grace period aware)
            $currentQuarter = null;
            foreach ($quarters as $qKey => $qData) {
                $graceEnd = \Carbon\Carbon::parse($qData['grace_end']);
                if ($today->lte($graceEnd)) {
                    $currentQuarter = $qKey;
                    break;
                }
            }
            
            // If all quarters are past, default to Q4
            if (!$currentQuarter) {
                $currentQuarter = 'Q4';
            }
            
            return getQuarterInfoWithGrace($currentYear, $currentQuarter);
        }
        
        // Get quarter info from the passed variables or use grace period function
        $quarterInfo = getQuarterInfoWithGrace();
        $quarter = $quarter ?? $quarterInfo->quarter;
        $year = $year ?? $quarterInfo->year;
        $quarterMonths = $quarterMonths ?? $quarterInfo->quarter_months;
        $dueDate = $dueDate ?? $quarterInfo->due_date;
        $dueDateFormatted = $quarterInfo->due_date_formatted;
        $isInGrace = $quarterInfo->is_in_grace;
        
        $weights = [15, 15, 15, 15, 10, 10, 10, 10];
        $kpaTitles = [
            "Strategic Planning & Execution",
            "Budget Management & Cost Control",
            "Staff Management & Development",
            "Procurement & Asset Management",
            "Policy Implementation",
            "Office Administration",
            "Stakeholder Engagement",
            "Reporting & Documentation"
        ];
        
        // Check if user has already submitted for this quarter (replace with actual check)
        $hasAnySubmissionThisQuarter = false;
    @endphp

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-purple-800 rounded-xl shadow-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Admin Manager Performance Appraisal</h1>
                        <p class="text-blue-100 mt-1">{{ $quarter }} {{ $year }}</p>
                    </div>
                    <a href="{{ route('appraisals.create') }}" class="bg-white text-blue-900 px-4 py-2 rounded-lg hover:bg-blue-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Grace Period Alert -->
        @if($isInGrace && !$hasAnySubmissionThisQuarter)
        <div class="grace-period-alert p-4 mb-6 flex items-center">
            <i class="fas fa-hourglass-half fa-2x text-warning mr-4" style="color: #d97706;"></i>
            <div>
                <h6 class="font-bold mb-1" style="color: #92400e;">Grace Period Active!</h6>
                <p class="mb-0 text-sm" style="color: #78350f;">
                    You can still submit your appraisal for <strong>{{ $quarterInfo->quarter_name }} ({{ $quarterInfo->quarter_months }})</strong> 
                    until <strong>{{ $dueDateFormatted }}, {{ $year }}</strong>. 
                    Don't miss this extended deadline!
                </p>
            </div>
        </div>
        @endif

        <!-- Employee Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Employee Name</label>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Employee ID</label>
                    <p class="font-semibold">{{ Auth::user()->employee_number }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Department</label>
                    <p class="font-semibold">Administration</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Review Period</label>
                    <p class="font-semibold">{{ $quarterMonths }} {{ $year }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Deadline</label>
                    <p class="font-semibold {{ $isInGrace ? 'text-amber-600' : 'text-red-600' }}">
                        {{ $dueDateFormatted }}, {{ $year }}
                        @if($isInGrace)
                        <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded ml-2">Grace Period</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <form id="appraisalForm" method="POST" action="{{ route('appraisals.store') }}">
            @csrf
            <input type="hidden" name="status" id="formStatus" value="draft">
            <input type="hidden" name="period" value="{{ $quarter }}">
            <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ date('Y-m-d', strtotime('+3 months')) }}">
            <input type="hidden" name="job_title" value="Admin Manager">

            <!-- KPA Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Key Performance Areas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-900 text-white">
                            <tr>
                                <th class="p-3 text-left">KPA</th>
                                <th class="p-3 text-left">KPIs / Expected Results</th>
                                <th class="p-3 text-center">Weight %</th>
                                <th class="p-3 text-center">Rating (1-4)</th>
                                <th class="p-3 text-left">Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kpaTitles as $index => $title)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">
                                    {{ $title }}
                                    <input type="hidden" name="kpas[{{ $index }}][category]" value="ADMIN MANAGEMENT">
                                    <input type="hidden" name="kpas[{{ $index }}][kpa]" value="{{ $title }}">
                                 </td>
                                <td class="p-3">
                                    <textarea name="kpas[{{ $index }}][result_indicators]" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Describe expected results..."></textarea>
                                    <input type="hidden" name="kpas[{{ $index }}][kpi]" value="4">
                                 </td>
                                <td class="p-3 text-center">
                                    <input type="number" name="kpas[{{ $index }}][weight]" value="{{ $weights[$index] }}" class="w-20 border rounded px-2 py-1 text-center weight-input" readonly>
                                 </td>
                                <td class="p-3">
                                    <div class="grid grid-cols-4 gap-1 min-w-[200px]">
                                        @foreach([1=>'ND',2=>'NS',3=>'S',4=>'EX'] as $val => $label)
                                        <div class="rating-option {{ $val==1?'nd':($val==2?'ns':($val==3?'s':'ex')) }}" 
                                             onclick="setRating({{ $index }}, {{ $val }}, this)">
                                            {{ $label }}
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="rating_{{ $index }}" name="kpas[{{ $index }}][self_rating]" value="">
                                 </td>
                                <td class="p-3">
                                    <textarea name="kpas[{{ $index }}][comments]" rows="2" class="w-full border rounded p-2 text-sm" placeholder="Add comments..."></textarea>
                                 </td>
                             </tr>
                            @endforeach
                            
                            <!-- Total Row -->
                            <tr class="bg-blue-50 font-bold">
                                <td colspan="2" class="p-3 text-right">TOTAL</td>
                                <td class="p-3 text-center"><span id="totalWeight">100</span>%</td>
                                <td class="p-3 text-center"><span id="totalRating">0</span></td>
                                <td class="p-3 text-center"><span id="totalScore">0%</span></td>
                             </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Development & Comments -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Development Needs</h3>
                    <textarea name="development_needs" rows="4" class="w-full border rounded-lg p-3" placeholder="List areas for improvement and development needs..."></textarea>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Additional Comments</h3>
                    <textarea name="employee_comments" rows="4" class="w-full border rounded-lg p-3" placeholder="Any additional comments or feedback..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-md p-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Please rate each KPA on a scale of 1-4 (ND=Not Demonstrated, NS=Not Satisfactory, S=Satisfactory, EX=Exemplary)
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="saveDraft()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Save Draft
                    </button>
                    <button type="button" onclick="submitForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let ratings = Array(8).fill(0);
        let isInGrace = {{ $isInGrace ? 'true' : 'false' }};
        
        function setRating(index, value, element) {
            ratings[index] = value;
            document.getElementById('rating_' + index).value = value;
            
            element.parentElement.querySelectorAll('.rating-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            
            recalculateTotal();
        }
        
        function recalculateTotal() {
            let totalRating = ratings.reduce((a,b) => a + b, 0);
            let totalScore = (totalRating / 32) * 100;
            
            document.getElementById('totalRating').innerText = totalRating;
            document.getElementById('totalScore').innerText = totalScore.toFixed(2) + '%';
        }
        
        function saveDraft() {
            document.getElementById('formStatus').value = 'draft';
            document.getElementById('appraisalForm').submit();
        }
        
        function submitForm() {
            // Check if all ratings are selected
            let allRated = ratings.every(r => r > 0);
            if (!allRated) {
                alert('Please rate all KPAs before submitting.');
                return;
            }
            
            let confirmMessage = 'Are you sure you want to submit this appraisal?';
            if (isInGrace) {
                confirmMessage = 'You are submitting during the grace period. Once submitted, it cannot be edited. Are you sure you want to proceed?';
            }
            
            if (confirm(confirmMessage)) {
                document.getElementById('formStatus').value = 'submitted';
                document.getElementById('appraisalForm').submit();
            }
        }
        
        recalculateTotal();
    </script>
</body>
</html>