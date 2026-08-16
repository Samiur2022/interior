<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the default admin user.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Create Admin User
        |--------------------------------------------------------------------------
        |
        | updateOrCreate() prevents duplicate admin users
        | when the seeder is executed more than once.
        |
        */

        User::updateOrCreate(

            // Find user by email
            [
                'email' => 'admin@example.com',
            ],

            // Create/update these values
            [
                'name' => 'Admin',

                'password' => bcrypt('123456'),
            ]
        );
    }
}