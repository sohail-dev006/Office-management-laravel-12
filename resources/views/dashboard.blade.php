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
                        <a href="{{ route('attendance.index') }}" class="nav-link text-white">
                            Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('leaves.index') }}" class="nav-link text-white">
                            Leave
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="{{ route('salary.generate', [
                            'employee' => auth()->user()->id, 
                            'month' => now()->month, 
                            'year' => now()->year
                        ]) }}" class="nav-link text-white">
                            Salary g
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="{{ route('salary.index') }}" class="nav-link text-white">
                            Salary
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 bg-light p-4">
                <h3 class="mb-4 fs-2">Dashboard</h3>

                <div class="row">
                    <!-- Employees -->
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5>Total Employees</h5>
                                <h2 class="fs-3">{{ $totalEmployees }}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Present -->
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5>Present Today</h5>
                                <h2 class="fs-3">{{ $presentToday }}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- On Leave -->
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5>On Leave</h5>
                                <h2 class="fs-3">{{ $onLeaveToday }}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Salary -->
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5>Salary Generated (This Month)</h5>
                                <h2 class="fs-3">{{ $salaryThisMonth }}</h2>
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
</style>