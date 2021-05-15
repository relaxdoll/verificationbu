<?php


namespace App\Http\Controllers\API;

use App\Tire;
use App\TirePlacement;
use App\TirePressureAndTread;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\TirePlacement as Model;


class TirePlacementController extends BaseController
{

    protected $name = 'TirePlacement';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test()
    {
    }

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

        $validator = \Validator::make($input, []);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

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
    public function update(Request $request, $id)
    {
        $response = Model::find($id)->update($request->all());

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }


    public function swap(Request $request)
    {
        $input = $request->all();

        if (is_null($input['end_mileage_2']))
        {
            $input['end_mileage_2'] = $input['end_mileage'];
        }

        $response = [];

        $model_1 = Model::find($request->placement_id_1);
        $model_2 = Model::find($request->placement_id_2);

        $update_placement_1 = [
            'end_tread_depth' => $input['end_tread_depth_1'],
            'end_mileage'     => $input['end_mileage'],
            'reason_id'       => 6,
            'end_date'        => $input['date'],
            'is_on_vehicle'   => 0,
        ];

        $update_placement_2 = [
            'end_tread_depth' => $input['end_tread_depth_2'],
            'end_mileage'     => $input['end_mileage_2'],
            'reason_id'       => 6,
            'end_date'        => $input['date'],
            'is_on_vehicle'   => 0,
        ];

        if ($model_1->is_spare === 1)
        {
            $update_placement_1['end_mileage']     = null;
            $update_placement_1['end_tread_depth'] = $model_1->start_tread_depth;
        }

        if ($model_2->is_spare === 1)
        {
            $update_placement_2['end_mileage']     = null;
            $update_placement_2['end_tread_depth'] = $model_2->start_tread_depth;
        }

        $response['tire_placement_1'] = $model_1->update($update_placement_1);
        $response['tire_placement_2'] = $model_2->update($update_placement_2);

        $tire_1 = Tire::find($model_1->tire_id);
        $tire_2 = Tire::find($model_2->tire_id);

        $distance_travel_1 = Model::where(['tire_id' => $model_1->tire_id, 'is_on_vehicle' => 0, 'is_spare' => 0])->get()->map(function ($item) {
            return $item['end_mileage'] - $item['start_mileage'];
        })->sum();

        $distance_travel_2 = Model::where(['tire_id' => $model_2->tire_id, 'is_on_vehicle' => 0, 'is_spare' => 0])->get()->map(function ($item) {
            return $item['end_mileage'] - $item['start_mileage'];
        })->sum();

        $update_tire_1 = [
            'tread_depth'        => $input['end_tread_depth_1'],
            'distance_travelled' => $distance_travel_1,
        ];

        $update_tire_2 = [
            'tread_depth'        => $input['end_tread_depth_2'],
            'distance_travelled' => $distance_travel_2,
        ];

        if ($model_1->is_spare === 1)
        {
            $update_tire_1['tread_depth'] = $model_1->start_tread_depth;
        }

        if ($model_2->is_spare === 1)
        {
            $update_tire_2['tread_depth'] = $model_2->start_tread_depth;
        }

        $response['tire_1'] = $tire_1->update($update_tire_1);
        $response['tire_2'] = $tire_2->update($update_tire_2);

        $new_tire_1 = Tire::find($model_2->tire_id);
        $new_tire_2 = Tire::find($model_1->tire_id);

        $new_placement_1 = [
            'start_date'        => $input['date'],
            'placement'         => $model_1->placement,
            'start_tread_depth' => $new_tire_1->tread_depth,
            'start_mileage'     => $input['end_mileage'],
            'vehicle_id'        => $model_1->vehicle_id,
            'tire_id'           => $model_2->tire_id,
            'is_spare'          => $model_1->is_spare
        ];

        $new_placement_2 = [
            'start_date'        => $input['date'],
            'placement'         => $model_2->placement,
            'start_tread_depth' => $new_tire_2->tread_depth,
            'start_mileage'     => $input['end_mileage_2'],
            'vehicle_id'        => $model_2->vehicle_id,
            'tire_id'           => $model_1->tire_id,
            'is_spare'          => $model_2->is_spare,
        ];

        if ($model_1->is_spare === 1)
        {
            $new_placement_1['start_mileage'] = null;
        }

        if ($model_2->is_spare === 1)
        {
            $new_placement_2['start_mileage'] = null;
        }

        $response['new'][1] = Model::create($new_placement_1);

        $response['new'][2] = Model::create($new_placement_2);

        $response['pressure1'] = TirePressureAndTread::where('is_updated', 1)->where('tire_placement_id', $request->placement_id_1)->update(['is_updated' => 0]);
        $response['pressure2'] = TirePressureAndTread::where('is_updated', 1)->where('tire_placement_id', $request->placement_id_2)->update(['is_updated' => 0]);


        return $this->sendResponse($response, $this->name . ' updated successfully.');
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

    /**
     * @param $current_tire_1
     * @return mixed
     */
    protected function getTreadValidate($current_tire_1)
    {
        if (!is_null($current_tire_1->end_tread_depth))
        {
            $current_tread_depth_1 = $current_tire_1->end_tread_depth;
        } else
        {
            $current_tread_depth_1 = $current_tire_1->start_tread_depth;
        }

        return $current_tread_depth_1;
    }

    public function replace(Request $request)
    {
        $input = $request->all();

        $response = [];

        if ($input['placement_id'])
        {
            $model = Model::find($input['placement_id']);

            $update_placement = [
                'end_tread_depth' => $input['end_tread_depth'],
                'end_mileage'     => $input['mileage'],
                'reason_id'       => $input['reason_id'],
                'end_date'        => $input['date'],
                'is_on_vehicle'   => 0,
            ];

            if ($model->is_spare === 1)
            {
                $update_placement['end_mileage']     = null;
                $update_placement['end_tread_depth'] = $model->start_tread_depth;
            }

            $response['tire_placement'] = $model->update($update_placement);

            $tire = Tire::find($model->tire_id);

            $distance_travel = Model::where(['tire_id' => $model->tire_id, 'is_on_vehicle' => 0, 'is_spare' => 0])->get()->map(function ($item) {
                return $item['end_mileage'] - $item['start_mileage'];
            })->sum();

            $update_tire = [
                'tread_depth'        => $input['end_tread_depth'],
                'distance_travelled' => $distance_travel,
                'reason_id'          => $input['reason_id'],
            ];

            if ($model->is_spare === 1)
            {
                $update_tire['tread_depth'] = $model->start_tread_depth;
            }

            $response['tire'] = $tire->update($update_tire);
        }

        $new_tire = Tire::find($input['tire_id']);

        $new_replace = [
            'start_tread_depth' => $new_tire->tread_depth,
            'placement'         => $input['placement'],
            'is_spare'          => $input['is_spare'],
            'start_mileage'     => $input['mileage'],
            'start_date'        => $input['date'],
            'vehicle_id'        => $input['vehicle_id'],
            'tire_id'           => $input['tire_id']
        ];

        if ($input['is_spare'] === 1)
        {
            $new_replace['start_mileage'] = null;
        }

        $response['new_placement'] = Model::create($new_replace);

        $response['new_tire'] = $new_tire->update(['is_available' => 0]);

        $response['pressure'] = TirePressureAndTread::where('is_updated', 1)->where('tire_placement_id', $input['placement_id'])->update(['is_updated' => 0]);

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }

    public function getActiveTireByTread()
    {

        $data = Model::where('is_on_vehicle', 1)->get()->map(function ($item) {
            $tread_depth = is_null($item->end_tread_depth) ? $item->start_tread_depth : $item->end_tread_depth;
            if ($tread_depth <= 5 && $item->tire)
            {
                return [
                    'id'                  => $item->id,
                    'placement'           => $item->placement,
                    'start_date'          => $item->start_date,
                    'current_tread_depth' => $tread_depth,
                    'start_mileage'       => $item->start_mileage,
                    'serial'              => $item->tire->serial,
                    'initial_tread_depth' => $item->tire->initial_tread_depth,
                    'tire_type'           => $item->tire->tire_type->name,
                    'tire_size'           => $item->tire->tire_type->size,
                    'brand'               => $item->tire->brand->name,
                    'license'             => $item->vehicle->license,
                    'tread_name'          => 'Tread = ' . $tread_depth,
                ];
            }

            return [];

        })->reject(function ($value) {
            return $value === [];
        })->groupBy('tread_name')->sortKeys()->toArray();

        return $this->sendResponse($data, 'ExpiredTireByTread retrieved successfully.');
    }
}
