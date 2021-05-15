<?php


namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Movement as Model;


class MovementController extends BaseController
{

	protected $name = 'Movement';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$response = Model::orderBy('id', 'DESC')->get();


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


		$validator = \Validator::make($input, [
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


    public function edit($id)
    {

        $model = new \App\Movement();

        $response = $model->edit($id);

        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function update(Request $request, $id)
    {

        $model = Model::find($id);

        $response = $model->update($request->all());


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

    public function all()
    {
        $result = Model::orderBy('date', 'DESC')->get()->map(function ($item) {

            return [
                'id'      => $item->id,
                'date'    => Carbon::createFromFormat('Y-m-d', $item['date'])->format('D, F d, Y'),
                'event'   => $item->event,
                'place'   => $item->place,
                'carrier' => $item->carrier,
            ];
        });


        return $this->sendResponse($result->toArray(), $this->name . ' deleted successfully.');

    }

}
