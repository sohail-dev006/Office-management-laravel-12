<x-app-layout>
    <div @class(['container-fluid'])>
        <div @class(['row'])>
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div @class(['col-md-9', 'col-lg-10', 'p-4'])>
                <div @class(['d-flex', 'text-white', 'justify-content-between', 'mb-3'])>
                    <h4 @class(['fs-4'])>Attendance</h4>
                    <a href="{{ route('attendance.create') }}" @class(['btn', 'btn-primary'])>Add Attendance</a>
                </div>

                <!-- Table -->
                <div @class(['table-responsive'])>
                    {{-- <table @class(['table', 'table-bordered', 'table-striped'])>
                        <thead @class(['table-dark'])>
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Check In</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $att)
                                <tr @class(['text-white'])>
                                    <td>{{ $att->date }}</td>
                                    <td>{{ $att->employee?->first_name ?? 'N/A' }} {{ $att->employee?->last_name ?? '' }}</td>


                                    <!-- Check In -->
                                    <td>
                                        @if($att->date == \Carbon\Carbon::today()->toDateString())
                                            @if(!$att->check_in)
                                                <form method="POST" action="{{ route('attendance.checkin', $att->employee->id) }}">
                                                    @csrf
                                                    <button @class(['btn', 'btn-sm', 'btn-success'])>Check In</button>
                                                </form>
                                            @else
                                                {{ $att->check_in }}
                                            @endif
                                        @else
                                            {{ $att->check_in ?? '-' }}
                                        @endif
                                    </td>

                            

                                    <!-- Status -->
                                    <td>
                                        @php
                                            $status = $att->status ?? 'absent';

                                            $statusClass = match($status) {
                                                'present' => 'bg-success',
                                                'late' => 'bg-warning text-dark',
                                                'absent' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span @class(['badge', '{{', '$statusClass', '}}'])>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <table @class(['table', 'table-bordered'])>
                    <thead>
                    <tr class="text-white">
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
                    <td>{{ $att->employee->first_name }}</td>
                    <td>{{ $att->check_in ?? '-' }}</td>
                    <td>{{ $att->check_out ?? '-' }}</td>
                    <td>{{ $att->working_hours }}</td>
                    <td>{{ ucfirst($att->status) }}</td>
                    <td>
                    @if(!$att->check_in)
                    <form method="POST" action="{{ url('attendance/checkin/'.$att->employee_id) }}">
                    @csrf <button @class(['btn', 'btn-success', 'btn-sm'])>Check In</button>
                    </form>
                    @endif

                    @if($att->check_in && !$att->check_out)
                    <form method="POST" action="{{ url('attendance/checkout/'.$att->employee_id) }}">
                    @csrf <button @class(['btn', 'btn-danger', 'btn-sm'])>Check Out</button>
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
</x-app-layout>
