<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Manideep',
            'email' => 'sandireddymanideep44@gmail.com',
            'password' => bcrypt('manideep123'), // Replace with your password
            'phone_no' => '1234567890',
            'department' => 'IT',
            'role' => 'Developer',
            'date_of_joined' => '2025-01-01',
            'is_active' => true,
            'admin_type' => 'SuperAdmin', // Specify the admin type
        ]);

        
    }
}