<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Platform Admin',
            'email' => 'platform@gymflow.com',
            'password' => bcrypt('password'),
        ]);

        \App\Models\UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => null,
            'role' => 'super_admin',
        ]);
    }
}
