<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'], // cari berdasarkan email
            [
                'name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'), // ganti dengan password default kamu
                'remember_token' => Str::random(10),
                'phone' => '082121212121',
                'role' => 'admin',
            ]
        );
    }
}
