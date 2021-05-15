<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'license',
        'mileage',
        'fleet_id',
        'vehicle_type_id',
        'is_repairing',
        'is_active',
    ];

    protected $whereData = [];

    protected $orderByData = [];

    protected $result = null;

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }

    public function vehicle_type()
    {
        return $this->belongsTo('App\VehicleType');
    }

    public function tire()
    {
        return $this->hasMany('App\Inventory', 'vehicle_id')->with('fleet', 'vendor', 'brand', 'purchase', 'user', 'type')->orderBy('status_id', 'asc')->orderBy('updated_at', 'desc');
    }

    public function activeTire()
    {
        return $this->hasMany('App\TirePlacement', 'vehicle_id')->where('is_on_vehicle', 1);
    }

    public function hasRequest()
    {
        return $this->hasMany('App\TirePlacement', 'vehicle_id')->where('is_on_vehicle', 1)->has('tireRequest');
    }

    public function drivers()
    {
        return $this->belongsToMany('App\Driver');
    }

    public function tailDrivers()
    {
        return $this->belongsToMany('App\Driver', 'driver_tail', 'tail_id', 'driver_id');
    }

    public function tireUpdateThisWeek()
    {
        return $this->hasMany('App\TirePressureAndTread', 'vehicle_id')->where('is_updated', 1)->where('created_at', '>', (new Carbon())->startOfWeek(6));
    }

    public function allTireUpdateThisWeek()
    {
        return $this->tireUpdateThisWeek()->count() >= $this->vehicle_type->no_of_wheels;
    }

    public static function getHeadOrStandAlone($fleet_id = null)
    {
        $types = VehicleType::where('is_tail', false)->pluck('id');

        $query = Vehicle::query();

        $query->whereIn('vehicle_type_id', $types);

        if (!is_null($fleet_id))
        {
            $query->where('fleet_id', $fleet_id);
        }

        return $query->get()->toArray();
    }

    public function index()
    {

        return $this->result = $this->setQueries()->get()->mapWithKeys(function ($item, $key) {

            return [$key => ['fleet'          => $item->fleet->name,
                             'fleet_id'       => $item->fleet_id,
                             'id'             => $item->id,
                             'license'        => $item->license,
                             'vehicle_type'   => $item->vehicle_type,
                             'mileage'        => (is_null($item->mileage)) ? null : number_format($item->mileage),
                             'mileage_int'    => $item->mileage,
                             'type'           => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->name,
                             'is_head'        => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_head,
                             'is_tail'        => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_tail,
                             'is_independent' => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_independent,
                             'no_of_wheels'   => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->no_of_wheels,
                             'isActive'       => $item->isActive,
                             'date'           => Carbon::parse($item['date'])->format('d M Y'),
                             'created_at'     => Carbon::parse($item['created_at'])->diffForHumans()]];
        });
    }

    public function list()
    {
        return $this->setQueries()->get()->map(function ($item, $key) {
            return ['text' => $item['license'], 'value' => $item['id']];
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

            return ['fleet_id'        => $item->fleet_id,
                    'id'              => $item->id,
                    'license'         => $item->license,
                    'mileage'         => $item->mileage,
                    'vehicle_type_id' => $item->vehicle_type_id,
            ];
        })[0];
    }

    public function show($id)
    {

        return $this->where('id', $id)->get()->map(function ($item) {

            return ['vehicle'    => $item->toArray(),
                    'frame'      => $item->vehicle_type->frame,
                    'activeTire' => $item->activeTire
                        ->mapWithKeys(function ($item, $key) {
                            return [$item['placement'] => array_merge($item->toArray(), [
                                'serial' => $item->tire->serial,
                                'brand'  => $item->tire->brand->name,
                            ])];
                        })->toArray(),
                    'request'    => $item->hasRequest
                        ->mapWithKeys(function ($item, $key) {
                            return [$item['placement'] => $item];
                        })->toArray()
            ];
        })[0];
    }

    public static function hasAllTire($id)
    {
        $active_tires = count(Vehicle::where('id', $id)->first()->activeTire);

        $no_of_wheels = Vehicle::where('id', $id)->first()->vehicle_type->no_of_wheels;

        return ($active_tires >= $no_of_wheels);
    }

    public static function mapActiveTire()
    {
        return function ($item) {
            return [
                'id'              => $item->id,
                'license'         => $item->license,
                'frame'           => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->frame,
                'type_id'         => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->id,
                'active_tires'    => $item->activeTire->mapWithKeys(function ($tire) {
                    return [$tire->placement => [
                        'id'                  => $tire->id,
                        'start_tread_depth'   => $tire->start_tread_depth,
                        'end_tread_depth'     => $tire->end_tread_depth,
                        'start_mileage'       => $tire->start_mileage,
                        'tire_id'             => $tire->tire_id,
                        'placement'           => $tire->placement,
                        'tire_change_request' => $tire->tireRequest,

                    ]];
                })->toArray(),
                'has_requests'    => $item->hasRequest->mapWithKeys(function ($tire) {
                    return [$tire->placement => [
                        'id'             => $tire->id,
                        'tire_placement' => $tire,
                        'request'        => $tire->tireRequest,
                    ]];
                })->toArray(),
                'is_head'         => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_head,
                'is_tail'         => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_tail,
                'is_independent'  => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->is_independent,
                'no_of_wheels'    => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->no_of_wheels,
                'vehicle_type_id' => (is_null($item->vehicle_type)) ? null : $item->vehicle_type->id,
            ];
        };
    }

    public function getSummary()
    {


        $result = Refuel::where('vehicle_id', $this->id)->get()->map(function ($item) {
            return ['odometer'     => $item->odometer,
                    'id'           => $item->id,
                    'previous_odo' => Refuel::where('id', '<', $item->id)->where('vehicle_id', $this->id)->orderBy('id', 'desc')->first(),
                    'month'        => $item->created_at->startOfMonth()->toDateString()
            ];
        })->groupBy('month')->toArray();

        dd($result);
    }
}
