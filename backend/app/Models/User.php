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
        'github_id',
        'name',
        'login',
        'avatar_url',
        'github_token',
        'github_refresh_token',
        'token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'github_token'         => 'encrypted',
            'github_refresh_token' => 'encrypted',
            'token_expires_at'     => 'datetime',
            'password'             => 'hashed',
        ];
    }
}