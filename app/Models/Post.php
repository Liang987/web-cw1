<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    // 允许批量赋值的字段
    protected $fillable = [
        'title',
        'body',
        'user_id',   // ✅ 使用外键而不是 author 字符串
    ];

    // 一篇帖子有很多评论
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 一篇帖子属于一个用户（作者）
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
