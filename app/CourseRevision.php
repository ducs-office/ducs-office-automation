<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseRevision extends Model
{
    protected $fillable = ['revised_at'];

    protected $dates = ['revised_at'];

    public $timestamps = false;

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
