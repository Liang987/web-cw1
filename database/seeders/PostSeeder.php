<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class PostSeeder extends Seeder
{
    /**
     * English: Generate posts and comments using factories
     * 中文：使用 Factory 自动生成帖子和评论数据
     */
    public function run(): void
    {
        // Create 5 random posts, each with 3 comments
        // 创建 5 条随机帖子，每条包含 3 条评论
        Post::factory(5)
            ->has(Comment::factory(3))
            ->create();

        // Manually add one clear example post
        // 手动添加一条示例帖子，验证关系正确
        Post::create([
            'title' => 'Welcome to the Blog',
            'body' => 'This is a manually added post to verify seeding relationships.',
            'author' => 'Admin',
        ])->comments()->createMany([
            ['content' => 'First comment on this post!', 'author' => 'Alice'],
            ['content' => 'Looks great, thanks for sharing.', 'author' => 'Bob'],
        ]);
    }
}
