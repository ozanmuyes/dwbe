<?php

namespace App\Http\Middleware;

use App\Exceptions\ServiceUnavailableException;
use Illuminate\Http\Request;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \App\Exceptions\ServiceUnavailableException
     */
    public function handle(Request $request, \Closure $next)
    {
        if (env('APP_MAINTENANCE', false) === true) {
            throw new ServiceUnavailableException();
        }

        return $next($request);
    }
}
