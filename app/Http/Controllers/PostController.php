<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // English: Display all posts and related comments
    // 中文：显示所有帖子及其评论
    public function index()
    {
        // Retrieve all posts with their comments
        // 获取所有帖子，并预加载评论数据
        $posts = Post::with('comments')->latest()->get();

        return view('posts.index', ['posts' => $posts]);
    }

    // English: Display post creation form
    // 中文：显示创建新帖子的表单
    public function create()
    {
        return view('posts.create');
    }

    // English: Handle post creation form submission
    // 中文：处理表单提交并保存新帖子
    public function store(Request $request)
    {
        // Validate form inputs
        // 验证表单字段
        $validated = $request->validate([
            'title' => 'required|min:3',
            'body' => 'nullable',
            'author' => 'required',
        ]);

        // Create a new post
        // 创建新帖子
        Post::create($validated);

        // Redirect to posts page with success message
        // 重定向到帖子列表并显示成功提示
        return redirect('/posts')->with('success', 'Post created successfully!');
    }
}
