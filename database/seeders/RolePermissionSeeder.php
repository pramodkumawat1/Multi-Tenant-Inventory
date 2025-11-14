<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user_management_access',
            'user_access',
            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'product_access',
            'product_create',
            'product_edit',
            'product_show',
            'product_delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['title' => $permission]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['title' => 'Admin']);
        $store = Role::firstOrCreate(['title' => 'Store']);

        // Assign permissions to roles
        $adminPermissionIds = Permission::whereIn('title', ['user_management_access', 'user_access', 'user_create', 'user_edit', 'user_show', 'user_delete'])->pluck('id')->toArray();
        $admin->permissions()->sync($adminPermissionIds);
        $storePermissionIds = Permission::whereIn('title', ['product_access', 'product_create', 'product_edit', 'product_show', 'product_delete'])->pluck('id')->toArray();
        $store->permissions()->sync($storePermissionIds);
    }
}
