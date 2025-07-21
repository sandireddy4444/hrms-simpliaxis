<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property string $name
 * @property string $email
 * @property string $phone_no
 * @property string $department
 * @property string $role
 * @property string $date_of_joined
 * @property bool $is_active
 * @property string $admin_type
 */
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'phone_no', 'department', 'role', 'date_of_joined', 'is_active', 'admin_type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
