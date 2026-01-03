<x-app-layout>
<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between text-white mb-3">
                <h4>Leave Requests</h4>
                <a href="{{ route('leaves.create') }}" class="btn btn-primary">Apply Leave</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th> 
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($leaves as $leave)
                        <tr class="text-white">
                            <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                            <td>{{ $leave->leave_type }}</td> 
                            <td>{{ $leave->start_date }}</td>
                            <td>{{ $leave->end_date }}</td>
                            <td>{{ $leave->days_requested }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td>
                                @php
                                    $statusClass = match($leave->status) {
                                        'Approved' => 'bg-success',
                                        'Pending' => 'bg-warning text-dark',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('leaves.edit', $leave->id) }}"
                                class="btn btn-sm btn-secondary">Edit</a>
            

                                <form action="{{ route('leaves.destroy', $leave->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
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
