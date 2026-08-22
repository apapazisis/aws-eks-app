<?php

namespace App\Http\Controllers;

class LoginController
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        return redirect()->route('auth.redirect');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('home');
    }
}