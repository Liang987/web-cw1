<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 生成 5 个随机用户
        User::factory()->count(5)->create();

        // 可选：再加一个固定的 admin 方便测试
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            // 密码字段会自动 hash，默认 factory 是 'password'
        ]);
    }
}
