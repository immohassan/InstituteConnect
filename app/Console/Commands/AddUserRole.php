<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:add-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the user role and super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding user role...');
        
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

        $this->info('User role has been added successfully!');
    }
}