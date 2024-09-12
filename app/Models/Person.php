<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasDateTimeFormatter;
    protected $table = 'person';

    public function approval()
    {
        return $this->hasMany(Approval::class, 'pid'); // 默认 person_id
    }
}
