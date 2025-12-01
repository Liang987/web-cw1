<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        // 预加载用户的帖子和评论
        $user->load(['posts', 'comments.post']);

        return view('users.show', compact('user'));
    }
}
