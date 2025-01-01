@extends('backend.app')

@section('title', 'Create Product')

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>

    <style>
        .variant-item {
            margin-bottom: 15px;
        }

        .variant-item input {
            margin-bottom: 10px;
        }

        .add-variant {
            margin-top: 15px;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Product</h4>
                        <div class="mt-4">
                            <form class="forms-sample"action="{{ route('product.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-lable required">Title:</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="name" name="title" value="{{ old('title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Sub Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Sub Title:</label>
                                    <input type="text" class="form-control @error('sub_title') is-invalid @enderror"
                                        id="sub_title" name="sub_title" value="{{ old('sub_title') }}" required>
                                    @error('sub_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-lable required">Price:</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-lable required">Image</label>
                                        <input class="form-control dropify @error('image_url') is-invalid @enderror"
                                            type="file"
                                            data-default-file="{{ asset('backend/images/placeholder/image_placeholder.png') }}"
                                            name="image_url">

                                        @error('image_url')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-lable required">Discount:</label>
                                    <input type="number" class="form-control @error('discount') is-invalid @enderror"
                                        id="discount" name="discount" value="{{ old('discount') }}">
                                    @error('discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label required">Description:</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Rating -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Rating:</label>
                                    <select name="rating"
                                        class="form-control @error('rating') is-invalid @enderror select2" required>
                                        <option value="" disabled selected>Select Rating</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('rating') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="required">Status:</label>
                                    <select name="status"
                                        class="form-control @error('status') is-invalid @enderror select2" required>
                                        @php($status = old('status', isset($data) ? $data->status : ''))
                                        @foreach (['Active', 'Inactive'] as $sts)
                                            <option value="{{ $sts }}" {{ $status == $sts ? 'selected' : '' }}>
                                                {{ $sts }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Stock Management -->
                                <div class="form-group mb-3">
                                    <label class="form-lable required">Stock Quantity:</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity" value="{{ old('quantity') }}">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                {{-- Product Variants --}}
                                <div id="variants-container">
                                    <!-- Basic Layout -->
                                    <div class="col-xxl" style="padding-top: 10px">
                                        <div class="card mb-4">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">Variants</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="variant-group">
                                                    <div class="row mb-3">
                                                        <label class="col-sm-2 col-form-label" for="color">Color</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-select" name="variants[0][color]" required>
                                                                <option selected>Select Product Color</option>
                                                                <option value="Light Brown">Light Brown</option>
                                                                <option value="light brownish-orange">light brownish-orange</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-10">
                                                        <input type="file"
                                                            class="form-control dropify @error('variants.0.image_url') is-invalid @enderror"
                                                            name="variants[0][image_url][]" accept="image/*" multiple
                                                            required>
                                                        <small class="text-muted">Upload 3 images for this
                                                            variant.</small>
                                                        @error('variants.0.image_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-variant" class="btn btn-secondary">Add Another
                                    Variant</button>
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('product.index') }}" class="btn btn-danger ">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>



    <script>
        // it's for ckeditor
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>

    {{-- finally --}}

    <script>
        let variantIndex = 1;

        document.getElementById('add-variant').addEventListener('click', function() {
            let newVariantGroup = `
        <div class="col-xxl variant-wrapper" style="padding-top: 10px" id="variant-${variantIndex}">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Variants</h4>
                    <button type="button" class="btn btn-danger remove-variant" data-id="${variantIndex}">Remove Variant</button>
                </div>
                <div class="card-body">
                    <div class="variant-group">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="color">Color</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="variants[${variantIndex}][color]" required>
                                     <option selected>Select Product Color</option>
                                        <option value="Light Brown">Light Brown</option>
                                        <option value="light brownish-orange">light brownish-orange</option>
                                </select>
                            </div>
                        </div>

                        {{-- Multiple image upload --}}
                        {{-- Multiple image upload --}}
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="image">Image</label>
                            <div class="col-sm-10">
                                <input type="file"
                                    class="form-control dropify"
                                    name="variants[${variantIndex}][image_url][]"
                                    accept="image/*"
                                    multiple
                                    required />
                                <small class="text-muted">Upload up to 5 images for this variant.</small>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        `;

            document.getElementById('variants-container').insertAdjacentHTML('beforeend', newVariantGroup);

            $('.select2').select2();
            $('.dropify').dropify();
            variantIndex++;
        });

        // Event delegation for removing variants
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-variant')) {
                const variantId = event.target.getAttribute('data-id');
                document.getElementById(`variant-${variantId}`).remove();
            }
        });
    </script>
@endpush
