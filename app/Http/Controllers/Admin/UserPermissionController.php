<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $permissions = Permission::all()->groupBy('module'); // Group by module
        $userRole = $user->roles->pluck('name')->first(); // First role
        $userPermissions = $user->getDirectPermissions()->pluck('name')->toArray(); // Only direct permissions

        return view('admin.users.permissions', compact(
            'user',
            'permissions',
            'userRole',
            'userPermissions'
        ));
    }

    // Save roles & permissions
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|max:50',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $roleName = $request->role;

        if (strtolower($roleName) === 'admin') {


            // Super Admin gets all permissions automatically
            $role = Role::firstOrCreate(['name' => 'admin']);
            $user->syncRoles([$role->name]);

            
            $user->syncPermissions(Permission::all()); // assign all permissions
        } else {
            // Create or get the role typed by admin
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Assign role to user
            $user->syncRoles([$role->name]);

            // Assign only the selected permissions
            $user->syncPermissions($request->permissions ?? []);
        }

        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated successfully');
    }

    public function create()
    {
        $users = User::all(); 
        $roles = Role::all(); 
        $permissions = Permission::all()->groupBy('module'); 

        return view('admin.users.create', compact('users', 'roles', 'permissions'));
    }


   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|max:50',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $role = Role::firstOrCreate(['name' => $request->role]);
        $user->assignRole($role);

        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }


}
