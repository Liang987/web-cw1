<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// 首页还用 Laravel 的欢迎页
Route::get('/', function () {
    return view('welcome');
});

// 列表
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// 显示创建表单
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

// 提交表单
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// 创建评论
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');