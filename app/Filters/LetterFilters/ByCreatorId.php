<?php

namespace App\Filters\LetterFilters;

use Closure;

class ByCreatorId
{
    public function __invoke($query, Closure $next)
    {
        if (is_numeric(request('filters.creator_id', ''))) {
            $query->where('creator_id', request('filters.creator_id'));
        }

        return $next($query);
    }
}
