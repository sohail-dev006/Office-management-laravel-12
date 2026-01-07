<x-app-layout>
<div class="container mt-2">
    <h2 class="text-white fs-4 py-2">
        Edit Salary: {{ $salary->employee?->first_name }} {{ $salary->employee?->last_name }}
    </h2>


    <form action="{{ route('salary.update', $salary->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="text-white">Employee</label>
            <select name="employee_id" class="form-control" required>
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}" 
                    {{ $employee->id == $salary->employee_id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="text-white">Month</label>
            <input type="number" name="month" class="form-control" min="1" max="12" 
                   value="{{ $salary->month }}" required>
        </div>

        <div class="mb-3">
            <label class="text-white">Year</label>
            <input type="number" name="year" class="form-control" min="2000" 
                   value="{{ $salary->year }}" required>
        </div>

        <div class="mb-3">
            <label class="text-white">Gross Salary</label>
            <input type="number" name="gross_salary" class="form-control" step="0.01" 
                   value="{{ $salary->gross_salary }}" required>
        </div>

        <div class="mb-3">
            <label class="text-white">Deduction</label>
            <input type="number" name="deduction" class="form-control" step="0.01" 
                   value="{{ $salary->deduction }}" required>
        </div>

        <div class="mb-3">
            <label class="text-white">Net Salary</label>
            <input type="number" name="net_salary" class="form-control" step="0.01" 
                   value="{{ $salary->net_salary }}" required>
        </div>

        <button class="btn btn-success">Update Salary</button>
        <a href="{{ route('salary.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</x-app-layout>
