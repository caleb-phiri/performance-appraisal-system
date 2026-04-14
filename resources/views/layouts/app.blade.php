@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appraisal - MOIC</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .rating-option {
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
            transition: all .2s ease;
        }
        .nd { background:#fee2e2;color:#dc2626 }
        .ns { background:#fef3c7;color:#d97706 }
        .s  { background:#dbeafe;color:#2563eb }
        .ex { background:#dcfce7;color:#16a34a }
        .rating-option.selected {
            border:2px solid currentColor;
            transform: scale(1.05);
            font-weight:bold;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 p-4">

@php
    // Debug: Check if appraisal exists
    if(!isset($appraisal)) {
        dd('$appraisal variable is not set');
    }
@endphp

<div class="max-w-7xl mx-auto mb-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 flex justify-between">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Performance Appraisal
                </h1>
                <p class="opacity-90">{{ $appraisal->appraisal_period ?? $appraisal->period ?? 'Appraisal Period' }}</p>
            </div>

            <a href="{{ route('appraisals.show', $appraisal->id) }}"
               class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
</div>

<main class="max-w-7xl mx-auto">
<form id="appraisalForm"
      method="POST"
      action="{{ route('appraisals.update', $appraisal->id) }}"
      class="space-y-6">

@csrf
@method('PUT')

<input type="hidden" name="status" id="formStatus" value="{{ $appraisal->status ?? 'draft' }}">

<!-- INFO -->
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold text-lg mb-4">Appraisal Information</h2>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-700">Employee</label>
            <input type="text" value="{{ $appraisal->employee->name ?? auth()->user()->name ?? 'N/A' }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Department</label>
            <input type="text" value="{{ $appraisal->employee->department ?? 'N/A' }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Supervisor</label>
            <input type="text" value="{{ $appraisal->supervisor->name ?? 'N/A' }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" value="{{ $appraisal->start_date ? (is_string($appraisal->start_date) ? $appraisal->start_date : $appraisal->start_date->format('Y-m-d')) : '' }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">End Date</label>
            <input type="date" value="{{ $appraisal->end_date ? (is_string($appraisal->end_date) ? $appraisal->end_date : $appraisal->end_date->format('Y-m-d')) : '' }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <input type="text" value="{{ ucfirst($appraisal->status ?? 'draft') }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>
    </div>
</div>

<!-- KPA TABLE -->
<div class="bg-white rounded-xl shadow p-6">
<h2 class="font-semibold text-lg mb-4">Key Performance Areas</h2>

<div class="overflow-x-auto">
<table class="w-full border-collapse">
<thead class="bg-blue-600 text-white">
<tr>
    <th class="p-3 text-left">Category</th>
    <th class="p-3 text-left">KPA</th>
    <th class="p-3 text-left">KPI</th>
    <th class="p-3 text-center">Weight %</th>
    <th class="p-3 text-center">Rating</th>
    <th class="p-3 text-left">Comments</th>
</tr>
</thead>

<tbody>
@php
    // Get KPAs - check different possible relationship names
    $kpas = collect([]);
    if(method_exists($appraisal, 'kpas')) {
        $kpas = $appraisal->kpas;
    } elseif(method_exists($appraisal, 'kpa')) {
        $kpas = $appraisal->kpa;
    } elseif(isset($appraisal->kpas)) {
        $kpas = $appraisal->kpas;
    } elseif(isset($appraisal->kpa)) {
        $kpas = $appraisal->kpa;
    }
@endphp

@forelse($kpas as $kpa)
<tr class="border-b hover:bg-gray-50">

<td class="p-3 font-semibold">{{ $kpa->category ?? 'N/A' }}</td>

<td class="p-3">
    <div class="font-medium">{{ $kpa->kpa ?? $kpa->name ?? 'N/A' }}</div>
    @if(!empty($kpa->result_indicators))
    <div class="text-xs text-gray-500">{{ $kpa->result_indicators }}</div>
    @endif
</td>

<td class="p-3 text-center font-bold">{{ $kpa->kpi ?? $kpa->target ?? 'N/A' }}</td>

<td class="p-3 text-center">
    <input type="number"
           name="kpas[{{ $kpa->id }}][weight]"
           value="{{ $kpa->weight ?? 0 }}"
           class="w-20 border rounded px-2 py-1 text-center"
           min="0" max="100" step="0.1" required
           oninput="recalculateTotals()">
</td>

<td class="p-3">
    <div class="grid grid-cols-4 gap-1 min-w-[200px]">
        @php
            $ratings = [
                1 => ['label' => 'ND', 'class' => 'nd'],
                2 => ['label' => 'NS', 'class' => 'ns'],
                3 => ['label' => 'S', 'class' => 's'],
                4 => ['label' => 'EX', 'class' => 'ex']
            ];
            $currentRating = $kpa->self_rating ?? $kpa->rating ?? 0;
        @endphp
        
        @foreach($ratings as $value => $rating)
        <div class="rating-option {{ $rating['class'] }} 
             {{ $currentRating == $value ? 'selected' : '' }}"
             onclick="setRating({{ $kpa->id }}, {{ $value }}, this)">
            {{ $rating['label'] }}
        </div>
        @endforeach
    </div>

    <input type="hidden"
           id="rating_{{ $kpa->id }}"
           name="kpas[{{ $kpa->id }}][self_rating]"
           value="{{ $currentRating }}">
</td>

<td class="p-3">
    <textarea name="kpas[{{ $kpa->id }}][comments]"
              rows="2"
              class="w-full border rounded px-2 py-1 text-sm"
              placeholder="Add comments...">{{ $kpa->comments ?? '' }}</textarea>
</td>

</tr>
@empty
<tr>
    <td colspan="6" class="p-6 text-center text-gray-500">
        No KPAs found for this appraisal. 
        @if(auth()->user()->user_type === 'admin')
        <a href="#" class="text-blue-600 hover:underline">Add KPAs</a>
        @endif
    </td>
</tr>
@endforelse

@if($kpas->count() > 0)
<tr class="bg-blue-50 font-bold">
<td colspan="3" class="p-3 text-right">TOTAL</td>
<td class="p-3 text-center">
    <span id="totalWeight">{{ $kpas->sum('weight') }}</span>%
</td>
<td class="p-3 text-center">
    <span id="totalRating">{{ $kpas->sum('self_rating') ?? $kpas->sum('rating') }}</span>
</td>
<td class="p-3 text-center">
    <span id="totalScore">{{ number_format($appraisal->total_self_score ?? $appraisal->total_score ?? 0, 2) }}%</span>
</td>
</tr>
@endif

</tbody>
</table>
</div>
</div>

<!-- COMMENTS -->
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-2">Development Needs</h3>
    <textarea name="development_needs" 
              class="w-full border rounded px-3 py-2" 
              rows="3"
              placeholder="What areas need development?">{{ $appraisal->development_needs ?? '' }}</textarea>

    <h3 class="font-semibold mt-4 mb-2">Additional Comments</h3>
    <textarea name="employee_comments" 
              class="w-full border rounded px-3 py-2" 
              rows="3"
              placeholder="Any additional comments or notes...">{{ $appraisal->employee_comments ?? $appraisal->comments ?? '' }}</textarea>
</div>

<!-- SUPERVISOR COMMENTS (if any) -->
@if(!empty($appraisal->supervisor_comments))
<div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-400">
    <h3 class="font-semibold mb-2 flex items-center">
        <i class="fas fa-user-tie text-yellow-600 mr-2"></i>
        Supervisor Comments
    </h3>
    <div class="bg-yellow-50 p-3 rounded">
        {{ $appraisal->supervisor_comments }}
    </div>
</div>
@endif

<!-- ACTIONS -->
<div class="bg-white rounded-xl shadow p-6 flex justify-between items-center">
    <div class="flex items-center text-sm text-gray-600">
        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
        <span>Last updated: {{ $appraisal->updated_at ? (is_string($appraisal->updated_at) ? $appraisal->updated_at : $appraisal->updated_at->format('M d, Y H:i')) : 'Not saved yet' }}</span>
    </div>

    <div class="flex gap-3">
        <button type="button" onclick="saveDraft()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition-colors">
            <i class="fas fa-save mr-1"></i> Save Draft
        </button>

        <button type="button" onclick="submitFinal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
            <i class="fas fa-paper-plane mr-1"></i> Submit
        </button>
    </div>
</div>

</form>
</main>

<script>
function setRating(id, value, el) {
    // Update hidden input
    document.getElementById('rating_' + id).value = value;

    // Remove selected class from all options in this row
    el.parentElement.querySelectorAll('.rating-option')
        .forEach(opt => opt.classList.remove('selected'));

    // Add selected class to clicked option
    el.classList.add('selected');
    
    recalculateTotals();
}

function recalculateTotals() {
    let totalWeight = 0;
    let totalRating = 0;
    let kpaCount = 0;

    // Calculate total weight
    document.querySelectorAll('input[name$="[weight]"]').forEach(w => {
        totalWeight += Number(w.value) || 0;
        kpaCount++;
    });

    // Calculate total rating
    document.querySelectorAll('input[name$="[self_rating]"]').forEach(r => {
        totalRating += Number(r.value) || 0;
    });

    // Update totals display
    document.getElementById('totalWeight').innerText = totalWeight.toFixed(1);
    document.getElementById('totalRating').innerText = totalRating;
    
    // Calculate percentage score (if max rating is 4 per KPA)
    if (kpaCount > 0) {
        let maxPossible = kpaCount * 4;
        let percentage = (totalRating / maxPossible) * 100;
        document.getElementById('totalScore').innerText = percentage.toFixed(2) + '%';
    }
}

function saveDraft() {
    document.getElementById('formStatus').value = 'draft';
    document.getElementById('appraisalForm').submit();
}

function submitFinal() {
    if (confirm('Are you sure you want to submit this appraisal? You will not be able to make further changes after submission.')) {
        document.getElementById('formStatus').value = 'submitted';
        document.getElementById('appraisalForm').submit();
    }
}

// Initialize totals on page load
document.addEventListener('DOMContentLoaded', function() {
    recalculateTotals();
});
</script>

</body>
</html>
@endsection