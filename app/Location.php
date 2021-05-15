<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $fillable = [
        'customer_id',
        'fleet_id',
        'vehicle_id',
        'user_id'
    ];


    protected $relationship = [
        'customer',
        'fleet',
        'user',
        'vehicle'
    ];

    protected $whereData = [];

    protected $orderByData = [];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function index()
    {

        return $this->setQueries()->get()->mapToGroups(function ($item, $key) {

            $customer = $item->original;

            $relations = $item->relations;

            foreach ($relations as $relationName => $relation)
            {
                $customer[$relationName] = $relation->name;
            }

            return [$item->customer->name =>  ['fleet'       => $item->fleet->nameTH,
                                               'fleet_id'    => $item->fleet_id,
                                               'id'          => $item->id,
                                               'customer_name'    => $item->customer->name,
                                               'customer'    => $item->customer,
                                               'customer_id' => $item->customer_id,
                                               'vehicle'     => $item->vehicle->license,
                                               'vehicle_id'  => $item->vehicle_id,
                                               'user'        => $item->user->name,
            ]];
        });
    }

    public function list()
    {

        return $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            $customer = $item->original;

            $relations = $item->relations;

            foreach ($relations as $relationName => $relation)
            {
                $customer[$relationName] = $relation->name;
            }

            return [$key =>  ['fleet'       => $item->fleet->name,
                             'fleet_id'    => $item->fleet_id,
                             'id'          => $item->id,
                             'customer'    => $item->customer->name,
                             'customer_id' => $item->customer_id,
                             'vehicle'     => $item->vehicle->license,
                             'vehicle_id'  => $item->vehicle_id,
                             'user'        => $item->user->name,
            ]];
        });
    }

    public function addQuery($queries)
    {
        if (array_key_exists('where', $queries))
        {
            foreach ($queries['where'] as $key => $query)
            {
                array_push($this->whereData, [$key, $query]);
            }
        }

        if (array_key_exists('orderBy', $queries))
        {
            foreach ($queries['orderBy'] as $key => $query)
            {
                array_push($this->orderByData, [$key, $query]);
            }
        }

        return $this;
    }

    public function setQueries()
    {
        $query = $this::query();

        $query->with($this->relationship)->where($this->whereData);

        foreach ($this->orderByData as $orderByDatum)
        {
            $query->orderBy($orderByDatum[0], $orderByDatum[1]);
        }


        return $query;
    }
}
