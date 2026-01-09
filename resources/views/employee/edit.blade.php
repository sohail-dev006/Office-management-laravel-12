<x-app-layout>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Edit Employee</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employee.update', $employee->id) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name" value="{{ old('first_name', $employee->first_name) }}">
                                @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name" value="{{ old('last_name', $employee->last_name) }}">
                                @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', $employee->email) }}">
                                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                                <input name="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="designation" class="form-label">Designation</label>
                                <input name="designation" id="designation" class="form-control @error('designation') is-invalid @enderror" placeholder="Designation" value="{{ old('designation', $employee->designation) }}">

                                @error('designation')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="salary" class="form-label">Salary</label>
                                <input name="salary" id="salary" type="number" class="form-control @error('salary') is-invalid @enderror" placeholder="Salary" value="{{ old('salary', $employee->salary) }}">
                                @error('salary') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="phone" class="form-label">Phone</label>
                                <input name="phone" id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone" value="{{ old('phone', $employee->phone) }}">
                                @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="address" class="form-label">Address</label>
                                <input name="address" id="address" type="text" class="form-control @error('address') is-invalid @enderror" placeholder="Address" value="{{ old('address', $employee->address) }}">
                                @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input name="dob" id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $employee->dob) }}">
                                @error('dob') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="join_date" class="form-label">Join Date</label>
                                <input name="join_date" id="join_date" type="date" class="form-control @error('join_date') is-invalid @enderror" value="{{ old('join_date', $employee->join_date) }}">
                                @error('join_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="job_type" class="form-label">Job Type</label>
                                <input name="job_type" id="job_type" type="text" class="form-control @error('job_type') is-invalid @enderror" placeholder="Job Type" value="{{ old('job_type', $employee->job_type) }}">
                                @error('job_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input name="city" id="city" type="text" class="form-control @error('city') is-invalid @enderror" placeholder="City" value="{{ old('city', $employee->city) }}">
                                @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="age" class="form-label">Age</label>
                                <input name="age" id="age" type="number" class="form-control @error('age') is-invalid @enderror" placeholder="Age" value="{{ old('age', $employee->age) }}">
                                @error('age') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Update Employee</button>
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
