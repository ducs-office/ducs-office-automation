<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class UserProfileAccessControll
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
        // $request->user is the user who's profile we are trying to see
        if ($request->user() == null || ! $request->user()->is_admin && $request->user()->id != $request->user->id) {
            throw new AuthorizationException('You cannot access other users profile!', 403);
        }

        return $next($request);
    }
}
