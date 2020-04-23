<?php

namespace App\Concerns;

use App\Models\SupervisorProfile;

trait SupervisesScholars
{
    public function supervisorProfile()
    {
        return $this->morphOne(SupervisorProfile::class, 'supervisor');
    }

    public function isSupervisor()
    {
        return $this->supervisorProfile !== null;
    }
}
