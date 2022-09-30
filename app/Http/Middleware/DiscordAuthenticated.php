<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiscordAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $ids
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $ids): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $user = $request->session()->get('auth');

        if (empty($user)) {
            $request->session()->put('url.intended', $request->url());

            return redirect()->route('login');
        }

        if (!empty($ids) && !in_array($user['id'], explode(',', $ids))) {
            return redirect()->route('main');
        }

        return $next($request);
    }
}
