<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryStock extends Model
{
    //use SoftDeletes;

    protected $fillable = ['name', 'code','stock','active','created_by','updated_by'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}
