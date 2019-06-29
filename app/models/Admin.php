<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'username','password','created_at','updated_at','api_token'
    ];
    protected $hidden = [
        'password','token'
    ];

    protected $dateFormat = 'U';
}
