<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    // 允许批量赋值的字段
    protected $fillable = [
        'title',
        'body',
        'author',
    ];

    // 一个帖子有很多评论
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
