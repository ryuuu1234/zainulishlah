<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jenis_tgl extends Model
{   
    protected $table = "jenis_tgl";
    protected $guarded = [];

    public function calendar()
    {
        return $this->hasMany('App\Calendar', 'jenis_tgl_id');
    }
}
