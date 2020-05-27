<?php

namespace App\Filters\User;

use Closure;

class SearchByName
{
    public function __invoke($query, Closure $next)
    {
        if (request()->has('search') && request('search') != null) {
            return $query->where(function ($query) {
                $query->where('first_name', 'like', request('search') . '%')
                    ->orWhere('last_name', 'like', request('search') . '%');
            });
        }

        return $next($query);
    }
}
