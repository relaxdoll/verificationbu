<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TirePressureAndTread extends Model
{
    protected $fillable = [
        'tire_id',
        'tire_placement_id',
        'tread_depth',
        'mileage',
        'tire_pressure',
        'driver_id',
        'vehicle_id',
        'is_updated',
    ];

    public function tire_placement()
    {
        return $this->belongsTo('App\TirePlacement');
    }
}
