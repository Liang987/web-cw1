<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'   => $this->faker->sentence(),
            'body'    => $this->faker->paragraph(),

            // 为每个 Post 关联一个用户：
            // 这里用随机已存在用户；如果没有，就新建一个
            'user_id' => User::inRandomOrder()->first()?->id
                        ?? User::factory(),
        ];
    }
}
