<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'phone_no', 'department', 'role', 'date_of_joined', 'is_active', 'admin_type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
