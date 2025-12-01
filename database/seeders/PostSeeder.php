<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // 拿到所有用户（前面 UserSeeder 已经创建了）
        $users = User::all();

        // 保险：如果没有用户，先造几个
        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        // 1) 使用 factory 批量生成帖子 + 评论
        Post::factory()
            ->count(3)
            ->has(
                Comment::factory()->count(3)
            )
            ->create();

        // 2) 手动创建一条示例帖子，明确指定作者
        $author = $users->first(); // 随便拿一个作为作者

        $post = Post::create([
            'title'   => 'Welcome to the Blog',
            'body'    => 'This is a manually added post to verify seeding relationships.',
            'user_id' => $author->id,
        ]);

        // 为这条帖子创建两条评论，分别指定不同用户
        $post->comments()->createMany([
            [
                'content' => 'First comment on this post!',
                'user_id' => $users->get(1, $author)->id, // 如果没有就用 author
            ],
            [
                'content' => 'Looks great, thanks for sharing.',
                'user_id' => $users->get(2, $author)->id,
            ],
        ]);
    }
}
