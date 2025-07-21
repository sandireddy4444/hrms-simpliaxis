<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'date',
        'check_in',
        'check_out',
        'total_hours',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


    
