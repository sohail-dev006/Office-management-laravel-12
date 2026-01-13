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

            <!-- SEARCH FORM -->
            <form method="GET" action="{{ route('salary.index') }}" class="mt-3">
                <div class="input-group mb-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Employee Name or Email">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a href="{{ route('salary.index', ['month'=>$month,'year'=>$year]) }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <!-- TABLE -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee</th>
                            <th>Month</th>
                            <th>Gross</th>
                            <th>Allowance</th>
                            <th>Bonus</th>
                            <th>OT</th>
                            <th>Advance</th>
                            <th>Tax</th>
                            <th>Net</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($salaries as $salary)
                        <tr class="text-white">
                            <td>{{ $salary->employee?->first_name }} {{ $salary->employee?->last_name }}</td>
                            <td>{{ $salary->month }}/{{ $salary->year }}</td>
                            <td>{{ number_format($salary->gross_salary,2) }}</td>
                            <td>{{ number_format($salary->allowance,2) }}</td>
                            <td>{{ number_format($salary->bonus,2) }}</td>
                            <td>{{ number_format($salary->overtime,2) }}</td>
                            <td>{{ number_format($salary->advance,2) }}</td>
                            <td>{{ number_format($salary->tax,2) }}</td>
                            <td class="fw-bold">{{ number_format($salary->net_salary,2) }}</td>

                            <td>
                                <a href="{{ route('salary.pdf',$salary) }}" class="btn btn-info btn-sm">PDF</a>
                                <a href="{{ route('salary.edit',$salary) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>

                </table>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
