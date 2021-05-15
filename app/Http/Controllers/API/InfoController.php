<?php


namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Info as Model;


class InfoController extends BaseController
{

	protected $name = 'Info';

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


		$validator = \Validator::make($input, [
//			'value' => 'required',
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

        $model = new \App\Info();

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
	 * @param  \Illuminate\Http\Request $request
	 * @param Model $model
	 * @return \Illuminate\Http\Response
	 */
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

    public function activate($id)
    {
        $response = Model::activate($id);

        return $this->sendResponse($response, $this->name . ' deleted successfully.');

	}

    public function getCurrent()
    {
        $model = Model::where('is_active',1)->get();

        $result = $model->map(function ($item) {

            return ['lat'    => $item->lat,
                    'id'     => $item->id,
                    'lng'    => $item->lng,
                    'eta'    => Carbon::createFromFormat('Y-m-d', $item['eta'])->format('D, F d, Y'),
                    'depart' => Carbon::createFromFormat('Y-m-d', $item['depart'])->format('D, F d, Y'),
                    'from'   => strtoupper($item->from),
                    'to'     => strtoupper($item->to),
            ];
        })[0];

        return $this->sendResponse($result, $this->name . ' deleted successfully.');
	}

}
