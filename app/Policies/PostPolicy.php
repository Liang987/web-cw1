<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can update the post.
     * 判断用户是否可以修改帖子。
     * * Rule: Admin or the post's author (user_id matches).
     * 规则：管理员 (admin) 或者 帖子的作者 (user_id 匹配)。
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the post.
     * 判断用户是否可以删除帖子。
     * * Rule: Admin or the post's author (user_id matches).
     * 规则：同上（管理员或作者）。
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }
}