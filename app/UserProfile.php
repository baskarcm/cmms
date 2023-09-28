<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use SoftDeletes;
    protected $appends = ['key'];
    
    public function setFirstnameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    /**
     * Set the user's  name.
     *
     * @param  string  $value
     * @return void
    */
    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = ucfirst($value);
    }

    public function users()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }

    public function profileRecord()
    {
        return $this->hasMany("App\ProfileCollection", "user_profile_id", "id");
    }

     public function collectAnswer()
    {
        return $this->hasMany("App\UserCollectAnswer", "user_profile_id", "id");
    }

    public function userCollection()
    {
        return $this->hasMany("App\ProfileCollection","user_profile_id","id");
    }

    public function getKeyAttribute()
    {
        return encrypt($this->id);
    }


}
