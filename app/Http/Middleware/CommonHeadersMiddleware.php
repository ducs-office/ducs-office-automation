<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CommonHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        return $response->header("X-Frame-Options", "SAMEORIGIN")
            ->header("X-XSS-Protection", "1; mode=block")
            ->header("X-Content-Type-Options", "nosniff");
    }
}
