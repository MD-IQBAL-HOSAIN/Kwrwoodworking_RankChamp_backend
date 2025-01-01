@extends('backend.app')

@section('title', 'Contact')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Contact Section</h4>

                        <div class="mt-4">
                            <form action="{{ route('cms.home.contact.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="id" value="1">

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Contact</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('contact') is-invalid @enderror"
                                            placeholder="Contact" name="contact" value="{{ $data[0]->contact }}" >
                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Email</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('email') is-invalid @enderror"
                                            placeholder="Email" name="email" value="{{ $data[0]->email }}" >
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Support Email</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('support_email') is-invalid @enderror"
                                            placeholder="Support Email" name="support_email" value="{{ $data[0]->support_email }}" >
                                        @error('support_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Location</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('location') is-invalid @enderror"
                                            placeholder="Location" name="location" value="{{ $data[0]->location }}" >
                                        @error('location')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable">Opening Time</label>
                                        <input type="text"
                                            class="form-control form-control-md border-left-0 @error('opening_time') is-invalid @enderror"
                                            placeholder="Opening Time" name="opening_time" value="{{ $data[0]->opening_time }}" >
                                        @error('opening_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
