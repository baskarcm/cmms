<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
    
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

    public function moduleType()
    {
        return $this->belongsTo("App\ModuleType", "module_type", "id");
    }

    public function notify()
    {
        return $this->belongsTo("App\NotifyStatus", "id", "schedule_id");
    }
    public function breakdown()
    {
        return $this->belongsTo("App\BreakdownModule", "id", "schedule_id");
    }

    public function actualScheduledate()
    {
        return $this->belongsTo("App\BreakdownModule", "id", "schedule_id");
    }
    
    public function getKeyAttribute()
    {
        return encrypt($this->id);
    }
}
