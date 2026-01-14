<x-app-layout>
<div class="container mt-4">
    <h3 class="mb-4 text-white">{{ isset($salary) ? 'Edit Salary' : 'Generate Salary' }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ isset($salary) ? route('salary.update', $salary) : route('salary.store') }}" method="POST">
        @csrf
        @if(isset($salary))
            @method('PUT')
        @endif

        <!-- Employee -->
        <div class="mb-3">
            <label for="employee_id" class="form-label text-white">Employee</label>
            <select name="employee_id" id="employee_id" class="form-control" required>
                <option value="">-- Select Employee --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"
                        {{ isset($salary) && $salary->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Month -->
        <div class="mb-3">
            <label for="month" class="form-label text-white">Month</label>
            <select name="month" id="month" class="form-control" required>
                @php
                    $months = [
                        1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                        7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'
                    ];
                @endphp
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ isset($salary) && $salary->month==$num ? 'selected' : ($num==date('n') ? 'selected' : '') }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Year -->
        <div class="mb-3">
            <label for="year" class="form-label text-white">Year</label>
            <select name="year" id="year" class="form-control" required>
                @php
                    $currentYear = date('Y');
                    $years = range($currentYear, $currentYear - 10);
                @endphp
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ isset($salary) && $salary->year==$year ? 'selected' : ($year==date('Y') ? 'selected' : '') }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <h5 class="text-white mt-4">Allowances</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">House Allowance</label>
                <input type="number" name="house_allowance" class="form-control"
                       value="{{ $salary->house_allowance ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Medical Allowance</label>
                <input type="number" name="medical_allowance" class="form-control"
                       value="{{ $salary->medical_allowance ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Transport Allowance</label>
                <input type="number" name="transport_allowance" class="form-control"
                       value="{{ $salary->transport_allowance ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Other Allowance</label>
                <input type="number" name="other_allowance" class="form-control"
                       value="{{ $salary->other_allowance ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Bonus</label>
                <input type="number" name="bonus" class="form-control"
                       value="{{ $salary->bonus ?? 0 }}">
            </div>
        </div>

        <h5 class="text-white mt-4">Deductions</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Advance Salary</label>
                <input type="number" name="advance_salary" class="form-control"
                       value="{{ $salary->advance_salary ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Tax</label>
                <input type="number" name="tax" class="form-control"
                       value="{{ $salary->tax ?? 0 }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-white">Other Deduction</label>
                <input type="number" name="other_deduction" class="form-control"
                       value="{{ $salary->other_deduction ?? 0 }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ isset($salary) ? 'Update' : 'Generate Salary' }}</button>
        <a href="{{ route('salary.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
</x-app-layout>
