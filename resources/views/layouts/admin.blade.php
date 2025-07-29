<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sta. Marta Parish')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <title>Admin Panel - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0d5c2f',
                            light: '#e6f0eb',
                            dark: '#094023'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }
        
        /* For Chrome, Edge, and Safari */
        *::-webkit-scrollbar {
            width: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: #f7fafc;
        }
        
        *::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 20px;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <!-- Top Navigation -->
    <nav class="bg-primary fixed w-full z-30 shadow-md">
        <div class="max-w-full mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <button class="text-white p-2 rounded-md lg:hidden focus:outline-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a class="flex items-center text-white font-bold text-xl ml-2 lg:ml-0" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-church mr-2"></i>
                        Sta Marta Parish
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-user mr-1"></i>
                            <span class="ml-1">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('landing') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-home mr-2"></i>View Site
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex h-screen pt-16">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white w-64 fixed inset-y-0 pt-16 shadow transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-20 border-r border-gray-200">
            <div class="h-full overflow-y-auto px-3 py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-light text-primary font-medium' : 'hover:bg-gray-100' }}">
                            <i class="fas fa-tachometer-alt w-5 h-5 text-center"></i>
                            <span class="ml-3 text-sm">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-primary-light text-primary font-medium' : 'hover:bg-gray-100' }}">
                            <i class="fas fa-users w-5 h-5 text-center"></i>
                            <span class="ml-3 text-sm">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.services') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.services') ? 'bg-primary-light text-primary font-medium' : 'hover:bg-gray-100' }}">
                            <i class="fas fa-concierge-bell w-5 h-5 text-center"></i>
                            <span class="ml-3 text-sm">Services</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-primary-light text-primary font-medium' : 'hover:bg-gray-100' }}">
                            <i class="fas fa-cog w-5 h-5 text-center"></i>
                            <span class="ml-3 text-sm">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 lg:ml-64 p-0">
            <div class="bg-white shadow-sm px-6 py-4 mb-6">
                @yield('header')
            </div>
            
            <div class="px-6 pb-8">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-check-circle text-green-500 mr-3"></i></div>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times text-green-500"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-exclamation-circle text-red-500 mr-3"></i></div>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times text-red-500"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Alpine.js for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth < 1024) { // lg breakpoint
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>