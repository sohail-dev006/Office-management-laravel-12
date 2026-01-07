<x-app-layout>
<div class="container mt-2">
    <h3 class="text-white fs-1 my-2">Edit Leave</h3>

    <form class="bg-secondary rounded" action="{{ route('leaves.update', $leave) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Employee -->
        <div class="mb-3 mx-3 pt-3">
            <label class="text-white">Employee</label>
            <select name="employee_id" class="form-control">
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ $employee->id == $leave->employee_id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Start Date -->
        <div class="mb-3 mx-3">
            <label class="text-white">Start Date</label>
            <input type="date" name="start_date"
                   value="{{ $leave->start_date }}" class="form-control">
        </div>

        <!-- End Date -->
        <div class="mb-3 mx-3">
            <label class="text-white">End Date</label>
            <input type="date" name="end_date"
                   value="{{ $leave->end_date }}" class="form-control">
        </div>

        <!-- Days -->
        <div class="mb-3 mx-3">
            <label class="text-white">Days</label>
            <input type="number" name="days_requested"
                   value="{{ $leave->days_requested }}" class="form-control">
        </div>

        <!-- Reason -->
        <div class="mb-3 mx-3">
            <label class="text-white">Reason</label>
            <textarea name="reason" class="form-control">{{ $leave->reason }}</textarea>
        </div>

        <!-- Leave Type -->
        <div class="mb-3 mx-3">
            <label class="text-white">Leave Type</label>
            <select name="leave_type" class="form-control" required>
                <option value="Casual" {{ $leave->leave_type=='Casual'?'selected':'' }}>Casual</option>
                <option value="Sick" {{ $leave->leave_type=='Sick'?'selected':'' }}>Sick</option>
                <option value="Earned" {{ $leave->leave_type=='Earned'?'selected':'' }}>Earned</option>
                <option value="Holiday" {{ $leave->leave_type=='Holiday'?'selected':'' }}>Holiday</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-3 mx-3">
            <label class="text-white">Status</label>
            <select name="status" class="form-control">
                <option value="Pending" {{ $leave->status=='Pending'?'selected':'' }}>Pending</option>
                <option value="Approved" {{ $leave->status=='Approved'?'selected':'' }}>Approved</option>
                <option value="Rejected" {{ $leave->status=='Rejected'?'selected':'' }}>Rejected</option>
            </select>
        </div>

        <div class="ma-2">
            <button class="btn btn-success mx-3 mb-3">Update</button>
            <a href="{{ route('leaves.index') }}" class="btn  mb-3 btn-dark">Back</a>
        </div>
    </form>
</div>
</x-app-layout>
