@extends('backend.app')

@section('title', 'Ratings')

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
                        <h4 class="card-title">Rating</h4>

                        <div>
                            <form id="rate-product">
                                <input type="hidden" name="product_id" value="1">
                                <label for="rating">Rate this product:</label>
                                <select name="rating" id="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <button type="submit">Submit</button>
                            </form>
                        </div>

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

            const response = await fetch('/api/ratings', {
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
