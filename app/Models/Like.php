<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    /**
     * 获取拥有此点赞的模型 (Post 或 Comment)
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}