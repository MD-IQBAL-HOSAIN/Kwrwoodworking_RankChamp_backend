@extends('backend.app')

@section('title', 'Product Show')

@push('style')
    <style>

    </style>
    <link rel="stylesheet" href="{{ asset('backend/vendors/datatable/css/datatables.min.css') }}">
@endpush
{{--
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $product->title }}</h4>
                        <img src="{{ asset($product->image_url) }}" width="150px" alt="{{ $product->title }}">

                        <!-- Display average rating -->
                        <p>Average Rating: {{ round($product->averageRating(), 1) }}</p>

                        <!-- Rating form -->
                        <form id="rate-product">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label for="rating">Rate this product:</label>
                            <select name="rating" id="rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <button type="submit">Submit Rating</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    document.getElementById('rate-product').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        const response = await fetch('/ratings', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        });

        const data = await response.json();
        alert(data.message);
    });
</script>
@endpush --}}
