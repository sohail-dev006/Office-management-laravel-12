<x-app-layout>
<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between text-white mb-3">
                <h4 class="fs-4">Leave Requests</h4>
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
                            @canany(['edit-employee', 'delete-employee'])
                                <th>Actions</th>
                            @endcanany
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
                                @can('edit-employee')
                                    <form action="{{ route('leaves.updateStatus', $leave) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="Pending" {{ $leave->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Approved" {{ $leave->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="Rejected" {{ $leave->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </form>
                                @else
                                    @php
                                        $statusClass = match($leave->status) {
                                            'Approved' => 'bg-success',
                                            'Pending' => 'bg-warning text-dark',
                                            'Rejected' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($leave->status) }}</span>
                                @endcan
                            </td>
                            <td>
                                @can('edit-employee')
                                    <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-secondary">Edit</a>
                                @endcan

                                @can('delete-employee')
                                    <form action="{{ route('leaves.destroy', $leave) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endcan
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
