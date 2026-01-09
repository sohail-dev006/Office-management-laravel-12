<div class="col-md-3 col-lg-2 bg-dark text-white p-0">
    <ul class="nav flex-column p-2 min-vh-100">
        <li class="nav-item">
            @can('view-dashboard')
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
        <li class="nav-item">
            @role('admin')
            <a href="{{ route('admin.users') }}"
            class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'active-link' : '' }}">
                Users & Permissions
            </a>
            @endrole
        </li>

    </ul>
</div>

<style>
    /* Only active link will get red background */
    .active-link {
        background-color: grey !important;
        border-radius: 10px;
    }

    .nav-link {
        margin: 5px 0;
    }
</style>