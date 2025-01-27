<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            BookingStatusSeeder::class,
            UserRoleSeeder::class
        ]);
    }
}
