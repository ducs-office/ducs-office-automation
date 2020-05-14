<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarCosupervisor extends Model
{
    protected $fillable = [
        'scholar_id',
        'person_type', 'person_id',
        'started_on', 'ended_on',
    ];

    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];

    protected $with = ['person'];

    public $timestamps = false;

    public function person()
    {
        return $this->morphTo();
    }

    public function getNameAttribute()
    {
        return $this->person->name;
    }

    public function getEmailAttribute()
    {
        return $this->person->email;
    }

    public function getDesignationAttribute()
    {
        return $this->person->designation;
    }

    public function getAffiliationAttribute()
    {
        if ($this->person_type === User::class) {
            return optional($this->person->college)->name ?? 'Unknown';
        }

        return $this->person->affiliation;
    }
}
