<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppendJwtFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasCookie('jwt_token') && !$request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('jwt_token'));
        }

        return $next($request);
    }
}
