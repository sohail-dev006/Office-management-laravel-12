<x-app-layout>
<div class="container mt-5">
    <div class="row">
        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 p-4">
            <h3 class="mb-4">
                {{ $employee->first_name }} {{ $employee->last_name }} – Leave Summary
            </h3>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Type</th>
                        <th>Total Days</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Leaves Taken</td>
                        <td>{{ $totalLeaves }}</td>
                    </tr>
                    <tr>
                        <td>Leaves This Year</td>
                        <td>{{ $yearLeaves }}</td>
                    </tr>
                    <tr>
                        <td>Casual Leaves</td>
                        <td>{{ $casualLeaves }}</td>
                    </tr>
                    <tr>
                        <td>Holiday Leaves</td>
                        <td>{{ $holidayLeaves }}</td>
                    </tr>
                    <tr>
                        <td>Absents</td>
                        <td>{{ $absents }}</td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                Back to Leave List
            </a>
        </div>
    </div>
</div>
</x-app-layout>
