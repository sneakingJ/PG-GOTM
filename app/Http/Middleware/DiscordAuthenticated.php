<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DiscordAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $user = $request->session()->get('auth');

        if (empty($user)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
