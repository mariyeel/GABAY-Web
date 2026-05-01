<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pairing extends Model
{
    protected $fillable = [
        'vi_user_id',
        'caregiver_user_id',
        'status',
        'paired_at',
        'unpaired_at',
    ];

    public function viUser()
    {
        return $this->belongsTo(User::class, 'vi_user_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_user_id');
    }
}
