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
                    
                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                   value="{{ old('first_name', auth()->user()->first_name ?? '') }}">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                   value="{{ old('last_name', auth()->user()->last_name ?? '') }}">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                   value="{{ old('email', auth()->user()->email ?? '') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                  placeholder="Enter your complete address">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date and Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Date *</label>
                            <input type="date" id="preferred_date" name="preferred_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                   min="{{ date('Y-m-d') }}">
                            @error('preferred_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                            <input type="time" id="preferred_time" name="preferred_time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent">
                            @error('preferred_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div>
                        <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
                        <textarea id="special_requests" name="special_requests" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent"
                                  placeholder="Any special requests or additional information...">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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