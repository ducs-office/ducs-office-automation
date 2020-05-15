<?php

namespace App\Filters\LetterFilters;

use App\Types\Priority;
use Closure;

class ByPriority
{
    public function __invoke($query, Closure $next)
    {
        if (in_array(request('filters.priority', ''), Priority::values())) {
            $query->where('priority', request('filters.priority'));
        }

        return $next($query);
    }
}
