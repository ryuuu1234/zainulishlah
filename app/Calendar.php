<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $guarded = [];

    public function jenis_tgl()
    {
        return $this->belongsTo('App\Jenis_tgl');
    }
}
