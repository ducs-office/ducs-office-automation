<?php

namespace App\Filters\LetterFilters;

use Closure;

class ByRecipientString
{
    public function __invoke($query, Closure $next)
    {
        if (request('filters.recipient', '')) {
            $query->where('recipient', request('filters.recipient'));
        }

        return $next($query);
    }
}
