<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // English: Store a new comment
    // 中文：保存一条新评论
    public function store(Request $request)
    {
        // Validate user input
        // 验证输入字段
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'author' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        // Save comment into database
        // 将评论保存到数据库
        Comment::create($validated);

        // Redirect back with success message
        // 返回帖子页并显示成功提示
        return redirect('/posts')->with('success', 'Comment added!');
    }
}
