<x-app-layout>
    <div class="container mt-4">
        <h3 class="mb-4 text-white">Generate Salary</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('salary.store') }}" method="POST">
            @csrf

            <!-- Employee -->
            <div class="mb-3">
                <label for="employee_id" class="form-label text-white">Employee</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Month -->
            @php
                $months = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
            @endphp
            <div class="mb-3">
                <label for="month" class="form-label text-white">Month</label>
                <select name="month" id="month" class="form-control" required>
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Year -->
            @php
                $currentYear = date('Y');
                $years = range($currentYear, $currentYear - 10);
            @endphp
            <div class="mb-3">
                <label for="year" class="form-label text-white">Year</label>
                <select name="year" id="year" class="form-control" required>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="text-white">

            <!-- Allowances -->
            <h5 class="text-white">Allowances</h5>
            <div class="mb-3">
                <label for="house_allowance" class="form-label text-white">House Allowance</label>
                <input type="number" name="house_allowance" id="house_allowance" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="medical_allowance" class="form-label text-white">Medical Allowance</label>
                <input type="number" name="medical_allowance" id="medical_allowance" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="transport_allowance" class="form-label text-white">Transport Allowance</label>
                <input type="number" name="transport_allowance" id="transport_allowance" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="other_allowance" class="form-label text-white">Other Allowance</label>
                <input type="number" name="other_allowance" id="other_allowance" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="bonus" class="form-label text-white">Bonus</label>
                <input type="number" name="bonus" id="bonus" class="form-control" step="0.01" value="0">
            </div>

            <hr class="text-white">

            <!-- Deductions -->
            <h5 class="text-white">Deductions</h5>
            <div class="mb-3">
                <label for="advance_salary" class="form-label text-white">Advance Salary</label>
                <input type="number" name="advance_salary" id="advance_salary" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="tax" class="form-label text-white">Tax</label>
                <input type="number" name="tax" id="tax" class="form-control" step="0.01" value="0">
            </div>
            <div class="mb-3">
                <label for="other_deduction" class="form-label text-white">Other Deduction</label>
                <input type="number" name="other_deduction" id="other_deduction" class="form-control" step="0.01" value="0">
            </div>

            <button type="submit" class="btn btn-primary">Generate Salary</button>
            <a href="{{ route('salary.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</x-app-layout>
