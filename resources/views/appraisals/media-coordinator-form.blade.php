
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media & Customer Coordinator Performance Appraisal - MOIC</title>
    
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
        .csat-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
            "Customer Service Management",
            "Complaint Resolution",
            "Media & Public Relations",
            "Customer Feedback Analysis",
            "Social Media Management",
            "Customer Communication",
            "Stakeholder Engagement",
            "Reporting & Analytics"
        ];
    @endphp

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-700 to-cyan-700 rounded-xl shadow-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Media & Customer Coordinator Performance Appraisal</h1>
                        <p class="text-teal-100 mt-1">{{ $quarter }} {{ $year }}</p>
                    </div>
                    <a href="{{ route('appraisals.create') }}" class="bg-white text-teal-800 px-4 py-2 rounded-lg hover:bg-teal-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded">
            <div class="flex">
                <i class="fas fa-headset text-teal-600 mr-3 mt-1"></i>
                <div>
                    <p class="font-semibold">Customer Experience Focus</p>
                    <p class="text-sm text-gray-600">Your role is critical in maintaining customer satisfaction and managing the company's public image.</p>
                </div>
                <div class="ml-auto">
                    <span class="csat-badge"><i class="fas fa-chart-line mr-1"></i> CSAT Target: 85%</span>
                </div>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid md:grid-cols-4 gap-4">
                <div><label class="text-sm text-gray-500">Employee Name</label><p class="font-semibold">{{ Auth::user()->name }}</p></div>
                <div><label class="text-sm text-gray-500">Employee ID</label><p class="font-semibold">{{ Auth::user()->employee_number }}</p></div>
                <div><label class="text-sm text-gray-500">Department</label><p class="font-semibold">Customer Relations</p></div>
                <div><label class="text-sm text-gray-500">Review Period</label><p class="font-semibold">{{ $quarterMonths }} {{ $year }}</p></div>
            </div>
        </div>

        <form id="appraisalForm" method="POST" action="{{ route('appraisals.store') }}">
            @csrf
            <input type="hidden" name="status" id="formStatus" value="draft">
            <input type="hidden" name="period" value="{{ $quarter }}">
            <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ date('Y-m-d', strtotime('+3 months')) }}">
            <input type="hidden" name="job_title" value="Media and Customer Coordinator">

            <!-- KPA Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Key Performance Areas</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-teal-700 text-white">
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
                                    <input type="hidden" name="kpas[{{ $index }}][category]" value="CUSTOMER RELATIONS">
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
                            
                            <tr class="bg-teal-50 font-bold">
                                <td colspan="2" class="p-3 text-right">TOTAL</td>
                                <td class="p-3 text-center"><span id="totalWeight">100</span>%</td>
                                <td class="p-3 text-center"><span id="totalRating">0</span></td>
                                <td class="p-3 text-center"><span id="totalScore">0%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customer Satisfaction Metrics -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Customer Satisfaction Metrics</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium">CSAT Score (%)</label>
                            <input type="number" name="csat_score" class="w-full border rounded-lg p-2" placeholder="Enter CSAT percentage">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Complaints Resolved (%)</label>
                            <input type="number" name="complaints_resolved" class="w-full border rounded-lg p-2" placeholder="Enter resolution rate">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Average Response Time (hours)</label>
                            <input type="number" name="response_time" class="w-full border rounded-lg p-2" placeholder="Enter average response time">
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Media & PR Metrics</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium">Media Mentions</label>
                            <input type="number" name="media_mentions" class="w-full border rounded-lg p-2" placeholder="Number of media mentions">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Social Media Engagement (%)</label>
                            <input type="number" name="social_engagement" class="w-full border rounded-lg p-2" placeholder="Engagement rate percentage">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Press Releases Issued</label>
                            <input type="number" name="press_releases" class="w-full border rounded-lg p-2" placeholder="Number of press releases">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Development & Comments -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Development Needs</h3>
                    <textarea name="development_needs" rows="4" class="w-full border rounded-lg p-3" placeholder="List areas for improvement..."></textarea>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-lg mb-3">Customer Feedback Summary</h3>
                    <textarea name="employee_comments" rows="4" class="w-full border rounded-lg p-3" placeholder="Summarize key customer feedback and actions taken..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-md p-6 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-2 text-teal-600"></i>
                    Rate each KPA based on customer satisfaction, communication effectiveness, and media management
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="saveDraft()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Save Draft
                    </button>
                    <button type="button" onclick="submitForm()" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
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
