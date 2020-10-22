<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Role;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','role_id', 'institution_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }
    public function institution()
    {
        return $this->belongsTo('App\Institution');
    }

    public function teacher()
    {
        return $this->hasOne('App\Teacher', 'user_id');
    }
    

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function($user) {
    //         $relationMethods = ['teacher'];

    //         foreach ($relationMethods as $relationMethod) {
    //             if ($user->$relationMethod()->count() > 0) {
    //                 return false;
    //             }
    //         }
    //     });
    // }

    protected static function boot() 
    {
        parent::boot();
        static::deleting(function($user) {
            $user->teacher()->delete();
        });
    }
}
