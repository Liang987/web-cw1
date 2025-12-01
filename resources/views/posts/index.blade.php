{{-- resources/views/posts/index.blade.php --}}
@extends('layouts.app') {{-- 如果你还没有 layout，可以先去掉这一行 --}}

@section('content')
    <h1>Posts</h1>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('posts.create') }}">Create New Post</a>

    <ul>
        @foreach ($posts as $post)
            <li>
                <h3>
                    <a href="{{ route('posts.show', $post) }}">
                        {{ $post->title }}
                    </a>
                </h3>
                <p>
                    By <a href="{{ route('users.show', $post->user_id) }}">
                        {{ $post->user->name ?? 'Unknown user' }}
                    </a>
                </p>
            </li>
        @endforeach
    </ul>
@endsection
