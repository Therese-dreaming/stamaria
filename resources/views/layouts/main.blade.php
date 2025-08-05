<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sta. Marta Parish')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-lg z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo & Parish Name -->
                <div class="flex items-center space-x-3">
                    <i class="fas fa-church text-[#0d5c2f] text-2xl"></i>
                    <span class="font-bold text-[#0d5c2f] text-xl">Sta. Marta Parish</span>
                </div>

                <!-- Main Navigation Links (centered) -->
                <div class="hidden md:flex items-center space-x-8 mx-auto">
                    <a href="{{ route('landing') }}" class="font-bold text-[#0d5c2f] text-m nav-link {{ request()->routeIs('landing') ? 'active' : '' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('services') }}" class="font-bold text-[#0d5c2f] text-m nav-link {{ request()->routeIs('services*') ? 'active' : '' }}">
                        <i class="fas fa-hands-praying mr-2"></i>Services
                    </a>
                    <a href="#" class="nav-link font-bold text-[#0d5c2f] text-m">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="#" class="nav-link font-bold text-[#0d5c2f] text-m">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>
                    <a href="#" class="nav-link font-bold text-[#0d5c2f] text-m">
                        <i class="fas fa-envelope mr-2"></i>Contact
                    </a>
                </div>

                <!-- Right Side: User Actions & Donate -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative">
                            <button id="profileDropdownBtn" class="flex items-center text-gray-700 font-semibold focus:outline-none">
                                <i class="fas fa-user-circle text-2xl mr-2"></i>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>
                            <div id="profileDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 z-50 hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"><i class="fas fa-user mr-2"></i>Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
                                </form>
                            </div>
                        </div>
                        <!-- Donate Button -->
                        <a href="#" class="bg-[#b8860b] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f] transition duration-200 font-semibold flex items-center">
                            Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-[#0d5c2f] hover:text-[#b8860b] transition duration-200 font-semibold flex items-center">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#b8860b] transition duration-200 font-semibold flex items-center">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                        <a href="#" class="bg-[#b8860b] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f] transition duration-200 font-semibold flex items-center">
                            Book Now
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-[#0d5c2f] ml-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-3 mt-2">
                    <a href="{{ route('landing') }}" class="nav-link-mobile {{ request()->routeIs('landing') ? 'active' : '' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('services') }}" class="nav-link-mobile {{ request()->routeIs('services*') ? 'active' : '' }}">
                        <i class="fas fa-hands-praying mr-2"></i>Services
                    </a>
                    <a href="#" class="nav-link-mobile">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="#" class="nav-link-mobile">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>
                    <a href="#" class="nav-link-mobile">
                        <i class="fas fa-envelope mr-2"></i>Contact
                    </a>
                    <hr class="my-2">
                    @auth
                        <div class="flex flex-col space-y-1">
                            <a href="#" class="nav-link-mobile"><i class="fas fa-user mr-2"></i>Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left nav-link-mobile"><i class="fas fa-sign-out-alt mr-2"></i>Logout</button>
                            </form>
                        </div>
                        <a href="#" class="bg-[#b8860b] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f] transition duration-200 font-semibold flex items-center mt-2">
                            Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-mobile flex items-center"><i class="fas fa-sign-in-alt mr-1"></i>Login</a>
                        <a href="{{ route('register') }}" class="nav-link-mobile flex items-center"><i class="fas fa-user-plus mr-1"></i>Register</a>
                        <a href="#" class="bg-[#b8860b] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f] transition duration-200 font-semibold flex items-center mt-2">
                            Book Now
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2"></div>

    <!-- Main Content -->
    <main class="">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-[#0d5c2f] text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Sta. Marta Parish</h3>
                    <p class="text-gray-300">Serving the community through faith, hope, and love.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-gray-300">
                        @auth
                            <li><a href="{{ route('landing') }}" class="hover:text-white transition">Home</a></li>
                            <li><a href="{{ route('services') }}" class="hover:text-white transition">Services</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">Register</a></li>
                        @endauth
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Contact Info</h4>
                    <div class="text-gray-300 space-y-2">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Pateros, Metro Manila</p>
                        <p><i class="fas fa-phone mr-2"></i>(02) 8xxx-xxxx</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@stamarta.parish</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-600 mt-8 pt-6 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} Sta. Marta Parish. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
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
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>

    <style>
        .nav-link {
            @apply text-gray-700 hover:text-[#0d5c2f] transition duration-200 font-medium;
        }
        .nav-link.active {
            @apply text-[#0d5c2f] font-semibold underline underline-offset-4;
        }
        .nav-link-mobile {
            @apply text-gray-700 hover:text-[#0d5c2f] transition duration-200 font-medium py-2 px-4 rounded;
        }
        .nav-link-mobile.active {
            @apply text-[#0d5c2f] font-semibold bg-gray-100 underline underline-offset-4;
        }
        /* Dropdown menu animation */
        .group:hover .group-hover\:opacity-100 {
            opacity: 1 !important;
        }
        .group:hover .group-hover\:visible {
            visibility: visible !important;
        }
    </style>
</body>
</html>
