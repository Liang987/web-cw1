<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 填充应用程序的数据库。
     */
    public function run(): void
    {
        // 1. Create 1 admin user (for demonstration)
        // 1. 创建 1 个管理员账号 (用于演示)
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Ensure password is login-able (default is 'password') / 确保密码可登录 (默认 password)
        ]);

        // 2. Create 5 normal users
        // 2. 创建 5 个普通用户
        User::factory(5)->create();

        // 3. Create 10 posts (automatically associated with random users)
        // 3. 创建 10 个帖子 (自动关联随机用户)
        Post::factory(10)->create();

        // 4. Create 20 comments (automatically associated with random users and posts)
        // 4. 创建 20 条评论 (自动关联随机用户和帖子)
        Comment::factory(20)->create();
    }
}