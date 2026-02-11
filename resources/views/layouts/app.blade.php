<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MOIC Performance System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind CSS (if not using Vite for Tailwind) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --moic-navy: #110484;
            --moic-accent: #e7581c;
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #110484, #1a0c9e);
        }
        
        .active-nav {
            color: var(--moic-navy);
            font-weight: 600;
            position: relative;
        }
        
        .active-nav::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--moic-navy);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <!-- Logo Section -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="bg-white p-2 border rounded-lg shadow-sm">
                                <img class="h-8 w-auto" src="{{ asset('images/moic.png') }}" alt="MOIC Logo" 
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2218%22 fill=%22%23110484%22>MOIC</text></svg>';">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[#110484]">MOIC</span>
                                <span class="text-xs text-gray-500">Performance System</span>
                            </div>
                        </div>

                        <!-- Partnership Separator -->
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                                <i class="fas fa-handshake text-white text-xs"></i>
                            </div>
                            <span class="text-xs text-gray-500 mt-1">Partner</span>
                        </div>

                        <!-- TKC Logo -->
                        <div class="flex items-center space-x-2">
                            <div class="bg-white p-2 border rounded-lg shadow-sm">
                                <img class="h-8 w-auto" src="{{ asset('images/TKC.png') }}" alt="TKC Logo"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text x=%2250%22 y=%2250%22 text-anchor=%22middle%22 dy=%22.3em%22 font-family=%22Arial%22 font-size=%2216%22 fill=%22%23e7581c%22>TKC</text></svg>';">
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[#e7581c]">TKC</span>
                                <span class="text-xs text-gray-500">Partner</span>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-6">
                        <!-- Dynamic Navigation based on User Role -->
                        @php
                            $currentRoute = Route::currentRouteName();
                            $isAdmin = auth()->user()->user_type === 'admin' || auth()->check() && in_array(auth()->user()->user_type, ['admin', 'super_admin']);
                        @endphp
                        
                        @if($isAdmin)
                            <!-- Admin Navigation -->
                            <a href="{{ route('admin.dashboard') }}" 
                               class="{{ $currentRoute === 'admin.dashboard' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-home mr-1"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" 
                               class="{{ str_starts_with($currentRoute, 'admin.users') ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-users mr-1"></i> Users
                            </a>
                            <a href="{{ route('admin.supervisor-assignments') }}" 
                               class="{{ $currentRoute === 'admin.supervisor-assignments' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-user-tie mr-1"></i> Supervisor Assignments
                            </a>
                            <a href="{{ route('admin.appraisals.index') }}" 
                               class="{{ str_starts_with($currentRoute, 'admin.appraisals') ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-file-alt mr-1"></i> Appraisals
                            </a>
                            <a href="{{ route('admin.reports') }}" 
                               class="{{ $currentRoute === 'admin.reports' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-chart-bar mr-1"></i> Reports
                            </a>
                            <a href="{{ route('admin.settings') }}" 
                               class="{{ $currentRoute === 'admin.settings' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-cog mr-1"></i> Settings
                            </a>
                        @elseif(auth()->user()->user_type === 'supervisor')
                            <!-- Supervisor Navigation -->
                            <a href="{{ route('supervisor.dashboard') }}" 
                               class="{{ $currentRoute === 'supervisor.dashboard' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                            <a href="{{ route('supervisor.my-team') }}" 
                               class="{{ $currentRoute === 'supervisor.my-team' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-users mr-1"></i> My Team
                            </a>
                            <a href="{{ route('supervisor.appraisals') }}" 
                               class="{{ $currentRoute === 'supervisor.appraisals' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-file-alt mr-1"></i> Appraisals
                            </a>
                            <a href="{{ route('supervisor.ratings') }}" 
                               class="{{ $currentRoute === 'supervisor.ratings' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-star mr-1"></i> Ratings
                            </a>
                        @else
                            <!-- Employee Navigation -->
                            <a href="{{ route('dashboard') }}" 
                               class="{{ $currentRoute === 'dashboard' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-home mr-1"></i> Dashboard
                            </a>
                            <a href="{{ route('appraisals.index') }}" 
                               class="{{ str_starts_with($currentRoute, 'appraisals') && !str_starts_with($currentRoute, 'admin.appraisals') ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-file-alt mr-1"></i> My Appraisals
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="{{ $currentRoute === 'profile.edit' ? 'active-nav text-[#110484] font-semibold' : 'text-gray-700 hover:text-[#110484]' }}">
                                <i class="fas fa-user mr-1"></i> Profile
                            </a>
                        @endif
                    </div>

                    <!-- User Info & Dropdown -->
                    <div class="flex items-center space-x-4 relative" x-data="{ open: false }">
                        <div class="text-right hidden md:block">
                            <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->user_type }}</p>
                        </div>
                        <button @click="open = !open" class="w-8 h-8 bg-[#110484] text-white rounded-full flex items-center justify-center hover:bg-[#0a0369] transition-colors">
                            <i class="fas fa-user"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-10 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <a href="{{ route('profile.password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-lock mr-2"></i> Change Password
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} MOIC Performance System. All rights reserved.
                    </div>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <a href="#" class="text-sm text-gray-500 hover:text-[#110484]">Privacy Policy</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-[#110484]">Terms of Service</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-[#110484]">Help Center</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Additional Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>