<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\RequestStatus;
use Illuminate\Database\Eloquent\Model;

class TitleApproval extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => CustomType::class . ':' . RequestStatus::class,
    ];

    public function getAppliedOnAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
