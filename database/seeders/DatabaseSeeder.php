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
            PostSeeder::class, // 运行 PostSeeder; Run PostSeeder
        ]);
    }
}
