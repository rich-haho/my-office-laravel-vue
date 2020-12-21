<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProductionRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage my products only', 'guard_name' => 'admin']);

        $role = Role::findByName('Partenaire', 'admin');
        $role->givePermissionTo('manage my products only');
    }
}
