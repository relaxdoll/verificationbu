<?php


namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Certificate as Model;


class CertificateController extends BaseController
{

	protected $name = 'Certificate';

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
	 * @param  \Illuminate\Http\Request $request
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
	 * @param  int $id
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
	 * @param  \Illuminate\Http\Request $request
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

}
