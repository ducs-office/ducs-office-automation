<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRevision extends Model
{
    protected $fillable = ['revised_at'];

    protected $dates = ['revised_at'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::deleting(static function ($courseRevision) {
            $courseRevision->attachments->each->delete();
        });
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
