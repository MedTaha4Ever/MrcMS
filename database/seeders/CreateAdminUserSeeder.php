<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Mohamed Taha Ben Brahim',
            'email' => 'mohamedtaha.bb@gmail.com',
            'password' => Hash::make('TAHA71500362'),
            'email_verified_at' => now(),
        ]);
    }
}
