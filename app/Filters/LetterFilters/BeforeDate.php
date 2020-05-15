<?php

namespace App\Filters\LetterFilters;

use Closure;

class BeforeDate
{
    public function __invoke($query, Closure $next)
    {
        if (request('filters.before_date', '') != '') {
            $query->where('date', '<', request('filters.before_date'));
        }

        return $next($query);
    }
}
