<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'management user']);
        Permission::create(['name' => 'management team']);

        // create roles and assign existing permissions
        $role = Role::create(['name' => 'super_admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'user_management_admin']);
        $role->givePermissionTo('management user');

        $role = Role::create(['name' => 'team_management_admin']);
        $role->givePermissionTo('management team');
    }
}
