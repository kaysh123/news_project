<x-dashboard-layout>
    <title>
        @yield('News', 'News')
    </title>
    <div class="content-wrapper">

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>News Table</h4>
            <!-- Add this to your Blade view file -->
            {{-- <button >
                Open Pop-up
            </button> --}}
            {{-- <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="">Add New
                News</a> --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
             <!-- Category Filter -->
             <div class="mb-3">
                <label for="categoryFilter" class="form-label">Filter by Category:</label>
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->title }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="card">
                {{-- <h5 class="card-header">Table</h5> --}}
                <div class="table-responsive" style="overflow-x: hidden;">
                    <table class="table" id="new-table" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th style="white-space: nowrap;">S.No</th>
                                <th style="white-space: nowrap;">Category-Id</th>
                                <th style="white-space: nowrap;">Title</th>
                                <th style="white-space: nowrap;">Country</th>
                                <th style="white-space: nowrap;">Author</th>
                                <th style="white-space: nowrap;">Publish</th>
                                <th style="white-space: nowrap;">Image</th>
                                <th style="white-space: nowrap;">City</th>
                                <th style="white-space: nowrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($allnews as $news)
                                <tr id="row-{{ $news->id }}">
                                    <td>{{ $news->id }}</td>
                                    <td>{{ $news->cetegory_id }}</td>
                                    <td>{{ $news->title }}</td>
                                    <td>{{ $news->country }}</td>
                                    <td>{{ $news->auther }}</td>
                                    <td>{{ $news->publish }}</td>
                                    <td>
                                        @if (!empty($news->image))
                                            <img src="{{ $news->image }}" alt="Image"
                                                style="max-width: 100px; max-height: 100px;">
                                        @else
                                            <img src="{{ asset('assets/img/icons/unicons/news.png') }}"
                                                alt="Default Image" style="max-width: 100px; max-height: 100px;">
                                        @endif
                                    </td>
                                    <td>{{ $news->city }}</td>
                                    <td>

                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger user-delete"
                                            data-user-id="{{ $news->id }}">Delete</a>
                                              <a href="{{ route('admin.notify.news', ['news' => $news]) }}" class="btn btn-info btn-sm mt-1">Notify</a>
                                        {{-- <a href="/user-edit/{{ $user->id }}"><button
                                                class="btn btn-sm btn-info">Edit</button></a>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger user-delete"
                                            data-user-id="{{ $user->id }}">Delete</a> --}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

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
                                    <form method="post" action="/add-news">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label for="cetegory_id">Cetegory-Id</label>
                                            <input type="text" class="form-control" id="cetegory_id"
                                                name="cetegory_id" required>
                                            @if ($errors->has('cetegory_id'))
                                                <p style="color: red;">{{ $errors->first('cetegory_id') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Title:</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                required>
                                            @if ($errors->has('title'))
                                                <p style="color: red;">{{ $errors->first('title') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="country">Country:</label>
                                            <input type="text" class="form-control" id="country" name="country"
                                                required>
                                            @if ($errors->has('country'))
                                                <p style="color: red;">{{ $errors->first('country') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="auther">Auther:</label>
                                            <input type="text" class="form-control" id="auther" name="auther"
                                                required>
                                            @if ($errors->has('auther'))
                                                <p style="color: red;">{{ $errors->first('auther') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="publish">Publish:</label>
                                            <input type="text" class="form-control" id="publish" name="publish"
                                                required>
                                            @if ($errors->has('publish'))
                                                <p style="color: red;">{{ $errors->first('publish') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <input type="text" class="form-control" id="description"
                                                name="description" required>
                                            @if ($errors->has('description'))
                                                <p style="color: red;">{{ $errors->first('description') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="content">Content:</label>
                                            <input type="text" class="form-control" id="content" name="content"
                                                required>
                                            @if ($errors->has('content'))
                                                <p style="color: red;">{{ $errors->first('content') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="content">Image:</label>
                                            <input type="text" class="form-control" id="image" name="image"
                                                required>
                                            @if ($errors->has('image'))
                                                <p style="color: red;">{{ $errors->first('image') }}</p>
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

        <script type="text/javascript">
//             $(document).ready( function () {
//     $('#new-table').DataTable();
// } );
            $(document).ready(function() {
                $('.user-delete').on('click', function(e) {
                    e.preventDefault(); // Prevent the default link behavior

                    let userId = $(this).data('user-id');
                    let token = "{{ csrf_token() }}";

                    if (confirm('Are you sure?')) {
                        $.ajax({
                            type: 'POST', // Change to POST since your route is defined as POST
                            url: '/news-delete/' + userId, // Fix the URL to include the user ID
                            data: {
                                "_token": token,
                                "news_id": userId // Correct parameter name to match the controller
                            },
                            success: function(data) {
                                // Handle the success response
                                if (data.success) {
                                    alert('News deleted successfully');
                                    $('#row-' + userId).remove();
                                    // You can also update the UI or perform other actions as needed
                                    // window.location.href = '/profile'; // Redirect to the profile page
                                } else {
                                    alert('Failed to delete news');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle the error response
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
                // Filter news by category
                $('#categoryFilter').on('change', function() {
                    let categoryId = $(this).val();
                    if (categoryId !== '') {
                        $('table tbody tr').hide();
                        $('table tbody tr').each(function() {
                            let categoryValue = $(this).find('td:eq(1)').text();
                            if (categoryValue === categoryId) {
                                $(this).show();
                            }
                        });
                    } else {
                        $('table tbody tr').show();
                    }
                });
            });
        </script>
    @endpush

</x-dashboard-layout>