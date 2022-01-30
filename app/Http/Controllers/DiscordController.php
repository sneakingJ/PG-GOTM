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
        return Socialite::driver('discord')->redirect();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('authId');

        return redirect('/');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function authCallback(Request $request): RedirectResponse
    {
        $user = Socialite::driver('discord')->user();

        $request->session()->put('authId', $user->getId());

        return redirect('/');
    }
}
