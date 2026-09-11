<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Zaenal Arifin',
            'email' => 'zaenal@gmail.com',
            'password' => Hash::make('123456789'),
            'roles' => 'administrator',
            'phone' => '081234567890',
        ]);

        $user = User::where('email', 'zaenal@gmail.com')->first();
        $user->email_verified_at = now();
        $user->save();
    }
}