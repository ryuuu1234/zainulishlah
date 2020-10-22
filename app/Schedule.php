<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{   
    protected $table = 'schedules';
    protected $guarded = [];

    public function clock()
    {
        return $this->belongsTo('App\Clock');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }
    public function kelas_mapel()
    {
        return $this->belongsTo('App\Kelas_mapel');
    }
    
}
