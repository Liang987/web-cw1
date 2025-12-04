<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(),
            'body'        => $this->faker->paragraphs(3, true), // 生成 3 段话，看起来更像文章
            'image_path'  => null, // 默认为空，你可以稍后手动放一些测试图
            
            // 🟢 让发布时间分散在过去一年内，看起来更真实
            'created_at'  => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at'  => function (array $attributes) {
                return $attributes['created_at'];
            },

            // 关联逻辑：优先随机取一个现有用户，没有则新建
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}