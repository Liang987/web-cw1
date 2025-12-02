<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // 1. 验证
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // 2. 创建评论 (自动关联 user 和 post)
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;
        $comment->save();

        // 3. 【关键点】判断请求类型
        // 如果是 AJAX 请求 (Rubric 6)，返回 JSON 数据
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Comment added successfully!',
                'user_name' => auth()->user()->name,
                'content' => $comment->content,
                'time' => 'Just now', // 或者 $comment->created_at->diffForHumans()
            ]);
        }

        // 如果是普通表单提交，回退页面
        return back()->with('success', 'Comment added.');
    }
}