<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    // English: Define default fake data for posts
    // 中文：定义帖子模型的假数据生成逻辑
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),      // 随机标题 Ramdom title
            'body' => $this->faker->paragraph(),      // 随机内容 Ramdom body
            'author' => $this->faker->name(),         // 随机作者 Ramdom author
        ];
    }
}
