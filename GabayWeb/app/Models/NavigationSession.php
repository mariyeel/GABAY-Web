<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationSession extends Model
{
    protected $fillable = [
        'user_id',
        'caregiver_user_id',
        'origin',
        'origin_latitude',
        'origin_longitude',
        'destination',
        'destination_latitude',
        'destination_longitude',
        'current_latitude',
        'current_longitude',
        'location_updated_at',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'location_updated_at' => 'datetime',
        'origin_latitude' => 'float',
        'origin_longitude' => 'float',
        'destination_latitude' => 'float',
        'destination_longitude' => 'float',
        'current_latitude' => 'float',
        'current_longitude' => 'float',
    ];

    //Relationship: belongs to VI user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_user_id');
    }

    //Helper: calculate duration
    public function getDurationAttribute()
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }
}
