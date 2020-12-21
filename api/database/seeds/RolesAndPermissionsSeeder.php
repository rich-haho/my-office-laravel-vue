<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage clients', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage products', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage bookings', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage sites', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage partners', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage services', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage my bookings only', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage my products only', 'guard_name' => 'admin']);

        // create a role and assign permissions
        $role = Role::create(['name' => 'Administrateur', 'guard_name' => 'admin']);
        $role->givePermissionTo('manage clients');
        $role->givePermissionTo('manage products');
        $role->givePermissionTo('manage sites');
        $role->givePermissionTo('manage partners');
        $role->givePermissionTo('manage services');

        // create a role and assign permissions
        Role::create(['name' => 'Concierge', 'guard_name' => 'admin'])
            ->givePermissionTo('manage bookings');

        // create a role and assign permissions
        Role::create(['name' => 'Partenaire', 'guard_name' => 'admin'])
            ->givePermissionTo('manage my bookings only')
            ->givePermissionTo('manage my products only');

        //assign role to Admin users
        foreach(Admin::get() as $admin) {
            $admin->assignRole('Administrateur');
        }
    }
}
