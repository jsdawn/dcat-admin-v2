<?php

namespace App\Admin\Repositories;

use App\Models\Approval as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Approval extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
