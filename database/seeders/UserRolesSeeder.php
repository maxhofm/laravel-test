<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('name', 'admin')->first();
        $admin->assignRole(User::ADMIN_ROLE);

        $manager = User::where('name', 'manager')->first();
        $manager->assignRole(User::MANAGER_ROLE);

        $client = User::where('name', 'client1')->first();
        $client->assignRole(User::ClENT_ROLE);
    }
}
