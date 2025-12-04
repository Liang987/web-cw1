<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 可批量赋值的属性。
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added role for authorization / 添加角色字段用于授权
    ];

    /**
     * The attributes that should be hidden for serialization.
     * 序列化时应隐藏的属性。
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * 获取应转换类型的属性。
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is an administrator.
     * 检查用户是否为管理员。
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Relationship: A user has many posts.
     * 关联：一个用户拥有多个帖子。
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship: A user has many comments.
     * 关联：一个用户拥有多条评论。
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}