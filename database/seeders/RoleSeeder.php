<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'manage_societies',
            'manage_announcements',
            'manage_results',
            'manage_attendance',
            
            // Sub-admin permissions
            'manage_society_announcements',
            'manage_society_members',
            
            // User permissions
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',
            'comment_on_posts',
            'like_posts',
            'send_chat_requests',
            'access_resources',
            'view_attendance',
            'view_results',
        ];

        foreach ($permissions as $permission) {
            // Create permission if it doesn't exist
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Create roles and assign permissions
        // Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Admin (Faculty)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'manage_announcements',
            'manage_results',
            'manage_attendance',
            'manage_societies',
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',
            'comment_on_posts',
            'like_posts',
            'send_chat_requests',
            'access_resources',
            'view_attendance',
            'view_results',
        ]);

        // Sub Admin (Society President/Convenor)
        $subAdminRole = Role::firstOrCreate(['name' => 'sub-admin']);
        $subAdminRole->syncPermissions([
            'manage_society_announcements',
            'manage_society_members',
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',
            'comment_on_posts',
            'like_posts',
            'send_chat_requests',
            'access_resources',
            'view_attendance',
            'view_results',
        ]);

        // User (Student)
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'create_posts',
            'edit_own_posts',
            'delete_own_posts',
            'comment_on_posts',
            'like_posts',
            'send_chat_requests',
            'access_resources',
            'view_attendance',
            'view_results',
        ]);

        // Create a super admin user if not exists
        $user = User::firstOrNew(['email' => 'superadmin@example.com']);
        if (!$user->exists) {
            $user->name = 'Super Admin';
            $user->password = Hash::make('password');
            $user->email_verified_at = now();
            $user->save();
        }
        $user->assignRole('super-admin');
    }
}
