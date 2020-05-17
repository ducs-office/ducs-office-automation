<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ScholarAppealStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScholarAppeal extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => CustomType::class . ':' . ScholarAppealStatus::class,
    ];

    public function scopePhdSeminarAppeals(Builder $builder)
    {
        return $builder->whereType(ScholarAppealTypes::PRE_PHD_SEMINAR)->orderBY('created_at', 'DESC')->get();
    }

    public function getAppliedOnAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function isRejected()
    {
        return $this->status->equals(ScholarAppealStatus::REJECTED);
    }

    public function isCompleted()
    {
        return $this->status->equals(ScholarAppealStatus::COMPLETED);
    }
}
