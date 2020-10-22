<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $guarded = [];

    protected $appends = ['open'];

    // ini menambah row label
    public function getOpenAttribute()
    {
        return false;
    }

    public function submenu()
    {
        return $this->hasMany('App\Submenu', 'menu_id');
    }
}
