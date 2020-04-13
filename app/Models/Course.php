<?php

namespace App\Models;

use App\Casts\CustomType;
use App\Types\CourseType;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'type'];

    protected $casts = [
        'type' => CustomType::class . ':' . CourseType::class,
    ];

    public function programmeRevisions()
    {
        return $this->belongsToMany(ProgrammeRevision::class);
    }

    public function revisions()
    {
        return $this->hasMany(CourseRevision::class);
    }
}
