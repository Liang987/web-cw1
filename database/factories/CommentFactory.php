<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 关联逻辑：优先随机取现有 Post
            'post_id' => Post::inRandomOrder()->first()?->id ?? Post::factory(),

            'content' => $this->faker->sentence(),

            // 关联逻辑：优先随机取现有 User
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),

            // 🟢 评论时间也随机一下
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}