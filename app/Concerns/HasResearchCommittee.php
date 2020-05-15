<?php

namespace App\Concerns;

use App\Models\Pivot\ScholarSupervisor;
use App\Models\ScholarAdvisor;
use App\Models\ScholarCosupervisor;
use App\Models\User;

trait HasResearchCommittee
{
    public function supervisors()
    {
        return $this->belongsToMany(
            User::class,
            'scholar_supervisor',
            'scholar_id',
            'supervisor_id'
        )
            ->withPivot(['started_on', 'ended_on'])
            ->using(ScholarSupervisor::class);
    }

    public function getCurrentSupervisorAttribute()
    {
        return $this->supervisors
            ->firstWhere('pivot.ended_on', null);
    }

    public function cosupervisors()
    {
        return $this->hasMany(ScholarCosupervisor::class);
    }

    public function currentCosupervisor()
    {
        return $this->hasOne(ScholarCosupervisor::class)->whereNull('ended_on');
    }

    public function advisors()
    {
        return $this->hasMany(ScholarAdvisor::class)->orderBy('started_on', 'desc');
    }

    public function currentAdvisors()
    {
        return $this->hasMany(ScholarAdvisor::class)
            ->whereNull('ended_on')
            ->orderBy('started_on', 'desc');
    }

    public function getCommitteeAttribute()
    {
        return (object) [
            'supervisor' => $this->currentSupervisor,
            'cosupervisor' => $this->currentCosupervisor,
            'advisors' => $this->currentAdvisors,
        ];
    }
}
