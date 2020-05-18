<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ActsAsCosupervisor
{
    public function isCosupervisor()
    {
        return $this->is_cosupervisor === true;
    }

    public function scopeAllCosupervisors(Builder $builder)
    {
        return $builder->where('is_cosupervisor', true)
            ->orWhere('is_supervisor', true);
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
