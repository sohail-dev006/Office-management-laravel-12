<x-app-layout>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">

            <div class="col-md-6 bg-light mt-4 p-4 rounded shadow">

                <h3 class="mb-4 text-center">
                    Welcome, {{ $user->name }}
                </h3>

                <div class="card">
                    <div class="card-body text-center">
                        <p class="fs-5">
                            <strong>Name:</strong> {{ $user->name }}
                        </p>
                        <p class="fs-5">
                            <strong>Email:</strong> {{ $user->email }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
