<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Create, edit, and delete users', 'category' => 'user_management'],
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'View user list and details', 'category' => 'user_management'],
            
            // Role Management
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'description' => 'Create, edit, and delete roles', 'category' => 'role_management'],
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'description' => 'View role list and details', 'category' => 'role_management'],
            
            // Product Management
            ['name' => 'manage_products', 'display_name' => 'Manage Products', 'description' => 'Create, edit, and delete products', 'category' => 'product_management'],
            ['name' => 'view_products', 'display_name' => 'View Products', 'description' => 'View product list and details', 'category' => 'product_management'],
            
            // Sales Management
            ['name' => 'manage_sales', 'display_name' => 'Manage Sales', 'description' => 'Create, edit, and delete sales', 'category' => 'sales_management'],
            ['name' => 'view_sales', 'display_name' => 'View Sales', 'description' => 'View sales list and details', 'category' => 'sales_management'],
            ['name' => 'export_sales', 'display_name' => 'Export Sales', 'description' => 'Export sales data', 'category' => 'sales_management'],
            
            // Cashier Operations
            ['name' => 'process_transactions', 'display_name' => 'Process Transactions', 'description' => 'Process cashier transactions', 'category' => 'cashier_operations'],
            ['name' => 'view_cashier', 'display_name' => 'View Cashier', 'description' => 'Access cashier interface', 'category' => 'cashier_operations'],
            ['name' => 'manage_pending_sales', 'display_name' => 'Manage Pending Sales', 'description' => 'Manage pending sales transactions', 'category' => 'cashier_operations'],
            
            // Payment Methods
            ['name' => 'manage_payment_methods', 'display_name' => 'Manage Payment Methods', 'description' => 'Create, edit, and delete payment methods', 'category' => 'payment_management'],
            ['name' => 'view_payment_methods', 'display_name' => 'View Payment Methods', 'description' => 'View payment method list', 'category' => 'payment_management'],
            
            // Dashboard & Reports
            ['name' => 'view_dashboard', 'display_name' => 'View Dashboard', 'description' => 'Access dashboard and statistics', 'category' => 'dashboard'],
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'View various reports', 'category' => 'reports'],
            
            // System Administration
            ['name' => 'system_admin', 'display_name' => 'System Administration', 'description' => 'Full system administration access', 'category' => 'system_admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create Roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => [
                    'manage_users', 'view_users', 'manage_roles', 'view_roles',
                    'manage_products', 'view_products', 'manage_sales', 'view_sales', 'export_sales',
                    'process_transactions', 'view_cashier', 'manage_pending_sales',
                    'manage_payment_methods', 'view_payment_methods',
                    'view_dashboard', 'view_reports', 'system_admin'
                ]
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Cashier',
                'description' => 'Cashier operations and basic access',
                'permissions' => [
                    'process_transactions', 'view_cashier', 'manage_pending_sales',
                    'view_products', 'view_dashboard'
                ]
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Management access with sales and product oversight',
                'permissions' => [
                    'view_users', 'view_products', 'manage_products',
                    'view_sales', 'export_sales', 'view_cashier',
                    'view_payment_methods', 'view_dashboard', 'view_reports'
                ]
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisory access with limited management capabilities',
                'permissions' => [
                    'view_users', 'view_products', 'view_sales',
                    'process_transactions', 'view_cashier', 'manage_pending_sales',
                    'view_dashboard', 'view_reports'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
            
            // Assign permissions to role
            $permissionModels = Permission::whereIn('name', $permissions)->get();
            $role->permissions()->sync($permissionModels->pluck('id'));
        }

        $this->command->info('Roles and permissions created successfully!');
    }
}