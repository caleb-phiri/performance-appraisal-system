<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Leave Application - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('layouts.navigation')
    
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-[#110484]">Edit Leave Application</h1>
            <p class="text-gray-600 mt-2">Update your leave request</p>
        </div>

        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                <h3 class="text-lg font-bold text-[#110484]">Edit Leave Application</h3>
            </div>
            
            <form action="{{ route('leave.update', $leave->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Employee Information (Read-only) -->
<div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
    <h4 class="font-bold text-[#110484] mb-3">
        <i class="fas fa-user-circle mr-2"></i> Employee Information
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
            <input type="text" value="{{ auth()->user()->name }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2" readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
            <input type="text" value="{{ auth()->user()->employee_number }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2" readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
            <input type="text" value="{{ auth()->user()->department ?? 'Not specified' }}" class="w-full bg-white border border-gray-300 rounded px-3 py-2" readonly>
        </div>
    </div>
</div>

                <!-- Leave Details -->
                <div class="space-y-6">
                    <!-- Leave Type -->
                    <div>
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Type of Leave
                        </label>
                        <select name="leave_type" id="leave_type" required
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent">
                            <option value="">Select leave type</option>
                            <option value="annual" {{ $leave->leave_type == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                            <option value="sick" {{ $leave->leave_type == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                            <option value="maternity" {{ $leave->leave_type == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                            <option value="paternity" {{ $leave->leave_type == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                            <option value="unpaid" {{ $leave->leave_type == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                            <option value="other" {{ $leave->leave_type == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('leave_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Start Date
                            </label>
                            <input type="date" name="start_date" id="start_date" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   value="{{ $leave->start_date->format('Y-m-d') }}">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> End Date
                            </label>
                            <input type="date" name="end_date" id="end_date" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   value="{{ $leave->end_date->format('Y-m-d') }}">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Days Count Display -->
                    <div id="daysCount" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg">
                        <p class="text-sm font-medium text-green-800">
                            <i class="fas fa-calendar-day mr-2"></i>
                            Total Leave Days: <span id="totalDays" class="font-bold">{{ $leave->total_days }}</span> days
                        </p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Reason for Leave
                        </label>
                        <textarea name="reason" id="reason" rows="4" required
                                  class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                  placeholder="Please provide details about why you need leave...">{{ old('reason', $leave->reason) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Address During Leave
                            </label>
                            <input type="text" name="contact_address" id="contact_address"
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   placeholder="Where can you be reached?"
                                   value="{{ old('contact_address', $leave->contact_address) }}">
                        </div>
                        
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Phone Number
                            </label>
                            <input type="tel" name="contact_phone" id="contact_phone"
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#110484] focus:border-transparent"
                                   placeholder="Phone number for emergencies"
                                   value="{{ old('contact_phone', $leave->contact_phone) }}">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('leave.show', $leave->id) }}" 
                       class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white px-6 py-2 rounded hover:shadow transition duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i> Update Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const daysCount = document.getElementById('daysCount');
            const totalDays = document.getElementById('totalDays');
            
            function calculateDays() {
                if (startDate.value && endDate.value) {
                    const start = new Date(startDate.value);
                    const end = new Date(endDate.value);
                    
                    // Calculate difference in days
                    const timeDiff = end.getTime() - start.getTime();
                    const dayDiff = Math.floor(timeDiff / (1000 * 3600 * 24)) + 1;
                    
                    totalDays.textContent = dayDiff;
                }
            }
            
            startDate.addEventListener('change', function() {
                if (this.value) {
                    endDate.min = this.value;
                    calculateDays();
                }
            });
            
            endDate.addEventListener('change', calculateDays);
        });
    </script>
</body>
</html>