<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class ScholarProfileAccessControll
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */

    //public function is
    public function handle($request, Closure $next)
    {
        // $request->user() is scholar
        if ($request->user() == null) {
            throw new AuthorizationException('You cannot access other scholar profile!', 403);
        }

        if ($request->user()->is_admin) {
            return $next($request);
        }

        if ($request->user()->id == $request->scholar->id) {
            return $next($request);
        } elseif ($request->user()->id != $request->scholar->id) {
            throw new AuthorizationException('You cannot access other scholar profile!', 403);
        }
        foreach ($request->user()->scholars as $scholar) { // if loggedin user is suppervisor of given scholar
            if ($scholar->id == $request->scholar->id) {
                return $next($request);
            }
        }
        throw new AuthorizationException('You cannot access other scholar profile!', 403);
    }
}
