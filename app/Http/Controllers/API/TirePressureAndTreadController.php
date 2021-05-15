<?php


namespace App\Http\Controllers\API;

use App\Driver;
use App\TirePlacement;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\TirePressureAndTread as Model;


class TirePressureAndTreadController extends BaseController
{

    protected $name = 'TirePressureAndTread';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Model::all();


        return $this->sendResponse($response->toArray(), $this->name . 's retrieved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $current_tire = TirePlacement::find($request->tire_placement_id);

        if (!is_null($current_tire->end_tread_depth))
        {
            $current_tread_depth = $current_tire->end_tread_depth;
        } else
        {
            $current_tread_depth = $current_tire->start_tread_depth;
        }

        $validator = \Validator::make($input,
            ['tread_depth' => 'lte:' . $current_tread_depth
            ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $tire_placement_id = $request->tire_placement_id;

        TirePlacement::where('id', $tire_placement_id)
            ->update(['end_tread_depth' => $request->tread_depth]);

        Model::where('tire_placement_id', $tire_placement_id)
            ->where('is_updated', 1)
            ->update(['is_updated' => 0]);

        $input = $request->all();

        $response = Model::create($input);


        return $this->sendResponse($response, $this->name . ' saved successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Model::find($id);


        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($model->toArray(), $this->name . ' retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Model $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $model)
    {
        $input = $request->all();


//		$validator = Validator::make($input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if($validator->fails()){
//			return $this->sendError('Validation Error.', $validator->errors());
//		}


        $model->name   = $input['name'];
        $model->detail = $input['detail'];
        $model->save();


        return $this->sendResponse($model->toArray(), $this->name . ' updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Model $model)
    {
        $model->delete();


        return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
    }

    public function tireUpdateThisWeek($vehicle_id)
    {
        $tire = Vehicle::find($vehicle_id);

        $response = $tire->tireUpdateThisWeek->mapWithKeys(function ($item) {
            return [$item->tire_placement->placement =>
                        [
                            'pressure'    => $item->tire_pressure,
                            'tread_depth' => $item->tread_depth,
                        ]];
        });

        return $this->sendResponse($response, 'Updated tire retrieved successfully');
    }

    public function timesDriverDoTirePressureJob()
    {
        function mapNotUpdateVehicle(string $param, $item)
        {
            return Vehicle::whereHas($param, function ($query) use ($item) {
                $query->where('driver_id', $item['id']);
            })->get()->map(function ($item) {
                if (!Vehicle::find($item['id'])->allTireUpdateThisWeek())
                {
                    return $item['license'];
                } else
                {
                    return false;
                }
            })->reject(function ($value) {
                return $value === false;
            })->toArray();
        }

        $first_date_data   = Model::take(1)->get()->first()->created_at;
        $first_date_carbon = Carbon::parse($first_date_data);
        $week_diff         = $first_date_carbon->diffInWeeks(Carbon::today());

        return Driver::whereHas('vehicles', function ($query) {
            $query->where('vehicle_type_id', '!=', 9);
        })->where('fleet_id', '!=', 99)->get()->map(function ($driver) use ($week_diff) {
            $not_do_pressure_license_amount = array_merge(mapNotUpdateVehicle('drivers', $driver), mapNotUpdateVehicle('tailDrivers', $driver));
            $data                           = Model::where('driver_id', $driver->id)->get()->mapToGroups(function ($item) use ($driver, $week_diff) {
                if (!is_null($item))
                {
                    $date = new \DateTime($item->created_at);
                    $week = $date->format('W');

                    return [$week => $item];
                } else
                {
                    return false;
                }

            })->reject(function ($value) {
                return $value === false;
            });

            return [
                'id'           => $driver->id,
                'firstName'    => $driver->firstName,
                'lastName'     => $driver->lastName,
                'fleet_id'     => $driver->fleet_id,
                'fleet_name'   => $driver->fleet->name,
                'do_this_week' => count($not_do_pressure_license_amount) > 0 ? false : true,
                'amount'       => count($data),
                'totalWeek'    => $week_diff,
            ];
        })->toArray();
    }

}
