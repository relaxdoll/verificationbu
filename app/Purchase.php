<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'fleet_id',
        'vendor_id',
        'brand_id',
        'date',
        'price',
        'amount',
        'user_id',
        'type_id',
        'inventory_type_id',
        'treadDepth',
        'purchase_order_number',
    ];

    protected $relationship = [
        'fleet',
        'vendor',
        'brand',
        'user',
        'type',
        'tire',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tire()
    {
        return $this->hasMany('App\Tire', 'purchase_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function type()
    {
        return $this->belongsTo('App\TireType');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
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

    public function index()
    {

        return $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            return [$key => ['fleet'                 => $item->fleet->name,
                             'vendor'                => $item->vendor->name,
                             'brand'                 => $item->brand->name,
                             'brand_id'              => $item->brand_id,
                             'id'                    => $item->id,
                             'vendor_id'             => $item->vendor_id,
                             'fleet_id'              => $item->fleet_id,
                             'price'                 => $item->price,
                             'type'                  => is_null($item->type) ? null : $item->type->name,
                             'type_id'               => $item->type_id,
                             'user'                  => $item->user->username,
                             'tire'                  => $item->tire,
                             'purchase'              => 'All Purchases',
                             'purchase_order_number' => $item->purchase_order_number,
                             'date'                  => Carbon::parse($item['date'])->format('d M Y'),
                             'created_at'            => Carbon::parse($item['created_at'])->diffForHumans()]];
        });
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
