<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clock extends Model
{
    protected $table = "clocks";
    protected $guarded = [];

    public function schedule()
    {
        return $this->hasMany('App\Schedule', 'clock_id');
    }
}
