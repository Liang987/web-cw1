{{-- resources/views/posts/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Posts</h1>

        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                Create New Post
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary">
                Login to Create Post
            </a>
        @endauth
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($posts->isEmpty())
        <div class="alert alert-info">
            No posts yet.
        </div>
    @else
        <div class="list-group mb-4">
            @foreach ($posts as $post)
                <div class="list-group-item list-group-item-action">

                    <h5 class="mb-1">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>

                    <small class="text-muted d-block mb-1">
                        By
                        <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none fw-bold text-dark">
                            {{ $post->user->name ?? 'Unknown' }}
                        </a>
                        · {{ $post->created_at->diffForHumans() }}
                    </small>

                    @if ($post->body)
                        <p class="mb-0 text-muted">
                            {{ \Illuminate\Support\Str::limit($post->body, 100) }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @endif
@endsection