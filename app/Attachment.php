<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = ['original_name', 'type', 'path'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(static function ($file) {
            Storage::delete($file->path);
        });
    }

    public function attachable()
    {
        return $this->morphTo();
    }
}
