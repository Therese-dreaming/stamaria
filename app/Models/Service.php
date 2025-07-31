<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'requirements',
        'price',
        'icon',
        'duration_minutes',
        'slots',
        'additional_notes',
        'schedules',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'slots' => 'integer',
        'schedules' => 'array',
    ];

    /**
     * Set the slug attribute when setting the name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }

    /**
     * Get duration in hours and minutes
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Get the form fields for this service
     */
    public function formFields()
    {
        return $this->hasMany(ServiceFormField::class)->orderBy('order');
    }

    /**
     * Get required form fields
     */
    public function requiredFormFields()
    {
        return $this->formFields()->where('required', true);
    }

    /**
     * Get conditional form fields
     */
    public function conditionalFormFields()
    {
        return $this->formFields()->where('is_conditional', true);
    }

}
