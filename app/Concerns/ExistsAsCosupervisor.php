<?php

namespace App\Concerns;

use App\Models\Cosupervisor;

trait ExistsAsCosupervisor
{
    public function cosupervisorProfile()
    {
        return $this->morphOne(Cosupervisor::class, 'professor');
    }

    public function isCosupervisor()
    {
        return $this->cosupervisorProfile !== null;
    }
}
