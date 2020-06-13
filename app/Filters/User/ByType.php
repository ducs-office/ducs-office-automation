<?php

namespace App\Filters\User;

use Closure;

class ByType
{
    public function __invoke($query, Closure $next)
    {
        if (request('filters.type', '') === 'is_supervisor') {
            $query->where('is_supervisor', 1);
        }

        if (request('filters.type', '') === 'is_cosupervisor') {
            $query->where('is_cosupervisor', 1)->where('is_supervisor', 0);
        }
        return $next($query);
    }
}
