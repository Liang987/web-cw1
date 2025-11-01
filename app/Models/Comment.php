<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    // English: Fields allowed for mass assignment
    // 中文：允许批量赋值的字段
    protected $fillable = [
        'post_id',
        'content',
        'author',
    ];

    // English: Each Comment belongs to one Post
    // 中文：每条评论属于一篇帖子（belongsTo）
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
