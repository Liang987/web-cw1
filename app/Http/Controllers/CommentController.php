<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // POST /posts/{post}/comments
    public function store(Request $request, Post $post)
    {
        // 验证评论内容（Q14 也可以在这里展示）
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $validated['post_id'] = $post->id;
        $validated['user_id'] = 1; // 暂时固定用户，之后改成 auth()->id()

        Comment::create($validated);

        return back()->with('success', 'Comment added.');
    }
}
