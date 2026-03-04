@extends('layouts.app')
@section('content')
    <div class="form mt-10 mb-10" id="signinmodal">

        <form class="card" method="POST" action="/signin">
            @csrf

            {{-- allert success registrer --}}
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- allert login eror --}}
            @if (session()->has('signinneror'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('signinneror') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <h1>Welcome</h1>
            <p>Please Sign in</p>


            <label for="email">User</label>
            <input class=" @error('email') is-invalid @enderror" name="email" type="email" id="email"
                placeholder="email" autofocus required value="{{ old('email') }}">
            @error('email')
                <div class="invalid-tooltip">
                    {{ $message }}
                </div>
            @enderror

            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="password">

            {{-- <div class="actions">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forget Password?</a>
            </div> --}}

            <button class="btn" type="submit">Sign in</button>

            {{-- <div class="alt">
                Not Registered yet ? <a href="/signup">sign up</a>
            </div> --}}
        </form>
    </div>
@endsection
