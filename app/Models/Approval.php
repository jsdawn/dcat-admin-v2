<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasDateTimeFormatter;
    protected $table = 'approval';

    public function person()
    {
        return $this->belongsTo(Person::class, 'pid');
    }
}
