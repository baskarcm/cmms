<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected  $table = 'products';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }
}
