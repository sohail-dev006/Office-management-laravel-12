<x-app-layout>
<div class="container-fluid">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 p-3">

            <!-- HEADER -->
            <div class="d-flex pt-2 align-items-center justify-content-between">
                <h2 class="text-white fs-4">
                    Salary Table - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                </h2>

                <div>
                    @role('admin')
                        <a href="{{ route('salary.create') }}" class="btn btn-success">+ Generate Salary</a>
                    @endrole
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Working Days</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Gross</th>
                            <th>Deduction</th>
                            <th>Net</th>
                            @role('admin')
                                <th>Action</th>
                            @endrole
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($salaries as $salary)
                        <tr class="text-white">
                            <td>
                                {{ $salary->employee->first_name }}
                                {{ $salary->employee->last_name }}
                            </td>

                            <td>{{ $salary->month }}/{{ $salary->year }}</td>

                            <td>{{ $salary->working_days }}</td>

                            <td>{{ $salary->present_days }}</td>

                            <td>{{ $salary->absent_days }}</td>

                            <td>{{ number_format($salary->gross_salary, 2) }}</td>

                            <td>{{ number_format($salary->deduction, 2) }}</td>

                            <td class="fw-bold">
                                {{ number_format($salary->net_salary, 2) }}
                            </td>

                            @role('admin')
                            <td>
                                <a href="{{ route('salary.pdf', $salary) }}"
                                   class="btn btn-sm btn-info mb-1">
                                    PDF
                                </a>

                                <a href="{{ route('salary.edit', $salary) }}"
                                   class="btn btn-sm btn-warning mb-1">
                                    Edit
                                </a>

                                <form action="{{ route('salary.destroy', $salary) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                            @endrole
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-white">
                                No salary records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
