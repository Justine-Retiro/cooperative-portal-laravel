<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $general_manager = Permission::where('name', 'general_manager')->firstOrFail();
        $repository_access = Permission::where('name', 'book_keeper')->firstOrFail();
        $accountant_access = Permission::where('name', 'accountant')->firstOrFail();


        // Prepare the users data for insertion
        $users = [
            [
                'permission_id'  => $general_manager->id,
                'role_id'        => 1,
                'account_number' => 1234,
                'name'           => 'Admin General Manager',
                'email'          => 'admin1@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'permission_id'  => $repository_access->id,
                'role_id'        => 1,
                'account_number' => 12345,
                'name'           => 'Admin Book Keeper',
                'email'          => 'admin2@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'permission_id'  => $accountant_access->id,
                'role_id'        => 1,
                'account_number' => 123456,
                'name'           => 'Admin Accountant',
                'email'          => 'admin3@admin.com', 
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'permission_id'  => null,
                'role_id'        => 2,
                'account_number' => 2345,
                'name'           => 'Member',
                'email'          => 'member@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
        ];

        // Use insert for batch operation
        User::insert($users);
    }
}