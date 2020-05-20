<?php

namespace App\Filters\User;

use App\Types\UserCategory;
use Closure;

class ByCategory
{
    public function __invoke($query, Closure $next)
    {
        if (in_array(request('filters.category', ''), UserCategory::values())) {
            $query->where('category', request('filters.category'));
        }

        return $next($query);
    }
}
