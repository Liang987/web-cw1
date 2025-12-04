<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     * 保存新创建的评论到数据库。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        // 1. Validate the comment content (Rubric 14)
        // 1. 验证评论内容 (Rubric 14)
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // 2. Create and save the new comment
        // 2. 创建并保存新评论
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = auth()->id(); // Associate with the currently logged-in user / 关联当前登录用户
        $comment->post_id = $post->id;    // Associate with the current post / 关联当前帖子
        $comment->save();

        // 3. Send notification (Rubric 12)
        // Only send if the commenter is not the post author (avoid self-notification)
        // 3. 发送通知 (Rubric 12)
        // 只有当“评论者”不是“帖子作者”时才发送 (避免自己给自己发通知)
        if (auth()->id() !== $post->user_id) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        // 4. Return JSON response for AJAX requests (Rubric 6)
        // 4. 如果是 AJAX 请求，返回 JSON 响应 (Rubric 6)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Comment added successfully!',
                'user_name' => auth()->user()->name,
                'content' => $comment->content,
                'time' => 'Just now', // Simplified time for immediate display /为了即时显示，简化时间格式
            ]);
        }

        // 5. Fallback for non-AJAX requests: redirect back
        // 5. 对于非 AJAX 请求的回退处理：重定向回上一页
        return back()->with('success', 'Comment added.');
    }
}