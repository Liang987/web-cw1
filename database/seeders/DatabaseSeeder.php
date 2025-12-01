<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * English: Run all seeders for the application
     * 中文：运行所有数据库填充器
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // 先创建用户
            PostSeeder::class, // 再创建帖子和评论
        ]);
    }

}
