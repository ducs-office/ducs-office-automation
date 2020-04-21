<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisoryMeeting extends Model
{
    protected $fillable = [
        'date', 'minutes_of_meeting_path', 'scholar_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
