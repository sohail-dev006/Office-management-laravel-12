<x-app-layout>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-header bg-primary text-white">
                    <h4>Apply Leave</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('leaves.store') }}">
                        @csrf

                        <!-- Employee -->
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            @if(auth()->user()->hasRole('admin'))
                                <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="employee_id" value="{{ $employees->first()->id }}">
                                <p class="form-control">{{ $employees->first()->first_name }} {{ $employees->first()->last_name }}</p>
                            @endif
                            @error('employee_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Leave Type -->
                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Leave Type</label>
                            <select name="leave_type" id="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                                <option value="">Select Leave Type</option>
                                <option value="Casual" {{ old('leave_type') == 'Casual' ? 'selected' : '' }}>Casual</option>
                                <option value="Sick" {{ old('leave_type') == 'Sick' ? 'selected' : '' }}>Sick</option>
                                <option value="Earned" {{ old('leave_type') == 'Earned' ? 'selected' : '' }}>Earned</option>
                                <option value="Holiday" {{ old('leave_type') == 'Holiday' ? 'selected' : '' }}>Holiday</option>
                            </select>
                            @error('leave_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Days Requested -->
                        <div class="mb-3">
                            <label for="days_requested" class="form-label">Days Requested</label>
                            <input type="number" name="days_requested" id="days_requested" class="form-control @error('days_requested') is-invalid @enderror" value="{{ old('days_requested') }}" readonly>
                            @error('days_requested') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror">{{ old('reason') }}</textarea>
                            @error('reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Submit Leave</button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const daysRequested = document.getElementById('days_requested');

    function calculateDays() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);

            if (end < start) {
                daysRequested.value = '';
                return;
            }

            const diffTime = end.getTime() - start.getTime();
            const diffDays = (diffTime / (1000 * 60 * 60 * 24)) + 1;
            daysRequested.value = diffDays;
        }
    }

    startDate.addEventListener('change', calculateDays);
    endDate.addEventListener('change', calculateDays);
</script>
</x-app-layout>
