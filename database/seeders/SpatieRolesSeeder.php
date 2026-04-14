<?php

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class SpatieRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = ['user', 'admin', 'engineer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}