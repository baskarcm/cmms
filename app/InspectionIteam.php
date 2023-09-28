<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionIteam extends Model
{
	use SoftDeletes;
    public $table = 'inspection_iteams';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

     public function point()
    {
        return $this->belongsTo("App\InspectionPoint", "inspec_point", "id");
    }
}
