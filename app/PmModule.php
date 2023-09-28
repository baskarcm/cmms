<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class PmModule extends Model
{
    //use SoftDeletes;
    protected $appends = ['key'];

    public function equipment()
    {
        return $this->belongsTo("App\ProductType", "product_type", "id");
    }

    public function users()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }

    public function manager()
    {
        return $this->belongsTo("App\User", "manager_id", "id");
    }
    
    public function engineer()
    {
        return $this->belongsTo("App\User", "engineer_id", "id");
    }

    public function schedule()
    {
        return $this->belongsTo("App\Schedule", "schedule_id", "id");
    }

    public function getKeyAttribute()
    {
        return encrypt($this->id);
    }
}
