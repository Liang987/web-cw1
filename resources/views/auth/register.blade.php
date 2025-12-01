@extends('layouts.app')

@section('content')
    <h1>Register</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="mt-3">
        @csrf

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
@endsection
