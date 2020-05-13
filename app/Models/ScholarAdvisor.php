<?php

namespace App\Models;

use App\Models\Model;
use App\Models\User;

class ScholarAdvisor extends Model
{
    protected $table = 'scholar_advisor';
    protected $fillable = [
        'advisor_type', 'advisor_id',
        'started_on', 'ended_on',
        'scholar_id',
    ];

    protected $with = ['advisor'];

    protected $casts = [
        'started_on' => 'datetime',
        'ended_on' => 'datetime',
    ];

    public $timestamps = false;

    public function advisor()
    {
        return $this->morphTo('advisor');
    }

    public function getNameAttribute()
    {
        return $this->advisor->name;
    }

    public function getEmailAttribute()
    {
        return $this->advisor->email;
    }

    public function getDesignationAttribute()
    {
        return $this->advisor->designation;
    }

    public function getAffiliationAttribute()
    {
        if ($this->advisor_type === User::class) {
            return optional($this->advisor->college)->name ?? 'Unknown';
        }

        return $this->advisor->affiliation;
    }
}
