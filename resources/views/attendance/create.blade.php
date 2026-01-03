<x-app-layout>
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-header bg-primary text-white">
                    <h4>Add Attendance</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('attendance.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror">
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input 
                                    name="date" 
                                    type="date" 
                                    id="date" 
                                    class="form-control @error('date') is-invalid @enderror" 
                                    value="{{ old('date', \Carbon\Carbon::today()->toDateString()) }}"
                                >
                                @error('date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="check_in" class="form-label">Check In</label>
                                <input 
                                    name="check_in" 
                                    type="time" 
                                    id="check_in" 
                                    class="form-control @error('check_in') is-invalid @enderror" 
                                    value="{{ old('check_in', \Carbon\Carbon::now()->format('H:i')) }}"
                                >
                                @error('check_in') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Save Attendance</button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
