<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\ScholarDocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ScholarDocument extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    protected $casts = [
        'type' => CustomType::class . ':' . ScholarDocumentType::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(static function ($document) {
            Storage::delete($document->path);
        });
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
