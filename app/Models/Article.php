<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $table = 'articles';

    // create时不可赋值
    protected $guarded = [
        'like_count',
        'comment_count',
        'collect_count'
    ];

    public function comments()
    {
        return $this->hasMany(ArticleComment::class, 'article_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
