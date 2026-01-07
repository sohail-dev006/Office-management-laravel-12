<x-app-layout>
    <div class="container-fluid mt-4">
        <div class="row">
            @include('layouts.sidebar')

            <div class="col-md-9 col-lg-10">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-white">Add Salary</h2>
                    <a href="{{ route('salary.index') }}" class="btn btn-secondary">Back to Salary List</a>
                </div>

                <!-- Form Card -->
                <div class="card bg-dark text-white p-4">
                    <form action="{{ route('salary.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Employee</label>
                            <select name="employee_id" class="form-control" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Month</label>
                            <input type="number" name="month" class="form-control" min="1" max="12" required>
                        </div>

                        <div class="mb-3">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" min="2000" required>
                        </div>

                        <button class="btn btn-success">Generate Salary</button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>

