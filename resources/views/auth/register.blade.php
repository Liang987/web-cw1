@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Register</div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- 名字 --}}
                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- 邮箱 --}}
                    {{-- email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- 密码 --}}
                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        <small class="text-muted">Min 8 characters.</small>
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- 确认密码 (name 是 password_confirmation) --}}
                    {{-- password-confirm --}}
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>

                    <div class="text-center">
                        Already have an account? <a href="{{ route('login') }}">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection