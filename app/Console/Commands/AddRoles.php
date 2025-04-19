<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the required roles and admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding remaining roles...');
        
        // Admin role
        if (!Role::where('name', 'admin')->exists()) {
            $adminRole = Role::create(['name' => 'admin']);
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
            $this->info('Admin role created.');
        } else {
            $this->info('Admin role already exists.');
        }

        // Sub-admin role
        if (!Role::where('name', 'sub-admin')->exists()) {
            $subAdminRole = Role::create(['name' => 'sub-admin']);
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
            $this->info('Sub-admin role created.');
        } else {
            $this->info('Sub-admin role already exists.');
        }

        // User role
        if (!Role::where('name', 'user')->exists()) {
            $userRole = Role::create(['name' => 'user']);
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
            $this->info('User role created.');
        } else {
            $this->info('User role already exists.');
        }

        // Create a super admin user if not exists
        if (!User::where('email', 'superadmin@example.com')->exists()) {
            $user = new User();
            $user->name = 'Super Admin';
            $user->email = 'superadmin@example.com';
            $user->password = Hash::make('password');
            $user->email_verified_at = now();
            $user->role = 'super-admin';
            $user->save();
            $user->assignRole('super-admin');
            $this->info('Super admin user created.');
        } else {
            $this->info('Super admin user already exists.');
        }

        $this->info('All roles and admin user have been added successfully!');
    }
}