<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
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
            'title'       => $this->faker->sentence(),
            'body'        => $this->faker->paragraphs(3, true), // Generate 3 paragraphs, looks more like an article / 生成 3 段话，看起来更像文章
            'image_path'  => null, // Default is null, you can manually add test images later / 默认为空，你可以稍后手动放一些测试图
            
            // Randomize the created_at timestamp to make it look more realistic (within the past year)
            // 让发布时间分散在过去一年内，看起来更真实
            'created_at'  => $this->faker->dateTimeBetween('-1 year', 'now'),
            
            // Set updated_at to match created_at
            // 设置 updated_at 与 created_at 一致
            'updated_at'  => function (array $attributes) {
                return $attributes['created_at'];
            },

            // Association logic: Prefer picking a random existing user, otherwise create a new one
            // 关联逻辑：优先随机取一个现有用户，没有则新建
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}