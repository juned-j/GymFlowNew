<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'name' => 'super_admin',
        ], [
            'scope' => 'platform',
        ]);

        Role::updateOrCreate([
            'name' => 'owner',
        ], [
            'scope' => 'tenant',
        ]);

        Role::updateOrCreate([
            'name' => 'trainer',
        ], [
            'scope' => 'tenant',
        ]);

        Role::updateOrCreate([
            'name' => 'member',
        ], [
            'scope' => 'tenant',
        ]);
    }
}
