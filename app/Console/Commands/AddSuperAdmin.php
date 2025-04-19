<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:add-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add super admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding super admin user...');
        
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

        $this->info('Super admin user has been added successfully!');
    }
}