@extends('layouts.main')

@section('title', 'Book Service - ' . $service->name)

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-8 pt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-2">Book Service</h1>
            <p class="text-gray-600">Complete your booking for {{ $service->name }}</p>
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

                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-2"><i class="fas fa-info-circle mr-2"></i>Description</h3>
                    <p class="text-gray-700">{{ $service->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2"><i class="fas fa-money-bill-wave mr-2 text-[#0d5c2f]"></i>Price</h3>
                        <span class="text-[#0d5c2f] font-bold text-xl">
                            @if($service->price > 0)
                                {{ $service->formatted_price }}
                            @else
                                <span class="text-green-600">Free</span>
                            @endif
                        </span>
                    </div>
                    @if($service->formatted_duration)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-2"><i class="fas fa-clock mr-2 text-[#0d5c2f]"></i>Duration</h3>
                            <span class="text-gray-700 font-medium text-lg">{{ $service->formatted_duration }}</span>
                        </div>
                    @endif
                </div>

                @if($service->schedules && count($service->schedules) > 0)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3"><i class="fas fa-calendar-alt mr-2"></i>Schedule Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @foreach($service->schedules as $schedule)
                                <div class="mb-4 last:mb-0">
                                    @if(isset($schedule['preset']) && $schedule['preset'] !== 'custom')
                                        <div class="font-medium text-gray-800 mb-2">
                                            {{ ucfirst(str_replace('_', ' ', $schedule['preset'])) }}
                                        </div>
                                    @endif
                                    
                                    @if(isset($schedule['days']) && is_array($schedule['days']) && count($schedule['days']) > 0)
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            @foreach($schedule['days'] as $day)
                                                <span class="px-3 py-1 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-full text-sm font-medium">
                                                    {{ ucfirst($day) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    @if(isset($schedule['primary_time']))
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            Primary Time: {{ $schedule['primary_time'] }}
                                        </div>
                                    @endif
                                    
                                    @if(isset($schedule['additional_times']) && count($schedule['additional_times']) > 0)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-plus mr-1"></i>
                                            Additional Times: {{ implode(', ', $schedule['additional_times']) }}
                                        </div>
                                    @endif
                                    
                                    @if(isset($schedule['description']))
                                        <div class="text-sm text-gray-600 mt-2 italic">
                                            "{{ $schedule['description'] }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($service->requirements)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3"><i class="fas fa-list-check mr-2"></i>Requirements</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-700 whitespace-pre-line">{{ $service->requirements }}</div>
                        </div>
                    </div>
                @endif

                @if($service->additional_notes)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3"><i class="fas fa-sticky-note mr-2"></i>Additional Notes</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-gray-700">{{ $service->additional_notes }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Booking Form -->
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Booking Information</h3>
                
                <form action="{{ route('booking.step2') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    
                    <!-- Personal Information (Read-only) -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="font-semibold text-lg mb-4 text-gray-700">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->first_name }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->last_name }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->email }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->phone }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->address }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Age</label>
                                <div class="text-gray-900 font-medium">{{ auth()->user()->age }} years old</div>
                            </div>
                        </div>

                        <div class="mt-4 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            To update your information, please visit your 
                            <a href="{{ route('profile.edit') }}" class="text-[#0d5c2f] hover:underline">profile settings</a>.
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms_accepted" name="terms_accepted" required
                                   class="mt-1 h-4 w-4 rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                            <label for="terms_accepted" class="ml-3 text-sm text-gray-700">
                                I understand and agree to provide all the required documents and follow the scheduling guidelines. 
                                I also agree to the terms and conditions of the service booking.
                            </label>
                        </div>
                        @error('terms_accepted')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('services') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Services
                        </a>
                        <button type="submit" 
                                class="bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Continue to Review <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection