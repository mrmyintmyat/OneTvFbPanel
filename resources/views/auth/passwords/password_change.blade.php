@extends('layouts.home')
@section('style')
    <style>
        .hover_menu_tag a:nth-child(9) {
            border-left: 3px solid #ff0505 !important;
            background: rgba(255, 255, 255, 0.251);
        }
    </style>
@endsection
@section('page')
    <div class="card text-start rounded-0 border-0 px-2" style="background: #ffffff00;">
        <div class="card-body">
            <div class="border-bottom border-2">
                <h5 class="">USER INFO</h5>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <form class="col-lg-6 col-12" method="POST" action="{{ route('password.change') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="exampleInputPassword1" required value="{{ Auth::user()->name }}">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Current Password</label>
                        <input type="password" name="org_pw" class="form-control @error('org_pw') is-invalid @enderror" id="exampleInputPassword1" required value="{{ old('org_pw') }}">

                        @error('org_pw')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" required value="{{ old('password') }}" autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password Confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="exampleInputPassword1" required value="{{ old('password_confirmation') }}" autocomplete="new-password">

                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    <button type="submit" class="btn bg-menu border-0 w-100 fw-medium py-2">UPDATE</button>
                </form>
            </div>
        </div>
    </div>
@endsection
