@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title">{{ $post->title }}</h1>
            
            <p class="text-muted">
                By 
                <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none fw-bold text-dark">
                    {{ $post->user->name ?? 'Unknown' }}
                </a> 
                | {{ $post->created_at->format('M d, Y') }}
            </p>
            
            @if ($post->image_path)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $post->image_path) }}" 
                         alt="Post Image" 
                         class="img-fluid rounded" 
                         style="max-height: 400px; object-fit: cover;">
                </div>
            @endif
            
            <div class="card-text mt-4 fs-5">
                {{ $post->body }}
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
                {{-- 帖子点赞按钮 --}}
                @auth
                    @php
                        $likedPost = $post->isLikedBy(auth()->user());
                    @endphp
                    <button 
                        class="btn btn-outline-danger like-btn"
                        data-id="{{ $post->id }}"
                        data-type="post"
                        data-url="{{ route('posts.like', $post) }}">
                        <span class="heart" data-liked="{{ $likedPost ? '1' : '0' }}">
                            {{ $likedPost ? '❤️' : '🤍' }}
                        </span>
                        Like 
                        <span class="like-count">{{ $post->likes()->count() }}</span>
                    </button>
                @else
                    <button class="btn btn-outline-secondary" disabled>
                        🤍 Likes {{ $post->likes()->count() }}
                    </button>
                @endauth

                {{-- 编辑/删除按钮 --}}
                @can('update', $post)
                    <div>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <h3>Comments</h3>
    
    <div id="comments-list" class="mb-4">
        @forelse($post->comments as $comment)
            <div class="card mb-2">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <strong>
                            <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark">
                                {{ $comment->user->name }}
                            </a>
                        </strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <p class="mb-2">{{ $comment->content }}</p>

                    {{-- 评论点赞按钮 --}}
                    @auth
                        @php
                            $likedComment = $comment->isLikedBy(auth()->user());
                        @endphp
                        <button 
                            class="btn btn-sm btn-outline-danger like-btn"
                            data-id="{{ $comment->id }}"
                            data-type="comment"
                            data-url="{{ route('comments.like', $comment) }}">
                            <span class="heart" data-liked="{{ $likedComment ? '1' : '0' }}">
                                {{ $likedComment ? '❤️' : '🤍' }}
                            </span>
                            <span class="like-count">{{ $comment->likes()->count() }}</span>
                        </button>
                    @else
                        <small class="text-muted">🤍 {{ $comment->likes()->count() }}</small>
                    @endauth
                </div>
            </div>
        @empty
            <p class="text-muted" id="no-comments-text">No comments yet.</p>
        @endforelse
    </div>

    @auth
        <div class="card">
            <div class="card-body">
                <h5>Add a Comment</h5>
                <form id="comment-form" action="{{ route('comments.store', $post) }}">
                    @csrf
                    <div class="form-group mb-2">
                        <textarea id="comment-body" name="content" class="form-control" rows="3" required placeholder="Write something..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Post Comment</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Please <a href="{{ route('login') }}">login</a> to like or comment.
        </div>
    @endauth
</div>

{{-- JavaScript 区域 --}}
<script>
    // 点赞功能 JS
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            const countSpan = this.querySelector('.like-count');
            const heart = this.querySelector('.heart');
            const btn = this;

            btn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // 数字更新
                countSpan.innerText = data.count;

                // 空心 / 实心切换
                if (data.liked) {
                    heart.textContent = '❤️';
                    heart.dataset.liked = '1';
                } else {
                    heart.textContent = '🤍';
                    heart.dataset.liked = '0';
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                btn.disabled = false;
            });
        });
    });

    // 评论提交 JS
    document.getElementById('comment-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const bodyInput = document.getElementById('comment-body');
        const list = document.getElementById('comments-list');
        const noCommentsText = document.getElementById('no-comments-text');
        const submitBtn = document.getElementById('submit-btn');

        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: bodyInput.value }) 
        })
        .then(response => {
            if (!response.ok) throw response;
            return response.json();
        })
        .then(data => {
            bodyInput.value = '';
            if (noCommentsText) noCommentsText.remove();

            const newCommentHtml = `
                <div class="card mb-2" style="background-color: #f0fdf4;">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <strong>${data.user_name}</strong>
                            <small class="text-muted">Just now</small>
                        </div>
                        <p class="mb-2">${data.content}</p>
                        <button class="btn btn-sm btn-outline-danger" disabled>
                            🤍 0 (Refresh to like)
                        </button>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', newCommentHtml);
        })
        .catch(error => alert('Error posting comment'))
        .finally(() => {
            submitBtn.disabled = false;
        });
    });
</script>
@endsection
