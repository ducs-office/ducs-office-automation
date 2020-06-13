<?php

namespace App\Concerns;

use App\Models\Pivot\ScholarAdvisor;
use App\Models\Pivot\ScholarCosupervisor;
use App\Models\Pivot\ScholarSupervisor;
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
            ->using(ScholarSupervisor::class)
            ->orderBy('pivot_started_on', 'DESC');
    }

    public function getCurrentSupervisorAttribute()
    {
        return $this->supervisors->firstWhere('pivot.ended_on', null);
    }

    public function cosupervisors()
    {
        return $this->belongsToMany(User::class, 'cosupervisor_scholar')
            ->withPivot(['started_on', 'ended_on'])
            ->using(ScholarCosupervisor::class)
            ->orderBy('pivot_started_on', 'DESC');
    }

    public function getCurrentCosupervisorAttribute()
    {
        return $this->cosupervisors->firstWhere('pivot.ended_on', null);
    }

    public function advisors()
    {
        return $this->belongsToMany(User::class, 'advisor_scholar')
            ->withPivot(['started_on', 'ended_on'])
            ->using(ScholarAdvisor::class)
            ->orderBy('pivot_started_on', 'DESC');
    }

    public function currentAdvisors()
    {
        return $this->advisors()->wherePivot('ended_on', null);
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
