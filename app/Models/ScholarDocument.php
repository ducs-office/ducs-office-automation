<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ScholarDocumentType;
use Illuminate\Database\Eloquent\Model;

class ScholarDocument extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'type' => CustomType::class . ':' . ScholarDocumentType::class,
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
