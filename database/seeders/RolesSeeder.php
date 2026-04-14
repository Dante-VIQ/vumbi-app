<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

         public function run()
    {
        $roles = [
            [
                'name' => 'Master Administrator',
                'slug' => 'master',
                'description' => 'Full system access with all privileges'
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System administrator with elevated privileges'
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular application user'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }

}