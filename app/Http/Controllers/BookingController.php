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

        // Load service with form fields
        $service = Service::with('formFields')->findOrFail($request->service_id);

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

        // Load service with form fields
        $service = Service::with('formFields')->findOrFail($request->service_id);

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

        // Build validation rules for custom fields
        $validationRules = [];
        $customFields = [];

        foreach ($service->formFields as $field) {
            $fieldName = 'custom_field_' . $field->field_name;
            $customFields[$fieldName] = $field;

            // Base validation rules from field configuration
            if ($field->validation_rules) {
                $validationRules[$fieldName] = $field->validation_rules;
            }

            // Handle conditional validation
            if ($field->is_conditional && $field->required) {
                $conditionField = 'custom_field_' . $field->condition_field;
                $conditionValue = $field->condition_value;

                // Check if the condition is met
                $conditionMet = false;
                if ($request->has($conditionField)) {
                    $conditionFieldValue = $request->input($conditionField);
                    $conditionMet = ($conditionFieldValue == $conditionValue);
                }

                // If condition is met, make the field required
                if ($conditionMet) {
                    if (isset($validationRules[$fieldName])) {
                        $validationRules[$fieldName] = 'required|' . $validationRules[$fieldName];
                    } else {
                        $validationRules[$fieldName] = 'required';
                    }
                }
            } elseif ($field->required && !$field->is_conditional) {
                // Always required field
                if (isset($validationRules[$fieldName])) {
                    $validationRules[$fieldName] = 'required|' . $validationRules[$fieldName];
                } else {
                    $validationRules[$fieldName] = 'required';
                }
            }
        }

        // Validate the request
        $request->validate($validationRules);

        // Store step 2 data in session
        $step2Data = $request->except(['_token', 'service_id']);
        $request->session()->put('booking.step2', $step2Data);

        // Combine all booking data
        $bookingData = array_merge($step1Data, $step2Data);
        $bookingData['service'] = $service;
        $bookingData['custom_fields'] = $customFields;

        return view('booking.success', compact('service', 'bookingData'));
    }
}