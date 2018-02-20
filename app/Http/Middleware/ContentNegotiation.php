<?php

namespace App\Http\Middleware;

use App\Exceptions\NotAcceptableException;
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
    public function handle(Request $request, \Closure $next)
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            if (!$request->isJson()) {
                throw new NotAcceptableException(43, 'Body content type wasn\'t specified or invalid.');
            }
        }

        if (!$request->expectsJson()) {
            throw new NotAcceptableException(19, 'Accept header is missing or invalid.');
        }

        return $next($request);
    }
}
