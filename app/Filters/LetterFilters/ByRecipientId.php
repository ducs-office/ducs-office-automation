<?php

namespace App\Filters\LetterFilters;

use Closure;

class ByRecipientId
{
    public function __invoke($query, Closure $next)
    {
        if (is_numeric(request('filters.recipient_id', ''))) {
            $query->where('recipient_id', request('filters.recipient_id'));
        }

        return $next($query);
    }
}
