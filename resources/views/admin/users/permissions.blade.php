<x-app-layout>
<div class="container-fluid">
    <div class="row">

        @include('layouts.sidebar')

        <div class="col-md-9 col-lg-10 pt-2">

            <h2 class="my-2 fs-3 text-white">Manage Roles & Permissions: {{ $user->name }}</h2>

            <form method="POST" action="{{ route('admin.users.permissions.update', $user->id) }}">
                @csrf

                {{-- ROLE DROPDOWN --}}
                <div class="mb-3">
                    <label class="text-white fs-5 py-1"><b>Role</b></label>
                    <input type="text" 
                        name="role" 
                        class="form-control" 
                        placeholder="Enter role name" 
                        value="{{ $userRole }}">
                    <small class="text-muted">Type a role name. If it doesn't exist, it will be created automatically.</small>
                </div>


                {{-- SELECT ALL --}}
                <div class="mb-3">
                    <label>
                        <input class="fs-5" type="checkbox" id="selectAll">
                        <b class="text-white fs-5">Select All Permissions</b>
                    </label>
                </div>

                {{-- MODULE CARDS --}}
                <div class="row">
                    @foreach($permissions as $module => $perms)
                        <div class="col-md-4 mb-3">
                            <div class="card bg-dark text-white">
                                <div class="card-header">
                                    <input type="checkbox" class="module-checkbox">
                                    <strong>{{ $module }}</strong>
                                </div>
                                <div class="card-body">
                                    @foreach($perms as $perm)
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $perm->name }}" 
                                                   id="perm-{{ $perm->id }}"
                                                   {{ in_array($perm->name, $userPermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-success mt-3">Save Changes</button>

            </form>

        </div>
    </div>
</div>
</x-app-layout>

{{-- JS --}}
<script>
    // Select All global checkbox
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = this.checked);
        document.querySelectorAll('.module-checkbox').forEach(cb => cb.checked = this.checked);
    });

    // Each module checkbox selects its own permissions
    document.querySelectorAll('.module-checkbox').forEach(moduleCheckbox => {
        moduleCheckbox.addEventListener('change', function () {
            let perms = this.closest('.card').querySelectorAll('.permission-checkbox');
            perms.forEach(cb => cb.checked = this.checked);
        });
    });
</script>
