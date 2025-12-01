<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

// 帖子资源路由（包含 index, show, create, store, edit, update, destroy）
Route::resource('posts', PostController::class);

// 给评论单独加一个路由（只需要 store）
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

// English: Home route
// 中文：首页路由
Route::get('/', function () {
    return view('welcome');
});

// English: Show all posts
// 中文：显示所有帖子
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// English: Show create post form
// 中文：显示创建帖子页面
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

// English: Handle post form submission
// 中文：处理帖子提交
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// English: Handle new comment submission
// 中文：处理评论提交
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');