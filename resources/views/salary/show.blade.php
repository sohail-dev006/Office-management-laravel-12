<x-app-layout>
<div class="container-fluid">
    <h3 class="text-white mb-4">
        Salary Sheet – {{ $salary->employee->first_name }} {{ $salary->employee->last_name }}
    </h3>

    <div class="row g-3">
        <!-- Basic Info Card -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header bg-secondary">
                    <strong>Employee Info</strong>
                </div>
                <div class="card-body">
                    <p><strong>Month:</strong> <span class="text-info ms-2">{{ \Carbon\Carbon::create($salary->year, $salary->month, 1)->format('F, Y') }}</span></p>
                    <p><strong>Working Days:</strong> <span class="text-info ms-2">{{ $salary->working_days }}</span></p>
                    <p><strong>Present Days:</strong> <span class="text-info ms-2">{{ $salary->present_days }}</span></p>
                    <p><strong>Paid Leaves:</strong> <span class="text-info ms-2">{{ $salary->leaves }}</span></p>
                    <p><strong>Absent Days:</strong> <span class="text-info ms-2">{{ $salary->absent_days }}</span></p>
                </div>
            </div>
        </div>

        <!-- Allowances Card -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header bg-info">
                    <strong>Allowances</strong>
                </div>
                <div class="card-body">
                    <p><strong>House Allowance:</strong> <span class="text-warning ms-2">{{ number_format($salary->house_allowance,2) }}</span></p>
                    <p><strong>Medical Allowance:</strong> <span class="text-warning ms-2">{{ number_format($salary->medical_allowance,2) }}</span></p>
                    <p><strong>Transport Allowance:</strong> <span class="text-warning ms-2">{{ number_format($salary->transport_allowance,2) }}</span></p>
                    <p><strong>Other Allowance:</strong> <span class="text-warning ms-2">{{ number_format($salary->other_allowance,2) }}</span></p>
                    <p><strong>Bonus:</strong> <span class="text-warning ms-2">{{ number_format($salary->bonus,2) }}</span></p>
                </div>
            </div>
        </div>

        <!-- Deductions Card -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header bg-warning">
                    <strong>Deductions</strong>
                </div>
                <div class="card-body">
                    <p><strong>Advance Salary:</strong> <span class="text-danger ms-2">{{ number_format($salary->advance_salary,2) }}</span></p>
                    <p><strong>Tax:</strong> <span class="text-danger ms-2">{{ number_format($salary->tax,2) }}</span></p>
                    <p><strong>Other Deduction:</strong> <span class="text-danger ms-2">{{ number_format($salary->other_deduction,2) }}</span></p>
                    <p><strong>Attendance Deduction:</strong> <span class="text-danger ms-2">{{ number_format($salary->deduction,2) }}</span></p>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-header bg-primary">
                    <strong>Salary Summary</strong>
                </div>
                <div class="card-body">
                    <p><strong>Gross Salary:</strong> <span class="text-info ms-2">{{ number_format($salary->gross_salary,2) }}</span></p>
                    <p><strong>Total Deductions:</strong> <span class="text-danger ms-2">{{ number_format($salary->total_deductions,2) }}</span></p>
                    <p><strong>Net Salary:</strong> <span class="text-success ms-2">{{ number_format($salary->net_salary,2) }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('salary.index') }}" class="btn btn-secondary">Back</a>
        <a href="{{ route('salary.pdf', $salary) }}" class="btn btn-primary">Download PDF</a>
    </div>
</div>
</x-app-layout>
