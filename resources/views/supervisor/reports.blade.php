@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Performance Reports</h2>
                
                <!-- Quarterly Performance Summary -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Quarterly Performance Averages</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($quarterlyAverages as $quarter => $data)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">{{ $quarter }}</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $data['average'] }}%</div>
                            <div class="text-xs text-gray-500">{{ $data['count'] }} appraisals</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Performance Data Table -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Detailed Performance Data</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance Level</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($performanceData as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $data['employee'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $data['employee_number'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $data['period'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-semibold 
                                                @if($data['score'] >= 90) text-green-600
                                                @elseif($data['score'] >= 70) text-blue-600
                                                @elseif($data['score'] >= 50) text-yellow-600
                                                @else text-red-600 @endif">
                                                {{ $data['score'] }}%
                                            </span>
                                            <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full
                                                    @if($data['score'] >= 90) bg-green-500
                                                    @elseif($data['score'] >= 70) bg-blue-500
                                                    @elseif($data['score'] >= 50) bg-yellow-500
                                                    @else bg-red-500 @endif"
                                                     style="width: {{ min($data['score'], 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($data['score'] >= 90)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Excellent
                                        </span>
                                        @elseif($data['score'] >= 70)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Good
                                        </span>
                                        @elseif($data['score'] >= 50)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Fair
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Needs Improvement
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        No performance data available yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Export Options -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Export Reports</h3>
                    <div class="flex space-x-4">
                        <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            <i class="fas fa-file-pdf mr-2"></i> Export as PDF
                        </button>
                        <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            <i class="fas fa-file-excel mr-2"></i> Export as Excel
                        </button>
                        <button onclick="printReport()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-print mr-2"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    alert('PDF export functionality would be implemented here.');
}

function exportToExcel() {
    alert('Excel export functionality would be implemented here.');
}

function printReport() {
    window.print();
}
</script>
@endsection