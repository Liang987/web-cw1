<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        // 1. 表单验证
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'author'  => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        // 2. 创建评论
        Comment::create($validated);

        // 3. 回到帖子列表页，并带成功消息
        return redirect('/posts')->with('success', 'Comment added!');
    }
}
