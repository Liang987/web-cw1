@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Post</h1>
    <div class="card p-4">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image (Optional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="body" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Post</button>
        </form>
    </div>
</div>
@endsection