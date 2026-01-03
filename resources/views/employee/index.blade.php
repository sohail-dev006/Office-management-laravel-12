<x-app-layout>
<div class="container-fluid">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 pt-2">

            <a href="{{ route('employee.create') }}" class="btn btn-primary mb-3">
                 Add Employee
            </a>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-white">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Designation</th>
                            <!-- <th>Phone</th> -->
                            <th>Address</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Join Date</th>
                            <th>Job Type</th>
                            <th>City</th>
                            <th>Salary</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($employees as $emp)
                        <tr class="text-white">
                            <td>{{ $emp->id }}</td>
                            <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                            <td>{{ $emp->email }}</td>
                            <td>{{ $emp->designation }}</td>
                            <!-- <td>{{ $emp->phone }}</td> -->
                            <td>{{ $emp->address }}</td>
                            <td>{{ ucfirst($emp->gender) }}</td>
                            <td>{{ $emp->dob }}</td>
                            <td>{{ $emp->join_date }}</td>
                            <td>{{ $emp->job_type }}</td>
                            <td>{{ $emp->city }}</td>
                            <td>{{ number_format($emp->salary, 2) }}</td>
                            <td>{{ $emp->age }}</td>
                            <td>
                                <span class="badge {{ $emp->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($emp->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('employee.edit', $emp->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <form action="{{ route('employee.destroy', $emp->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger mb-1" onclick="return confirm('Delete this employee?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
