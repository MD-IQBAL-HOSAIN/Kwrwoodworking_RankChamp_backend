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
                        <h4 class="card-title">All User</h4>
                        <p class="card-description">The user details below</p>
                       {{--  <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('service.create') }}" class="btn btn-primary"><i
                                    class="bi bi-plus-circle me-2"></i>User List</a>
                        </div> --}}

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">SL</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Image</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user as $key=>$u)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->role }}</td>
                                    <td>
                                        @if($u->image)
                                            <img src="{{ asset( $u->image) }}" width="50" height="50" alt="user image">
                                        @else
                                            <p>No image</p>
                                        @endif
                                    </td>                                
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $user->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection