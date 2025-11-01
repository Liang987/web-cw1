<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
</head>
<body>
    <h1>All Posts</h1>

    {{-- 成功提示 --}}
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <p>
        <a href="{{ route('posts.create') }}">Create a new post</a>
    </p>

    @foreach ($posts as $post)
        <div style="border:1px solid #ccc; margin-bottom:20px; padding:10px;">
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->body }}</p>
            <p><strong>Author:</strong> {{ $post->author }}</p>

            <h4>Comments:</h4>
            <ul>
                @forelse ($post->comments as $comment)
                    <li>{{ $comment->content }} — <em>{{ $comment->author }}</em></li>
                @empty
                    <li>No comments yet.</li>
                @endforelse
            </ul>

            {{-- 在这一篇帖子下面添加评论的表单 --}}
            <h5>Add a comment:</h5>
            <form action="{{ route('comments.store') }}" method="POST" style="margin-top: 8px;">
                @csrf
                {{-- 这条是关键：告诉后端这条评论是属于哪篇帖子的 --}}
                <input type="hidden" name="post_id" value="{{ $post->id }}">

                <div>
                    <input type="text" name="author" placeholder="Your name" value="{{ old('author') }}">
                </div>
                <div style="margin-top: 4px;">
                    <input type="text" name="content" placeholder="Write a comment" value="{{ old('content') }}" style="width: 60%;">
                </div>
                <button type="submit" style="margin-top: 4px;">Add Comment</button>
            </form>
        </div>
    @endforeach
</body>
</html>
