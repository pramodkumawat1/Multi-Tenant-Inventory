<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\StoreDetail;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $adminRole = Role::where('title', 'Admin')->pluck('id')->toArray();
        $admin->roles()->sync($adminRole);

        $store = User::firstOrCreate(
            ['email' => 'store1@yopmail.com'],
            ['name' => 'Store 1', 'password' => bcrypt('store1')]
        );

        StoreDetail::firstOrCreate(['user_id' => $store->id, 'name' => 'Vendor Store 1']);

        $storeRole = Role::where('title', 'Store')->pluck('id')->toArray();
        $store->roles()->sync($storeRole);
    }
}
