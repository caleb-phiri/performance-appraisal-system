<header class="bg-white shadow fixed top-0 left-0 right-0 z-50">
    <div class="flex justify-between items-center px-6 py-4">
        <!-- Left: Logo and brand -->
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-chart-line text-2xl text-blue-600"></i>
            </div>
            <div class="ml-3">
                <h1 class="text-xl font-bold text-gray-900">Performance Appraisal System</h1>
                <p class="text-sm text-gray-600">Administrator Panel</p>
            </div>
        </div>
        
        <!-- Right: User menu -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button onclick="toggleNotifications()" 
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                    <i class="fas fa-bell"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        3
                    </span>
                </button>
            </div>
            
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 focus:outline-none">
                    <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->user_type) }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </button>
                
                <!-- Dropdown menu -->
                <div x-show="open" 
                     @click.away="open = false" 
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('profile.show') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i> My Profile
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleNotifications() {
    // Implement notification dropdown
    alert('Notifications dropdown would open here');
}
</script>