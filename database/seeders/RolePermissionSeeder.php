<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Define roles with guard_name
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

        // Assign engineer role to specific emails
        $engineerEmails = array_filter(array_map('trim', explode(',', config('app.engineer_emails', ''))));
        foreach ($engineerEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user && !$user->hasRole('engineer')) {
                $user->assignRole('engineer');
                $this->command->info("Assigned 'engineer' to {$email}");
            }
        }

        // Assign admin role to admin emails (if any)
        $adminEmails = array_filter(array_map('trim', explode(',', config('app.admin_emails', ''))));
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user && !$user->hasRole('engineer') && !$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->command->info("Assigned 'admin' to {$email}");
            }
        }
    }
}