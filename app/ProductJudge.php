<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductJudge extends Model
{
    use SoftDeletes;
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
