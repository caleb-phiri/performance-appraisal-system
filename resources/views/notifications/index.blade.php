{{-- resources/views/notifications/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notifications - MOIC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('layouts.navigation')
    
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#110484]">All Notifications</h1>
                <p class="text-sm text-gray-600 mt-1">View and manage your notifications</p>
            </div>
            
            <div class="flex space-x-3 mt-4 md:mt-0">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-check-double mr-2"></i> Mark All Read
                    </button>
                </form>
                
                <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to clear all notifications?')" 
                            class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i> Clear All
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-200 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/30' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                @if($notification->data['type'] ?? '' == 'leave_approved')
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                    </div>
                                @elseif($notification->data['type'] ?? '' == 'leave_rejected')
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-times-circle text-red-600 text-lg"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bell text-[#110484] text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div>
                                <p class="text-gray-800 font-medium">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $notification->created_at->format('F j, Y, g:i a') }}
                                </p>
                                
                                @if(isset($notification->data['leave_id']))
                                    <a href="{{ route('leave.show', $notification->data['leave_id']) }}" 
                                       class="text-sm text-[#110484] hover:text-[#e7581c] mt-2 inline-flex items-center font-medium">
                                        View Leave Details <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                @endif
                                
                                @if(isset($notification->data['remarks']) && $notification->data['remarks'])
                                    <div class="mt-2 p-2 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Remarks:</p>
                                        <p class="text-sm text-gray-700">{{ $notification->data['remarks'] }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex space-x-2">
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors" title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" title="Delete" onclick="return confirm('Delete this notification?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Notifications</h3>
                    <p class="text-gray-500">You don't have any notifications at the moment.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
    
    <script>
    // Auto-refresh unread count every 30 seconds
    setInterval(function() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            });
    }, 30000);
    </script>
</body>
</html>