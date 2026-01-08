<x-app-layout>
<div class="container-fluid">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 pt-2">

            <h2 class="my-2 pb-2 fs-2 text-white">Users & Permissions</h2>

            {{-- Tabs --}}
            <ul class="nav d-flex justify-content-between nav-tabs mb-3" id="userTabs" role="tablist">
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="existing-tab" data-bs-toggle="tab" 
                            data-bs-target="#existing" type="button" role="tab">
                        Existing Users
                    </button>
                </li> -->
                <li class="nav-item">
                    <button class="nav-link active" id="create-tab" data-bs-toggle="tab" 
                            data-bs-target="#create" type="button" role="tab">
                        Create New Roles
                    </button>
                </li>
                <li class="nav-item mx-2">
                    <a  href="{{ route('admin.users') }}" class="bg-info px-2 py-1 fs-5 rounded">
                        Back
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="userTabsContent">

                {{-- Existing Users Tab --}}
                <!-- <div class="tab-pane fade show active" id="existing" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-white">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->getRoleNames()->first() ?? 'N/A' }}</td>
                                    <td>
                                        @foreach($user->getAllPermissions() as $perm)
                                            <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.permissions', $user->id) }}" 
                                        class="btn btn-sm btn-primary mb-1">Manage</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> -->

             <!-- -- Create New User Tab -- -->
                <div class="tab-pane fade show active" id="create" role="tabpanel">
                    <div class="card mb-4 bg-dark text-white">
                        <div class="card-header"><strong>New User Details</strong></div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.users.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>

                                {{-- Role --}}
                                <div class="mb-3">
                                    <input type="text" name="role" class="form-control" placeholder="Role name" required>
                                </div>

                                {{-- Permissions --}}
                                <div class="mb-3">
                                    <label><b>Permissions</b></label>
                                    <div class="row">
                                        @foreach($permissions as $module => $perms)
                                            <div class="col-md-4 mb-2">
                                                <div class="card bg-secondary text-white">
                                                    <div class="card-header">
                                                        <input type="checkbox" class="module-checkbox-create"> <strong>{{ $module }}</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach($perms as $perm)
                                                            <div class="form-check">
                                                                <input class="form-check-input permission-checkbox-create" 
                                                                    type="checkbox" 
                                                                    name="permissions[]" 
                                                                    value="{{ $perm->name }}" 
                                                                    id="perm-create-{{ $perm->id }}">
                                                                <label class="form-check-label" for="perm-create-{{ $perm->id }}">
                                                                    {{ $perm->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button class="btn btn-success">Create User</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        </div>
    </div>
</div>
</x-app-layout>

{{-- JS --}}
<script>
    // Each module checkbox selects its own permissions
document.querySelectorAll('.module-checkbox-create').forEach(moduleCheckbox => {
    moduleCheckbox.addEventListener('change', function () {
        let perms = this.closest('.card').querySelectorAll('.permission-checkbox-create');
        perms.forEach(cb => cb.checked = this.checked);
    });
});

</script>
