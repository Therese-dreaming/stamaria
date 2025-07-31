<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',
        'phone',
        'bio',
        'image',
        'status',
        'ordination_date',
        'assignment_date',
    ];

    protected $casts = [
        'ordination_date' => 'date',
        'assignment_date' => 'date',
    ];

    /**
     * Get the priest's full name with title
     */
    public function getFullNameAttribute()
    {
        return $this->title ? "{$this->title} {$this->name}" : $this->name;
    }

    /**
     * Get the priest's image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-priest.jpg');
    }

    /**
     * Scope for active priests
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive priests
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
