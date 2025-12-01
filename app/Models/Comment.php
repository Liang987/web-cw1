<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'content',
        'user_id',    // ✅ 使用外键
    ];

    // 每条评论属于一个帖子
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 每条评论属于一个用户（评论者）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
