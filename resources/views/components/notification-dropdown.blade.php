{{-- resources/views/components/notification-dropdown.blade.php --}}
@php
    $unreadCount = auth()->user()->unreadNotifications->count();
    $recentNotifications = auth()->user()->notifications()->take(5)->get();
@endphp

<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-[#110484] transition-colors focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        
        <!-- Unread Count Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold text-lg">Notifications</h3>
                @if($unreadCount > 0)
                    <span class="bg-white/20 text-white text-xs px-2 py-1 rounded-full">
                        {{ $unreadCount }} unread
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($recentNotifications as $notification)
                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'bg-white' : 'bg-blue-50/50' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->data['type'] ?? '' == 'leave_approved')
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                            @elseif($notification->data['type'] ?? '' == 'leave_rejected')
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times-circle text-red-600"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-bell text-[#110484]"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $notification->data['message'] ?? 'New notification' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                            
                            @if(isset($notification->data['leave_id']))
                                <a href="{{ route('leave.show', $notification->data['leave_id']) }}" 
                                   class="text-xs text-[#110484] hover:text-[#e7581c] mt-2 inline-flex items-center font-medium"
                                   @click="open = false">
                                    View Details <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            @endif
                        </div>
                        
                        <!-- Unread indicator -->
                        @if(!$notification->read_at)
                            <button onclick="event.stopPropagation(); markAsRead('{{ $notification->id }}')" 
                                    class="text-xs text-gray-400 hover:text-[#110484] transition-colors"
                                    title="Mark as read">
                                <i class="fas fa-circle text-xs"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No notifications yet</p>
                    <p class="text-xs text-gray-400 mt-1">We'll notify you when something arrives</p>
                </div>
            @endforelse
        </div>
        
        <!-- Footer -->
        @if($recentNotifications->count() > 0)
            <div class="p-3 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                <div class="flex justify-between items-center text-xs">
                    <button onclick="markAllAsRead()" 
                            class="text-[#110484] hover:text-[#e7581c] font-medium transition-colors flex items-center">
                        <i class="fas fa-check-double mr-1"></i> Mark all as read
                    </button>
                    <a href="{{ route('notifications.index') }}" 
                       class="text-[#110484] hover:text-[#e7581c] font-medium transition-colors flex items-center"
                       @click="open = false">
                        View all <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        window.location.reload();
    }).catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(() => {
        window.location.reload();
    }).catch(error => {
        console.error('Error:', error);
    });
}
</script>