<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'view products',
            'create product',
            'edit product',
            'delete product',
            'view orders',
            'create order',
            'update order',
            'delete order',
            'view users',
            'create user',
            'edit user',
            'delete user',
            'process payment',
            'view payment history',
            'access dashboard',
            'manage settings',
            'view reports',
            'manage roles',
            'manage permissions'
        ];

        foreach ($permissions as $permissionName) {
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo(['view products', 'create order', 'view orders']);

        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123')
        ]);

        $adminUser->assignRole('Admin');

        $User = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password123')
        ]);

        $User->assignRole('User');
    }
}
