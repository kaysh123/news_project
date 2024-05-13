<x-dashboard-layout>
    <title>
        @yield('Api-Select', 'Api-Select')
    </title>
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Api Table</h4>
            {{-- <form action="#" method="post">
                @csrf
                <label for="api">Select API:</label>
                <select name="api" id="api">
                    <option value="paragon">Paragon API</option>
                    <option value="news">News API</option>
                </select>
                <button type="submit">Select</button>
            </form> --}}
            <!-- Add this to your Blade view file -->
            {{-- <button >
                Open Pop-up
            </button> --}}
            <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="">Add New
                Api</a>
            <br><br>

            <div class="card">
                {{-- <h5 class="card-header">Table</h5> --}}

                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>API</th>
                                <th>Key</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Work</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($apis as $api)
                                <tr id="row-{{ $api->id }}">
                                    <td>{{ $api->id }}</td>
                                    <td>{{ $api->api_name }}</td>
                                    <td>{{ $api->api_key }}</td>
                                    <td>{{ $api->status == 1 ? 'Active' : 'Disabled' }}</td>
                                    <td>
                                        @if ($api->status == 0)
                                            <form action="{{ route('enable.api', ['id' => $api->id]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Enable</button>
                                            </form>
                                        @endif

                                        <!-- <a href="javascript:void(0)" class="btn btn-sm btn-danger api-delete"  data-api-id="{{ $api->id }}">Delete</a> -->
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#updateForm">Update</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- {{ $cetegories->links('pagination::simple-bootstrap-4') }} --}}
                    </table>
                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New Api</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <!-- Form inside the modal -->
                                    <form id="api_form" action="{{ route('admin.api.create') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="api_name">Api Name</label>
                                            <input type="text" class="form-control" id="api_name" name="api_name"
                                                required>
                                            @if ($errors->has('api_name'))
                                                <p style="color: red;">{{ $errors->first('api_name') }}</p>
                                            @endif
                                            <label for="api_key">Api Key</label>
                                            <input type="text" class="form-control" id="api_key" name="api_key"
                                                required>
                                            @if ($errors->has('api_key'))
                                                <p style="color: red;">{{ $errors->first('api_key') }}</p>
                                            @endif
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" id="status" name="status"
                                                required>
                                            @if ($errors->has('status'))
                                                <p style="color: red;">{{ $errors->first('status') }}</p>
                                            @endif
                                        </div>
                                        <br>
                                        <button type="button" class="btn btn-info"
                                            onclick="submitForm()">Submit</button>
                                    </form>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="updateForm">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Update Api</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <!-- Form inside the modal -->
                                    <form id="api_form" action="{{ route('admin.api.update', ['id' => $api->id]) }}"
                                        method="POST">

                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="api_name">Api Name</label>
                                                <input type="text" class="form-control" id="api_name"
                                                    name="api_name" value="{{ $api->api_name }}" required>
                                                @if ($errors->has('api_name'))
                                                    <p style="color: red;">{{ $errors->first('api_name') }}</p>
                                                @endif
                                                <label for="api_key">Api Key</label>
                                                <input type="text" class="form-control" id="api_key"
                                                    value="{{ $api->api_key }}" name="api_key" required>
                                                @if ($errors->has('api_key'))
                                                    <p style="color: red;">{{ $errors->first('api_key') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </div>
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
                $('.api-delete').on('click', function(e) {
                    e.preventDefault(); // Prevent the default link behavior

                    let versionId = $(this).data('api-id');
                    let token = "{{ csrf_token() }}";

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST',
                            url: '/api-delete/' + versionId,
                            data: {
                                "_token": token,
                                "api-id": versionId
                            },
                            success: function(data) {
                                if (data.success) {
                                    alert('API deleted successfully');
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
        <script>
            $(document).ready(function() {
                $('.toggle-api-status').on('click', function(e) {
                    e.preventDefault();

                    let apiName = $(this).data('api-name');
                    let status = $(this).data('status');
                    let token = "{{ csrf_token() }}";

                    $.ajax({
                        type: 'POST',
                        url: '/api/' + apiName + '/toggle-status',
                        data: {
                            "_token": token,
                            "status": status
                        },
                        success: function(data) {
                            // Handle success response
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush


</x-dashboard-layout>