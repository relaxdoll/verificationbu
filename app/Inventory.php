<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'serial',
        'is_available',
        'is_sold',
        'price',
        'quantity',
        'current_quantity',
        'inventory_type_id',
        'brand_id',
        'purchase_id',
        'fleet_id',
        'distance_travelled',
        'name',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    protected $result = null;

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function inventory_type()
    {
        return $this->belongsTo('App\InventoryType');
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

        return $this->setQueries()->orderBy('is_available', 'desc')->get()->mapWithKeys(function ($item, $key) {

            return $this->result = [$key => [
                'id'               => $item->id,
                'name'             => $item->name,
                'serial'           => $item->serial,
                'is_available'     => $item->is_available,
                'is_sold'          => $item->is_sold,
                'price'            => $item->price,
                'quantity'         => $item->quantity,
                'current_quantity' => $item->current_quantity,
                'fleet'            => $item->fleet->name,
                'inventory_type'   => $item->inventory_type->name,
                'brand'            => $item->brand->name,
                'created_at'       => Carbon::parse($item['created_at'])->diffForHumans()]];
        });
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

    public function edit($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return [
                'id'                => $item->id,
                'name'             => $item->name,
                'serial'            => $item->serial,
                'is_available'      => $item->is_available,
                'is_sold'           => $item->is_sold,
                'price'             => $item->price,
                'quantity'          => $item->quantity,
                'current_quantity'  => $item->current_quantity,
                'inventory_type_id' => $item->inventory_type_id,
                'brand_id'          => $item->brand_id,
                'purchase_id'       => $item->purchase_id,
                'fleet_id'          => $item->fleet_id,
            ];
        })[0];
    }

}
