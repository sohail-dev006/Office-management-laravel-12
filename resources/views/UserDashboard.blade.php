<x-app-layout>
<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-dark text-white p-0">
            <ul class="nav flex-column p-2 min-vh-100">
                <li class="nav-item">
                    @can('dashboard')
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                        Dashboard
                    </a>
                    @endcan
                </li>
                <li class="nav-item">
                    @can('employee-list')
                    <a href="{{ route('employee.index') }}" class="nav-link text-white {{ request()->routeIs('employee.*') ? 'active-link' : '' }}">
                        Employee
                    </a>
                    @endcan
                </li>
                <li class="nav-item">
                    @can('add-attendance')
                    <a href="{{ route('attendance.index') }}" class="nav-link text-white {{ request()->routeIs('attendance.*') ? 'active-link' : '' }}">
                        Attendance
                    </a>
                    @endcan
                </li>
                <li class="nav-item">
                    @can('leave-list')
                    <a href="{{ route('leaves.index') }}" class="nav-link text-white {{ request()->routeIs('leaves.*') ? 'active-link' : '' }}">
                        Leave
                    </a>
                    @endcan
                </li>
                <li class="nav-item">
                    @can('salary-list')
                    <a href="{{ route('salary.index') }}" class="nav-link text-white {{ request()->routeIs('salary.*') ? 'active-link' : '' }}">
                        Salary
                    </a>
                   @endcan
                </li>
                        {{-- 🔥 CALENDAR SECTION --}}
                <li class="nav-item mt-3">
                    <span class="text-uppercase text-muted px-2 small">Calendar</span>
                </li>

                {{-- Main Calendar --}}
                <li class="nav-item">
                    <a href="{{ route('calendar.index') }}"
                    class="nav-link text-white ps-4 {{ request()->routeIs('calendar.index') ? 'active-link' : '' }}">
                        Calendar
                    </a>
                </li>

                {{-- Holidays --}}
                <li class="nav-item">
                    <a href="{{ route('calendar.index', ['type' => 'holiday']) }}"
                    class="nav-link text-white ps-4 {{ request('type') == 'holiday' ? 'active-link' : '' }}">
                        Holidays
                    </a>
                </li>

                {{-- Events --}}
                <li class="nav-item">
                    <a href="{{ route('calendar.index', ['type' => 'event']) }}"
                    class="nav-link text-white ps-4 {{ request('type') == 'event' ? 'active-link' : '' }}">
                        Events
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h3 class="mb-4 text-white fs-2">Welcome, {{ $user->name }}!</h3>

            <div class="row">
                <!-- Personal Info -->
                <div class="col-md-4">
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-dark text-white">Personal Info</div>
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p>
                                        <strong>Name:</strong>   
                                    </p>
                                    <p>
                                        <p><strong>Email:</strong>
                                    </p>
                                     @if($employee)
                                    <p>
                                        <strong>City:</strong> 
                                    </p>
                                    <p>
                                        <strong>Joining Date:</strong> 
                                    </p>
                                    @endif
                                </div>
                                <div class="">
                                    <p>{{ $user->name }}</p>
                                    <p>
                                        {{ $user->email }}
                                    </p>
                                    @if($employee)
                                    <p>{{ $employee->city }}</p>
                                    <p> {{ $employee->join_date }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            
                                {{-- <!-- <p><strong>Designation:</strong> {{ $employee->designation }}</p>
                                <p><strong>Phone:</strong> {{ $employee->phone }}</p> -->
                                
                                <!-- <p><strong>Job Type:</strong> {{ $employee->job_type }}</p> -->
                                <!-- <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p> --> --}}
                                
                          
                        </div>
                    </div>
                </div>

                <!-- Salary Info -->
                <div class="col-md-4">
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-dark text-white">Salary (This Month)</div>
                        <div class="card-body text-white">
                            @if($salary)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p>
                                        <strong>Gross:</strong>  
                                    </p>
                                    <p>
                                        <strong>Deductions:</strong> 
                                    </p>
                                    <p>
                                        <strong>Net:</strong>  
                                    </p>
                                </div>
                                <div class="">
                                    <p>PKR {{ number_format($salary->gross_salary, 2) }}</p>
                                    <p>PKR {{ number_format($salary->deduction, 2) }}</p>
                                    <p>PKR {{ number_format($salary->net_salary, 2) }}</p>
                                </div>
                            </div>
                            @else
                                <p>No salary generated yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="col-md-4">
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-dark text-white">Attendance Summary</div>
                        <div class="card-body text-white">
                            @if ($presentCount)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="">
                                    <p>
                                        <strong>Total Days (This Month):</strong> 
                                    </p>
                                    <p>
                                        <strong>Present:</strong> 
                                    </p>
                                    <p>
                                        <strong>Leave:</strong> 
                                    </p>
                                    <p>
                                        <strong>Absent:</strong> 
                                    </p>
                                </div>
                                <div class="">
                                    <p>
                                        {{ $presentCount + $leaveCount + $absentCount }}
                                    </p>
                                    <p>
                                        {{ $presentCount }}
                                    </p>
                                    <p>
                                        {{ $leaveCount }}
                                    </p>
                                    <p>
                                        {{ $absentCount }}
                                    </p>
                                </div>
                            </div>
                            @else
                                <p>No attendance yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Leaves -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-dark text-white">Recent Leaves</div>
                        <div class="card-body text-white">
                            @if($recentLeaves && $recentLeaves->count())
                                <table class="table ">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentLeaves as $leave)
                                            @php
                                                $statusClass = match($leave->status) {
                                                    'Approved' => 'bg-success text-white',
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Rejected' => 'bg-danger text-white',
                                                    default => 'bg-secondary text-white'
                                                };
                                            @endphp
                                            <tr class="text-white">
                                                <td>{{ $leave->leave_type }}</td>
                                                <td>{{ $leave->start_date }}</td>
                                                <td>{{ $leave->end_date }}</td>
                                                <td>{{ $leave->days_requested }}</td>
                                                <td><span class="badge {{ $statusClass }}">{{ ucfirst($leave->status) }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No recent leaves applied yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .active-link {
        background-color: grey !important;
        border-radius: 10px;
    }
    .nav-link {
        margin: 5px 0;
    }
    .card-body {
        min-height: 150px;
    }
</style>
</x-app-layout>
