<?php

namespace App\Filters\LetterFilters;

use Closure;

class AfterDate
{
    public function __invoke($query, Closure $next)
    {
        if (request('filters.after_date', '') != '') {
            $query->where('date', '>', request('filters.after_date'));
        }

        return $next($query);
    }
}
