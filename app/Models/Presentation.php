<?php

namespace App\Models;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $fillable = ['date', 'city', 'country', 'publication_id', 'event_name', 'event_type'];

    protected $dates = ['date'];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
}
