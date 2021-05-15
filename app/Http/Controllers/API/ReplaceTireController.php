<?php


namespace App\Http\Controllers\API;

use App\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\ReplaceTire;


class ReplaceTireController extends BaseController
{
	protected $name = 'Tire Replacement';

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$model = new ReplaceTire();

		$response = $model->addQuery($request->query())->index()->toArray();

		return $this->sendResponse($response, $this->name . ' retrieved successfully.');
	}

	public function amount()
	{
		$date = new Carbon();

		$startDate = new Carbon($date);
		$endDate   = new Carbon($date);

		$startDate->startOfMonth();
		$endDate->endOfMonth();

		$firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
		$lastDayofPreviousMonth  = Carbon::now()->subMonth()->endOfMonth()->toDateString();

		$data = Inventory::get();

		$total = $data->filter(function ($value) use ($startDate, $endDate) {
			return ($value->updated_at >= $startDate) && ($value->updated_at <= $endDate) && ($value->status_id == 1);
		})
			->map(function ($item) {
				return $item->price;
			})
			->sum();

		$last_total = $data->filter(function ($value) use ($firstDayofPreviousMonth, $lastDayofPreviousMonth) {
			return ($value->updated_at >= $firstDayofPreviousMonth) && ($value->updated_at <= $lastDayofPreviousMonth) && ($value->status_id == 1);
		})
			->map(function ($item) {
				return $item->price;
			})
			->sum();

		return $this->sendResponse(['total' => $total, 'last_total' => $last_total], $this->name . ' retrieved successfully.');
	}

	public function quantity()
	{
		$date = new Carbon();

		$startDate = new Carbon($date);
		$endDate   = new Carbon($date);

		$startDate->startOfMonth();
		$endDate->endOfMonth();

		$firstDayofPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
		$lastDayofPreviousMonth  = Carbon::now()->subMonth()->endOfMonth()->toDateString();

		$data = Inventory::get();

		$total = $data->filter(function ($value) use ($startDate, $endDate) {
			return ($value->updated_at >= $startDate) && ($value->updated_at <= $endDate) && ($value->status_id == 1);
		})
			->count();

		$last_total = $data->filter(function ($value) use ($firstDayofPreviousMonth, $lastDayofPreviousMonth) {
			return ($value->updated_at >= $firstDayofPreviousMonth) && ($value->updated_at <= $lastDayofPreviousMonth) && ($value->status_id == 1);
		})
			->count();

		return $this->sendResponse(['total' => $total, 'last_total' => $last_total], $this->name . ' retrieved successfully.');
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
			'date'       => 'required',
			'reason_id'  => 'required',
			'replace_id' => 'required',
		]);


		if ($validator->fails())
		{
			return $this->sendError('Validation Error . ', $validator->errors());
		}

		$response = ReplaceTire::create($input);

		return $this->sendResponse($response, $this->name . ' saved successfully.');
	}

    public function old(Request $request)
    {
        $input = $request->all();

        $response = ReplaceTire::create($input);

        return $this->sendResponse($response, $this->name . ' saved successfully.');
    }

	public function validateAPI(Request $request)
	{
		$inputs = $request->all();

		$rule = [];

		foreach ($inputs as $index => $input)
		{
			$rule[$index . '.serial'] = 'required|unique:inventories,serial';
		}

		$customMessages = [
			'required' => 'This field is required.',
			'unique'   => 'This serial has already been taken.'
		];

		$validator = \Validator::make($inputs, $rule, $customMessages);

		if ($validator->fails())
		{
			return $this->sendError('Validation Error.', $validator->errors());
		}


		return $this->sendResponse('validated', $this->name . ' saved successfully.');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$data = ReplaceTire::find($id);


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
	 * @param ReplaceTire $model
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, ReplaceTire $model)
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
	 * @param ReplaceTire $model
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 */
	public function destroy(ReplaceTire $model)
	{
		$model->delete();


		return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
	}
}
