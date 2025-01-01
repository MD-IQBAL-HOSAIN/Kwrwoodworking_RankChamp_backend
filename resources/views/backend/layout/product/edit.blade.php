@extends('backend.app')

@section('title', 'Edit Product')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

        .variant-image-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .variant-image {
            position: relative;
            display: inline-block;
        }

        .variant-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .variant-image .delete-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Product</h4>
                        <div class="mt-4">
                            <form class="forms-sample" action="{{ route('product.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Title:</label>
                                    <input type="text" class="form-control" name="title" value="{{ $product->title }}">
                                </div>

                                <!-- Sub Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Sub Title:</label>
                                    <input type="text" class="form-control" name="sub_title"
                                        value="{{ $product->sub_title }}">
                                </div>

                                <!-- Price -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Price:</label>
                                    <input type="number" class="form-control" name="price" value="{{ $product->price }}">
                                </div>

                                <!-- Product Image -->
                                <div class="form-group row mb-3">
                                    <div class="col">
                                        <label class="form-label required">Image</label>
                                        <input class="form-control dropify" type="file" name="image_url"
                                            data-default-file="{{ asset($product->image_url) }}">
                                    </div>
                                </div>

                                <!-- Discount -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Discount:</label>
                                    <input type="number" class="form-control" name="discount"
                                        value="{{ $product->discount }}">
                                </div>

                                <!-- Description -->
                                <div class="form-group mb-3">
                                    <label class="form-label required">Description:</label>
                                    <textarea class="form-control" name="description" id="description">{{ $product->description }}</textarea>

                                </div>

                                <!-- Variants -->
                                <div id="variants-container">
                                    @foreach ($product->variants as $index => $variant)
                                        <div class="variant-item" id="variant-{{ $variant->id }}">
                                            <div class="form-group mb-3">
                                                <label>Color:</label>
                                                <input type="text" class="form-control"
                                                    name="variants[{{ $variant->id }}][color]"
                                                    value="{{ $variant->color }}">
                                            </div>

                                            <!-- Images -->
                                            <div class="form-group mb-3">
                                                <label>Variant Images:</label>
                                                <div class="variant-image-container">
                                                    @foreach ($variant->images ?? [] as $image)
                                                        <div class="variant-image" id="variant-image-{{ $image->id }}">
                                                            <img src="{{ asset($image->image_url) }}" alt="Variant Image">
                                                            <button type="button"
                                                                class="delete-image btn btn-danger btn-sm"
                                                                data-id="{{ $image->id }}">&times;</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <input type="file" class="form-control dropify"
                                                    name="variants[{{ $variant->id }}][new_images][]" multiple>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="{{ route('product.index') }}" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>

    <script>
        $('.dropify').dropify();

        // Handle variant image deletion
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-image')) {
                let imageId = event.target.getAttribute('data-id');
                document.getElementById('variant-image-' + imageId).remove();

                // Optionally, make an AJAX call to delete the image from the database
            }
        });
    </script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>


    <script>
        // Handle variant image deletion
        $(document).on('click', '.delete-image', function() {
            const imageId = $(this).data('id'); // Get the image ID from the button's data attribute
            const url = `/variant-image/${imageId}`; // Construct the URL

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`#variant-image-${imageId}`)
                                .remove(); // Remove the image container from the DOM
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the image.');
                    }
                });
            }
        });
    </script>
@endpush
