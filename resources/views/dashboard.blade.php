<x-app-layout>
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 bg-dark text-white p-0">

                <ul class="nav flex-column p-2">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employee.index') }}" class="nav-link text-white">
                            Employee
                        </a>
                    </li>
                    <li class="nav-item">
                        @can('add-attendance')
                        <a href="{{ route('attendance.index') }}" class="nav-link text-white">
                            Attendance
                        </a>
                        @endcan
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('leaves.index') }}" class="nav-link text-white">
                            Leave
                        </a>
                    </li>
                    {{-- <!-- <li class="nav-item">
                        <a href="{{ route('salary.generate', [
                            'employee' => auth()->user()->id, 
                            'month' => now()->month, 
                            'year' => now()->year
                        ]) }}" class="nav-link text-white">
                            Salary g
                        </a>
                    </li> --> --}}
                    <li class="nav-item">   
                        <a href="{{ route('salary.index') }}" class="nav-link text-white">
                            Salary
                        </a>
                    </li>
                    <li class="nav-item">
                        @role('admin')
                        <a href="{{ route('admin.users') }}" class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active-link' : '' }}">
                            Users & Permission
                        </a>
                        @endrole
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

        <div class="col-md-9 col-lg-10 p-4">
        <h3 class="mb-4 fs-2 text-white">Admin Dashboard</h3>

            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-body">
                            <h5 class="text-center fs-4">Total Employees</h5>
                            <h2 class="text-center pt-3 fs-2">{{ $totalEmployees }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-body">
                            <h5 class="text-center fs-4">Present Today</h5>
                            <h2 class="text-center pt-3 fs-2">{{ $presentToday }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-body">
                            <h5 class="text-center fs-4">On Leave</h5>
                            <h2 class="text-center pt-3 fs-2">{{ $onLeaveToday }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-secondary mb-3">
                        <div class="card-body">
                            <h5 class="text-center fs-4">Salary Generated</h5>
                            <h2 class="text-center pt-3 fs-2">{{ $salaryThisMonth }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</x-app-layout>
<style>

.active-link {
    background-color: grey !important;
    border-radius: 10px;
}
.nav-link {
    margin: 5px 0;
}
.card{
    min-height: 130px;
}
</style>