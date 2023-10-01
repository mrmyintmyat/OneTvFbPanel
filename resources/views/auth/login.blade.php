@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="loginBox">
            <h3 class="fs-1 fw-bold">LOGIN</h3>
            @error('email')
                <span class="text-warning mb-3">
                    <strong>Your Password Wrong Or Email Not found.</strong>
                </span>
            @enderror
            @error('password')
                <span class="text-danger mb-3">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="inputBox">
                    <input id="email" type="text" class="@error('email') is-invalid @enderror" name="email"
                        @if (session('em_ph')) value="{{ session('em_ph') }}"

                      @else
                      value="{{ old('email') }}" @endif
                        required autocomplete="email" autofocus placeholder="Email">

                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password"
                        required autocomplete="current-password" placeholder="Password">
                    <div class="form-check p-0">
                        <input style="width: 20px;" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </div>
                </div>
                <input type="submit" class="rounded-1" name="" value="Login">
            </form>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
    <script>
        var timezone = moment.tz.guess();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "/set-timezone",
            type: "POST",
            data: {
                timezone: timezone
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
            },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle errors if needed
                console.log(error);
            }
        });
    </script>
@endsection
