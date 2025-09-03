<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $existingUser = User::where('username', 'admin')->first();
        
        if (!$existingUser) {
            User::create([
                'nama' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@cashier-app.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'dibuat_oleh' => 'System',
                'date_created' => now(),
                'date_updated' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Username: admin');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}
