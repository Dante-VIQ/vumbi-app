<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Create roles with explicit slug
        $roles = [
            ['name' => 'user', 'guard_name' => 'web', 'slug' => 'user'],
            ['name' => 'admin', 'guard_name' => 'web', 'slug' => 'admin'],
            ['name' => 'engineer', 'guard_name' => 'web', 'slug' => 'engineer'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                ['slug' => $roleData['slug']]
            );
        }

        // Assign engineer role to specific admin emails
        $engineerEmails = array_filter(array_map('trim', explode(',', config('app.engineer_emails', ''))));
        foreach ($engineerEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['engineer']);
                $this->command->info("Assigned 'engineer' role to {$email}");
            } else {
                $this->command->warn("User with email {$email} not found. Create the user first or run this seeder after user creation.");
            }
        }

        // Optional: assign admin role to other emails
        $adminEmails = array_filter(array_map('trim', explode(',', config('app.admin_emails', ''))));
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user && !$user->hasRole('engineer')) {
                $user->assignRole('admin');
                $this->command->info("Assigned 'admin' role to {$email}");
            }
        }
    }
}