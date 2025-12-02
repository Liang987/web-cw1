@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 用户头部信息 --}}
    <div class="bg-white p-4 rounded shadow-sm mb-4 text-center">
        <h1 class="display-6">{{ $user->name }}'s Profile</h1>
        <p class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</p>
        
        {{-- 如果是管理员，显示一个小徽章 --}}
        @if($user->isAdmin())
            <span class="badge bg-danger">Administrator</span>
        @else
            <span class="badge bg-secondary">User</span>
        @endif
    </div>

    <div class="row">
        {{-- 左侧：该用户的帖子 --}}
        <div class="col-md-7">
            <h3 class="mb-3 border-bottom pb-2">Posts ({{ $posts->count() }})</h3>
            
            @forelse($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="text-muted small">{{ $post->created_at->diffForHumans() }}</p>
                        
                        {{-- 如果有图，显示缩略图 --}}
                        @if($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        @endif
                        
                        <p class="card-text text-truncate">{{ Str::limit($post->body, 100) }}</p>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">Read more</a>
                    </div>
                </div>
            @empty
                <p class="text-muted">This user hasn't posted anything yet.</p>
            @endforelse
        </div>

        {{-- 右侧：该用户的评论 --}}
        <div class="col-md-5">
            <h3 class="mb-3 border-bottom pb-2">Comments ({{ $comments->count() }})</h3>
            
            @forelse($comments as $comment)
                <div class="card mb-2 bg-light border-0">
                    <div class="card-body py-2">
                        <small class="text-muted d-block mb-1">
                            On post: <a href="{{ route('posts.show', $comment->post) }}">{{ Str::limit($comment->post->title, 20) }}</a>
                        </small>
                        <p class="mb-0 text-dark">"{{ $comment->content }}"</p>
                        <small class="text-muted" style="font-size: 0.8rem;">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted">No comments yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection