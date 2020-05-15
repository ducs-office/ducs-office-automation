<?php

namespace App\Filters\LetterFilters;

use Closure;

class BySenderString
{
    public function __invoke($query, Closure $next)
    {
        if (request('filters.sender', '')) {
            $query->where('sender', request('filters.sender'));
        }

        return $next($query);
    }
}
