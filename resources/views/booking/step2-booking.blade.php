@extends('layouts.main')

@section('title', 'Select Date & Time - ' . $service->name)

@section('content')
<div class="relative min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-8 pt-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#0d5c2f] mb-2">Select Date & Time</h1>
            <p class="text-gray-600">Choose your preferred date and time for {{ $service->name }}</p>
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2"><i class="fas fa-calendar-check mr-2 text-[#0d5c2f]"></i>Available Dates</h3>
                        <span class="text-gray-700 font-medium text-lg">{{ count($availableDates) }} dates</span>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="p-6">
                <form action="{{ route('booking.step3') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    
                    <!-- Calendar Section -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Select Date</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <!-- Calendar Info -->
                            <div class="mb-4 text-sm text-gray-600 text-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Available dates are highlighted. Past dates are disabled.
                            </div>
                            <div id="calendar" class="grid grid-cols-7 gap-1">
                                <!-- Calendar header -->
                                <div class="text-center font-semibold text-gray-600 py-2">Sun</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Mon</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Tue</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Wed</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Thu</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Fri</div>
                                <div class="text-center font-semibold text-gray-600 py-2">Sat</div>
                                
                                <!-- Calendar days will be populated by JavaScript -->
                            </div>
                            
                            <!-- Calendar Legend -->
                            <div class="mt-4 flex justify-center space-x-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-[#0d5c2f] rounded mr-1"></div>
                                    <span class="text-gray-600">Today</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-100 border border-gray-300 rounded mr-1"></div>
                                    <span class="text-gray-600">Available</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-100 rounded mr-1"></div>
                                    <span class="text-gray-400">Past</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                    <div class="mb-8" id="time-slots-section" style="display: none;">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Select Time</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div id="time-slots" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                <!-- Time slots will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Selected Date and Time Display -->
                    <div class="mb-6" id="selected-info" style="display: none;">
                        <div class="bg-[#0d5c2f]/10 rounded-lg p-4">
                            <h4 class="font-semibold text-lg text-[#0d5c2f] mb-2">Selected Appointment</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-medium text-gray-900" id="selected-date"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Time:</span>
                                    <span class="font-medium text-gray-900" id="selected-time"></span>
                                </div>
                            </div>
                            <div class="slot-info text-sm text-gray-600 mt-2">
                                <i class="fas fa-users mr-1"></i>
                                <span class="font-semibold text-[#0d5c2f]">0</span> of {{ $service->slots }} slots remaining
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" name="booking_date" id="booking_date">
                    <input type="hidden" name="booking_time" id="booking_time">

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

                    <!-- Error Messages -->
                    @error('booking_date')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    @error('booking_time')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Submit Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('booking.step1', $service) }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        <button type="submit" id="continue-btn" disabled
                                class="bg-gray-300 text-gray-500 px-6 py-2 rounded-lg font-medium transition-colors disabled:cursor-not-allowed">
                            Continue to Requirements <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const availableDates = @json($availableDates);
    const serviceId = {{ $service->id }};
    const serverDate = '{{ now()->format("Y-m-d") }}'; // Get server's current date
    let selectedDate = null;
    let selectedTime = null;
    let selectedSlotInfo = null;

    // Initialize calendar
    function initializeCalendar() {
        const calendar = document.getElementById('calendar');
        
        // Use server date to avoid timezone issues
        const serverToday = new Date(serverDate + 'T00:00:00');
        const today = new Date();
        const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        // Add month header
        const monthHeader = document.createElement('div');
        monthHeader.className = 'col-span-7 text-center font-bold text-xl text-[#0d5c2f] py-4';
        monthHeader.textContent = currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        calendar.appendChild(monthHeader);

        // Add empty cells for days before the first day of the month
        const firstDayOfWeek = currentMonth.getDay();
        for (let i = 0; i < firstDayOfWeek; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'text-center py-2 text-gray-400';
            calendar.appendChild(emptyCell);
        }

        // Add days of the month
        for (let day = 1; day <= lastDay.getDate(); day++) {
            // Create date using the current year and month, but the specific day
            const date = new Date(today.getFullYear(), today.getMonth(), day);
            const dateString = date.toISOString().split('T')[0];
            
            const dayOfWeek = date.toLocaleDateString('en-US', { weekday: 'long' });
            const isAvailable = availableDates.includes(dateString);
            const isToday = dateString === serverDate; // Use server date for today comparison
            
            // Check if date is in the past using server date
            const isPast = dateString < serverDate;

            const dayCell = document.createElement('div');
            dayCell.className = `text-center py-2 cursor-pointer rounded-lg transition-colors ${
                isPast ? 'text-gray-400 cursor-not-allowed bg-gray-100' :
                isAvailable ? 'text-gray-900 hover:bg-[#0d5c2f]/10 cursor-pointer' :
                'text-gray-400 cursor-not-allowed'
            }`;

            if (isToday) {
                dayCell.classList.add('bg-[#0d5c2f]', 'text-white');
            }

            dayCell.textContent = day;

            // Add tooltip for past dates
            if (isPast) {
                dayCell.title = 'Past dates are not available for booking';
            }

            if (isAvailable && !isPast) {
                dayCell.addEventListener('click', () => selectDate(dateString));
            }

            calendar.appendChild(dayCell);
        }
    }

    // Select date and load time slots
    function selectDate(date) {
        selectedDate = date;
        document.getElementById('booking_date').value = date;
        
        // Update selected date display
        const dateObj = new Date(date);
        document.getElementById('selected-date').textContent = dateObj.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        // Load time slots
        loadTimeSlots(date);
    }

    // Load time slots for selected date
    function loadTimeSlots(date) {
        fetch('{{ route("booking.available-times") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                service_id: serviceId,
                date: date
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Show error message
                const timeSlotsContainer = document.getElementById('time-slots');
                timeSlotsContainer.innerHTML = `<div class="col-span-full text-center text-red-500 py-4">${data.error}</div>`;
                document.getElementById('time-slots-section').style.display = 'block';
                return;
            }
            
            displayTimeSlots(data.formatted_times, data.available_times, data.slot_info, data.slots_remaining);
        })
        .catch(error => {
            console.error('Error loading time slots:', error);
            const timeSlotsContainer = document.getElementById('time-slots');
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center text-red-500 py-4">Error loading time slots. Please try again.</div>';
            document.getElementById('time-slots-section').style.display = 'block';
        });
    }

    // Display time slots
    function displayTimeSlots(formattedTimes, availableTimes, slotInfo, slotsRemaining = 1) {
        const timeSlotsContainer = document.getElementById('time-slots');
        timeSlotsContainer.innerHTML = '';
    
        if (formattedTimes.length === 0) {
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center text-gray-500 py-4">No available time slots for this date.</div>';
            document.getElementById('time-slots-section').style.display = 'none';
            return;
        }
    
        // Add slot information header
        const slotInfoHeader = document.createElement('div');
        slotInfoHeader.className = 'col-span-full text-sm text-gray-600 mb-3 text-center';
        slotInfoHeader.innerHTML = `<i class="fas fa-info-circle mr-1"></i>Select a time slot below. Numbers show remaining slots.`;
        timeSlotsContainer.appendChild(slotInfoHeader);
    
        // Check if any slots are running low
        const lowSlots = slotInfo.filter(slot => slot.remaining_slots <= 1).length;
        if (lowSlots > 0) {
            const warningDiv = document.createElement('div');
            warningDiv.className = 'col-span-full text-sm text-orange-600 mb-3 text-center bg-orange-50 border border-orange-200 rounded-lg py-2';
            warningDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${lowSlots} time slot${lowSlots > 1 ? 's' : ''} with limited availability`;
            timeSlotsContainer.appendChild(warningDiv);
        }
    
        formattedTimes.forEach((formattedTime, index) => {
            const timeSlot = document.createElement('div');
            timeSlot.className = 'text-center py-3 px-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-[#0d5c2f]/10 hover:border-[#0d5c2f] transition-colors relative';
            
            // Find slot info for this time
            const slotData = slotInfo.find(slot => slot.time === availableTimes[index]);
            const remainingSlots = slotData ? slotData.remaining_slots : 0;
            const totalSlots = slotData ? slotData.total_slots : 1;
            
            // Create slot content with time and remaining slots
            timeSlot.innerHTML = `
                <div class="font-medium text-gray-900">${formattedTime}</div>
                <div class="text-xs text-gray-500 mt-1">
                    <span class="slot-remaining font-semibold text-[#0d5c2f]">${remainingSlots}</span> of ${totalSlots} slots left
                </div>
            `;
            
            // Add visual indicator for low availability
            if (remainingSlots <= 1) {
                timeSlot.classList.add('border-orange-300', 'bg-orange-50');
                timeSlot.querySelector('.slot-remaining').classList.add('text-orange-600');
            } else if (remainingSlots <= Math.ceil(totalSlots / 2)) {
                timeSlot.classList.add('border-yellow-300', 'bg-yellow-50');
                timeSlot.querySelector('.slot-remaining').classList.add('text-yellow-600');
            }
            
            timeSlot.addEventListener('click', () => selectTime(availableTimes[index], formattedTime, remainingSlots));
            timeSlotsContainer.appendChild(timeSlot);
        });
    
        document.getElementById('time-slots-section').style.display = 'block';
    }

    // Select time
    function selectTime(time, formattedTime, remainingSlots = 1) {
        selectedTime = time;
        selectedSlotInfo = { remainingSlots, totalSlots: {{ $service->slots }} };
        document.getElementById('booking_time').value = time;
        document.getElementById('selected-time').textContent = formattedTime;
        
        // Update selected info display with slot information
        const selectedInfo = document.getElementById('selected-info');
        const slotInfoDiv = selectedInfo.querySelector('.slot-info');
        if (slotInfoDiv) {
            slotInfoDiv.innerHTML = `
                <div class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-users mr-1"></i>
                    <span class="font-semibold text-[#0d5c2f]">${remainingSlots}</span> of ${selectedSlotInfo.totalSlots} slots remaining
                </div>
            `;
        }
        
        // Show selected info
        selectedInfo.style.display = 'block';
        
        // Enable continue button
        document.getElementById('continue-btn').disabled = false;
        document.getElementById('continue-btn').className = 'bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 text-white px-6 py-2 rounded-lg font-medium transition-colors';
        
        // Update time slot selection
        const timeSlots = document.querySelectorAll('#time-slots > div');
        timeSlots.forEach(slot => {
            slot.classList.remove('bg-[#0d5c2f]', 'text-white', 'border-[#0d5c2f]');
            if (slot.textContent.includes(formattedTime)) {
                slot.classList.add('bg-[#0d5c2f]', 'text-white', 'border-[#0d5c2f]');
                // Update text color for selected slot
                const timeText = slot.querySelector('.font-medium');
                const slotText = slot.querySelector('.text-xs');
                if (timeText) timeText.classList.add('text-white');
                if (slotText) slotText.classList.add('text-white');
            }
        });
    }

    // Initialize the calendar
    initializeCalendar();
});
</script>
@endsection
