<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\User', 'institution_id');
    }
}
