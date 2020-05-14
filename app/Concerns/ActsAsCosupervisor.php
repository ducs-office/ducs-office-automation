<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ActsAsCosupervisor
{
    public function isCosupervisor()
    {
        return $this->is_cosupervisor === true;
    }

    public function scopeCosupervisors(Builder $builder)
    {
        return $builder->where('is_cosupervisor', true);
    }

    public function scopeNonCosupervisors(Builder $builder)
    {
        return $builder->where('is_cosupervisor', false);
    }
}
