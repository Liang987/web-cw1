<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // 显示所有通知列表
    public function index()
    {
        // 获取当前用户的所有通知
        $notifications = auth()->user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    // 标记已读并跳转
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // 标记为已读
        $notification->markAsRead();

        // 跳转到对应的帖子
        return redirect()->route('posts.show', $notification->data['post_id']);
    }
    
    // 一键全部已读
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    // 检查是否有新消息
    public function check()
    {
        return response()->json([
            'unread_count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}