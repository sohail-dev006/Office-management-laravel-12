<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $request->validate([
            'documents.*.file' => 'required|file|max:5120',
            'documents.*.type' => 'required'
        ]);

        foreach ($request->documents as $doc) {
            $file = $doc['file'];
            $path = $file->store('employee_documents','public');

            $employee->documents()->create([
                'title' => $doc['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'type'  => $doc['type'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        return back()->with('success','Documents uploaded successfully.');
    }

public function update(Request $request, Employee $employee)
{
    // Update employee info
    $data = $request->except(['documents', 'documents_existing', '_token', '_method']);
    if($request->filled('password')){
        $data['password'] = bcrypt($request->password);
    }
    $employee->update($data);

    // Update existing documents
    if($request->has('documents_existing')){
        foreach($request->documents_existing as $docId => $docData){
            $document = EmployeeDocument::find($docId);
            if(!$document) continue;

            if(isset($docData['file'])){
                Storage::disk('public')->delete($document->file_path);
                $path = $docData['file']->store('employee_documents', 'public');
                $document->file_path = $path;
                $document->file_name = $docData['file']->getClientOriginalName();
                $document->mime_type = $docData['file']->getClientMimeType();
                $document->file_size = $docData['file']->getSize();
            }

            $document->update([
                'title' => $docData['title'],
                'type' => $docData['type'],
            ]);
        }
    }

    // Add new documents
    if($request->has('documents')){
        foreach($request->documents as $doc){
            if(!isset($doc['file'])) continue; // skip if no file

            $file = $doc['file'];
            $path = $file->store('employee_documents','public');
            $employee->documents()->create([
                'title' => $doc['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'type' => $doc['type'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Employee updated successfully.');
}



    public function destroy(EmployeeDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success','Document deleted successfully.');
    }
}
