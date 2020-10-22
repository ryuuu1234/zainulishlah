<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function kelas_mapel()
    {
        return $this->hasMany('App\Kelas_mapel', 'teacher_id');
    }
    public function schedule()
    {
        return $this->hasMany('App\Schedule', 'teacher_id');
    }

    protected static function boot() 
    {
        parent::boot();
        static::deleting(function($teacher) {
            $teacher->user()->delete();
        });
    }
}
