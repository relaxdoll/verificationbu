<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = [
        'lineId',
        'lineName',
        'type',
        'avatar'
    ];
}
