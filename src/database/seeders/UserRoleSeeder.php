<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        if(!UserRole::where('role_id', 1)->exists()){
            UserRole::create([
                'role_id' => UserRole::ADMINISTRATOR,
                'type'=> 'Administrator',
            ]);
        }
        if (!UserRole::where('role_id', 2)->exists()) {
            UserRole::create([
                'role_id' => UserRole::CUSTOMER,
                'type' => 'Customer',
            ]);
        }

        if (!UserRole::where('role_id', 3)->exists()) {
            UserRole::create([
                'role_id' => UserRole::LESSOR,
                'type' => 'Lessor',
            ]);
        }
    }
}
