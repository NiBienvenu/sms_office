<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Routing\Route;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            // 'name' => 'Test User',
            'email' => 'admin@school.com',
            'password' => bcrypt('12345678'),
        ]);

        $roles = ['admin', 'Secretaires'];
        foreach ($roles as $role) {
            Role::crete([
                'name' => $role,
            ]);
        }

    }
}
