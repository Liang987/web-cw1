<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PostSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 运行 PostSeeder 来生成测试数据
        $this->call([
            PostSeeder::class,
        ]);
    }
}
