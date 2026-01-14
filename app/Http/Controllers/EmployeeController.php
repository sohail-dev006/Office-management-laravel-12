<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\EmployeeDocument;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        $query = Employee::withCount('documents');
    } else {
        $query = Employee::where('user_id', $user->id)
                         ->withCount('documents');
    }

    // Simple search filter
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('first_name', 'like', "%{$request->search}%")
              ->orWhere('last_name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    $employees = $query->orderBy('first_name')->get();

    return view('employee.index', compact('employees'));
}


    public function create()
    {
        return view('employee.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        // Create User
        $user = User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $data['user_id'] = $user->id;
        $data['password'] = Hash::make($data['password']);

        

        if($request->hasFile('profile_image')){
            if(isset($employee) && $employee->profile_image){
                Storage::disk('public')->delete($employee->profile_image); // delete old image
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $employee = Employee::create($data);

        // Save multiple documents
        if ($request->filled('documents')) {
            foreach ($request->documents as $doc) {
                if (!isset($doc['file'])) continue;

                $file = $doc['file'];
                $path = $file->store('employee_documents', 'public');

                EmployeeDocument::create([
                    'employee_id' => $employee->id,
                    'title'       => $doc['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'type'        => $doc['type'],
                    'file_path'   => $path,
                    'file_name'   => $file->getClientOriginalName(),
                    'mime_type'   => $file->getClientMimeType(),
                    'file_size'   => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('employee.index')
                         ->with('success','Employee + Documents saved successfully.');
    }

    public function show(Employee $employee)
    {
        $currentYear = date('Y');

        $leaves = $employee->leaves()->whereYear('start_date', $currentYear)->orderBy('start_date','desc')->get();
        $attendance = $employee->attendances()->whereYear('date', $currentYear)->orderBy('date','desc')->get();
        $salaries = $employee->salaries()->where('year', $currentYear)->orderBy('month','asc')->get();
        $documents = $employee->documents;

        return view('employee.show', compact('employee','leaves','attendance','salaries','documents','currentYear'));
    }

    public function edit(Employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
{
    $data = $request->except(['documents', 'documents_existing', '_token', '_method']);

    // Update password if provided
    // Handle password: only hash & update if provided
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
        $employee->user()->update(['password' => $data['password']]);
    } else {
        // remove password from $data so it won't be updated as NULL
        unset($data['password']);
    }

    // Update profile image if uploaded
    if ($request->hasFile('profile_image')) {
        if ($employee->profile_image) {
            Storage::disk('public')->delete($employee->profile_image);
        }
        $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
    }

    $employee->update($data);

    // Update existing documents
    if ($request->filled('documents_existing')) {
        foreach ($request->documents_existing as $docId => $docData) {
            $document = EmployeeDocument::find($docId);
            if (!$document) continue;

            if (isset($docData['file'])) {
                Storage::disk('public')->delete($document->file_path);
                $path = $docData['file']->store('employee_documents', 'public');
                $document->file_path = $path;
                $document->file_name = $docData['file']->getClientOriginalName();
                $document->mime_type = $docData['file']->getClientMimeType();
                $document->file_size = $docData['file']->getSize();
            }

            $document->update([
                'title' => $docData['title'] ?? $document->title,
                'type' => $docData['type'] ?? $document->type,
            ]);
        }
    }

    // Add new documents
    if ($request->filled('documents')) {
        foreach ($request->documents as $doc) {
            if (!isset($doc['file'])) continue;
            $file = $doc['file'];
            $path = $file->store('employee_documents', 'public');

            EmployeeDocument::create([
                'employee_id' => $employee->id,
                'title' => $doc['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'type' => $doc['type'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    return redirect()->route('employee.index')->with('success', 'Employee updated successfully.');
}
    public function destroy(Employee $employee)
    {
        
        if (
            $employee->attendances()->exists() ||
            $employee->leaves()->exists() ||
            $employee->salaries()->exists()
        ) {
            return back()->withErrors([
                'delete_error' => 'Employee cannot be deleted because attendance, leave, or salary records exist.'
            ]);
        }

        
        foreach ($employee->documents as $doc) {
            $doc->delete();
        }

       
        $employee->delete();

        return back()->with('success', 'Employee deleted safely (soft delete).');
    }


}
