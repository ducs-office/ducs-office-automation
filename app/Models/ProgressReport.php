<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ProgressReportRecommendation;
use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'recommendation' => CustomType::class . ':' . ProgressReportRecommendation::class,
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
