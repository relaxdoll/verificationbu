<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\TireType;


class TireTypeController extends BaseController
{
	protected $name = 'TireType';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$data = TireType::orderBy('name', 'asc')->get()->map(function ($item, $key) {
			return ['text' => $item['name'], 'value' => $item['id']];
		});

		return $this->sendResponse($data->toArray(), $this->name . 's retrieved successfully.');
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


		$validator = \Validator::make($input, [
			'name' => 'required',
		]);


		if ($validator->fails())
		{
			return $this->sendError('Validation Error.', $validator->errors());
		}


//		$product = Vendor::create($input);


		return $this->sendResponse('test', $this->name . ' saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$data = TireType::find($id);


		if (is_null($data))
		{
			return $this->sendError('Product not found.');
		}


		return $this->sendResponse($data->toArray(), $this->name . ' retrieved successfully.');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param TireType $cheque
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, TireType $cheque)
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


		$cheque->name   = $input['name'];
		$cheque->detail = $input['detail'];
		$cheque->save();


		return $this->sendResponse($cheque->toArray(), $this->name . ' updated successfully.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param TireType $cheque
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function destroy(TireType $cheque)
	{
		$cheque->delete();


		return $this->sendResponse($cheque->toArray(), $this->name . ' deleted successfully.');
	}
}