<?php


namespace App\Http\Controllers\API;

use App\Customer;
use App\Location;
use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Location as Model;


class LocationController extends BaseController
{

	protected $name = 'Location';

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        $model = new Model();

        $response = $model->list()->toArray();


		return $this->sendResponse($response, $this->name . 's retrieved successfully.');
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
            'file' => 'required',
            'fleet_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

		$filePath = $request->file;

        $delimiter = ',';

        $header = null;
        $data   = array();

        if (($handle = fopen($filePath, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false)
            {
                if (!$header)
                {
                    $headers = $row;



                    //Fix ID Bug
                    foreach ($headers as $key => $header)
                    {
                        if (strpos($header, "ID") == 3 && strlen($header) == 5 && $key == 0)
                        {
                            $headers[$key] = 'ID';
                            break;
                        }
                    }

                } else
                {
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        }

        $fleet_id = $request->fleet_id;

        Location::where('fleet_id', $fleet_id)->delete();

        $response = [];

        foreach ($data as $key => $datum)
        {

            $location = [];

            $customer = Customer::where('name', $datum['Customer'])->first();
            $location['customer_id'] = $customer->id;

            $vehicle = Vehicle::where('license', $datum['License'])->first();
            $location['vehicle_id'] = $vehicle->id;

            $location['fleet_id'] = $customer->fleet_id;

            $location['user_id'] = $request->user_id;

            $response[$key]= Location::create($location);
        }

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
	public function destroy($id)
	{

	    $response = Location::find($id)->delete();

		return $this->sendResponse($response, $this->name . ' deleted successfully.');
	}

    public function sendOne(Request $request)
    {
        $location = new \App\Http\Controllers\LocationController();

        $response = $location->sendOne($request);

        return $this->sendResponse($response, $this->name . ' sent successfully.');
    }

}
