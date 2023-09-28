<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;
    protected $appends = ['key'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','user_type', 'email', 'password','phone', 'gender', 'active', 'profile_pic', 'created_by', 'updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function gen()
    {
        return $this->belongsTo("App\Gender", "gender", "id");
    }
    
    public function type()
    {
        return $this->belongsTo("App\UserType", "user_type", "id");
    }
    
    /**
     * Set the user's email.
     *
     * @param  string  $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Set the user's  name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }


    /**
     * Set the user's  name.
     *
     * @param  string  $value
     * @return void
     */
    public function getChangePasswordAttribute()
    {
        return $this->attributes['change_password'] = empty($this->password) ? 0 : 1;
    }

    /**
     * Set the user's  secxret.
     *
     * @param  string  $value
     * @return void
     */
    public function getKeyAttribute()
    {
        return encrypt($this->id);
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
