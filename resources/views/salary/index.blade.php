<x-app-layout>
<div class="container-fluid">
    <div class="row">
         @include('layouts.sidebar')
         <div class="col-md-9 col-10 col-12">
            <div class="d-flex pt-2 align-items-center justify-content-between">
                <div class="">
                    <h2 class="text-white">Salary Table - {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</h2>
                </div>
                <div>
                    <!-- <a href="{{ route('salary.create') }}" class="btn btn-success">Add Salary</a> -->
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
            <table class="table table-bordered mt-3">
                <thead class="table-dark text-white">
                    <tr>
                        <th>Employee</th>
                        <th>Working Days</th>
                        <th>Present Days</th>
                        <th>Leave Days</th>
                        <th>Absent Days</th>
                        <th>Gross Salary</th>
                        <th>Deduction</th>
                        <th>Net Salary</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $s)
                        <tr class="text-white">
                            <td>{{ $s['employee']->first_name }} {{ $s['employee']->last_name }}</td>
                            <td>{{ $s['working_days'] }}</td>
                            <td>{{ $s['present_days'] }}</td>
                            <td>{{ $s['leave_days'] }}</td>
                            <td>{{ $s['absent_days'] }}</td>
                            <td>{{ number_format($s['gross_salary'], 2) }}</td>
                            <td>{{ number_format($s['deduction'], 2) }}</td>
                            <td>{{ number_format($s['net_salary'], 2) }}</td>
                            <!-- <td>
                                
                                    <a class="rounded text-white" href="{{ route('salary.edit', $s['employee']->id) }}">
                                        <button class="px-1 rounded btn-secondary">Edit</button>
                                    </a>

                                    <form action="{{ route('salary.destroy', $s['employee']->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-1 rounded btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this salary?')">
                                            Delete
                                        </button>
                                    </form>
                                
                            </td> -->

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-white">No salary records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
         </div>
    </div>
</div>
</x-app-layout>
