<x-dashboard-layout>
    <title>
        @yield('User', 'User')
    </title>
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Users Table</h4>
            <!-- Add this to your Blade view file -->
            {{-- <button >
                Open Pop-up
            </button> --}}
            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="">Add New
                User</a>
            <br><br>

            <div class="card">
                {{-- <h5 class="card-header">Table</h5> --}}

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>E-Mail</th>
                                {{-- <th>Status</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($users as $user)
                                <tr id="row-{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>

                                        <a href="/user-edit/{{ $user->id }}"><button
                                                class="btn btn-sm btn-info">Edit</button></a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger user-delete"
                                            data-user-id="{{ $user->id }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                    {{ $users->links('pagination::simple-bootstrap-4') }}
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New User</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <!-- Form inside the modal -->
                                    <form method="post" action="/user-made">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                            @if ($errors->has('name'))
                                                <p style="color: red;">{{ $errors->first('name') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                required>
                                            @if ($errors->has('email'))
                                                <p style="color: red;">{{ $errors->first('email') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                            @if ($errors->has('password'))
                                                <p style="color: red;">{{ $errors->first('password') }}</p>
                                            @endif
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- {{ $users->links() }} --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.user-delete').on('click', function(e) {
                    e.preventDefault(); // Prevent the default link behavior

                    let userId = $(this).data('user-id');
                    let token = "{{ csrf_token() }}";

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST', // Change to POST since your route is defined as POST
                            url: '/user-delete/' + userId, // Fix the URL to include the user ID
                            data: {
                                "_token": token,
                                "user_id": userId // Correct parameter name to match the controller
                            },
                            success: function(data) {
                                // Handle the success response
                                if (data.success) {
                                    alert('User deleted successfully');
                                    $('#row-' + userId).remove();
                                    // You can also update the UI or perform other actions as needed
                                    // window.location.href = '/profile'; // Redirect to the profile page
                                } else {
                                    alert('Failed to delete user');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle the error response
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush

</x-dashboard-layout>
