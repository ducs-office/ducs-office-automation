<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\RequestStatus;
use App\Types\ScholarAppealStatus;
use Illuminate\Database\Eloquent\Model;

class PrePhdSeminar extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => CustomType::class . ':' . RequestStatus::class,
    ];

    protected $dates = [
        'scheduled_on',
    ];

    public function getAppliedOnAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function isCompleted()
    {
        return $this->status->equals(RequestStatus::APPROVED);
    }
}
