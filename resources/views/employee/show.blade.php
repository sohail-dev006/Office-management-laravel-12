<x-app-layout>
<div class="container-fluid bg-dark text-white min-vh-100">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 pt-3">

            <!-- Employee Profile Card -->
            <div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

                <!-- Header -->
                <div class="row g-0 bg-black rounded-top p-4 align-items-center">
                    <div class="col-md-2 d-flex justify-content-center">
                        @if($employee->profile_image)
                            <img src="{{ asset('storage/' . $employee->profile_image) }}"
                                class="rounded-circle img-fluid border border-3 border-light"
                                style="width:120px; height:120px;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center border border-3 border-light"
                                style="width:120px; height:120px; font-size:48px;">
                                {{ strtoupper(substr($employee->first_name,0,1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h3 class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                        <p class="mb-1">{{ $employee->designation }}</p>
                        <span class="badge {{ $employee->status == 'active' ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div class="row text-center g-3 mt-4 px-3">
                    <div class="col-md-4">
                        <div class="bg-secondary rounded-4 p-3">
                            <i class="bi bi-calendar-x display-6 text-danger"></i>
                            <h5 class="mt-2">{{ $leaves->count() }}</h5>
                            <small class="text-light">Leaves This Year</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-secondary rounded-4 p-3">
                            <i class="bi bi-calendar-check display-6 text-success"></i>
                            <h5 class="mt-2">{{ $attendance->count() }}</h5>
                            <small class="text-light">Days Present</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-secondary rounded-4 p-3">
                            <i class="bi bi-currency-dollar display-6 text-warning"></i>
                            <h5 class="mt-2">${{ number_format($salaries->sum('net_salary'),2) }}</h5>
                            <small class="text-light">Salary Paid</small>
                        </div>
                    </div>
                </div>

                <!-- Employee Info -->
                <div class="row mt-4 px-3">
                    <div class="col-12">
                        <div class="bg-secondary rounded-4 p-4">
                            <h5 class="fw-bold mb-3">Employee Information</h5>

                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $employee->email }}</div>
                                <div class="col-md-6 mb-2"><strong>Phone:</strong> {{ $employee->phone }}</div>
                                <div class="col-md-6 mb-2"><strong>Department:</strong> {{ $employee->department->name ?? 'N/A' }}</div>
                                <div class="col-md-6 mb-2"><strong>Joining Date:</strong> {{ \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') }}</div>
                                <div class="col-md-6 mb-2"><strong>Gender:</strong> {{ ucfirst($employee->gender) }}</div>
                                <div class="col-md-6 mb-2"><strong>DOB:</strong> {{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</div>
                                <div class="col-md-12"><strong>Address:</strong> {{ $employee->address }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-pills nav-fill mt-4 mx-3">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#leave">Leave</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#attendance">Attendance</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#salary">Salary</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#documents">Documents</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-3">

                    <!-- Leave -->
                    <div class="tab-pane fade show active" id="leave">
                        <table class="table table-dark table-bordered text-center">
                            <thead>
                                <tr><th>#</th><th>Start</th><th>End</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td><span class="badge bg-success">{{ ucfirst($leave->status) }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="4">No records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Attendance -->
                    <div class="tab-pane fade" id="attendance">
                        <table class="table table-dark table-bordered text-center">
                            <thead>
                                <tr><th>#</th><th>Date</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($attendance as $att)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $att->date }}</td>
                                    <td><span class="badge bg-success">{{ ucfirst($att->status) }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="3">No records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Salary -->
                    <div class="tab-pane fade" id="salary">
                        <table class="table table-dark table-bordered text-center">
                            <thead>
                                <tr><th>#</th><th>Month</th><th>Net Salary</th></tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::create()->month($salary->month)->format('F') }}</td>
                                    <td>${{ number_format($salary->net_salary,2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3">No records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Documents -->
                    <div class="tab-pane fade" id="documents">
                        <table class="table table-dark table-bordered text-center">
                            <thead>
                                <tr><th>#</th><th>Title</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $doc)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $doc->title }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3">No documents</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Footer -->
                <div class="card-footer bg-black d-flex">
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary me-2">Back</a>
                    <a href="{{ route('employee.edit',$employee->id) }}" class="btn btn-warning">Edit</a>
                </div>

            </div>
        </div>
    </div>
</div>
</x-app-layout>
