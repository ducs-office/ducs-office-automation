<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CoAuthor extends Model
{
    protected $fillable = [
        'name',
        'noc_path',
        'publication_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($coAuthor) {
            Storage::delete($coAuthor->noc_path);
        });
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
