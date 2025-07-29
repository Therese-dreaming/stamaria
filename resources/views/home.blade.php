<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div class="flex items-center py-4">
                        <i class="fas fa-graduation-cap text-red-800 text-2xl mr-3"></i>
                        <span class="font-semibold text-gray-800 text-lg">Platform</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-800 hover:text-red-900 transition duration-200">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-800 to-red-900 p-6">
                    <h1 class="text-3xl font-bold text-white">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </h1>
                    <p class="text-red-100 mt-2">Welcome to your account</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle mr-2"></i>You are logged in and your email has been verified!
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-user mr-2 text-red-800"></i>Account Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium text-gray-600">Name:</span>
                                    <span class="ml-2 text-gray-800">{{ Auth::user()->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-600">Email:</span>
                                    <span class="ml-2 text-gray-800">{{ Auth::user()->email }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-600">Email Status:</span>
                                    <span class="ml-2">
                                        @if(Auth::user()->email_verified_at)
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">
                                                <i class="fas fa-check-circle mr-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm">
                                                <i class="fas fa-times-circle mr-1"></i>Not Verified
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                @if(Auth::user()->email_verified_at)
                                    <div>
                                        <span class="font-medium text-gray-600">Verified at:</span>
                                        <span class="ml-2 text-gray-800">{{ Auth::user()->email_verified_at->format('M d, Y H:i:s') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-cog mr-2 text-red-800"></i>Quick Actions
                            </h3>
                            <div class="space-y-3">
                                <button class="w-full bg-red-100 hover:bg-red-200 text-red-800 font-semibold py-2 px-4 rounded transition duration-200">
                                    <i class="fas fa-edit mr-2"></i>Edit Profile
                                </button>
                                <button class="w-full bg-red-100 hover:bg-red-200 text-red-800 font-semibold py-2 px-4 rounded transition duration-200">
                                    <i class="fas fa-key mr-2"></i>Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
