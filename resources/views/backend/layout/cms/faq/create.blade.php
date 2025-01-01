@extends('backend.app')

@section('title', 'Create FAQs')

@push('style')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2ygs4k6l7e3eXsw4y5l4j5Rg5K4w5J5W" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        integrity="sha384-4H0ngthn0r2wSxWVRqVf4tZ2lUoU8vT5yFE1i5m5K5z5j5Rg5K4w5J5W" crossorigin="anonymous">
@endpush

@section('content')

    <h1>Create FAQs</h1>
    <form action="{{ route('faq.store') }}" method="POST">
        @csrf        
        {{--  <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <input type="text" class="form-control" id="question" name="question" placeholder="Enter question" required>
        </div> --}}
        <div class="mb-3">
            <label for="description" class="form-label">Answer</label>
            <textarea class="form-control" id="descr" name="answer" rows="6" placeholder="Enter answer"></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create FAQ</button>
    </form>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    
 {{-- ck editor --}}
 <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#descr'))
        .catch(error => {
            console.error(error);
        });
</script>  
@endpush
