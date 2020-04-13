<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupervisorProfile extends Model
{
    public function supervisor()
    {
        return $this->morphTo('supervisor');
    }

    public function publications()
    {
        return $this->morphMany(Publication::class, 'main_author');
    }

    public function journals()
    {
        return $this->publications()->where('type', 'journal')->orderBy('date', 'DESC');
    }

    public function conferences()
    {
        return $this->publications()->where('type', 'conference')->orderBy('date', 'DESC');
    }

    public function scholars()
    {
        return $this->hasMany(Scholar::class, 'supervisor_profile_id');
    }
}
