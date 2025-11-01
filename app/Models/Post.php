<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    // English: Fields allowed for mass assignment
    // 中文：允许批量赋值的字段（可通过 create() 一次性写入）
    protected $fillable = [
        'title',
        'body',
        'author',
    ];

    // English: One Post has many Comments
    // 中文：一个帖子可以有多条评论（hasMany）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
