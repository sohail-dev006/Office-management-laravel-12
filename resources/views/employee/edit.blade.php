<x-app-layout>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Edit Employee</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employee.update', $employee->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- First & Last Name --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}">
                            </div>
                        </div>

                        {{-- Email & Password --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email', $employee->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password (leave blank to keep current)</label>
                                <input name="password" type="password" class="form-control">
                            </div>
                        </div>

                        {{-- Designation & Salary --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Designation</label>
                                <input name="designation" class="form-control" value="{{ old('designation', $employee->designation) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Salary</label>
                                <input name="salary" type="number" class="form-control" value="{{ old('salary', $employee->salary) }}">
                            </div>
                        </div>

                        {{-- Phone & Address --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input name="phone" type="text" class="form-control" value="{{ old('phone', $employee->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input name="address" type="text" class="form-control" value="{{ old('address', $employee->address) }}">
                            </div>
                        </div>

                        {{-- Gender, DOB & Age --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="male" {{ $employee->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $employee->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DOB</label>
                                <input name="dob" type="date" id="dob" class="form-control" value="{{ $employee->dob }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input name="age" type="number" id="age" class="form-control" value="{{ $employee->age }}" readonly>
                            </div>
                        </div>

                        {{-- Job Type, City, Join Date --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Job Type</label>
                                <input name="job_type" class="form-control" value="{{ $employee->job_type }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input name="city" class="form-control" value="{{ $employee->city }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Join Date</label>
                                <input name="join_date" type="date" class="form-control" value="{{ $employee->join_date }}">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Profile Image --}}
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control">
                            @if($employee->profile_image)
                                <img src="{{ asset('storage/' . $employee->profile_image) }}" width="100" class="mt-2 rounded">
                            @endif
                        </div>

                        {{-- Documents --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee Documents</label>
                            <div id="document-wrapper">

                                {{-- Existing documents --}}
                                @foreach($employee->documents as $i => $doc)
                                <div class="row document-row mb-2">
                                    <div class="col-md-3">
                                        <input type="text" name="documents_existing[{{ $doc->id }}][title]" class="form-control" value="{{ $doc->title }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="documents_existing[{{ $doc->id }}][type]" class="form-select">
                                            <option value="cv" {{ $doc->type=='cv'?'selected':'' }}>CV</option>
                                            <option value="cnic" {{ $doc->type=='cnic'?'selected':'' }}>CNIC</option>
                                            <option value="degree" {{ $doc->type=='degree'?'selected':'' }}>Degree</option>
                                            <option value="contract" {{ $doc->type=='contract'?'selected':'' }}>Contract</option>
                                            <option value="other" {{ $doc->type=='other'?'selected':'' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-info w-100">View File</a>
                                        <input type="file" name="documents_existing[{{ $doc->id }}][file]" class="form-control mt-1">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-document">X</button>
                                    </div>
                                </div>
                                @endforeach

                                {{-- New documents --}}
                                <div class="row document-row mb-2">
                                    <div class="col-md-3">
                                        <input type="text" name="documents[0][title]" class="form-control" placeholder="Document Title">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="documents[0][type]" class="form-select">
                                            <option value="">Select Type</option>
                                            <option value="cv">CV</option>
                                            <option value="cnic">CNIC</option>
                                            <option value="degree">Degree</option>
                                            <option value="contract">Contract</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="file" name="documents[0][file]" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-document d-none">X</button>
                                    </div>
                                </div>

                            </div>

                            <button type="button" id="add-document" class="btn btn-sm btn-secondary mt-2">+ Add Another Document</button>
                        </div>

                        <button type="submit" class="btn btn-success">Update Employee</button>
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary ms-2">Cancel</a>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // DOB -> Age
    const dobInput = document.getElementById('dob');
    const ageInput = document.getElementById('age');
    if(dobInput){
        dobInput.addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if(m<0 || (m===0 && today.getDate()<dob.getDate())) age--;
            ageInput.value = age;
        });
    }

    // Add/remove document rows
    let docIndex = {{ $employee->documents->count() }};
    document.getElementById('add-document').addEventListener('click', function(){
        let wrapper = document.getElementById('document-wrapper');
        let html = `
        <div class="row document-row mb-2">
            <div class="col-md-3">
                <input type="text" name="documents[${docIndex}][title]" class="form-control" placeholder="Document Title">
            </div>
            <div class="col-md-3">
                <select name="documents[${docIndex}][type]" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="cv">CV</option>
                    <option value="cnic">CNIC</option>
                    <option value="degree">Degree</option>
                    <option value="contract">Contract</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="file" name="documents[${docIndex}][file]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-document">X</button>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
        docIndex++;
    });

    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-document')){
            e.target.closest('.document-row').remove();
        }
    });
</script>
</x-app-layout>
