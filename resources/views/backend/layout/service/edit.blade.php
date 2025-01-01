@extends('backend.app')

@section('title', 'Edit Service')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Service </h4>
                        <p class="card-description">Update the service details below</p>

                        <form class="forms-sample" method="POST" action="{{ route('service.update', $service->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Title Input -->
                            <div class="form-group row mb-3">
                                <div>
                                    <label for="title" class="col-sm-2 col-form-label">Title:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="text"
                                        class="form-control form-control-md border-left-0 @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ $service->title }}" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Image Input -->
                            <div class="form-group row mb-3">
                                <div>
                                    <label for="image" class="col-sm-2 col-form-label">Image:</label>
                                </div>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="dropify form-control form-control-md border-left-0 @error('image') is-invalid @enderror"
                                        id="image" name="image" data-default-file="{{ asset($service->image) }}"
                                        value="{{ $service->image }}">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <!-- Description Input -->
                            <div class="form-group row mb-3">
                                <div>
                                    <label for="description" class="col-sm-2 col-form-label">Description:</label>
                                </div>
                                <div class="col-sm-10">
                                    <textarea class="form-control form-control-md border-left-0 @error('description') is-invalid @enderror" id="description"
                                        name="description" rows="6" required>{{ $service->description }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary me-2">Update Service</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Dropify JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop a file here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something went wrong.'
                }
            });
        });
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
