<x-app-layout>
<div class="container mt-2">
    <h2>Add Salary</h2>

    <form action="{{ route('salary.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Employee</label>
            <select name="employee_id" class="form-control" required>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
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

        <div class="mb-3">
            <label>Gross Salary</label>
            <input type="number" name="gross_salary" class="form-control" step="0.01" required>
        </div>

        <button class="btn btn-success">Save Salary</button>
        <a href="{{ route('salary.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-app-layout>
