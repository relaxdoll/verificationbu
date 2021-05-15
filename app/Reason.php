<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    public function tire()
    {
        return $this->hasMany('App\Tire');
    }
}
