<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like status for a Post.
     * 切换帖子的点赞状态 (点赞/取消点赞)。
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePost(Post $post)
    {
        return $this->toggleLike($post);
    }

    /**
     * Toggle like status for a Comment.
     * 切换评论的点赞状态 (点赞/取消点赞)。
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleComment(Comment $comment)
    {
        return $this->toggleLike($comment);
    }

    /**
     * Common logic to toggle like status for any likeable model.
     * 切换任何可点赞模型的点赞状态的通用逻辑。
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\JsonResponse
     */
    private function toggleLike($model)
    {
        $user = auth()->user();

        // Check if the user has already liked this model
        // Using morphMany relationship, Laravel automatically handles 'likeable_id' and 'likeable_type'
        // 检查当前用户是否已经点赞了这个模型
        // 使用 morphMany 多态关联，Laravel 会自动处理 'likeable_id' 和 'likeable_type'
        $like = $model->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // If already liked, delete the like (Unlike)
            // 如果已经点赞，则删除该点赞记录 (取消点赞)
            $like->delete();
            $liked = false;
        } else {
            // If not liked, create a new like (Like)
            // 如果未点赞，则创建一条新的点赞记录 (点赞)
            $model->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        // Return JSON response for frontend AJAX to update the button status
        // 返回 JSON 响应，供前端 AJAX 更新按钮状态
        return response()->json([
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }
}