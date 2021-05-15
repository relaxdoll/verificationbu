<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refuel extends Model
{
    protected $fillable = [

        'image_array',
        'driver_id',
        'vehicle_id',
        'fleet_id',
        'tank_id',
        'vehicle_id2',
        'odometer',
        'quantity',
        'distance',
        'job_id',
        'status',
        'is_checked',
        'deleted',
    ];

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function index()
    {

        return $this->orderBy('id', 'DESC')->get()->mapWithKeys(function ($item, $key) {

            return [$key => ['fleet'    => $item->fleet->name,
                             'fleet_id' => $item->fleet_id,
                             'id'       => $item->id,
                             'driver'   => (is_null($item->driver)) ? null : $item->driver->name,
                             'quantity' => $item->quantity,
                             'distance' => $item->distance,
                             'odometer' => '' . $item->odometer,
                             'rate'     => $item->rate,
                             'vehicle'  => (is_null($item->vehicle)) ? null : $item->vehicle->license,
            ]];
        });
    }
}
