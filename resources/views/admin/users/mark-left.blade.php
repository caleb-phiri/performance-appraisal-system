<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark User as Left Company - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Simple Navigation -->
    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-blue-600">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-red-600 px-6 py-4">
                    <h1 class="text-xl font-bold text-white">Mark User as Left Company</h1>
                    <p class="text-red-100 text-sm">This will deactivate the user account</p>
                </div>
                
                <!-- User Info -->
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                            @if($user->profile_photo)
                                <img class="w-16 h-16 rounded-full" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                            @else
                                <span class="text-gray-600 font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $user->employee_number }}</p>
                            <p class="text-sm text-gray-600">{{ $user->job_title }}</p>
                            <p class="text-sm text-gray-600">{{ $user->department }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Form -->
<div class="bg-white rounded-lg shadow-md p-6">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

   <form action="{{ route('admin.users.mark-as-left', ['user' => $user->employee_number]) }}" method="POST">
        @csrf
        @method('PUT') <!-- ADD THIS LINE -->
        
        <!-- Reason for Leaving -->
        <div class="mb-6">
            <label for="left_reason" class="block text-gray-700 text-sm font-bold mb-2">
                Reason for Leaving <span class="text-red-500">*</span>
            </label>
            <select name="left_reason" id="left_reason" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <option value="">-- Select Reason --</option>
                <option value="resignation">Resignation</option>
                <option value="termination">Termination</option>
                <option value="retirement">Retirement</option>
                <option value="end_of_contract">End of Contract</option>
                <option value="transfer">Transfer</option>
                <option value="other">Other</option>
            </select>
            @error('left_reason')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Rest of your form remains the same... -->
                    <!-- Additional Notes -->
                    <div class="mb-6">
                        <label for="left_notes" class="block text-gray-700 text-sm font-bold mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea name="left_notes" id="left_notes" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Enter any additional information..."></textarea>
                        <p class="text-gray-500 text-xs mt-1">Maximum 500 characters</p>
                        @error('left_notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warning -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Warning</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This action will:</p>
                                    <ul class="list-disc pl-5 mt-1">
                                        <li>Mark the user as inactive</li>
                                        <li>Prevent them from logging in</li>
                                        <li>Move them to the "Inactive Users" list</li>
                                        <li>The user can be reactivated later if needed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between">
                        <a href="{{ route('admin.users.show', $user->employee_number) }}"
                           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to mark {{ $user->name }} as left company?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Mark as Left Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Simple confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to mark this user as left company?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>