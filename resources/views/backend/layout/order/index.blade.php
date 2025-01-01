@extends('backend.app')

@section('title', 'Order Table')

@push('style')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-primary fw-bold">All Orders</h4>
                        <p class="card-description text-muted">The Orders details below</p>
                        <table id="order-table" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr class=" text-white">
                                    <th class=" text-white">S/L</th>
                                    <th class=" text-white">Receiver Name</th>
                                    <th class=" text-white">Product</th>
                                    <th class=" text-white">Order Number</th>
                                    <th class=" text-white">Total Amount</th>
                                    <th class=" text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $order->receiver_name }}</td>
                                        <td>
                                            @php
                                                
                                                $productNames = \App\Models\Product::whereIn('id', json_decode($order->products))->pluck('title')->toArray();
                                                $product_names = implode(', ', $productNames);
                                            @endphp
                                            {{ $product_names }}
                                        </td>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->total_amount }}</td>

                                        <td>
                                            @if ($order->status != 'delivered')
                                                <form id="statusForm-{{ $order->id }}"
                                                    action="{{ route('order.update-status', $order->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select"
                                                        onchange="updateStatus({{ $order->id }})">
                                                        <option value="pending"
                                                            {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                            <i class="bi bi-clock"></i> Pending
                                                        </option>
                                                        <option value="approved"
                                                            {{ $order->status == 'approved' ? 'selected' : '' }}>
                                                            Approved
                                                        </option>
                                                        <option value="cancelled"
                                                            {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                            Cancelled
                                                        </option>
                                                    </select>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#order-table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pagingType": "full_numbers",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search orders..."
                }
            });
        });
    </script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updateStatus(orderId) {
            var status = $('select[name="status"]').val(); // Get selected status
            $.ajax({
                url: '/order/update-status/' + orderId,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    // SweetAlert success message
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        timer: 1000, // 1 second
                        confirmButtonText: 'OK'
                    }).then(() => {

                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // SweetAlert error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error updating the order status.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
@endpush
