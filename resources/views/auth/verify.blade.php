<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="container mx-auto">
        <div class="flex rounded-xl shadow-2xl overflow-hidden max-w-4xl mx-auto">
            <!-- Left Column - Form -->
            <div class="w-1/2 p-12">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Verify Your Email</h2>
                    <p class="text-gray-600 mt-2">Please check your email to continue</p>
                </div>
                
                @if (session('resent'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle mr-2"></i>A fresh verification link has been sent to your email address.
                    </div>
                @endif
                
                <div class="text-gray-700 mb-6">
                    <p class="mb-4"><i class="fas fa-envelope-open mr-2 text-red-800"></i>Before proceeding, please check your email for a verification link.</p>
                    <p class="mb-6">If you did not receive the email, you can request another verification link:</p>
                </div>
                
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-red-800 to-red-900 hover:from-red-900 hover:to-red-950 text-white font-bold py-3 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        <i class="fas fa-paper-plane mr-2"></i>Resend Verification Email
                    </button>
                </form>
            </div>
            
            <!-- Right Column - Logo and Design -->
            <div class="w-1/2 bg-gradient-to-br from-red-800 to-red-900 p-12 flex flex-col items-center justify-center text-white">
                <div class="text-center">
                    <i class="fas fa-envelope-circle-check text-8xl mb-8"></i>
                    <h1 class="text-4xl font-bold mb-4">Check Your Email</h1>
                    <p class="text-xl opacity-90">We've sent you a verification link</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
