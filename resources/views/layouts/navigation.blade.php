<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Application System - MOIC</title>
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
    <!-- Simple Header -->
    <div class="bg-gradient-to-r from-[#110484] to-[#1a0c9e] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo Section -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <!-- MOIC Logo -->
                        <div class="bg-white rounded p-1">
                            <div class="h-6 w-6 bg-[#110484] flex items-center justify-center rounded">
                                <span class="text-white font-bold text-xs">M</span>
                            </div>
                        </div>
                        
                        <!-- Partnership Badge -->
                        <div class="relative">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                                <i class="fas fa-handshake text-white text-xs"></i>
                            </div>
                        </div>
                        
                        <!-- TKC Logo -->
                        <div class="bg-white rounded p-1">
                            <div class="h-6 w-6 bg-[#e7581c] flex items-center justify-center rounded">
                                <span class="text-white font-bold text-xs">T</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dashboard Title -->
                    <div class="flex items-center">
                        <div class="h-6 w-[1px] bg-white/30 mx-4"></div>
                        <div>
                            <h1 class="text-lg font-bold tracking-tight">MOIC Leave Application System</h1>
                            <p class="text-xs text-blue-200/90 mt-0.5">
                                @auth
                                    Welcome, {{ Auth::user()->name }}
                                @endauth
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Section -->
                <div class="flex items-center space-x-3">
                    <!-- User Profile -->
                    <div class="hidden md:flex flex-col items-end">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="font-medium">
                                @auth
                                    {{ Auth::user()->name }}
                                @endauth
                            </span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="text-xs text-blue-300 hover:text-white mt-0.5 transition duration-200 flex items-center">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                    </div>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="bg-white text-[#110484] px-3 py-1.5 rounded text-sm hover:bg-gray-100 transition duration-200 font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                    
                    <!-- Mobile Menu -->
                    <div class="md:hidden relative">
                        <button id="mobileUserMenu" class="bg-white/20 p-2 rounded-lg hover:bg-white/30 transition-colors">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-100">
                            @auth
                            <div class="px-4 py-3 border-b">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-[#110484] to-[#e7581c] rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-600">{{ Auth::user()->employee_number }}</p>
                                    </div>
                                </div>
                            </div>
                            @endauth
                            
                            <div class="py-2">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors text-sm">
                                    <i class="fas fa-home mr-2 text-[#110484]"></i> Dashboard
                                </a>
                                
                                <a href="{{ route('leave.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors text-sm">
                                    <i class="fas fa-calendar-alt mr-2 text-rose-600"></i> My Leaves
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors text-sm">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileUserMenu');
            const userDropdown = document.getElementById('userDropdown');
            
            if (mobileMenuBtn && userDropdown) {
                mobileMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>