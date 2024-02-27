<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleHasPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Example: Assign all permissions to the 'Admin' role
        $adminRole = Role::where('title', 'Admin')->first();
        $permissions = Permission::all();

        if ($adminRole) {
            $adminRole->permissions()->sync($permissions->pluck('id'));
        }
    }
}