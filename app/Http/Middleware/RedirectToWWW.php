<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class RedirectToWWW
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
        if (!str_starts_with($request->header('host'), "www.")) {
            $request->headers->set('host', "www." . $request->header('host'));

            return Redirect::to($request->path());
        }

        return $next($request);
    }
}
