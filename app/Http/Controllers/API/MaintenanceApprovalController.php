<?php


namespace App\Http\Controllers\API;

use App\Approver;
use App\Classes\Drive;
use App\Driver;
use App\RefuelJob;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\API\BaseController as BaseController;
use App\MaintenanceApproval as Model;


class MaintenanceApprovalController extends BaseController
{

    protected $name = 'MaintenanceApproval';

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

        $validator = \Validator::make($input, [
            'vehicle_id'   => 'required',
            'requester_id' => 'required',
            'request_date' => 'required',
        ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

//        $image_array = [];
//
//        for ($x = 0; $x < $request->image_number; $x ++)
//        {
//
//            $file       = $request->file($x);
//            $image_name = time() . $this->generateRandomString(2) . '.png';
//
//            $drive           = new Drive();
//            $image_array[$x] = $drive->uploadImage($file, $image_name, 2)['file_id'];
//
//        }
//
//        $image_array = json_encode($image_array);
//
//        $input['image_array'] = $image_array;


        $response = Model::create($input);

        $this->createDriverJob($response);

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
        $input = $request->all();

        if ($request->request_mileage && $request->start_mileage)
        {
            $validator = \Validator::make($input, [
                'start_mileage' => ['required', 'gte:' . $input['request_mileage']],
            ]);

            if ($validator->fails())
            {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        if ($request->start_mileage && $request->end_mileage)
        {
            $validator = \Validator::make($input, [
                'end_mileage' => ['required', 'gte:' . $input['start_mileage']],
            ]);

            if ($validator->fails())
            {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        $model = Model::find($id);

        $response = $model->update($input);

        $this->createJob($input, $id);

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

    public function getRequestByStatusId($status_id)
    {
        $model    = new Model();
        $response = $model->index(['status_id', $status_id])->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function getRequestByDriver($requester_id)
    {
        $model    = new Model();
        $response = $model->index(['requester_id', $requester_id])->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function getUnapprovedJobByFleet($approver_id)
    {
        $approver_fleet_id = Approver::where('id', $approver_id)->get()->pluck('fleet')->first()->id;

        $response = Model::where('status_id', 1)->get()->map(function ($item) use ($approver_fleet_id) {
            if ($item->vehicle->fleet->id === $approver_fleet_id)
            {
                return [
                    'id'                => $item->id,
                    'request_date'      => $item->request_date,
                    'start_date'        => $item->start_date,
                    'end_date'          => $item->end_date,
                    'approve_date'      => $item->approve_date,
                    'schedule_date'     => $item->schedule_date,
                    'symptom'           => $item->symptom,
                    'request_mileage'   => $item->request_mileage,
                    'start_mileage'     => $item->start_mileage,
                    'vehicle'           => $item->vehicle,
                    'requester'         => $item->requester,
                    'approver'          => $item->approver,
                    'status'            => $item->status,
                    'repaired_fleet_id' => $item->repaired_fleet_id,
                ];
            } else
            {
                return false;
            }
        })->reject(function ($value) {
            return $value === false;
        })->toArray();


        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function isVehicleFinished($vehicle_id)
    {
        $everRequested = Model::where('vehicle_id', $vehicle_id)->get()->first();

        if ($everRequested)
        {
            $finishedStatus = [3, 5];
            $model          = Model::where('vehicle_id', $vehicle_id)->whereIn('status_id', $finishedStatus)->latest()->first();

            $arr = (array) $model;
            if ($arr)
            {
                return $this->sendResponse(true, $this->name . 's retrieved successfully.');
            }

            return $this->sendResponse(false, $this->name . 's retrieved successfully.');
        } else
        {
            return $this->sendResponse(true, $this->name . 's retrieved successfully.');
        }

    }

    public function getDetailByApprovalId($id)
    {
        $response = Model::where('id', $id)->get()->mapWithKeys(function ($item) use ($id) {
            if (count($item->maintenance_detail) > 0)
            {
                return ['details' => $item->maintenance_detail];
            } else
            {
                return false;
            }
        })->reject(function ($value) {
            return $value === false;
        })->toArray();


        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function createDriverJob($data)
    {
        RefuelJob::create(['refuel_id' => $data->id, 'model' => 'maintenance_approval_driver']);
    }

    public function createJob($data, $id)
    {
        if (array_key_exists('status_id', $data))
        {
            switch ($data['status_id'])
            {
                case 2:
                    return RefuelJob::create(['refuel_id' => $id, 'model' => 'maintenance_approval_approver']);
                case 5:
                    return RefuelJob::create(['refuel_id' => $id, 'model' => 'maintenance_approval_technician']);
                default:
                    break;
            }
        }
    }

}
