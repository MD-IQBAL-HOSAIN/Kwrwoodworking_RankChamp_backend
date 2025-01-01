@extends('backend.app')

@section('title', 'Home Banner ')

@push('style')
    <!-- Add any custom styles if necessary -->
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Home Banner </h4>
                        <div class="mt-4">
                            <form action="{{ route('cms.home.header.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Hidden input for the banner ID -->
                                <input type="hidden" name="id" value="{{ $service->id }}">

                                <!-- Title input -->
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $service->title) }}">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Sub Title input -->
                                <div class="form-group">
                                    <label for="sub_title">Sub Title</label>
                                    <input type="text" id="sub_title" name="sub_title"
                                        class="form-control @error('sub_title') is-invalid @enderror"
                                        value="{{ old('sub_title', $service->sub_title) }}">
                                    @error('sub_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Image input -->
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" id="image" name="image"
                                        class="dropify form-control @error('image') is-invalid @enderror"
                                        data-default-file="{{ asset($service->image) }}">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Submit button -->
                                <div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    </script>

    {{-- ck editor --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>


@endpush
