<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = 'slides';
    protected $guarded = [];

    // public function user()
    // {
    //     return $this->belongsTo('App\User');
    // }

    // protected static function boot() 
    // {
    //     parent::boot();
    //     static::deleting(function($teacher) {
    //         $teacher->user()->delete();
    //     });
    // }
}
