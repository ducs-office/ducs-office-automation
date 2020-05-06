<?php

namespace App\Models;

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
        return $this->professor ? 'Professor' : $designation;
    }

    public function getAffiliationAttribute($affiliation)
    {
        if ($this->professor_type === Teacher::class) {
            return optional($this->professor)->profile->college->name ??
                    'Affiliation Not Set';
        }

        return $this->professor ? 'DUCS' : $affiliation;
    }
}
