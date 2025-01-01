@extends('backend.app')

@section('title', 'Cart Header')

@push('style')
    <!-- Add any custom styles if necessary -->
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Cart Banner</h4>
                        <div class="mt-4">
                            <form action="{{ route('cart.banner.update') }}" method="POST" enctype="multipart/form-data">
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

                                <!-- Description input -->
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                        rows="3">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
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
