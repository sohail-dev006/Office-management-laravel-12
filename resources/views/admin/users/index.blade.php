<x-app-layout>
<div class="container-fluid">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 pt-2">

            <h2 class="my-2 pb-2 fs-2 text-white">Users & Permissions</h2>
            <div class="pb-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    + Create
                </a>
            </div>

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
                        <tr class="text-white">
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
                                   class="btn btn-sm btn-primary mb-1">
                                   Manage
                                </a>
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
