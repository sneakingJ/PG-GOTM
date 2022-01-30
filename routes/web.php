<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiscordController;

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

Route::get('/discord-auth/login', [DiscordController::class, 'login'])->name('login');
Route::get('/discord-auth/logout', [DiscordController::class, 'logout'])->middleware('auth.discord');
Route::get('/discord-auth/callback', [DiscordController::class, 'authCallback']);
