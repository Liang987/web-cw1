<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    // English: Define default fake data for comments
    // 中文：定义评论模型的假数据生成逻辑
    public function definition(): array
    {
        return [
            'post_id' => Post::inRandomOrder()->first()?->id ?? 1, // 随机关联一个帖子 Randomly associate a post
            'content' => $this->faker->sentence(),                  // 随机评论内容 Random comment content
            'author' => $this->faker->firstName(),                  // 随机评论人 Random Commenter
        ];
    }
}
