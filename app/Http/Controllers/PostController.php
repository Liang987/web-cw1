<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts  —— 显示所有帖子
    public function index()
    {
        $posts = Post::with('comments')->latest()->get();

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    // GET /posts/create —— 显示创建表单
    public function create()
    {
        return view('posts.create');
    }

    // POST /posts —— 处理表单提交
    public function store(Request $request)
    {
        // 1. 验证表单
        $validated = $request->validate([
            'title' => 'required|min:3',
            'body' => 'nullable',
            'author' => 'required',
        ]);

        // 2. 存数据库
        Post::create($validated);

        // 3. 跳回列表页并给个提示
        return redirect('/posts')->with('success', 'Post created successfully!');
    }
}
