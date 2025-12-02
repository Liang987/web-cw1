@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Post</h1>
    <div class="card p-4">
        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            </div>

            @if ($post->image_path)
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div class="d-block">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Current Image" style="max-height: 200px; border-radius: 5px;">
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Change Image (Leave empty to keep current)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="body" class="form-control" rows="5" required>{{ old('body', $post->body) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Post</button>
            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection