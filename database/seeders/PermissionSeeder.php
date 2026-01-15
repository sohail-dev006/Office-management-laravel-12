<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
   public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            ['module'=>'Dashboard','name'=>'view-dashboard','guard_name'=>'web'],
            ['module'=>'Dashboard','name'=>'dashboard','guard_name'=>'web'],
            ['module'=>'Employee','name'=>'add-employee','guard_name'=>'web'],
            ['module'=>'Employee','name'=>'employee-list','guard_name'=>'web'],
            ['module'=>'Employee','name'=>'view-employee','guard_name'=>'web'],
            ['module'=>'Employee','name'=>'edit-employee','guard_name'=>'web'],
            ['module'=>'Employee','name'=>'delete-employee','guard_name'=>'web'],
            ['module'=>'Attendance','name'=>'add-attendance','guard_name'=>'web'],
            ['module'=>'Attendance','name'=>'attendance-list','guard_name'=>'web'],
            ['module'=>'Leave','name'=>'add-leave','guard_name'=>'web'],
            ['module'=>'Leave','name'=>'leave-list','guard_name'=>'web'],
            ['module'=>'Leave','name'=>'edit-leave','guard_name'=>'web'],
            ['module'=>'Leave','name'=>'delete-leave','guard_name'=>'web'],
            ['module'=>'Salary','name'=>'add-salary','guard_name'=>'web'],
            ['module'=>'Salary','name'=>'salary-list','guard_name'=>'web'],
            ['module'=>'Salary','name'=>'edit-salary','guard_name'=>'web'],
            ['module'=>'Salary','name'=>'delete-salary','guard_name'=>'web'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['module' => $perm['module']]
            );
        }

        // Assign all permissions to admin
        $adminRole->syncPermissions(Permission::all());
        $userRole->syncPermissions(Permission::where('name','dashboard')->get());
    }
}
