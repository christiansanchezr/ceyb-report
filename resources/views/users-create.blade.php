@extends('layouts.dashboard');

@section('content')
    <div class="container-fluid">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-10">
                <form class="bg-light rounded p-4 p-sm-5 my-4 mx-3" method="post" action="{{ route('create-user') }}">
                    @csrf
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <h3>Create user</h3>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Full Name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        <label for="floatingInput">Full Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" required autocomplete="current-password">
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password_confirmation" placeholder="Password" name="password_confirmation" required autocomplete="current-password">
                        <label for="floatingPassword">Confirm Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <select class="form-control" name="role" required id="role">
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingRole">Select a Role</label>
                    </div>
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Create</button>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
