<?php


namespace App\Classes;


use App\Driver;
use App\Fleet;
use App\Refuel as Model;
use App\Vehicle;

class RefuelGraph
{
    protected $vehicle_type_id;

    protected $whereData;

    /**
     * RefuelGraph constructor.
     * @param $vehicle_type_id
     * @param $whereData
     */
    public function __construct($vehicle_type_id, $whereData)
    {
        $this->vehicle_type_id = $vehicle_type_id;
        $this->whereData       = $whereData;
    }


    /**
     * RefuelGraph constructor.
     * @param array $mergeData
     * @param null $mode
     * @return array
     */


    public function zoning($mergeData = [], $mode = null): array
    {
        $data  = [];
        $zones = [20, 70, 120, 170, 220, 280, 360, 500, '>500'];
        for ($i = 0; $i < count($zones); $i ++)
        {
            if ($i === (count($zones) - 1))
            {
                $model = Model::where(array_merge($this->whereData, $mergeData))->where('distance', '>', $zones[$i - 1]);
            } else if ($i === 0)
            {
                $model = Model::where(array_merge($this->whereData, $mergeData))->whereBetween('distance', [0, $zones[$i]]);
            } else
            {
                $model = Model::where(array_merge($this->whereData, $mergeData))->whereBetween('distance', [$zones[$i - 1], $zones[$i]]);
            }

            if ($mode === 'fleet')
            {
                $model->whereHas('vehicle', function ($query) {
                    return $query->where('vehicle_type_id', $this->vehicle_type_id);
                });
            }

            $distance = $model->pluck('distance')->toArray();

            $quantity = array_map('floatval', $model->pluck('quantity')->toArray());

            $rate = $model->pluck('rate');

            $data_set = ['x', 'y'];

            $data[$zones[$i]] = ['data_set' => array_merge([$data_set], collect($quantity)->zip($distance)->toArray()),
                                 'max'      => $rate->max(),
                                 'min'      => $rate->min(),
                                 'mean'     => array_sum($quantity) === 0 ? null : array_sum($distance) / array_sum($quantity),
                                 'median'   => $rate->median(),
                                 'mode'     => (count($rate) === 0) ? null : ($rate->map(function ($item) {
                                         return (int) round($item * 100, 0);
                                     })->mode()[0] / 100),];
        }

        return $data;
    }

    public function byFleet()
    {

        return Fleet::all()->mapWithKeys(function ($item) {

            $model = Model::whereHas('vehicle', function ($query) {
                return $query->where('vehicle_type_id', $this->vehicle_type_id);
            })->where('fleet_id', $item->id)->where($this->whereData);

            $distance = $model->pluck('distance')->toArray();
            $quantity = array_map('floatval', $model->pluck('quantity')->toArray());
            $rate     = $model->pluck('rate');
            $data_set = ['x', 'y'];

            return [$item->name => ['data_set' => array_merge([$data_set], collect($quantity)->zip($distance)->toArray()),
                                    'max'      => $rate->max(),
                                    'min'      => $rate->min(),
                                    'mean'     => array_sum($quantity) === 0 ? null : array_sum($distance) / array_sum($quantity),
                                    'median'   => $rate->median(),
                                    'mode'     => (count($rate) === 0) ? null : ($rate->map(function ($item) {
                                            return (int) round($item * 100, 0);
                                        })->mode()[0] / 100),
                                    'zone'     => $this->zoning([['fleet_id', $item->id]], 'fleet'),
            ]];
        })->toArray();
    }

    public function byLicense()
    {
        return Vehicle::where('vehicle_type_id', $this->vehicle_type_id)->get()->mapWithKeys(function ($item) {
            $vehicle_id = $item->id;
            $model      = Model::where(array_merge($this->whereData, [['vehicle_id', $vehicle_id]]));

            $fleet_id = $item->fleet_id;
            $distance = $model->pluck('distance')->toArray();
            $quantity = array_map('floatval', $model->pluck('quantity')->toArray());
            $rate     = $model->pluck('rate');
            $data_set = ['x', 'y'];

            return [$item->license => ['data_set' => array_merge([$data_set], collect($quantity)->zip($distance)->toArray()),
                                       'max'      => $rate->max(),
                                       'min'      => $rate->min(),
                                       'mean'     => array_sum($quantity) === 0 ? null : array_sum($distance) / array_sum($quantity),
                                       'median'   => $rate->median(),
                                       'mode'     => (count($rate) === 0) ? null : ($rate->map(function ($item) {
                                               return (int) round($item * 100, 0);
                                           })->mode()[0] / 100),
                                       'zone'     => $this->zoning([['vehicle_id', $vehicle_id]]),
                                       'fleet_id' => $fleet_id,
            ]];
        })->toArray();

    }

    public function byDriver()
    {
//        return Driver::all()->mapWithKeys(function ($item) {
//
//            $model = Model::whereHas('vehicle', function ($query) {
//                return $query->where('vehicle_type_id', $this->vehicle_type_id);
//            })->where('driver_id', $item->id)->where($this->whereData);
//
//            $distance = $model->pluck('distance')->toArray();
//            $quantity = array_map('floatval', $model->pluck('quantity')->toArray());
//            $rate     = $model->pluck('rate');
//            $data_set = ['x', 'y'];
//            $name     = $item->firstName . ' ' . $item->lastName;
//
//            return [$name => ['data_set' => array_merge([$data_set], collect($quantity)->zip($distance)->toArray()),
//                              'max'      => $rate->max(),
//                              'min'      => $rate->min(),
//                              'mean'     => array_sum($quantity) === 0 ? null : array_sum($distance) / array_sum($quantity),
//                              'median'   => $rate->median(),
//                              'mode'     => (count($rate) === 0) ? null : ($rate->map(function ($item) {
//                                      return (int) round($item * 100, 0);
//                                  })->mode()[0] / 100),
//                              'zone'     => $this->zoning([['driver_id', $item->id]], 'fleet'),
//            ]];
//        })->toArray();
//        $result = Vehicle::where('vehicle_type_id', $this->vehicle_type_id)->whereHas('drivers', function ($query) {
//            return $query;
//        })->get();
        return Driver::whereHas('vehicles', function ($query) {
            return $query->where('vehicle_type_id', $this->vehicle_type_id);
        })->get()->mapWithKeys(function ($item) {
            $model = Model::where('driver_id', $item->id)->where($this->whereData);

            $fleet_id = $item->fleet_id;
            $distance = $model->pluck('distance')->toArray();
            $quantity = array_map('floatval', $model->pluck('quantity')->toArray());
            $rate     = $model->pluck('rate');
            $data_set = ['x', 'y'];
            $name     = $item->firstName . ' ' . $item->lastName;

            return [$name => ['data_set' => array_merge([$data_set], collect($quantity)->zip($distance)->toArray()),
                              'max'      => $rate->max(),
                              'min'      => $rate->min(),
                              'mean'     => array_sum($quantity) === 0 ? null : array_sum($distance) / array_sum($quantity),
                              'median'   => $rate->median(),
                              'mode'     => (count($rate) === 0) ? null : ($rate->map(function ($item) {
                                      return (int) round($item * 100, 0);
                                  })->mode()[0] / 100),
                              'zone'     => $this->zoning([['driver_id', $item->id]], 'fleet'),
                              'fleet_id' => $fleet_id,
            ]];
        })->toArray();

    }
}
