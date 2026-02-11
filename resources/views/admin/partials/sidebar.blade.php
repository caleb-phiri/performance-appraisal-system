<aside class="fixed left-0 top-16 h-screen w-64 bg-white shadow-lg border-r border-gray-200 z-40 overflow-y-auto">
    <nav class="pt-6 pb-4">
        <div class="px-6 mb-6">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Navigation</h3>
        </div>
        
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                Dashboard
            </a>
            
            <!-- User Management -->
            <div class="px-6 mt-6 mb-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">User Management</h3>
            </div>
            
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.users.index') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-users mr-3 w-5"></i>
                All Users
            </a>
            
            <a href="{{ route('admin.users.inactive') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.users.inactive') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-slash mr-3 w-5"></i>
                Inactive Users
            </a>
            
            <!-- Appraisal Management -->
            <div class="px-6 mt-6 mb-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Appraisal Management</h3>
            </div>
            
            <a href="{{ route('admin.appraisals.index') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.appraisals.index') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-file-alt mr-3 w-5"></i>
                All Appraisals
            </a>
            
            <a href="{{ route('admin.appraisals.report') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.appraisals.report') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-chart-bar mr-3 w-5"></i>
                Reports
            </a>
            
            <!-- Team Management -->
            @if(auth()->user()->user_type === 'supervisor' || auth()->user()->user_type === 'admin')
            <div class="px-6 mt-6 mb-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Team Management</h3>
            </div>
            
            <a href="{{ route('admin.team-members') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('admin.team-members') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-user-friends mr-3 w-5"></i>
                My Team
            </a>
            @endif
            
            <!-- Settings -->
            <div class="px-6 mt-6 mb-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Settings</h3>
            </div>
            
            <a href="{{ route('profile.show') }}" 
               class="flex items-center px-6 py-3 text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="fas fa-cog mr-3 w-5"></i>
                Profile Settings
            </a>
        </div>
        
        <!-- Quick Stats -->
        <div class="mt-8 px-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Quick Stats</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Users</span>
                        <span class="font-medium">{{ \App\Models\User::count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Active Appraisals</span>
                        <span class="font-medium">{{ \App\Models\Appraisal::whereIn('status', ['submitted', 'approved'])->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Pending Reviews</span>
                        <span class="font-medium">{{ \App\Models\Appraisal::where('status', 'submitted')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</aside>