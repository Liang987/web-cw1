@extends('layouts.app')

@section('content')
    <h1>User: {{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>

    <hr>

    <h2>Posts by {{ $user->name }}</h2>
    <ul>
        @forelse ($user->posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </li>
        @empty
            <p>No posts yet.</p>
        @endforelse
    </ul>

    <hr>

    <h2>Comments by {{ $user->name }}</h2>
    <ul>
        @forelse ($user->comments as $comment)
            <li>
                On <a href="{{ route('posts.show', $comment->post) }}">
                    {{ $comment->post->title }}
                </a>:
                {{ $comment->content }}
            </li>
        @empty
            <p>No comments yet.</p>
        @endforelse
    </ul>
@endsection
