<x-dashboard-layout>
    <title>
        @yield('Notification', 'Notification')
    </title>
    <div class="modal-body">
        <!-- Form inside the modal -->
        <form action="{{ route('admin.send.notification') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
                @error('title')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" required>
                @error('description')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image" required>
                @error('image')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <br>
            <button type="submit" class="btn btn-info">Submit</button>
        </form>
    </div>
</x-dashboard-layout>
