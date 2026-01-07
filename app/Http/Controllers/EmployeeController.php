<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResources;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        return view('employee.create');
    }

   public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        // $user = User::create([
        //     'name' => $data['first_name'].' '.$data['last_name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);

        // $user->employee('Employee');

        // $data['user_id'] = $user->id;
        $data['password'] = Hash::make($data['password']);

        Employee::create($data);

        return redirect()->route('employee.index');
    }



    public function edit(Employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->except('password'));

        return redirect()->route('employee.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back();
    }
}
