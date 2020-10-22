<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas_mapel extends Model
{   
    protected $table = 'kelas_mapels';
    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function schedule()
    {
        return $this->hasMany('App\Schedule', 'kelas_mapel_id');
    }
}
