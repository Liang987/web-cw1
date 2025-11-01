<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'content',
        'author',
    ];

    // 每条评论属于一篇帖子
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
