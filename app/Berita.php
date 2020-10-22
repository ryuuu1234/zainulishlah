<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table ="beritas";
    protected $guarded = [];

    // public function jenis_tgl()
    // {
    //     return $this->belongsTo('App\Jenis_tgl');
    // }
}
