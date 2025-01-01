@extends('backend.app')

@section('title', 'Edit FAQs')

@push('style')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2ygs4k6l7e3eXsw4y5l4j5Rg5K4w5J5W" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" integrity="sha384-4H0ngthn0r2wSxWVRqVf4tZ2lUoU8vT5yFE1i5m5K5z5j5Rg5K4w5J5W" crossorigin="anonymous">
@endpush

@section('content')
<h1>Edit Review qq</h1>
<form action="{{ route('review.update', $review->slug) }}" method="POST">
    @csrf
    @method('PUT')

   {{--  <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{ old('title', $review->title) }}">
    </div> --}}

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="descriptionreview" name="description" rows="3" placeholder="Enter description" required>{{ old('description', $review->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name', $review->name) }}">
    </div>

    <div class="mb-3">
        <label for="designation" class="form-label">Designation</label>
        <textarea class="form-control" id="designation" name="designation" rows="3" placeholder="Enter designation">{{ old('designation', $review->designation) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="active" {{ old('status', $review->status) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $review->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Review</button>
</form>


@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    


     {{-- ck editor --}}
     <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#descriptionreview'))
            .catch(error => {
                console.error(error);
            });
    </script>  
@endpush