<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            'manage users',
            'manage roles',
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'manage forms',
            'manage products',
            'manage orders',
            'manage invoices',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Buat roles dan assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'manage forms',
            'manage products',
            'manage orders',
            'manage invoices',
        ]);
    }
}