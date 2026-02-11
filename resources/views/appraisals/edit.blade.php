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

<div class="max-w-7xl mx-auto mb-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 flex justify-between">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Performance Appraisal
                </h1>
                <p class="opacity-90">{{ $appraisal->appraisal_period }}</p>
            </div>

            <a href="{{ route('appraisals.show',$appraisal->id) }}"
               class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-blue-50">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
</div>

<main class="max-w-7xl mx-auto">
<form id="appraisalForm"
      method="POST"
      action="{{ route('appraisals.update',$appraisal->id) }}"
      class="space-y-6">

@csrf
@method('PUT')

<input type="hidden" name="status" id="formStatus" value="draft">

<!-- INFO -->
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="font-semibold text-lg mb-4">Appraisal Information</h2>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="text-sm">Start Date</label>
            <input type="date" value="{{ $appraisal->start_date->format('Y-m-d') }}"
                   class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
        </div>

        <div>
            <label class="text-sm">End Date</label>
            <input type="date" value="{{ $appraisal->end_date->format('Y-m-d') }}"
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
    <th class="p-3">Category</th>
    <th class="p-3">KPA</th>
    <th class="p-3">KPI</th>
    <th class="p-3">Weight %</th>
    <th class="p-3">Rating</th>
    <th class="p-3">Comments</th>
</tr>
</thead>

<tbody>
@foreach($appraisal->kpas as $kpa)
<tr class="border-b hover:bg-gray-50">

<td class="p-3 font-semibold">{{ $kpa->category }}</td>

<td class="p-3">
    <div class="font-medium">{{ $kpa->kpa }}</div>
    <div class="text-xs text-gray-500">{{ $kpa->result_indicators }}</div>
</td>

<td class="p-3 text-center font-bold">{{ $kpa->kpi }}</td>

<td class="p-3 text-center">
    <input type="number"
           name="kpas[{{ $kpa->id }}][weight]"
           value="{{ $kpa->weight }}"
           class="w-20 border rounded px-2 py-1 text-center"
           min="0" max="100" required
           oninput="recalculateTotals()">
</td>

<td class="p-3">
    <div class="grid grid-cols-4 gap-1">
        @for($i=1;$i<=4;$i++)
        <div class="rating-option {{ ['','nd','ns','s','ex'][$i] }}
             {{ $kpa->self_rating==$i?'selected':'' }}"
             onclick="setRating({{ $kpa->id }}, {{ $i }}, this)">
            {{ ['','ND','NS','S','EX'][$i] }}
        </div>
        @endfor
    </div>

    <input type="hidden"
           id="rating_{{ $kpa->id }}"
           name="kpas[{{ $kpa->id }}][self_rating]"
           value="{{ $kpa->self_rating }}">
</td>

<td class="p-3">
    <textarea name="kpas[{{ $kpa->id }}][comments]"
              rows="2"
              class="w-full border rounded px-2 py-1">{{ $kpa->comments }}</textarea>
</td>

</tr>
@endforeach

<tr class="bg-blue-50 font-bold">
<td colspan="3" class="p-3 text-right">TOTAL</td>
<td class="p-3 text-center"><span id="totalWeight">{{ $appraisal->kpas->sum('weight') }}</span>%</td>
<td class="p-3 text-center"><span id="totalRating">{{ $appraisal->kpas->sum('self_rating') }}</span></td>
<td class="p-3 text-center">{{ number_format($appraisal->total_self_score,2) }}%</td>
</tr>

</tbody>
</table>
</div>
</div>

<!-- COMMENTS -->
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-2">Development Needs</h3>
    <textarea name="development_needs" class="w-full border rounded px-3 py-2" rows="3">{{ $appraisal->development_needs }}</textarea>

    <h3 class="font-semibold mt-4 mb-2">Additional Comments</h3>
    <textarea name="employee_comments" class="w-full border rounded px-3 py-2" rows="3">{{ $appraisal->employee_comments }}</textarea>
</div>

<!-- ACTIONS -->
<div class="bg-white rounded-xl shadow p-6 flex justify-between">
    <span class="text-sm text-gray-600">
        <i class="fas fa-info-circle mr-1"></i> Draft mode
    </span>

    <div class="flex gap-3">
        <button type="button" onclick="saveDraft()" class="bg-yellow-500 text-white px-6 py-2 rounded">
            <i class="fas fa-save mr-1"></i> Save Draft
        </button>

        <button type="button" onclick="submitFinal()" class="bg-blue-600 text-white px-6 py-2 rounded">
            <i class="fas fa-paper-plane mr-1"></i> Submit
        </button>
    </div>
</div>

</form>
</main>

<script>
function setRating(id, value, el) {
    document.getElementById('rating_' + id).value = value;

    el.parentElement.querySelectorAll('.rating-option')
        .forEach(opt => opt.classList.remove('selected'));

    el.classList.add('selected');
    recalculateTotals();
}

function recalculateTotals() {
    let totalWeight = 0, totalRating = 0;

    document.querySelectorAll('input[name$="[weight]"]').forEach(w => {
        totalWeight += Number(w.value) || 0;
    });

    document.querySelectorAll('input[name$="[self_rating]"]').forEach(r => {
        totalRating += Number(r.value) || 0;
    });

    document.getElementById('totalWeight').innerText = totalWeight;
    document.getElementById('totalRating').innerText = totalRating;
}

function saveDraft() {
    document.getElementById('formStatus').value = 'draft';
    document.getElementById('appraisalForm').submit();
}

function submitFinal() {
    if (confirm('Submit appraisal? You will not be able to edit again.')) {
        document.getElementById('formStatus').value = 'submitted';
        document.getElementById('appraisalForm').submit();
    }
}
</script>

</body>
</html>
