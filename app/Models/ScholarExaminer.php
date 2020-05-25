<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\RequestStatus;
use Illuminate\Database\Eloquent\Model;

class ScholarExaminer extends Model
{
    protected $guarded = [];

    protected $dates = [
        'recommended_on',
        'approved_on',
    ];

    protected $casts = [
        'status' => CustomType::class . ':' . RequestStatus::class,
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function getAppliedOnAttribute()
    {
        return $this->created_at;
    }
}
