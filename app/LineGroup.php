<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineGroup extends Model
{
    protected $fillable = [

        'lineId',
        'name',
        'fleet_id',
        'avatar',
        'isLinked',
    ];
}
