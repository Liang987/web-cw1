<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // 点赞/取消点赞 帖子
    public function togglePost(Post $post)
    {
        return $this->toggleLike($post);
    }

    // 点赞/取消点赞 评论
    public function toggleComment(Comment $comment)
    {
        return $this->toggleLike($comment);
    }

    // 通用逻辑：判断是添加还是删除
    private function toggleLike($model)
    {
        $user = auth()->user();

        // 查找当前用户是否已经点赞了这个模型
        // morphMany 关联会自动处理 likeable_id 和 likeable_type
        $like = $model->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // 如果点过赞 -> 删除 (取消点赞)
            $like->delete();
            $liked = false;
        } else {
            // 如果没点过 -> 创建 (点赞)
            $model->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        // 返回 JSON 给前端 JS 更新按钮状态
        return response()->json([
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }
}