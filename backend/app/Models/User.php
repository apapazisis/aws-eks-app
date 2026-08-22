<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'github_id',
        'github_name',
        'github_email',
        'login',
        'avatar_url',
        'github_token',
        'github_refresh_token',
        'remember_token',
        'token_expires_at',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'github_token'         => 'encrypted',
            'github_refresh_token' => 'encrypted',
            'token_expires_at'     => 'datetime',
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
        ];
    }
}