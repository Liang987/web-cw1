<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import related models
// 引入相关模型
use App\Models\Like;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 可批量赋值的属性。
     */
    protected $fillable = ['title', 'body', 'user_id', 'image_path'];

    /**
     * Relationship: A post belongs to a user (author).
     * 关联：帖子属于一个用户（作者）。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A post has many comments.
     * 关联：帖子拥有多个评论。
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Polymorphic relationship: Get all likes for the post.
     * 多态关联：获取该帖子的所有点赞。
     */
    public function likes()
    {
        // 'likeable' corresponds to 'likeable_id' and 'likeable_type' in the 'likes' table
        // 'likeable' 对应数据库 likes 表里的 likeable_id 和 likeable_type
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Helper method: Check if a specific user has liked this post.
     * Used for frontend to display red heart (liked) or outline heart (not liked).
     * * 辅助方法：判断某个人是否点赞过该帖子。
     * 用于前端显示实心红心（已赞）或空心心（未赞）。
     *
     * @param  mixed  $user
     * @return bool
     */
    public function isLikedBy($user)
    {
        // If user is not logged in or null, return false
        // 如果用户未登录或为 null，直接返回 false
        if (!$user) return false;

        // Check if a like record exists for this user on this post
        // 检查该用户是否对该帖子存在点赞记录
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}