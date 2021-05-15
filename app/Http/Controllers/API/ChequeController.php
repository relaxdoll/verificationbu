<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Vendor;


class ChequeController extends BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$cheques = Vendor::all();


		return $this->sendResponse($cheques->toArray(), 'Products retrieved successfully.');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$input = $request->all();


		$validator = \Validator::make($input, [
			'chequeNumber' => 'required',
			'pvNumber' => 'required',
			'date' => 'required',
			'payee' => 'required',
			'amount' => 'required'
		]);


		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}


//		$product = Vendor::create($input);


		return $this->sendResponse('test', 'Cheque saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$product = Vendor::find($id);


		if (is_null($product)) {
			return $this->sendError('Product not found.');
		}


		return $this->sendResponse($product->toArray(), 'Product retrieved successfully.');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param Vendor $cheque
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Vendor $cheque)
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


		return $this->sendResponse($cheque->toArray(), 'Product updated successfully.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Vendor $cheque
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function destroy(Vendor $cheque)
	{
		$cheque->delete();


		return $this->sendResponse($cheque->toArray(), 'Product deleted successfully.');
	}
}