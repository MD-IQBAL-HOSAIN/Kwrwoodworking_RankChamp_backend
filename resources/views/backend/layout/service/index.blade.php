@extends('backend.app')

@section('title', 'All services')

<style>
    .status-toggle {
        width: 50px;
        height: 25px;
        -webkit-appearance: none;
        appearance: none;
        background-color: #ccc;
        border-radius: 50px;
        position: relative;
        cursor: pointer;
        outline: none;
        transition: background-color 0.3s ease;
    }

    .status-toggle:checked {
        background-color: #0d6efd;
        /* Bootstrap primary color when active */
    }

    .status-toggle:checked::before {
        transform: translateX(25px);
        /* Move the knob to the right */
    }

    .status-toggle::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 21px;
        height: 21px;
        border-radius: 50%;
        background-color: white;
        transition: transform 0.3s ease;
    }
</style>

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">All Services</h4>
                        <p class="card-description">The service details below</p>
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('service.create') }}" class="btn btn-primary"><i
                                    class="bi bi-plus-circle me-2"></i>Add Service</a>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($service as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ Str::limit($item->description, 50) }}{{ strlen($item->description) > 50 ? '...' : '' }}</td>
                                        <td>
                                            <img src="{{ asset($item->image) }}" width="50" height="50" alt="service image">

                                        </td>
                                        <td>
                                            <!-- Toggle Switch for status -->
                                            <input type="checkbox" class="status-toggle"
                                                data-service-id="{{ $item->id }}"
                                                {{ $item->status == 'active' ? 'checked' : '' }}
                                                onchange="showStatusChangeAlert({{ $item->id }})">
                                        </td>

                                        <td>
                                            <a href="{{ route('service.edit', $item->id) }}" class="btn btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button onclick="deleteService({{ $item->id }})" class="btn btn-danger">
                                                <i class="bi bi-trash-fill me-2"></i>
                                            </button>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-start mt-3">
                            {{ $service->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- JavaScript for SweetAlert and AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">


<script>
    // Status Change Confirm Alert
    function showStatusChangeAlert(id) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to update the status?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                statusChange(id);
            } else {
                // Optionally reset the checkbox status if the user cancels
                const checkbox = document.querySelector(`input[data-service-id="${id}"]`);
                checkbox.checked = !checkbox.checked; // revert the change
            }
        });
    }

    // Status Change Function
    function statusChange(id) {
        var url = '{{ route('service.update-status', ':id') }}';
        url = url.replace(':id', id);

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: '{{ csrf_token() }}', // CSRF Token
            },
            success: function(resp) {
                console.log(resp);
                if (resp.success) {
                    toastr.success(resp.message); // Show success message
                    // Optionally reload the table or data
                    $('#data-table').DataTable().ajax.reload();
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error("There was an error updating the status.");
            }
        });
    }
</script>


{{-- Delete Function for service --}}
<script>
    function deleteService(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = '{{ route('service.destroy', ':id') }}';
                url = url.replace(':id', id);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF Token
                        _method: 'DELETE' // To make it a DELETE request
                    },
                    success: function(resp) {
                        if (resp.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Your service has been deleted.',
                                icon: 'success',
                                timer: 700, // Time in milliseconds
                                showCancelButton: false,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload or refresh the page or table after successful deletion
                                location
                            .reload(); // or $('#data-table').DataTable().ajax.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: resp.message,
                                icon: 'error',
                                timer: 1000, // Time in milliseconds
                                showCancelButton: false,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error deleting the service.',
                            icon: 'error',
                            timer: 2000, // Time in milliseconds
                            showCancelButton: false,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    }
</script>
