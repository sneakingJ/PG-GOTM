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

Route::get('/', MainController::class)->name('main');
Route::get('/nominate', NominateController::class)->name('nominate')->middleware('auth.discord:');
Route::get('/voting', VotingController::class)->name('voting')->middleware('auth.discord:');
Route::get('/jury', JuryController::class)->name('jury');
Route::get('/jury-members', JuryMemberController::class)->name('jury-members');
Route::get('/privacy', PrivacyController::class)->name('privacy');

Route::get('/admin', AdminController::class)->name('admin')->middleware('auth.discord:' . env('ADMIN_DISCORD_IDS'));

Route::get('/discord-auth/login', [DiscordController::class, 'login'])->name('login');
Route::get('/discord-auth/logout', [DiscordController::class, 'logout'])->middleware('auth.discord:');
Route::get('/discord-auth/callback', [DiscordController::class, 'callback']);
