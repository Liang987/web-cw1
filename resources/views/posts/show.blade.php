{{-- resources/views/posts/show.blade.php --}}
@extends('layouts.app')

@section('content')
    {{-- 返回列表 --}}
    <div class="mb-3">
        <a href="{{ route('posts.index') }}" class="btn btn-link">&larr; Back to list</a>
    </div>

    {{-- Post 卡片 --}}
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title">{{ $post->title }}</h1>

            <h6 class="card-subtitle mb-2 text-muted">
                By 
                <a href="{{ route('users.show', $post->user_id) }}">
                    {{ $post->user->name ?? 'Unknown user' }}
                </a>
                · {{ $post->created_at->toDayDateTimeString() }}
            </h6>

            @if ($post->body)
                <p class="card-text mt-3">{{ $post->body }}</p>
            @endif

            {{-- Edit 按钮（已登录才能看到） --}}
            @auth
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                    Edit
                </a>

                {{-- Delete 按钮 --}}
                <form action="{{ route('posts.destroy', $post) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this post?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Delete
                    </button>
                </form>
            @endauth
        </div>
    </div>

    {{-- 评论区域 --}}
    <div class="mb-4">
        <h3>Comments ({{ $post->comments->count() }})</h3>

        @forelse ($post->comments as $comment)
            <div class="border rounded p-2 mb-2 bg-white">
                <strong>{{ $comment->user->name ?? 'Unknown user' }}</strong>
                <small class="text-muted">
                    · {{ $comment->created_at->diffForHumans() }}
                </small>

                <p class="mb-0">{{ $comment->content }}</p>
            </div>
        @empty
            <p class="text-muted">No comments yet.</p>
        @endforelse
    </div>

    {{-- 添加评论（登录后可见） --}}
    @auth
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add a Comment</h4>

                {{-- 验证错误 --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <textarea 
                            name="content" 
                            rows="3" 
                            class="form-control"
                            placeholder="Write your comment here...">{{ old('content') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            </div>
        </div>
    @else
        <p class="text-muted">
            Please <a href="{{ route('login') }}">login</a> to add a comment.
        </p>
    @endauth
@endsection
