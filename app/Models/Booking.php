<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'booking_date',
        'booking_time',
        'status',
        'special_requests',
        'custom_fields',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'custom_fields' => 'array',
    ];

    /**
     * Get the user that owns the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service for this booking
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the booking datetime
     */
    public function getBookingDateTimeAttribute()
    {
        return Carbon::parse($this->booking_date . ' ' . $this->booking_time);
    }

    /**
     * Get the end time of the booking
     */
    public function getEndTimeAttribute()
    {
        if (!$this->service || !$this->service->duration_minutes) {
            return $this->booking_time;
        }
        
        return Carbon::parse($this->booking_time)->addMinutes($this->service->duration_minutes);
    }

    /**
     * Check if booking conflicts with another booking
     */
    public function conflictsWith($otherBooking)
    {
        $thisStart = $this->bookingDateTime;
        $thisEnd = $this->endTime;
        $otherStart = $otherBooking->bookingDateTime;
        $otherEnd = $otherBooking->endTime;

        return $thisStart < $otherEnd && $thisEnd > $otherStart;
    }
}
