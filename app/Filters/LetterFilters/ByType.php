<?php

namespace App\Filters\LetterFilters;

use App\Types\OutgoingLetterType;
use Closure;

class ByType
{
    public function __invoke($query, Closure $next)
    {
        if (in_array(request('filters.type', ''), OutgoingLetterType::values())) {
            $query->where('type', request('filters.type'));
        }

        return $next($query);
    }
}
