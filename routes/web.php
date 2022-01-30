<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $userId = session('authId');

    return view('main', ['authId' => $userId]);
});

Route::get('/discord-auth/login', function () {
    return Socialite::driver('discord')->redirect();
});

Route::get('/discord-auth/logout', function () {
    session(['authId' => null]);

    return redirect('/');
});

Route::get('/discord-auth/callback', function () {
    $user = Socialite::driver('discord')->user();

    session(['authId' => $user->getId()]);

    return redirect('/');
});
