<?php


namespace App\Http\Controllers\API;

use App\TireChangeRequest;
use App\TirePlacement;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Technician as Model;
use App\LineUser;
use Illuminate\Validation\Rule;


class TechnicianController extends BaseController
{

    protected $name = 'Technician';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $model = new Model();

        $response = $model->index()->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function indexGroup()
    {

        $model = new Model();

        $response = $model->index()->groupBy('fleet')->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
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
            'firstName' => Rule::unique('technicians')->where(function ($query) use ($request) {
                return $query->where('lastName', $request->lastName);
            }),
            'lastName'  => 'required',
            'lineId'    => 'required',
            'fleet_id'  => 'required',
        ],
            [
                'firstName.unique' => $request->firstName . ' ' . $request->lastName . ' already existed.',
            ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $line = LineUser::find($request->lineId);

        $input['line_user_id'] = $line->id;
        $input['lineId']       = $line->lineId;
        $input['avatar']       = $line->avatar;

        $response = Model::create($input);

//        $controller = new \App\Http\Controllers\LineController();
//
//        $controller->linkDriverMenu($line->lineId);

        $line->save();

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

    public function edit($id)
    {
        $model = new \App\Technician;

        $response = $model->edit($id);

        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
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

//		$validator = Validator::make($input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if($validator->fails()){
//			return $this->sendError('Validation Error.', $validator->errors());
//		}

        $model = Model::find($id);

        $response = $model->update($input);

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

    public function getTechnicianIdByLineId($lineId)
    {
        $technician = Model::where('lineId', $lineId)->first();

        return $this->sendResponse($technician, $this->name . 's retrieved successfully.');
    }

    public function getRequestedByTirePlacementId($id)
    {
        $model = Model::find($id);

        $response = $model->index()->toArray();
        dd($response);
        // Find fleeted all drivers
        // Find Vehicle of thoss drivers
        // Find Request by vehicle_id map to tire_placement_id


//        return $this->sendResponse($technician, $this->name . 's retrieved successfully.');
    }

    public function changeTire(Request $request)
    {
        // Update Tire Change Request
        $tireChangeRequest                = TireChangeRequest::where('tire_placement_id', $request->tire_placement_id)->where('is_changed', 0)->where('is_rejected', 0)->firstOrFail();
        $tireChangeRequest->technician_id = $request->technician_id;
        $tireChangeRequest->is_changed    = $request->is_changed;
        $tireChangeRequest->save();

        // Update Tire Placement
        $tirePlacement                  = TirePlacement::where('id', $request->tire_placement_id)->firstOrFail();
        $tirePlacement->end_mileage     = $request->end_mileage;
        $tirePlacement->end_tread_depth = $request->end_tread_depth;
        $tirePlacement->reason_id       = $request->reason_id;
        $tirePlacement->installer_id    = $request->technician_id;
        $tirePlacement->end_date        = now()->format('Y-m-d H:i:s');
        $tirePlacement->save();

//        return $this->sendResponse($model->toArray(), $this->name . ' updated successfully.');
    }

    public function rejectChangeTire(Request $request)
    {
        // Update Tire Change Request
        $tireChangeRequest                = TireChangeRequest::where('tire_placement_id', $request->tire_placement_id)->where('is_changed', 0)->where('is_rejected', 0)->firstOrFail();
        $tireChangeRequest->technician_id = $request->technician_id;
        $tireChangeRequest->is_rejected   = 1;
        $tireChangeRequest->save();

//        return $this->sendResponse($model->toArray(), $this->name . ' updated successfully.');
    }

}
