<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PhdCourse extends Model
{
    protected $fillable = ['code', 'name', 'type'];

    public function scopeCore(Builder $builder)
    {
        return $builder->whereType('C');
    }
}
