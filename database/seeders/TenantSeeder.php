<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\UserTenantRole;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Platform Admin User
        $user = User::create([
            'name' => 'GymFlow Admin',
            'email' => 'admin@gymflow.com',
            'password' => Hash::make('password'),
            'phone' => '9999999999',
            'status' => 'active',
            'is_super_admin' => true, // optional but recommended
        ]);

        // 2. Create Tenant
        DB::table('tenants')->insert([
            'id' => 1,
            'name' => 'GymFlow Demo',
            'slug' => 'gymflow-demo',
            'owner_user_id' => $user->id,
            'email' => 'admin@gymflow.com',
            'phone' => '9999999999',
            'country' => 'India',
            'currency' => 'INR',
            'currency_symbol' => '₹',
            'timezone' => 'Asia/Kolkata',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Get Super Admin Role
        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            throw new \Exception("super_admin role not found. Please seed roles first.");
        }

        // 4. Assign Role in pivot table (IMPORTANT PART)
        UserTenantRole::create([
            'user_id' => $user->id,
            'tenant_id' => null,        // platform level user
            'branch_id' => null,
            'role_id' => $role->id,     // ✅ correct FK usage
        ]);
    }
}
