<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'disabled',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
        'disabled',
        'created_at',
        'updated_at'
    ];
}
