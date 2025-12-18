<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'latitude',
        'longitude',
        'is_late',
        'notes',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function schedule():BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
