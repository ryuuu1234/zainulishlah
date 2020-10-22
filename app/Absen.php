<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table ="absens";
    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }
}
