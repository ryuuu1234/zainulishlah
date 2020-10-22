<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    protected $table = 'submenus';
    protected $guarded = [];
    protected $appends = ['open'];

    // ini menambah row label
    public function getOpenAttribute()
    {
        return false;
    }

    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
