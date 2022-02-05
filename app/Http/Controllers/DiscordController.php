<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function login(): RedirectResponse
    {
        return Socialite::driver('discord')->setScopes(['identify'])->redirect();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth');

        return redirect('/');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $user = Socialite::driver('discord')->user();
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return redirect('/');
        }

        $request->session()->put('auth', $user);

        return redirect($request->session()->get('url.intended'));
    }
}
