<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Service;

class ScheduleHelper
{
    /**
     * Get available time slots for a specific date and service
     */
    public static function getAvailableTimeSlots(Service $service, $date)
    {
        $date = Carbon::parse($date);
        $dayOfWeek = strtolower($date->format('l'));
        
        // Get service schedules
        $schedules = $service->schedules ?? [];
        $availableTimes = [];
        
        // Handle both array of schedules and single schedule object
        if (!empty($schedules)) {
            // If schedules is an array of schedule objects
            if (is_array($schedules) && isset($schedules[0])) {
                foreach ($schedules as $schedule) {
                    $times = self::getTimesForSchedule($schedule, $dayOfWeek);
                    $availableTimes = array_merge($availableTimes, $times);
                }
            } else {
                // If schedules is a single schedule object
                $times = self::getTimesForSchedule($schedules, $dayOfWeek);
                $availableTimes = array_merge($availableTimes, $times);
            }
        }
        
        // Remove duplicates and sort
        $availableTimes = array_unique($availableTimes);
        sort($availableTimes);
        
        // Filter out times that conflict with existing bookings and add slot information
        $filteredTimes = [];
        foreach ($availableTimes as $time) {
            if (self::isTimeSlotAvailable($service, $date, $time)) {
                $remainingSlots = $service->getRemainingSlots($date->format('Y-m-d'), $time);
                $filteredTimes[] = [
                    'time' => $time,
                    'remaining_slots' => $remainingSlots,
                    'total_slots' => $service->slots
                ];
            }
        }
        
        return $filteredTimes;
    }

    /**
     * Get available times for a specific schedule and day
     */
    private static function getTimesForSchedule($schedule, $dayOfWeek)
    {
        $times = [];
        
        // Check if this schedule applies to the selected day
        if (isset($schedule['days']) && in_array($dayOfWeek, $schedule['days'])) {
            // Add primary time if it exists
            if (isset($schedule['primary_time']) && !empty($schedule['primary_time'])) {
                $times[] = $schedule['primary_time'];
            }
            
            // Add additional times if they exist
            if (isset($schedule['additional_times']) && is_array($schedule['additional_times'])) {
                $times = array_merge($times, $schedule['additional_times']);
            }
            
            // Add day-specific times if they exist
            if (isset($schedule['day_specific_times']) && isset($schedule['day_specific_times'][$dayOfWeek])) {
                $dayTimes = $schedule['day_specific_times'][$dayOfWeek];
                if (is_array($dayTimes)) {
                    $times = array_merge($times, $dayTimes);
                }
            }
        }
        
        return $times;
    }
    
    /**
     * Check if a specific time slot is available
     */
    public static function isTimeSlotAvailable(Service $service, $date, $time)
    {
        $date = Carbon::parse($date);
        $time = Carbon::parse($time);
        $bookingDateTime = $date->copy()->setTime($time->hour, $time->minute);
        
        // Check if date is in the past
        if ($date->isPast()) {
            return false;
        }
        
        // Get all bookings for this date and time
        $existingBookings = Booking::where('booking_date', $date->format('Y-m-d'))
            ->where('booking_time', $time->format('H:i'))
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Check if we've reached the slot limit
        if ($existingBookings >= $service->slots) {
            return false;
        }
        
        // Check for duration conflicts with existing bookings
        $conflictingBookings = Booking::where('booking_date', $date->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->get();
        
        foreach ($conflictingBookings as $booking) {
            $bookingStart = $booking->bookingDateTime;
            $bookingEnd = $booking->endTime;
            
            $serviceStart = $bookingDateTime;
            $serviceEnd = $bookingDateTime->copy()->addMinutes($service->duration_minutes);
            
            // Check if there's an overlap
            if ($serviceStart < $bookingEnd && $serviceEnd > $bookingStart) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get available dates for the next 30 days
     */
    public static function getAvailableDates(Service $service)
    {
        $availableDates = [];
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays(30);
        
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            // Skip past dates
            if ($currentDate->isPast()) {
                $currentDate->addDay();
                continue;
            }
            
            $dayOfWeek = strtolower($currentDate->format('l'));
            $schedules = $service->schedules ?? [];
            
            // Handle both array of schedules and single schedule object
            if (!empty($schedules)) {
                $hasAvailableTimes = false;
                
                // If schedules is an array of schedule objects
                if (is_array($schedules) && isset($schedules[0])) {
                    foreach ($schedules as $schedule) {
                        if (isset($schedule['days']) && in_array($dayOfWeek, $schedule['days'])) {
                            // Check if there are any available time slots for this date
                            $availableTimes = self::getAvailableTimeSlots($service, $currentDate);
                            if (!empty($availableTimes)) {
                                $availableDates[] = $currentDate->format('Y-m-d');
                                $hasAvailableTimes = true;
                                break; // Found at least one available time, move to next date
                            }
                        }
                    }
                } else {
                    // If schedules is a single schedule object
                    if (isset($schedules['days']) && in_array($dayOfWeek, $schedules['days'])) {
                        // Check if there are any available time slots for this date
                        $availableTimes = self::getAvailableTimeSlots($service, $currentDate);
                        if (!empty($availableTimes)) {
                            $availableDates[] = $currentDate->format('Y-m-d');
                            $hasAvailableTimes = true;
                        }
                    }
                }
                
                if ($hasAvailableTimes) {
                    $currentDate->addDay();
                    continue;
                }
            }
            
            $currentDate->addDay();
        }
        
        return $availableDates;
    }
    
    /**
     * Format time for display
     */
    public static function formatTime($time)
    {
        return Carbon::parse($time)->format('g:i A');
    }
    
    /**
     * Parse time from various formats
     */
    public static function parseTime($time)
    {
        return Carbon::parse($time)->format('H:i');
    }
} 