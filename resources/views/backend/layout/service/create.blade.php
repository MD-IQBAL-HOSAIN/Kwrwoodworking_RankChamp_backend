@extends('backend.app')

@section('title', 'service create')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Service </h4>
                        <p class="card-description">create the service details below</p>

                        <!-- Display errors if any -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                            </div>

                            
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="dropify" name="image" id="image" class="form-control"
                                required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive
                                    </option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Create Service</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    
    @push('script')
        {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        </script> --}}
    @endpush