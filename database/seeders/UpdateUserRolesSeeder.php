<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UpdateUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $cashierRole = Role::where('name', 'cashier')->first();

        if (!$adminRole || !$cashierRole) {
            $this->command->error('Roles not found. Please run RolePermissionSeeder first.');
            return;
        }

        // Update existing users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $user) {
            $user->update(['role_id' => $adminRole->id]);
            $this->command->info("Updated user {$user->username} to admin role");
        }

        $cashierUsers = User::where('role', 'cashier')->get();
        foreach ($cashierUsers as $user) {
            $user->update(['role_id' => $cashierRole->id]);
            $this->command->info("Updated user {$user->username} to cashier role");
        }

        $this->command->info('User roles updated successfully!');
    }
}