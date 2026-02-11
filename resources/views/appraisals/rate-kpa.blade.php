<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate KPA - {{ $kpa->kpa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-[#110484] mb-6">Rate KPA</h1>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">{{ $kpa->category }}: {{ $kpa->kpa }}</h2>
                <p class="text-gray-600 mb-4">{{ $kpa->result_indicators }}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">KPI Scale</p>
                        <p class="font-medium">1 - {{ $kpa->kpi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Weight</p>
                        <p class="font-medium">{{ $kpa->weight }}%</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('kpa.rate', $kpa->id) }}" method="POST" id="ratingForm">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Rating *</label>
                    <div class="flex items-center space-x-2" id="ratingButtons">
                        @for($i = 1; $i <= $kpa->kpi; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" 
                                       class="hidden peer" required>
                                <div class="w-12 h-12 flex items-center justify-center border-2 rounded-lg text-gray-400 
                                         peer-checked:bg-gradient-to-r peer-checked:from-[#110484] peer-checked:to-[#1a0c9e] 
                                         peer-checked:text-white peer-checked:border-[#110484] 
                                         hover:bg-gray-100 transition-colors">
                                    <span class="text-lg font-bold">{{ $i }}</span>
                                </div>
                            </label>
                        @endfor
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                        <span>Needs Improvement</span>
                        <span>Excellent</span>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Comments</label>
                    <textarea name="comments" rows="4" 
                              class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#110484] focus:border-[#110484]"
                              placeholder="Provide specific feedback on this KPA..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <a href="{{ url()->previous() }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-lg hover:shadow">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('ratingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rating submitted successfully!');
                    window.location.href = "{{ url()->previous() }}";
                } else {
                    alert(data.error || 'Error submitting rating.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting rating.');
            });
        });
    </script>
</body>
</html>