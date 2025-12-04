<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     * 显示当前登录用户的所有通知列表。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all notifications for the current authenticated user
        // 获取当前认证用户的所有通知
        $notifications = auth()->user()->notifications;

        // Return the view with notifications data
        // 返回包含通知数据的视图
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read and redirect to the related post.
     * 将特定通知标记为已读并跳转到相关帖子。
     *
     * @param  string  $id  Notification UUID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead($id)
    {
        // Find the notification belonging to the current user or fail with 404
        // 查找属于当前用户的通知，如果找不到则抛出 404 错误
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // Mark the notification as read (updates 'read_at' timestamp)
        // 标记为已读（更新 'read_at' 时间戳）
        $notification->markAsRead();

        // Redirect to the post related to this notification
        // 跳转到与该通知相关的帖子
        return redirect()->route('posts.show', $notification->data['post_id']);
    }
    
    /**
     * Mark all unread notifications as read.
     * 将所有未读通知一键标记为已读。
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllRead()
    {
        // Mark all unread notifications for the user as read
        // 将该用户的所有未读通知标记为已读
        auth()->user()->unreadNotifications->markAsRead();

        // Redirect back with a success message
        // 重定向回上一页并显示成功消息
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Check for unread notifications count via AJAX.
     * 通过 AJAX 检查未读通知的数量。
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        // Return the count of unread notifications as JSON response
        // 以 JSON 格式返回未读通知的数量
        return response()->json([
            'unread_count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}