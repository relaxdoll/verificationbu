<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'date_time',
        'is_complete',
        'is_active',
        'allow_track',
        'start_time',
        'end_time',
        'job_status_id',
        'customer_id',
        'route_id',
        'driver_id',
        'head_id',
        'tail_id',
        'fleet_id',
    ];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function head()
    {
        return $this->belongsTo('App\Vehicle', 'head_id');
    }

    public function tail()
    {
        return $this->belongsTo('App\Vehicle', 'tail_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function route()
    {
        return $this->belongsTo('App\Route');
    }

    public function job_status()
    {
        return $this->belongsTo('App\JobStatus');
    }

    public function job_revenues()
    {
        return $this->hasMany('App\JobRevenue');
    }

    public function job_costs()
    {
        return $this->hasMany('App\JobCost');
    }

    public function refuels()
    {
        return $this->hasMany('App\Refuel');
    }

    public function index()
    {

        return $this->orderBy('date_time', 'ASC')->get()->mapWithKeys(function ($item, $key) {

            return [
                $key => [
                    'fleet'         => $item->fleet->name,
                    'fleet_id'      => $item->fleet_id,
                    'id'            => $item->id,
                    'is_active'     => $item->is_active,
                    'customer'      => $item->customer,
                    'customer_name' => $item->customer->name,
                    'is_complete'   => $item->is_complete,
                    'allow_track'   => $item->allow_track,
                    'date_time'     => $item->date_time,
                    'start_time'    => $item->start_time,
                    'end_time'      => $item->end_time,
                    'job_status'    => $item->job_status->name,
                    'route'         => $item->route->name,
                    'firstName'     => $item->driver->firstName,
                    'lastName'      => $item->driver->lastName,
                    'head'          => $item->head->license,
                    'tail'          => $item->tail->license,
                ]
            ];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return [
                'fleet'         => $item->fleet->name,
                'fleet_id'      => $item->fleet_id,
                'id'            => $item->id,
                'is_active'     => $item->is_active,
                'customer'      => $item->customer,
                'customer_id'   => $item->customer->id,
                'is_complete'   => $item->is_complete,
                'allow_track'   => $item->allow_track,
                'date_time'     => $item->date_time,
                'start_time'    => $item->start_time,
                'end_time'      => $item->end_time,
                'job_status_id' => $item->job_status->id,
                'route_name'    => $item->route->name,
                'route_id'      => $item->route->id,
                'driver_id'     => $item->driver->id,
                'head_id'       => $item->head->id,
                'tail_id'       => $item->tail->id,
            ];
        })[0];
    }

}
