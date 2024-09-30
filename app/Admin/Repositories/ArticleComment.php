<?php

namespace App\Admin\Repositories;

use App\Models\ArticleComment as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ArticleComment extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
