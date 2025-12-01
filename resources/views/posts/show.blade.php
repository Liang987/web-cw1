{{-- resources/views/posts/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>
        By {{ $post->user->name ?? 'Unknown user' }}
    </p>

    <p>{{ $post->body }}</p>

    <a href="{{ route('posts.edit', $post) }}">Edit this post</a>

    <hr>

    <h2>Comments ({{ $post->comments->count() }})</h2>

    @foreach ($post->comments as $comment)
        <div style="margin-bottom: 10px;">
            <strong>{{ $comment->user->name ?? 'Unknown user' }}</strong>:
            {{ $comment->content }}
        </div>
    @endforeach

    <hr>

    <h3>Add a Comment</h3>

    {{-- 显示评论相关的错误 --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('comments.store', $post) }}" method="POST">
        @csrf

        <div>
            <textarea name="content" rows="3">{{ old('content') }}</textarea>
        </div>

        <button type="submit">Submit Comment</button>
    </form>
@endsection