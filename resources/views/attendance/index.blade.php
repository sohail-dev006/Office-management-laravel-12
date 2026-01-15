<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex text-white justify-content-between mb-3">
                    <h4 class="fs-4">Attendance</h4>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Add Attendance</a>
                </div>

                <!-- Search Input -->
                <div class="mb-3">
                    <input type="text" id="attendanceSearch" class="form-control" placeholder="Search by date, employee, or status...">
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="attendanceTable">
                        <thead class="table-dark text-white">
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $att)
                            <tr class="text-white">
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->employee->first_name }} {{ $att->employee->last_name }}</td>
                                <td>{{ $att->check_in ?? '-' }}</td>
                                <td>{{ $att->check_out ?? '-' }}</td>
                                <td>{{ $att->working_hours ?? '-' }}</td>
                                <td>{{ ucfirst($att->status ?? 'Absent') }}</td>
                                <td>
                                    @if(!$att->check_in)
                                    <form method="POST" action="{{ url('attendance/checkin/'.$att->employee_id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Check In</button>
                                    </form>
                                    @endif

                                    @if($att->check_in && !$att->check_out)
                                    <form method="POST" action="{{ url('attendance/checkout/'.$att->employee_id) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">Check Out</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Fixed JS Filter -->
    <script>
        const searchInput = document.getElementById('attendanceSearch');
        const tableRows = document.querySelectorAll('#attendanceTable tbody tr');

        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();

            tableRows.forEach(row => {
                let cells = row.querySelectorAll('td');
                let match = false;

                cells.forEach(cell => {
                    if(cell.innerText.toLowerCase().includes(filter)){
                        match = true;
                    }
                });

                row.style.display = match ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
