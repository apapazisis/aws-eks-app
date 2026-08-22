<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubController;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use App\Http\Controllers\LoginController;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});


Route::group(['middleware' => ['auth']], function ()
{
    Route::get('/home', [LoginController::class, 'home'])->name('home');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/auth/github', [GithubController::class, 'redirect'])->name('auth.redirect');
    Route::get('/auth/github/callback', [GithubController::class, 'callback'])->name('auth.callback');
    Route::post('/auth/github/disconnect', [GithubController::class, 'disconnect'])->name('github.disconnect');
    Route::get('/github/search', [GithubController::class, 'search'])->name('github.search');
});



Route::post('/webhook', function () {
    $payload = request()->getContent();
    $signature = request()->header('X-Hub-Signature-256');
    $secret = env('GITHUB_WEBHOOK_SECRET');

    return response('Webhook received', 200);
})->withoutMiddleware([PreventRequestForgery::class])->name('webhook');