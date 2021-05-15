<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\VehicleInspectionList as Model;


class VehicleInspectionListController extends BaseController
{

    protected $name = 'VehicleInspectionList';

    protected $validateRule = [
        'name'            => 'required',
        'vehicle_type_id' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new Model();

        $response = $model->index()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function indexGroup(Request $request)
    {
        $model = new Model();

        $response = $model->index()->groupBy('vehicle_type_name')->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function list(Request $request)
    {
        $model = new Model();

        $response = $model->addQuery($request->query())->list()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
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


        $validator = \Validator::make($input, $this->validateRule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $response = Model::create($request->all());

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
        $model = new Model();

        $response = $model->show($id);

        if (is_null($response))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function edit($id)
    {

        $model = new Model();

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
     * @param Vehicle $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = \Validator::make($input, $this->validateRule);

        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $model = Model::find($id);

        $response = $model->update($request->all());

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Vehicle $model
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Vehicle $model)
    {
        $model->delete();


        return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
    }

    public function getInspectionListByVehicleType($vehicle_type_id)
    {
        $response = Model::where('vehicle_type_id', $vehicle_type_id)->get()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function createSameInspectionList()
    {
//        $inspectionLists = Model::where('vehicle_type_id', 1)->get()->toArray();
//
//        foreach ($inspectionLists as $value)
//        {
//            $inspectionList                  = new Model();
//            $inspectionList->name            = $value['name'];
//            $inspectionList->standard        = $value['standard'];
//            $inspectionList->vehicle_type_id = 18;
//            $inspectionList->save();
//        }

        return 'Success';
    }

}
