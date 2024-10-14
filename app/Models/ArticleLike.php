<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleLike extends Model
{
    use HasFactory;

    protected $table = 'article_likes';
    protected $guarded = [];
    protected $with = ['user:id,avatar,name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
