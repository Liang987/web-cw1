<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import related models
// 引入相关模型
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 可批量赋值的属性。
     */
    protected $fillable = ['content', 'user_id', 'post_id'];

    /**
     * Relationship: A comment belongs to a post.
     * 关联：评论属于帖子。
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relationship: A comment belongs to a user (author).
     * 关联：评论属于用户（作者）。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relationship: Get all likes for the comment.
     * 多态关联：获取该评论的所有点赞。
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Helper method: Check if a specific user has liked this comment.
     * 辅助方法：判断某个人是否点赞过该评论。
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function isLikedBy($user)
    {
        // If user is not logged in, return false
        // 如果用户未登录，直接返回 false
        if (!$user) return false;
        
        // Check if a like record exists for this user
        // 检查该用户是否存在点赞记录
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}