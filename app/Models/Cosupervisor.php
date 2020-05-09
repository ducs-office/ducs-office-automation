<?php

namespace App\Models;

use App\Types\UserType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cosupervisor extends Model
{
    protected $guarded = [];

    public function professor()
    {
        return $this->morphTo('professor');
    }

    public function getNameAttribute($name)
    {
        return optional($this->professor)->name ?? $name;
    }

    public function getEmailAttribute($email)
    {
        return optional($this->professor)->email ?? $email;
    }

    public function getDesignationAttribute($designation)
    {
        return $this->professor ? $this->professor->designation : $designation;
    }

    public function getAffiliationAttribute($affiliation)
    {
        if (! $this->professor) {
            return $affiliation;
        }

        return $this->professor->college->name;
    }
}
