<?php


namespace App\Http\Controllers\API;

use App\Vehicle;
use App\Vehicle as Model;
use App\LineGroup;
use App\ReplaceTire;
use App\VehicleOld;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;


class VehicleController extends BaseController
{
    protected $name = 'Vehicle';

    protected $validateRule = [
        'license'         => 'required',
        'fleet_id'        => 'required',
        'vehicle_type_id' => 'required',
    ];

    public function test()
    {
        $this->getSummary(37);
    }

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

        $response = $model->index()->groupBy('fleet')->toArray();

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

    public function getActiveTires($vehicle_id)
    {
        $vehicle = Model::where('id', $vehicle_id)->get()->map(Vehicle::mapActiveTire());


        return $this->sendResponse($vehicle, $this->name . ' deleted successfully.');
    }

    public function getSummary($vehicle_id)
    {
        Model::find($vehicle_id)->getSummary();
    }
}
