<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // likeable_id   存 ID (例如 1, 5)
            // likeable_type 存 模型类名 (例如 "App\Models\Post", "App\Models\Comment")
            $table->morphs('likeable');

            $table->timestamps();
            
            // 防止一个人重复点赞同一条内容
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};