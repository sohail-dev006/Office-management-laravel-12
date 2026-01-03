<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'employee.view',
            'employee.create',
            'employee.edit',
            'employee.delete',

            'leave.view',
            'leave.create',
            'leave.approve',
            'leave.reject',
            'leave.view.own',

            'salary.view',
            'salary.create',
            'salary.edit',
            'salary.delete',
            'salary.view.own',

            'attendance.view.own',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());

        $employee->givePermissionTo([
            'leave.create',
            'leave.view.own',
            'salary.view.own',
            'attendance.view.own',
        ]);
    }
}
