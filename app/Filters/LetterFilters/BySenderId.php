<?php

namespace App\Filters\LetterFilters;

use Closure;

class BySenderId
{
    public function __invoke($query, Closure $next)
    {
        if (is_numeric(request('filters.sender_id', ''))) {
            $query->where('sender_id', request('filters.sender_id'));
        }

        return $next($query);
    }
}
