@extends('backend.app')

@section('title', 'List of FAQs')

@push('style')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie2ygs4k6l7e3eXsw4y5l4j5Rg5K4w5J5W" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        integrity="sha384-4H0ngthn0r2wSxWVRqVf4tZ2lUoU8vT5yFE1i5m5K5z5j5Rg5K4w5J5W" crossorigin="anonymous">

        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 34px;
                height: 20px;
            }

            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 12px;
                width: 12px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
            }

            input:checked + .slider {
                background-color: #0d6efd;
            }

            input:checked + .slider:before {
                transform: translateX(14px);
            }

            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }
        </style>

@endpush

@section('content')

    <div class="container mt-4">
        <h2 class="mb-4">List of FAQs</h2>
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('faq.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New FAQ
            </a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                {{-- <th scope="col">Question</th> --}}
                                <th scope="col">Answer</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $faq)
                                <tr>
                                    <th scope="row">{{ ($faqs->currentPage() - 1) * $faqs->perPage() + $loop->iteration }}</th>
                                    </td>
                                    <td>{{ Str::limit($faq->answer, 50) }}{{ strlen($faq->answer) > 50 ? '...' : '' }}</td>

                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" id="toggle-{{ $faq->id }}" onchange="toggleStatus({{ $faq->id }})" {{ $faq->status === 'active' ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td class="d-flex justify-content-start">
                                        <a href="{{ route('faq.edit', ['slug' => $faq->slug]) }}"
                                            class="btn btn-primary me-2">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form id="deleteForm-{{ $faq->id }}"
                                            action="{{ route('faq.destroy', $faq->slug) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $faq->id }})"
                                                class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No FAQs available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-start mt-3">
                    {{ $faqs->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(faqId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form for the specific FAQ item
                    document.getElementById('deleteForm-' + faqId).submit();
                }
            });
        }
    </script>

    {{-- status toggle start --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleStatus(id) {
            $.ajax({
                url: '{{ route('faq.toggleStatus', '') }}/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Optional: Update the state of the toggle if needed
                    let toggleSwitch = $('#toggle-' + id);
                    if (response.status === 'active') {
                        toggleSwitch.prop('checked', true);
                    } else {
                        toggleSwitch.prop('checked', false);
                    }
                },
                error: function(xhr) {
                    console.error('Error toggling status');
                    // Revert switch state if error occurs
                    $('#toggle-' + id).prop('checked', !$('#toggle-' + id).is(':checked'));
                }
            });
        }
    </script>


<script>
    function toggleStatus(id) {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to change the status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with AJAX request if confirmed
                $.ajax({
                    url: '{{ route('faq.toggleStatus', '') }}/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Show success message with Swal and timer
                        Swal.fire({
                            title: 'Status Changed!',
                            text: 'The FAQ status has been updated.',
                            icon: 'success',
                            timer: 800,
                            showConfirmButton: false,
                        });

                        // Optionally update the state of the toggle
                        let toggleSwitch = $('#toggle-' + id);
                        if (response.status === 'active') {
                            toggleSwitch.prop('checked', true);
                        } else {
                            toggleSwitch.prop('checked', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error toggling status');
                        // Revert switch state if error occurs
                        $('#toggle-' + id).prop('checked', !$('#toggle-' + id).is(':checked'));
                    }
                });
            } else {
                // If not confirmed, revert the switch
                $('#toggle-' + id).prop('checked', !$('#toggle-' + id).is(':checked'));
            }
        });
    }
</script>

    {{-- status toggle end --}}
@endpush
