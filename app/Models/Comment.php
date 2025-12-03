<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'post_id'];

    // 关联：评论属于帖子
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 关联：评论属于用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 获取该评论的所有点赞
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // 辅助方法：判断某个人是否点赞过
    public function isLikedBy($user)
    {
        if (!$user) return false;
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}