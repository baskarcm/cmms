<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{

    use SoftDeletes;
    protected  $table = 'inventories';

    public function users()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }

    public function equipment()
    {
        
        return $this->belongsTo("App\ProductType", "product_type", "id");
    }
}
