<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class MustBeSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->supervisorProfile) {
            throw new AuthorizationException('You must be a supervisor!', 403);
        }

        return $next($request);
    }
}
