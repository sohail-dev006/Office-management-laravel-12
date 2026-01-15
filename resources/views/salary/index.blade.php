<x-app-layout>
<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')
        <div class="col-md-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-white fs-4">
                    Salary Overview - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                </h2>
                <div>
                    @can('add-salary')
                     <a href="{{ route('salary.create') }}" class="btn btn-success">+ Generate Salary</a>
                    @endcan
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('salary.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="month" class="form-control">
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(2026, $m, 1)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" class="form-control">
                            @foreach(range(date('Y'), date('Y')-10) as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search Employee">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                        <a href="{{ route('salary.index') }}" class="btn btn-secondary flex-fill">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Salary Cards -->
            <div class="row">
                @forelse($salaries as $salary)
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card bg-dark text-white shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $salary->employee?->first_name }} {{ $salary->employee?->last_name }}</h5>
                                <small>{{ \Carbon\Carbon::create($salary->year, $salary->month, 1)->format('F Y') }}</small>
                            </div>
                            <div class="card-body p-3">

                                <!-- Salary Info -->
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Gross Salary:</strong> <span class="text-info ms-2">{{ number_format($salary->gross_salary,2) }}</span></div>
                                    <div class="col-6"><strong>Net Salary:</strong> <span class="text-success ms-2">{{ number_format($salary->net_salary,2) }}</span></div>
                                </div>

                                <!-- Allowances -->
                                <div class="row mb-2">
                                    <div class="col-6"><strong>House Allowance:</strong> <span class="text-warning ms-2">{{ number_format($salary->house_allowance,2) }}</span></div>
                                    <div class="col-6"><strong>Medical:</strong> <span class="text-warning ms-2">{{ number_format($salary->medical_allowance,2) }}</span></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Transport:</strong> <span class="text-warning ms-2">{{ number_format($salary->transport_allowance,2) }}</span></div>
                                    <div class="col-6"><strong>Other Allow:</strong> <span class="text-warning ms-2">{{ number_format($salary->other_allowance,2) }}</span></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Bonus:</strong> <span class="text-warning ms-2">{{ number_format($salary->bonus,2) }}</span></div>
                                    <div class="col-6"><strong>Total Deductions:</strong> <span class="text-danger ms-2">{{ number_format($salary->total_deductions,2) }}</span></div>
                                </div>

                                <!-- Deductions -->
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Advance:</strong> <span class="text-danger ms-2">{{ number_format($salary->advance_salary,2) }}</span></div>
                                    <div class="col-6"><strong>Tax:</strong> <span class="text-danger ms-2">{{ number_format($salary->tax,2) }}</span></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6"><strong>Other Deduction:</strong></div>
                                    <div class="col-6"><span class="text-danger ms-2">{{ number_format($salary->other_deduction,2) }}</span></div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-end mt-3 gap-2">
                                    <a href="{{ route('salary.pdf',$salary) }}" class="btn btn-info btn-sm">PDF</a>
                                    @can('edit-salary')
                                    <a href="{{ route('salary.edit',$salary) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endcan
                                    @can('delete-salary')
                                    <form action="{{ route('salary.destroy', $salary) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this salary?')">Delete</button>
                                    </form>
                                    @endcan
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-white">
                        No salaries found for this month/year.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
</x-app-layout>
