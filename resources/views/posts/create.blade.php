<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a new Post</h1>

    {{-- 显示验证错误 --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div>
            <label>Title:</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
        </div>

        <div>
            <label>Body:</label><br>
            <textarea name="body" rows="4" cols="40">{{ old('body') }}</textarea>
        </div>

        <div>
            <label>Author:</label><br>
            <input type="text" name="author" value="{{ old('author', 'liang') }}">
        </div>

        <button type="submit">Save</button>
    </form>

    <p><a href="{{ route('posts.index') }}">← Back to posts</a></p>
</body>
</html>
