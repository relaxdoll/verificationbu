<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleInspectionLog extends Model
{
    protected $fillable = [
        'log',
        'vehicle_id',
        'driver_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }
}
