<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建一篇帖子
        $post = Post::create([
            'title' => 'My first post',
            'body' => 'This post was inserted by a database seeder.',
            'author' => 'liang',
        ]);

        // 给这篇帖子加两条评论
        Comment::create([
            'post_id' => $post->id,
            'content' => 'Nice work!',
            'author' => 'teacher',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'content' => 'Try adding authentication later.',
            'author' => 'classmate',
        ]);
    }
}
