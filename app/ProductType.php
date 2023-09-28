<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductType extends Model
{
    use SoftDeletes;
    protected  $table = 'product_types';
    protected $appends = ['key'];

    
    public function equipment()
    {
        return $this->belongsTo("App\Product", "product_id", "id");
    }

    public function product()
    {
        return $this->belongsTo("App\Product", "product_id", "id");
    }

    public function schedule()
    {
        return $this->belongsTo("App\Schedule", "id", "product_type");
    }
    public function getKeyAttribute()
    {
        return encrypt($this->id);
    }
}
