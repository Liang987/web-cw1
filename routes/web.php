<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// 首页重定向到帖子列表
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// ============ 认证相关 ============

// 注册
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 登录
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 登出
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============ Posts 路由  ============

Route::middleware('auth')->group(function () {

    // create, store, edit, update, destroy
    // 注意：这里包含了 /posts/create
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    // 评论
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});

// 🟢 然后再定义通配符路由 (Index/Show)
// 只有上面的 create 没匹配上，才会走到这里，把剩余的当成 ID 处理
Route::resource('posts', PostController::class)->only(['index', 'show']);

// ============ 用户页面（公开） ============

Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');