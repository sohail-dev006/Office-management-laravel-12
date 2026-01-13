<x-app-layout>
<div class="container-fluid py-3 bg-dark min-vh-100 text-white">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fs-3 text-white">Employee List</h4>

                @can('add-employee')
                <a href="{{ route('employee.create') }}" class="btn btn-success">
                    + Add Employee
                </a>
                @endcan
            </div>

                @if ($errors->has('delete_error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('delete_error') }}
                    </div>
                @endif
            {{-- SEARCH FORM --}}
            <form method="GET" action="{{ route('employee.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Name or Email">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            {{-- EMPLOYEE CARDS --}}
            <div class="row g-3">
                @forelse($employees as $emp)
                <div class="col-md-6 col-xl-4">
                    <div class="card bg-dark text-white shadow h-100">
                        <div class="card-body">

                            <h5 class="border-bottom pb-2 mb-3">{{ $emp->first_name }} {{ $emp->last_name }}</h5>

                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Name:</div>
                                <div class="col-7">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Department:</div>
                                <div class="col-7">{{ $emp->department ?? 'N/A' }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Designation:</div>
                                <div class="col-7">{{ $emp->designation }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Email:</div>
                                <div class="col-7">{{ $emp->email }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Gender:</div>
                                <div class="col-7">{{ ucfirst($emp->gender) }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">DOB:</div>
                                <div class="col-7">{{ $emp->dob }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Join Date:</div>
                                <div class="col-7">{{ $emp->join_date }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Job Type:</div>
                                <div class="col-7">{{ $emp->job_type }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">City:</div>
                                <div class="col-7">{{ $emp->city }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Salary:</div>
                                <div class="col-7">{{ number_format($emp->salary, 2) }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-5 fw-bold text-white">Age:</div>
                                <div class="col-7">{{ $emp->age }}</div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-5 fw-bold text-white">Status:</div>
                                <div class="col-7">
                                    <span class="badge {{ $emp->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($emp->status) }}
                                    </span>
                                </div>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="card-footer bg-transparent border-top d-flex gap-1 flex-wrap">
                            @can('view-employee')
                            <a href="{{ route('employee.show', $emp->id) }}" class="btn btn-sm btn-info flex-fill">View</a>
                            @endcan
                            @can('edit-employee')
                            <a href="{{ route('employee.edit', $emp->id) }}" class="btn btn-sm btn-warning flex-fill">Edit</a>
                            @endcan
                            @can('delete-employee')
                            <form action="{{ route('employee.destroy', $emp->id) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger w-100" onclick="return confirm('Delete this employee?')">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-secondary">
                    No employees found.
                </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
</x-app-layout>
