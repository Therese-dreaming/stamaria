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
                    <div class="relative">
                        <button id="profileDropdownBtn" class="flex items-center text-white hover:text-gray-200 focus:outline-none">
                            <i class="fas fa-user mr-1"></i>
                            <span class="ml-1">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
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
                        <a href="{{ route('admin.priests.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.priests.*') ? 'bg-primary-light text-primary font-medium' : 'hover:bg-gray-100' }}">
                            <i class="fas fa-user-tie w-5 h-5 text-center"></i>
                            <span class="ml-3 text-sm">Priests</span>
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
            
            <!-- Toast Container -->
            <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2"></div>
            
            <div class="px-6 pb-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Toast Notification System
        function showToast(message, type = 'success', duration = 5000) {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `${colors[type]} px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out opacity-0 translate-x-full max-w-sm`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${icons[type]} mr-3 text-lg"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                    <button onclick="removeToast('${toastId}')" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-x-full');
                toast.classList.add('opacity-100', 'translate-x-0');
            }, 100);
            
            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    removeToast(toastId);
                }, duration);
            }
        }
        
        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('opacity-100', 'translate-x-0');
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Show session messages as toasts
        @if(session('success'))
            showToast('{{ addslashes(session('success')) }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ addslashes(session('error')) }}', 'error');
        @endif
        
        @if(session('warning'))
            showToast('{{ addslashes(session('warning')) }}', 'warning');
        @endif
        
        @if(session('info'))
            showToast('{{ addslashes(session('info')) }}', 'info');
        @endif
        
        // Profile dropdown functionality
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        
        if (profileDropdownBtn && profileDropdown) {
            profileDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileDropdownBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }
        
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