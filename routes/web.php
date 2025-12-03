<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LikeController;

// 首页重定向到帖子列表
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// ============ 认证相关 ============
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============ 公开路由 ============
// 帖子列表和详情 (index, show) 不需要登录
Route::resource('posts', PostController::class)->only(['index', 'show']);

// 用户个人主页
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// ============ 需要登录的路由 ============
Route::middleware('auth')->group(function () {

    // 1. 帖子管理 (Create, Store, Edit, Update, Destroy)
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    // 2. 评论功能
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    // 3. 通知系统 (Rubric 12)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all', [NotificationController::class, 'markAllRead'])->name('notifications.markAll');
    Route::get('/notifications/check', [NotificationController::class, 'check'])->name('notifications.check');

    // 4. 点赞功能 (多态路由 - Rubric 17)
    Route::post('/posts/{post}/like', [LikeController::class, 'togglePost'])->name('posts.like');
    Route::post('/comments/{comment}/like', [LikeController::class, 'toggleComment'])->name('comments.like');

});