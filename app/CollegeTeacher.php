<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class CollegeTeacher extends User
{
    protected $guarded = [];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
