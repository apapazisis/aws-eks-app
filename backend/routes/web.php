<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubController;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/auth/github', [GithubController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/github/callback', [GithubController::class, 'callback'])->name('auth.callback');
Route::get('/auth/logout', [GithubController::class, 'logout'])->name('auth.logout');
Route::get('/github/search', [GithubController::class, 'search'])->name('github.search');
