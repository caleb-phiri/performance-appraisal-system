
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior TCE Performance Appraisal - MOIC</title>
    
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
        }
        .tech-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
    </style>
</head>
<body class="bg-gray-50">
    @php
        $quarter = $quarter ?? 'Q1';
        $year = $year ?? date('Y');
        $quarterMonths = $quarterMonths ?? 'January - March';
        $dueDate = $dueDate ?? date('M d', strtotime("April 20, $year"));
        
        $weights = [20, 20, 15, 15, 10, 10, 5, 5];
        $kpaTitles = [
            "Technical Team Leadership",
            "Equipment Maintenance Oversight",
            "Emergency Response Management",
            "Technical Training & Development",
            "Preventive Maintenance Planning",
            "Spare Parts Management",
            "Technical Reporting & Documentation",
            "Safety Compliance & Standards"
        ];
    @endphp

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-800 to-red-800 rounded-xl shadow-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Senior TCE Performance Appraisal</h1>
                        <p class="text-orange-100 mt-1">{{ $quarter }} {{ $year }}</p>
                    </div>
                    <a href="{{ route('appraisals.create') }}" class="bg-white text-orange-800 px-4 py-2 rounded-lg hover:bg-orange-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold">Technical Leadership Role</p>
                    <p class="text-sm text-gray-600">As Senior TCE, you are responsible for leading the technical team, ensuring equipment reliability, and managing emergency responses.</p>
                </div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid md:grid-cols-4 gap-4">
                <div><label class="text-sm text-gray-500">Employee Name</label><p class="font-semibold">{{ Auth::user()->name }}</p></div>
                <div><label class="text-sm text-gray-500">Employee ID</label><p class="font-semibold">{{ Auth::user()->employee_number }}</p></div>
                <div><label class="text-sm text-gray-500">Department</label><p class="font-semibold">Technical Services</p></div>
                <div><label class="text-sm text-gray-500">Review Period</label><p class="font-semibold">{{ $quarterMonths }} {{ $year }}</p></div>
            </div>
        </div>

        <form id="appraisalForm" method="POST" action="{{ route('appraisals.store') }}">
            @csrf
            <input type="hidden" name="status" id="formStatus" value="draft">
            <input type="hidden" name="period" value="{{ $quarter }}">
            <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ date('Y-m-d', strtotime('+3 months')) }}">
            <input type="hidden" name="job_title" value="Senior TCE">

            <!-- KPA Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Key Performance Areas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-orange-800 text-white">
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
                                    @if(in_array($index, [0,2,7]))
                                        <span class="tech-badge"><i class="fas fa-tools mr-1"></i>Critical</span>
                                    @endif
                                    <input type="hidden" name="kpas[{{ $index }}][category]" value="TECHNICAL MANAGEMENT">
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
                            
                            <tr class="bg-orange-50 font-bold">
                                <td colspan="2" class="p-3 text-right">TOTAL</td>
                                <td class="p-3 text-center"><span id="totalWeight">100</span>%</td>
                                <td class="p-3 text-center"><span id="totalRating">0</span></td>
                                <td class="p-3 text-center"><span id="totalScore">0%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Technical Equipment Checklist -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="font-bold text-lg mb-3">Equipment Reliability Checklist</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div><input type="checkbox" class="mr-2"> Lane Controllers - 100% uptime</div>
                    <div><input type="checkbox" class="mr-2"> Ticket Printers - No recurring faults</div>
                    <div><input type="checkbox" class="mr-2"> Barrier Gates - Operational</div>
                    <div><input type="checkbox" class="mr-2"> Vehicle Detectors - Calibrated</div>
                    <div><input type="checkbox" class="mr-2"> UPS Systems - Tested monthly</div>
                    <div><input type="checkbox" class="mr-2"> CCTV Systems - Fully functional</div>
                </div>
            </div>

            <!-- Development & Comments -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Development Needs</h3>
                    <textarea name="development_needs" rows="4" class="w-full border rounded-lg p-3" placeholder="List technical training needs and development areas..."></textarea>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Technical Observations & Comments</h3>
                    <textarea name="employee_comments" rows="4" class="w-full border rounded-lg p-3" placeholder="Equipment issues, improvements, recommendations..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-md p-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-2 text-orange-600"></i>
                    Rate each KPA based on technical expertise, leadership, and equipment reliability
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="saveDraft()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Save Draft
                    </button>
                    <button type="button" onclick="submitForm()" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let ratings = Array(8).fill(0);
        
        function setRating(index, value, element) {
            ratings[index] = value;
            document.getElementById('rating_' + index).value = value;
            element.parentElement.querySelectorAll('.rating-option').forEach(opt => opt.classList.remove('selected'));
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
            if (confirm('Are you sure you want to submit this appraisal?')) {
                document.getElementById('formStatus').value = 'submitted';
                document.getElementById('appraisalForm').submit();
            }
        }
        
        recalculateTotal();
    </script>
</body>
</html>
