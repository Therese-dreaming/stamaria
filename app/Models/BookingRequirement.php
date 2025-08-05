<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'requirement_name',
        'file_path',
        'original_filename',
        'file_type',
        'file_size',
        'notes',
    ];

    /**
     * Get the booking that owns this requirement
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
