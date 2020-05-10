<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ProgressReportRecommendation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProgressReport extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'recommendation' => CustomType::class . ':' . ProgressReportRecommendation::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($report) {
            Storage::delete($report->path);
        });
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
