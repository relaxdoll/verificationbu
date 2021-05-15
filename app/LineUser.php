<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
{
    protected $fillable = [
        'lineId',
        'lineName',
        'avatar'
    ];

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function approver()
    {
        return $this->hasOne('App\Approver');
    }

    public function technician()
    {
        return $this->hasOne('App\Technician');
    }
}
