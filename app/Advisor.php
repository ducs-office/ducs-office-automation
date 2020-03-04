<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    protected $fillable = [
        'title',
        'name',
        'designation',
        'affiliation',
        'type',
    ];
}
