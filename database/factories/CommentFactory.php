<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     * 定义模型的默认状态。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Logic for Post association: Randomly pick an existing Post, or create a new one if none exist.
            // 关联逻辑：优先随机取现有 Post，如果没有则新建一个。
            'post_id' => Post::inRandomOrder()->first()?->id ?? Post::factory(),

            // Generate a random sentence for the comment content.
            // 生成随机的句子作为评论内容。
            'content' => $this->faker->sentence(),

            // Logic for User association: Randomly pick an existing User, or create a new one if none exist.
            // 关联逻辑：优先随机取现有 User，如果没有则新建一个。
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),

            // 🟢 Randomize the creation time to make the data look more realistic (within the last year).
            // 🟢 评论时间也随机一下，让数据看起来更真实（过去一年内）。
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            
            // Set updated_at to match created_at.
            // 设置 updated_at 与 created_at 一致。
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}