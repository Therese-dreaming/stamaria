<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
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

        <main class="py-4">
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

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
</body>
</html>
