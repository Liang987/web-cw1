<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id', 'image_path'];

    // 关联：帖子属于用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 关联：帖子有多个评论
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    
    // 获取该帖子的所有点赞
    public function likes()
    {
        // 'likeable' 对应数据库 likes 表里的 likeable_id 和 likeable_type
        return $this->morphMany(Like::class, 'likeable');
    }

    // 2. 辅助方法：判断某个人是否点赞过（用于前端显示红心还是空心）
    public function isLikedBy($user)
    {
        if (!$user) return false;

        return $this->likes()->where('user_id', $user->id)->exists();
    }
}