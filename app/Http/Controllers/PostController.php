<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 必须引用这个

class PostController extends Controller
{
    use AuthorizesRequests; // 开启权限验证功能

    // 1. 帖子列表页
    public function index()
    {
        // 预加载 user，防止 N+1 查询问题 (Rubric: Working with Data)
        // latest() 保证最新的在前面
        // paginate(10) 实现分页 (Rubric: Progress 5)
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    // 2. 显示发帖表单
    public function create()
    {
        return view('posts.create');
    }

    // 3. 保存新帖子
    public function store(Request $request)
    {
        // 表单验证 (Rubric: Working with Data 14)
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        // 自动关联当前登录用户 (Rubric: User Presence 9)
        $request->user()->posts()->create($validated);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    // 4. 显示单篇帖子详情
    public function show(Post $post)
    {
        // 加载这篇帖子的评论，以及评论的作者
        $post->load('comments.user');
        return view('posts.show', compact('post'));
    }

    // 5. 编辑页面
    public function edit(Post $post)
    {
        // 权限检查：如果不符合 Policy 规则，直接报错 403
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    // 6. 更新逻辑
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Post updated!');
    }

    // 7. 删除逻辑
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}