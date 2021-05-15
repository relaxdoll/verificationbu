<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    public function vehicle()
    {
        return $this->hasMany('App\Vehicle');
    }
}
