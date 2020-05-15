<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ScholarAppealStatus;
use Illuminate\Database\Eloquent\Model;

class ScholarAppeal extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => CustomType::class . ':' . ScholarAppealStatus::class,
    ];

    protected $dates = [
        'applied_on',
        'response_date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
