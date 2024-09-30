<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, HasDateTimeFormatter;

    const STATUS_CHECKING = 0;
    const STATUS_NORMAL = 1;
    const STATUS = [
        self::STATUS_CHECKING => '审核中',
        self::STATUS_NORMAL => '正常',
    ];

    const TYPE_ARTICLE = 1;
    const TYPE_QUESTIONS = 2;
    const TYPE_MOOD = 3;
    const TYPE = [
        self::TYPE_ARTICLE => '文章',
        self::TYPE_QUESTIONS => '问答',
        self::TYPE_MOOD => '心情',
    ];

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
