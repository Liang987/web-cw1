{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>

    {{-- Bootstrap 5 CSS（CDN，引入一个 CSS 框架满足 Q5 要求） --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('posts.index') }}">My Blog</a>

            <div class="d-flex">
                @auth
                    <span class="navbar-text me-3">
                        Hello, {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>


    <div class="container">
        @yield('content')
    </div>

    {{-- 可选：Bootstrap JS（目前用不到也可以不加） --}}
    {{-- 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    --}}
</body>
</html>
