<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'general_manager',
                'guard_name' => 'Admin'
            ],
            [
                'name' => 'accountant',
                'guard_name' => 'Admin'
            ],
            [
                'name' => 'book_keeper',
                'guard_name' => 'Admin'
            ],
        ];

        Permission::insert($permissions);
    }
}
