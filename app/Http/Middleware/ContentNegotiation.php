<?php

namespace App\Http\Middleware;

use App\Exceptions\NotAcceptableException;
use Closure;
use Illuminate\Http\Request;

class ContentNegotiation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \App\Exceptions\NotAcceptableException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->expectsJson()) {
            throw new NotAcceptableException();
        }

        return $next($request);
    }
}
