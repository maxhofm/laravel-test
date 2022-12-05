<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $clientRole = Role::create(['name' => 'Client']);

        Permission::create(['name' => 'all']);
        Permission::create(['name' => 'view-order-list']);
        Permission::create(['name' => 'proccess-order']);
        Permission::create(['name' => 'view-order-form']);
        Permission::create(['name' => 'create-order']);

        $adminRole->givePermissionTo([
            'all',
        ]);

        $managerRole->givePermissionTo([
            'view-order-list',
            'proccess-order',
        ]);

        $clientRole->givePermissionTo([
            'view-order-form',
            'create-order',
        ]);
    }
}
