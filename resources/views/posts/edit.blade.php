{{-- resources/views/posts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Title</label><br>
            <input type="text" name="title" value="{{ old('title', $post->title) }}">
        </div>

        <div>
            <label>Body</label><br>
            <textarea name="body" rows="4">{{ old('body', $post->body) }}</textarea>
        </div>

        <button type="submit">Update</button>
    </form>
@endsection
