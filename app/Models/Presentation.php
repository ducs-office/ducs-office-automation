<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Models\Publication;
use App\Types\PresentationEventType;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $fillable = ['date', 'city', 'country', 'publication_id', 'event_name', 'event_type'];

    protected $casts = [
        'date' => 'datetime',
        'event_type' => CustomType::class . ':' . PresentationEventType::class,
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
