<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;
        $comment->save();

        // 只有当“评论者”不是“帖子作者”时才发送 (自己评自己不需要通知)
        if (auth()->id() !== $post->user_id) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Comment added successfully!',
                'user_name' => auth()->user()->name,
                'content' => $comment->content,
                'time' => 'Just now',
            ]);
        }

        return back()->with('success', 'Comment added.');
    }
}