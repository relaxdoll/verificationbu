<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoutePivot extends Model
{
    protected $fillable = [
      'order',
      'place_id',
      'route_id',
      'type_id',
    ];

    public function route()
    {
        return $this->belongsTo('App\Route');
    }

    public function place()
    {
        return $this->belongsTo('App\Place');
    }
}
