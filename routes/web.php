<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// 可选：把首页直接跳到 posts 列表
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// 注册
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 登录
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 登出
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== Posts 路由 ====================

// 所有 posts 路由都先注册出来
Route::resource('posts', PostController::class);

// 评论：只有登录用户可以提交
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');


// 用户页面（公开）
Route::get('/users/{user}', [UserController::class, 'show'])
    ->name('users.show');
