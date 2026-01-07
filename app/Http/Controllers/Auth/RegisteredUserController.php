<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        

        event(new Registered($user));
        // if (strtolower($request->email) === 'admin@example.com') {
        //     $user->assignRole('super-admin');
        //     $user->syncPermissions(\Spatie\Permission\Models\Permission::all());
        // } else {
        //     $user->assignRole('user');
        // }

            $role = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
            $user->assignRole($role);


        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
