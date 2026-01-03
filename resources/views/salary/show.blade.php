<x-app-layout>
<div class="container mt-4 ">
    <h3 class="text-white">
        Salary Sheet – {{ $employee->first_name }} {{ $employee->last_name }}
    </h3>

    <table class="table table-bordered mt-3 text-white">
        <tr>
            <th>Month</th>
            <td>{{ $salary->month }}/{{ $salary->year }}</td>
        </tr>
        <tr>
            <th>Working Days</th>
            <td>{{ $salary->working_days }}</td>
        </tr>
        <tr>
            <th>Present Days</th>
            <td>{{ $salary->present_days }}</td>
        </tr>
        <tr>
            <th>Absent Days</th>
            <td>{{ $salary->absent_days }}</td>
        </tr>
        <tr>
            <th>Gross Salary</th>
            <td>{{ number_format($salary->gross_salary, 2) }}</td>
        </tr>
        <tr>
            <th>Deduction</th>
            <td>{{ number_format($salary->deduction, 2) }}</td>
        </tr>
        <tr class="table-success">
            <th>Net Salary</th>
            <td><strong>{{ number_format($salary->net_salary, 2) }}</strong></td>
        </tr>
    </table>

    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
</div>
</x-app-layout>
