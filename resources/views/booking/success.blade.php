@extends('layouts.main')

@section('title', 'Booking Confirmation - ' . $service->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-2">Booking Confirmed!</h1>
            <p class="text-gray-600">Your booking for {{ $service->name }} has been submitted successfully.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Service Information -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-[#0d5c2f]/10 rounded-xl flex items-center justify-center mr-4">
                        <i class="{{ $service->icon }} text-[#0d5c2f] text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h2>
                        <p class="text-gray-500">Service ID: {{ $service->id }}</p>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="mb-6">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="w-16 h-1 bg-[#0d5c2f]"></div>
                            <div class="w-8 h-8 bg-[#0d5c2f] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-2 text-sm text-gray-600">
                        <span class="mr-8">Basic Info</span>
                        <span class="mr-8">Additional Info</span>
                        <span>Review</span>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Booking Summary</h3>
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h4 class="font-semibold text-lg mb-3 text-[#0d5c2f]">
                        <i class="fas fa-user mr-2"></i>Personal Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Name:</span>
                            <p class="text-gray-900">{{ $bookingData['first_name'] }} {{ $bookingData['last_name'] }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Email:</span>
                            <p class="text-gray-900">{{ $bookingData['email'] }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Phone:</span>
                            <p class="text-gray-900">{{ $bookingData['phone'] }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Address:</span>
                            <p class="text-gray-900">{{ $bookingData['address'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="mb-6">
                    <h4 class="font-semibold text-lg mb-3 text-[#0d5c2f]">
                        <i class="fas fa-calendar-alt mr-2"></i>Service Details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Booking Date:</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($bookingData['booking_date'])->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Booking Time:</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($bookingData['booking_time'])->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Requirements -->
                @if(isset($bookingData['booking']) && $bookingData['booking']->requirements->count() > 0)
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-3 text-[#0d5c2f]">
                            <i class="fas fa-file-upload mr-2"></i>Uploaded Documents
                        </h4>
                        <div class="space-y-3">
                            @foreach($bookingData['booking']->requirements as $requirement)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file mr-3 text-[#0d5c2f]"></i>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $requirement->requirement_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $requirement->original_filename }} ({{ $requirement->formatted_file_size }})</p>
                                        </div>
                                    </div>
                                    <a href="{{ $requirement->file_url }}" target="_blank" 
                                       class="text-[#0d5c2f] hover:text-[#0d5c2f]/80 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif



                <!-- Special Requests -->
                @if(isset($bookingData['special_requests']) && !empty($bookingData['special_requests']))
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-3 text-[#0d5c2f]">
                            <i class="fas fa-comment mr-2"></i>Special Requests
                        </h4>
                        <p class="text-gray-900">{{ $bookingData['special_requests'] }}</p>
                    </div>
                @endif

                <!-- What's Next -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-lg mb-2 text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>What's Next?
                    </h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• You will receive a confirmation email shortly</li>
                        <li>• Our team will review your booking and contact you within 24 hours</li>
                        <li>• Please prepare all required documents as specified in the service requirements</li>
                        <li>• If you have any questions, please contact us at the parish office</li>
                    </ul>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <a href="{{ route('services') }}" 
                       class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Services
                    </a>
                    <a href="{{ route('home') }}" 
                       class="w-full sm:w-auto px-6 py-2 bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white rounded-lg font-medium transition-colors text-center">
                        <i class="fas fa-home mr-2"></i>Go to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 