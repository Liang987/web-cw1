<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $comment;

    // Constructor: Receive the comment instance
    // 构造函数：接收评论实例
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    // Specify delivery channels: save to database
    // 指定发送渠道：存入数据库
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Define the data structure to be stored in the database
    // 定义存入数据库的数据结构
    public function toArray(object $notifiable): array
    {
        return [
            // Data stored here will be displayed on the page later
            // 这里存的数据，之后会在页面上显示
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post->title,
            'user_name' => $this->comment->user->name, // Name of the commenter / 评论者名字
            'message' => 'commented on your post',
        ];
    }
}