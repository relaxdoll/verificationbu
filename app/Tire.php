<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tire extends Model
{
    protected $fillable = [
        'serial',
        'initial_tread_depth',
        'tread_depth',
        'side_wall_age',
        'reason_id',
        'is_sold',
        'is_available',
        'distance_travelled',
        'price',
        'fleet_id',
        'purchase_id',
        'tire_type_id',
        'brand_id',
    ];


    protected $whereData = [];

    protected $orderByData = [];

    protected $result = null;

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }

    public function tire_type()
    {
        return $this->belongsTo('App\TireType');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function reason()
    {
        return $this->belongsTo('App\Reason');
    }

    public function tire_placement()
    {
        return $this->hasMany('App\TirePlacement', 'tire_id');
    }

    public function all_placement()
    {
        return $this->hasMany('App\TirePlacement', 'tire_id')->orderBy('created_at', 'asc');
    }

    public function first_placement()
    {
        return $this->hasOne('App\TirePlacement', 'tire_id')->oldest();
    }

    public function last_placement()
    {
        return $this->hasOne('App\TirePlacement', 'tire_id')->latest();
    }

    public function current_placement()
    {
        return $this->hasOne('App\TirePlacement', 'tire_id')->where('is_on_vehicle', 1);
    }

    public function disabled_tire()
    {
        return $this->hasOne('App\TirePlacement', 'tire_id')->where('is_on_vehicle', 0);
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

        $query->where($this->whereData);

        foreach ($this->orderByData as $orderByDatum)
        {
            $query->orderBy($orderByDatum[0], $orderByDatum[1]);
        }


        return $query;
    }

    public function index()
    {

        return $this->result = $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            return [$key => ['fleet'             => $item->fleet->name,
                             'fleet_id'          => $item->fleet_id,
                             'id'                => $item->id,
                             'serial'            => $item->serial,
                             'purchase'          => $item->purchase,
                             'tire_type'         => $item->tire_type_id,
                             'brand'             => $item->brand->name,
                             'tread_depth'       => $item->tread_depth,
                             'is_available'      => $item->is_available,
                             'reason'            => ($item->is_available === 1 && is_null($item->reason)) ? 'Available' : $item->reason,
                             'tire_placement'    => $item->tire_placement,
                             'current_placement' => $item->current_placement,
                             'created_at'        => Carbon::parse($item['created_at'])->diffForHumans()]];
        });
    }

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['fleet_id'            => $item->fleet_id,
                    'id'                  => $item->id,
                    'serial'              => $item->serial,
                    'initial_tread_depth' => $item->initial_tread_depth,
                    'tread_depth'         => $item->tread_depth,
                    'side_wall_age'       => $item->side_wall_age,
                    'distance_travelled'  => $item->distance_travelled,
                    'price'               => $item->price,
                    'purchase_id'         => $item->purchase_id,
                    'tire_type_id'        => $item->tire_type_id,
                    'brand_id'            => $item->brand_id,
                    'is_available'        => $item->is_available,
                    'reason_id'           => $item->reason_id,
                    'is_sold'             => $item->is_sold,
                    'created_at'          => Carbon::parse($item['created_at'])->diffForHumans()
            ];
        })[0];
    }

}
