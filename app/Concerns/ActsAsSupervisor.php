<?php

namespace App\Concerns;

use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Builder;

trait ActsAsSupervisor
{
    public function canBecomeSupervisor()
    {
        return in_array($this->category, [
            UserCategory::COLLEGE_TEACHER,
            UserCategory::FACULTY_TEACHER,
        ]);
    }

    public function isSupervisor()
    {
        return $this->is_supervisor === true;
    }

    public function scopeSupervisors(Builder $builder)
    {
        return $builder->where('is_supervisor', true);
    }

    public function scopeNonSupervisors(Builder $builder)
    {
        return $builder->where('is_supervisor', true);
    }
}
