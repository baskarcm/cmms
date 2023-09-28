<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InspectionPoint extends Model
{
    public $table = 'inspection_points';
    use SoftDeletes;
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function point()
    {
        return $this->belongsTo("App\InspectionPoint", "inspec_iteam", "id");
    }
}
