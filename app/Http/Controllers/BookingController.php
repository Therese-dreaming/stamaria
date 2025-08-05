<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Helpers\ScheduleHelper;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function step1(Service $service)
    {
        $user = auth()->user();
        
        // Check if user's profile is complete
        if (!$user->isProfileComplete()) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Please complete your profile before booking a service.');
        }
        
        return view('booking.step1-booking', compact('service'));
    }

    public function step2(Request $request)
    {
        // Validate step 1 data
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'terms_accepted' => 'required|accepted',
        ]);

        // Get user profile data
        $user = auth()->user();
        $profileData = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'age' => $user->age,
        ];

        // Store step 1 data in session (combine profile data with service_id and terms)
        $step1Data = array_merge($profileData, [
            'service_id' => $request->service_id,
            'terms_accepted' => $request->terms_accepted,
        ]);
        $request->session()->put('booking.step1', $step1Data);

        // Load service
        $service = Service::findOrFail($request->service_id);

        // Check if service is active (if you have an is_active field)
        if (isset($service->is_active) && !$service->is_active) {
            return back()->withErrors(['service_id' => 'This service is currently not available.']);
        }

        // Get available dates for the calendar
        $availableDates = ScheduleHelper::getAvailableDates($service);

        return view('booking.step2-booking', compact('service', 'availableDates'));
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $service = Service::findOrFail($request->service_id);
        $requestedDate = Carbon::parse($request->date);
        
        // Additional validation for past dates
        if ($requestedDate->isPast()) {
            return response()->json([
                'available_times' => [],
                'formatted_times' => [],
                'slot_info' => [],
                'error' => 'Cannot book for past dates.'
            ]);
        }
        
        $availableTimeSlots = ScheduleHelper::getAvailableTimeSlots($service, $request->date);
        
        // Extract times and slot information
        $availableTimes = array_column($availableTimeSlots, 'time');
        $formattedTimes = array_map([ScheduleHelper::class, 'formatTime'], $availableTimes);
        $slotInfo = array_map(function($slot) {
            return [
                'time' => $slot['time'],
                'remaining_slots' => $slot['remaining_slots'],
                'total_slots' => $slot['total_slots']
            ];
        }, $availableTimeSlots);

        return response()->json([
            'available_times' => $availableTimes,
            'formatted_times' => $formattedTimes,
            'slot_info' => $slotInfo,
            'slots_remaining' => $service->slots,
            'date' => $request->date
        ]);
    }

    public function step3(Request $request)
    {
        // Validate step 2 data
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Load service
        $service = Service::findOrFail($request->service_id);

        // Check if service is active (if you have an is_active field)
        if (isset($service->is_active) && !$service->is_active) {
            return back()->withErrors(['service_id' => 'This service is currently not available.']);
        }

        // Get step 1 data from session
        $step1Data = $request->session()->get('booking.step1');
        if (!$step1Data) {
            return redirect()->route('booking.step1', $service);
        }

        // Validate that the selected time slot is available
        $availableTimes = ScheduleHelper::getAvailableTimeSlots($service, $request->booking_date);
        $selectedTime = ScheduleHelper::parseTime($request->booking_time);
        
        if (!in_array($selectedTime, $availableTimes)) {
            return back()->withErrors(['booking_time' => 'The selected time slot is no longer available.']);
        }

        // Additional validations
        $bookingDate = Carbon::parse($request->booking_date);
        $bookingTime = Carbon::parse($request->booking_time);
        
        // Check if date is in the past
        if ($bookingDate->isPast()) {
            return back()->withErrors(['booking_date' => 'Cannot book for past dates.']);
        }
        
        // Check if booking is too far in advance (optional - 6 months limit)
        $maxBookingDate = Carbon::now()->addMonths(6);
        if ($bookingDate->gt($maxBookingDate)) {
            return back()->withErrors(['booking_date' => 'Cannot book more than 6 months in advance.']);
        }
        
        // Check if time is in the past for today's date
        if ($bookingDate->isToday() && $bookingTime->isPast()) {
            return back()->withErrors(['booking_time' => 'Cannot book for past times on the same day.']);
        }
        
        // Check slot availability
        $existingBookings = Booking::where('booking_date', $request->booking_date)
            ->where('booking_time', $selectedTime)
            ->where('status', '!=', 'cancelled')
            ->count();
            
        if ($existingBookings >= $service->slots) {
            return back()->withErrors(['booking_time' => 'This time slot is fully booked.']);
        }
        
        // Check if user already has a booking for this date and time
        $userExistingBooking = Booking::where('user_id', auth()->id())
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $selectedTime)
            ->where('status', '!=', 'cancelled')
            ->first();
            
        if ($userExistingBooking) {
            return back()->withErrors(['booking_time' => 'You already have a booking for this time slot.']);
        }
        
        // Check for duration conflicts
        $bookingDateTime = $bookingDate->copy()->setTime($bookingTime->hour, $bookingTime->minute);
        $serviceEndTime = $bookingDateTime->copy()->addMinutes($service->duration_minutes);
        
        $conflictingBookings = Booking::where('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        foreach ($conflictingBookings as $booking) {
            $existingStart = $booking->bookingDateTime;
            $existingEnd = $booking->endTime;
            
            // Check for overlap
            if ($bookingDateTime < $existingEnd && $serviceEndTime > $existingStart) {
                return back()->withErrors(['booking_time' => 'This time slot conflicts with an existing booking.']);
            }
        }

        // Store step 2 data in session
        $step2Data = $request->except(['_token', 'service_id']);
        $request->session()->put('booking.step2', $step2Data);

        // Combine all booking data
        $bookingData = array_merge($step1Data, $step2Data);
        $bookingData['service'] = $service;

        // Show step 3 - requirements upload
        return view('booking.step3-booking', compact('service', 'bookingData'));
    }

    /**
     * Final step - Submit booking with requirements
     */
    public function step4(Request $request)
    {
        // Validate step 3 data
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'requirements.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ]);

        // Load service
        $service = Service::findOrFail($request->service_id);

        // Get step 1 and 2 data from session
        $step1Data = $request->session()->get('booking.step1');
        $step2Data = $request->session()->get('booking.step2');
        
        if (!$step1Data || !$step2Data) {
            return redirect()->route('booking.step1', $service);
        }

        // Create the booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'booking_date' => $step2Data['booking_date'],
            'booking_time' => $step2Data['booking_time'],
            'special_requests' => $step2Data['special_requests'] ?? null,
            'status' => 'pending',
        ]);

        // Handle requirements upload
        if ($request->hasFile('requirements')) {
            foreach ($request->file('requirements') as $requirementName => $file) {
                if ($file && $file->isValid()) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Store file
                    $filePath = $file->storeAs('booking-requirements', $filename, 'public');
                    
                    // Create requirement record
                    $booking->requirements()->create([
                        'requirement_name' => $requirementName,
                        'file_path' => $filePath,
                        'original_filename' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }

        // Clear session data
        $request->session()->forget(['booking.step1', 'booking.step2']);

        // Combine all booking data for success page
        $bookingData = array_merge($step1Data, $step2Data);
        $bookingData['service'] = $service;
        $bookingData['booking'] = $booking;

        return view('booking.success', compact('service', 'bookingData'));
    }
}