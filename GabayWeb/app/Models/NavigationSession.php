<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationSession extends Model
{
    protected $fillable = [
        'user_id',
        'origin',
        'destination',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    //Relationship: belongs to VI user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
