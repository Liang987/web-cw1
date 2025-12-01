<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');              // 标题
            $table->text('body')->nullable();     // 正文

            // 新增：作者外键，关联 users 表
            $table->foreignId('user_id')
                  ->constrained()                 // 默认指向 users 表的 id
                  ->onDelete('cascade');          // 用户被删时，一并删除 ta 的帖子

    $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
