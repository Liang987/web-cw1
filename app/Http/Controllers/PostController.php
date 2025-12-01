<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts
    public function index()
    {
        // 先简单点，用 get()，之后再改 paginate()
        $posts = Post::with('user')->latest()->get();

        return view('posts.index', compact('posts'));
    }

    // GET /posts/create
    public function create()
    {
        return view('posts.create');
    }

    // POST /posts
    public function store(Request $request)
    {
        // Q14: 数据验证
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
        ]);

        // 暂时先写死一个 user_id（之后换成 auth()->id()）
        $validated['user_id'] = 1;

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    // GET /posts/{post}
    public function show(Post $post)
    {
        // 带上作者和评论以及评论的作者
        $post->load(['user', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    // GET /posts/{post}/edit
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // PUT /posts/{post}
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    // 先不实现 destroy，有需要后面再加
    public function destroy(Post $post)
    {
        //
    }
}
