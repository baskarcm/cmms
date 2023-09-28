<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductForm extends Model
{
    use SoftDeletes;
    protected  $table = 'product_forms';

    // public function point()
    // {
    //     return $this->belongsTo("App\InspectionPoint", "inspec_point", "id");
    // }

    public function item()
    {
        return $this->belongsTo("App\InspectionIteam", "inspec_iteam", "id");
    }
}
