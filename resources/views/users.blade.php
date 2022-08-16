
@extends('layouts.dashboard')

@section('content')
    <div class="container-fuild pt-4 px-4">
        <div class="bg-light rounded p-4">
            <div class="row d-flex align-items-center justify-content-between mb-4">
                <div class="col-md-6 d-flex justify-content-center flex-column">
                    <form method="post" action="{{ route('search-users') }}">
                        @csrf
                        <h6 class="mt-3 text-left">Search user</h6>
                        <label>ID / Name / Email</label>
                        <div class="d-flex">
                            <input type="text" name="value" id="value" class="form-control"/>
                            <button class="btn btn-secondary" style="margin-left: 8px;"><i class="fa fa-search"></i></button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Search Results</h6>
                <a href="{{ route('view-create-user') }}" class="btn btn-secondary">Create user</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                    <tr class="text-dark">
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Registration Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
