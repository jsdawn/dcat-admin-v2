<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $guarded = [];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
