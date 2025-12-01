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
            // 如果外面没指定 post，会自动创建一个 Post
            'post_id' => Post::inRandomOrder()->first()?->id
                         ?? Post::factory(),

            'content' => $this->faker->sentence(),

            // 为评论随机指定一个用户（或者自动创建）
            'user_id' => User::inRandomOrder()->first()?->id
                         ?? User::factory(),
        ];
    }
}
