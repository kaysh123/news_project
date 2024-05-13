<x-dashboard-layout>
    <title>
        @yield('Categories', 'Categories')
    </title>
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Category Table</h4>
            <!-- Add this to your Blade view file -->
            {{-- <button >
                Open Pop-up
            </button> --}}
            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="">Add New
                Cetegory</a>
            <br><br>

            <div class="card">
                {{-- <h5 class="card-header">Table</h5> --}}

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($cetegories as $cetegory)
                                <tr id="row-{{ $cetegory->id }}">
                                    <td>{{ $cetegory->id }}</td>
                                    <td>{{ $cetegory->title }}</td>
                                    <td>
                                        {{-- <button class="btn btn-info">Update</button>
                                    <button class="btn btn-danger">Delete</button> --}}
                                        <a href="/user-edit/{{ $cetegory->id }}"><button
                                                class="btn btn-sm btn-info">Edit</button></a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger cetegory-delete"
                                            data-cetegory-id="{{ $cetegory->id }}">Delete</a>
                                        {{-- <a href="/hit-api/{{ $cetegory->title }}"><button
                                                class="btn btn-sm btn-info">Load News</button></a>

                                        <button id="apiButton" class="btn btn-sm btn-secondary">Load News</button> --}}

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                        {{ $cetegories->links('pagination::simple-bootstrap-4') }}
                    </table>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New Category</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <!-- Form inside the modal -->
                                    <form method="post" action="/add-cetegory">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label for="name">Title</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                required>
                                            @if ($errors->has('title'))
                                                <p style="color: red;">{{ $errors->first('title') }}</p>
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
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.cetegory-delete').on('click', function(e) {
                    e.preventDefault(); // Prevent the default link behavior

                    let categoryId = $(this).data('cetegory-id');
                    //alert(categoryId);
                    let token = "{{ csrf_token() }}";

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST',
                            url: '/cetegory-delete/' + categoryId,
                            data: {
                                "_token": token,
                                "cetegory_id": categoryId
                            },
                            success: function(data) {
                                if (data.success) {
                                    alert('Category deleted successfully');
                                    $('#row-' + categoryId).remove();
                                } else {
                                    alert('Failed to delete Category');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        </script>
        <script></script>
    @endpush

</x-dashboard-layout>
