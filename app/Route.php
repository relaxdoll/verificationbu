<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'fleet_id',
        'customer_id',
        'name',
        'distance',
        'fuel_allowance',
        'fuel_rate',
        'trip_rate_id',
        'incentive_id',
        'fuel_save',
        'toll_fee',
        'pm_fee',
        'other',
        'is_active',
    ];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function trip_rate()
    {
        return $this->belongsTo('App\TripRate');
    }

    public function incentive()
    {
        return $this->belongsTo('App\Incentive');
    }

    public function route_pivot()
    {
        return $this->hasMany('App\RoutePivot');
    }

    public function index()
    {

        return $this->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [
                $key => [
                    'fleet'           => $item->fleet->name,
                    'fleet_id'        => $item->fleet_id,
                    'id'              => $item->id,
                    'name'            => $item->name,
                    'toll_fee'        => number_format($item->toll_fee),
                    'trip_rate'       => $item->trip_rate,
                    'trip_rate_id'    => $item->trip_rate->id,
                    'customer_id'     => $item->customer->id,
                    'customer'        => $item->customer,
                    'incentive_id'    => $item->incentive->id,
                    'incentive'       => $item->incentive,
                    'incentive_price' => number_format($item->incentive->price),
                    'route_pivot'     => $item->route_pivot,
                ]
            ];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return [
                'fleet'           => $item->fleet->name,
                'fleet_id'        => $item->fleet_id,
                'id'              => $item->id,
                'name'            => $item->name,
                'toll_fee'        => number_format($item->toll_fee),
                'trip_rate'       => $item->trip_rate,
                'trip_rate_id'    => $item->trip_rate->id,
                'customer_id'     => $item->customer->id,
                'customer'        => $item->customer,
                'incentive_id'    => $item->incentive->id,
                'incentive'       => $item->incentive,
                'incentive_price' => number_format($item->incentive->price),
                'route_pivot'     => $item->route_pivot,
            ];
        })[0];
    }
}
