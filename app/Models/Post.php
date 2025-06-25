<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
Use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

        protected $fillable = [
        'title',
        'body',
        'user_id',
        'image',
    ];

    # Post - User
    #to get the owner of the post
    public function user(): BelongsTo
    {
        // 関連するユーザーがソフトデリートされていても取得できるように withTrashed() を追加
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
