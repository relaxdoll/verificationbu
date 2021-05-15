<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Route as Model;


class RouteController extends BaseController
{

    protected $name = 'Route';

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
            'name'        => 'required',
            'customer_id' => 'required',
        ]);


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

    public function edit($id)
    {

        $model = new \App\Route();

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


        $model    = Model::find($id);
        $response = $model->update($input);


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

    public function getRouteByCustomerId($customer_id)
    {
        $response    = Model::where('customer_id', $customer_id)->get()->toArray();


        return $this->sendResponse($response, $this->name . ' deleted successfully.');
    }

}
