<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test admin user
        User::create([
            'kd' => 'ADM001',
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@freshfood.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'dibuat_oleh' => 'System',
            'date_created' => now(),
            'date_updated' => now(),
        ]);

        // Create a test cashier user
        User::create([
            'kd' => 'CSH001',
            'nama' => 'Kasir Test',
            'username' => 'kasir',
            'email' => 'kasir@freshfood.com',
            'password' => Hash::make('kasir123'),
            'email_verified_at' => now(),
            'dibuat_oleh' => 'Administrator',
            'date_created' => now(),
            'date_updated' => now(),
        ]);
    }
}
