<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <div class="navbar-nav ms-auto">
                    @guest
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    @else
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                        <span class="nav-link">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="border: none; background: none;">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Toast Container -->
        <div id="toast-container" class="position-fixed" style="top: 20px; right: 20px; z-index: 1050;"></div>
        
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0d5c2f] text-white mt-auto">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">About Us</h3>
                    <p class="text-white/80">Serving the community with faith, love, and dedication.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/80 hover:text-white">Services</a></li>
                        <li><a href="#" class="text-white/80 hover:text-white">Schedule</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-white/80">
                        <li>B. Morcilla St.,</li>
                        <li>Pateros, Metro Manila</li>
                        <li>Phone: 0917-366-4359</li>
                        <li>Email: diocesansaintmartha@gmail.com</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/StaMartaYSanRoque" class="text-white/80 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/channel/UCclt6h0RgU0jv6amSIcBsrA" class="text-white/80 hover:text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-8 text-center text-white/60">
                <p>&copy; {{ date('Y') }} Santa Marta Parish. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toast Notification System
        function showToast(message, type = 'success', duration = 5000) {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            
            const colors = {
                success: 'alert-success',
                error: 'alert-danger',
                warning: 'alert-warning',
                info: 'alert-info'
            };
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `alert ${colors[type]} alert-dismissible fade show mb-2`;
            toast.style.maxWidth = '350px';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${icons[type]} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" onclick="removeToast('${toastId}')"></button>
                </div>
            `;
            
            container.appendChild(toast);
            
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
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 150);
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
    </script>
</body>
</html>
