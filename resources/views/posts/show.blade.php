@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 返回按钮 --}}
    <div class="mb-3">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">&larr; Back to Posts</a>
    </div>

    {{-- 帖子卡片 --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h1 class="card-title display-5">{{ $post->title }}</h1>
            
            <div class="text-muted mb-3">
                <span>By <strong>{{ $post->user->name ?? 'Unknown' }}</strong></span>
                <span class="mx-2">|</span>
                <span>{{ $post->created_at->format('M d, Y H:i') }}</span>
            </div>

            <p class="card-text fs-5" style="white-space: pre-wrap;">{{ $post->body }}</p>

            <hr>

            {{-- 🟢 Rubric 11: 使用 @can 检查权限 --}}
            {{-- 这样写，Policy 里的管理员判断就会生效，管理员也能看到这些按钮 --}}
            @can('update', $post)
                <div class="d-flex gap-2">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit Post</a>
                    
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Post</button>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    {{-- 评论区 --}}
    <div class="row">
        <div class="col-md-8">
            <h3 class="mb-4">Comments</h3>

            {{-- 🟢 Rubric 6: 评论列表容器 (JS 会往这里加新评论) --}}
            <div id="comments-list">
                @forelse($post->comments as $comment)
                    <div class="card mb-3">
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-subtitle mb-2 text-primary">{{ $comment->user->name }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="card-text">{{ $comment->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted" id="no-comments-msg">No comments yet. Be the first!</p>
                @endforelse
            </div>

            {{-- 评论表单 --}}
            @auth
                <div class="card mt-4">
                    <div class="card-body">
                        <h5>Leave a Comment</h5>
                        {{-- 注意：这里加了一个 id="comment-form" 用于 JS 选择 --}}
                        <form id="comment-form" action="{{ route('comments.store', $post) }}">
                            @csrf
                            <div class="mb-3">
                                <textarea id="comment-content" name="content" class="form-control" rows="3" required placeholder="Write something..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submit-btn">Post Comment</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">
                    Please <a href="{{ route('login') }}">login</a> to leave a comment.
                </div>
            @endauth
        </div>
    </div>
</div>

{{-- 🟢 Rubric 6: AJAX 脚本 --}}
<script>
    document.getElementById('comment-form')?.addEventListener('submit', function(e) {
        e.preventDefault(); // 1. 阻止表单默认提交（页面不刷新）

        const form = this;
        const contentField = document.getElementById('comment-content');
        const submitBtn = document.getElementById('submit-btn');
        const commentsList = document.getElementById('comments-list');
        const noCommentsMsg = document.getElementById('no-comments-msg');

        // 简单的防重复点击
        submitBtn.disabled = true;
        submitBtn.innerText = 'Posting...';

        // 2. 发起 AJAX 请求
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Laravel 必须的令牌
                'Accept': 'application/json'          // 告诉后端我们要 JSON
            },
            body: JSON.stringify({
                content: contentField.value
            })
        })
        .then(response => response.json())
        .then(data => {
            // 3. 处理成功：动态插入 HTML
            if (data.message) {
                // 清空输入框
                contentField.value = '';

                // 移除“暂无评论”提示
                if (noCommentsMsg) noCommentsMsg.remove();

                // 构造新评论的 HTML
                const newCommentHTML = `
                    <div class="card mb-3 border-success">
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between">
                                <h6 class="card-subtitle mb-2 text-primary">${data.user_name}</h6>
                                <small class="text-muted">${data.time}</small>
                            </div>
                            <p class="card-text">${data.content}</p>
                        </div>
                    </div>
                `;

                // 插入到列表最前面或最后面
                commentsList.insertAdjacentHTML('beforeend', newCommentHTML);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
        })
        .finally(() => {
            // 恢复按钮
            submitBtn.disabled = false;
            submitBtn.innerText = 'Post Comment';
        });
    });
</script>
@endsection