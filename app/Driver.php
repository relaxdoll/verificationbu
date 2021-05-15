<?php

namespace App;

use App\Tool\Graph;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'fleet_id',
        'lineId',
        'avatar',
    ];

    protected $relationship = [
        'fleet',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    protected $result = null;

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function vehicles()
    {
        return $this->belongsToMany('App\Vehicle', 'driver_vehicle');
    }

    public function tails()
    {
        return $this->belongsToMany('App\Vehicle', 'driver_tail', 'driver_id', 'tail_id');
    }

    public function image_tracks()
    {
        return $this->hasMany('App\ImageTrackReport', 'driver_id');
    }

    public function refuels()
    {
        return $this->hasMany('App\Refuel', 'driver_id');
    }

    public function line_id_id()
    {
        return $this->hasOne('App\LineUser', 'driver_id');
    }

    public function getNameAttribute()
    {
        return ucfirst($this->firstName) . ' ' . ucfirst($this->lastName);
    }

    public static function getFleetID($id)
    {
        return Driver::find($id)->fleet_id;
    }

    public function index()
    {

        return $this->orderBy('firstName', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => ['fleet'     => $item->fleet->name,
                                             'fleet_id'  => $item->fleet_id,
                                             'id'        => $item->id,
                                             'name'      => $item->firstName . ' ' . $item->lastName,
                                             'firstName' => $item->firstName,
                                             'lastName'  => $item->lastName,
                                             'phone'     => $item->phone,
                                             'avatar'    => $item->avatar,
                                             'lineId'    => $item->lineId,
                                             'vehicle'   => (is_null($item->vehicles)) ? null : $item->vehicles->pluck('license'),
                                             'tail'      => (is_null($item->tails)) ? null : $item->tails->pluck('license'),
            ]];
        });
    }

    public function show($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet'        => $item->fleet->name,
                    'fleet_id'     => $item->fleet_id,
                    'id'           => $item->id,
                    'name'         => $item->firstName . ' ' . $item->lastName,
                    'firstName'    => $item->firstName,
                    'lastName'     => $item->lastName,
                    'phone'        => $item->phone,
                    'avatar'       => $item->avatar,
                    'refuel'       => [
                        'graph' => Graph::getRefuelRate($item->refuels),
                        'all'   => $item->refuels,
                    ],
                    'image_tracks' => [
                        'all'   => $item->image_tracks->map(function ($item) {
                            return ImageTrackReport::getImageTrackDetail($item);
                        }),
                        'graph' => [
                            'byDay'   => Graph::getGraphByDay($item, 'image_tracks'),
                            'byWeek'  => Graph::getGraphByWeek($item, 'image_tracks'),
                            'byMonth' => Graph::getGraphByMonth($item, 'image_tracks'),
                        ],

                    ],
                    'vehicle'      => (is_null($item->vehicles)) ? null : $item->vehicles->pluck('license'),
                    'tail'         => (is_null($item->tails)) ? null : $item->tails->pluck('license'),
            ];
        })[0];
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet_id'   => $item->fleet_id,
                    'id'         => $item->id,
                    'firstName'  => $item->firstName,
                    'lastName'   => $item->lastName,
                    'phone'      => $item->phone,
                    'lineId'     => $item->line_id_id->id,
                    'vehicle_id' => $item->vehicles->pluck('id'),
                    'tail_id'    => $item->tails->pluck('id'),
            ];
        })[0];
    }

}
