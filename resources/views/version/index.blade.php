<x-dashboard-layout>
    <title>
        @yield('Version-Update', 'Version-Update')
    </title>
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Version Table</h4>
            <!-- Add this to your Blade view file -->
            {{-- <button >
                Open Pop-up
            </button> --}}
            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="">Add New
                Version</a>
            <br><br>

            <div class="card">
                {{-- <h5 class="card-header">Table</h5> --}}

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Version-Name</th>
                                <th>Version-Code</th>
                                <th>Force-Update</th>
                                <th>Release-Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody class="table-border-bottom-0">
                            @foreach ($versions as $version)
                                <tr id="row-{{ $version->id }}">
                                    <td>{{ $version->id }}</td>
                                    <td>{{ $version->version_name }}</td>
                                    <td>{{ $version->version_code }}</td>
                                    <td>{{ $version->force_update }}</td>
                                    <td>{{ $version->release_notes }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger version-delete"
                                            data-version-id="{{ $version->id }}">Delete</a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                        </tbody>

                        {{-- {{ $cetegories->links('pagination::simple-bootstrap-4') }} --}}
                    </table>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New Version</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <!-- Form inside the modal -->
                                    {{-- <form method="post" action="/add-cetegory">
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
                                    </form> --}}
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
                $('.version-delete').on('click', function(e) {
                    e.preventDefault(); // Prevent the default link behavior

                    let versionId = $(this).data('version-id');
                    let token = "{{ csrf_token() }}";

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST',
                            url: '/version-delete/' + versionId,
                            data: {
                                "_token": token,
                                "version-id": versionId
                            },
                            success: function(data) {
                                if (data.success) {
                                    alert('Version deleted successfully');
                                    $('#row-' + versionId).remove();
                                } else {
                                    alert('Failed to delete version');
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
    @endpush


</x-dashboard-layout>
