<?php

namespace App\Filters\LetterFilters;

use Closure;

class SearchLike
{
    public function __invoke($query, Closure $next)
    {
        if (request()->has('search') && request('search') !== '') {
            $query->where('subject', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%');
        }

        return $next($query);
    }
}
