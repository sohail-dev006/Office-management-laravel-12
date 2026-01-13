<x-app-layout>
<div class="container-fluid mt-4">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-white">Generate Salary</h2>
                <a href="{{ route('salary.index') }}" class="btn btn-secondary">Back</a>
            </div>

            <div class="card bg-dark text-white p-4">
                <form action="{{ route('salary.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Employee</label>
                            <select name="employee_id" class="form-control" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Month</label>
                            <input type="number" name="month" class="form-control" min="1" max="12" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" min="2000" required>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-info">Additions</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Allowance</label>
                            <input type="number" step="0.01" name="allowance" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Bonus</label>
                            <input type="number" step="0.01" name="bonus" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Overtime</label>
                            <input type="number" step="0.01" name="overtime" class="form-control" value="0">
                        </div>
                    </div>

                    <h6 class="text-danger mt-3">Deductions</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Advance</label>
                            <input type="number" step="0.01" name="advance" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tax</label>
                            <input type="number" step="0.01" name="tax" class="form-control" value="0">
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mt-3">
                        Generate Salary
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
