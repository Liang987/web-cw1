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
            <div class="card-text mt-4">
                {{ $post->body }}
            </div>

            @can('update', $post)
                <div class="mt-3">
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

    <h3>Comments</h3>
    
    <div id="comments-list" class="mb-4">
        @forelse($post->comments as $comment)
            <div class="card mb-2">
                <div class="card-body py-2">
                    <strong>
                        <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark">
                            {{ $comment->user->name }}
                        </a>
                    </strong>
                    
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    <p class="mb-0">{{ $comment->content }}</p> 
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
                        <span class="text-danger" id="comment-error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Post Comment</button>
                </form>
            </div>
        </div>
    @else
        <p><a href="{{ route('login') }}">Login</a> to comment.</p>
    @endauth
</div>

<script>
    document.getElementById('comment-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const bodyInput = document.getElementById('comment-body');
        const errorSpan = document.getElementById('comment-error');
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
            errorSpan.textContent = '';
            if (noCommentsText) noCommentsText.remove();

            const newCommentHtml = `
                <div class="card mb-2" style="background-color: #f0fdf4;">
                    <div class="card-body py-2">
                        <strong>${data.user_name}</strong>
                        <small class="text-muted">Just now</small>
                        <p class="mb-0">${data.content}</p>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', newCommentHtml);
        })
        .catch(async error => {
            console.error(error);
            alert('Error posting comment');
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    });
</script>
@endsection