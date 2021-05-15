<?php


namespace App\Http\Controllers\API;

use App\Customer;
use App\Driver;
use App\ImageTrack;
use App\ImageTrackTitle;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\ImageTrack as Model;


class ImageTrackController extends BaseController
{

    protected $name = 'ImageTrack';

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

    public function info($lineId)
    {
        $driver = Driver::where('lineId', $lineId)->first();

        $customers = Customer::where('fleet_id', $driver->fleet_id)->get()->pluck('nameTH', 'id')->toArray();

        $vehicles = $driver->vehicles->pluck('license', 'id');

        if (count($vehicles) === 0)
        {
            $vehicles = Vehicle::where('fleet_id', $driver->fleet_id)->get()->pluck('license', 'id')->toArray();
        }

        return $this->sendResponse(['driver' => $driver, 'customer' => $customers, 'vehicle' => $vehicles], $this->name . 's retrieved successfully.');
    }

    public function report($customer_id)
    {

        $customer = Customer::find($customer_id);

        $reports = $customer->reports->pluck('title', 'id');

        if (count($reports) === 0)
        {
            $reports = ImageTrack::where('fleet_id', $customer->fleet_id)->get()->pluck('title', 'id');
        }

        return $this->sendResponse($reports, $this->name . 's retrieved successfully.');
    }

    public function reportDetail($report_id)
    {

        $report = ImageTrack::with('image_title')->find($report_id);


        return $this->sendResponse($report, $this->name . 's retrieved successfully.');
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
            'title'    => 'required',
            'type'     => 'required',
            'fleet_id' => 'required',
        ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $model    = Model::create($input);
        $response = $model->customers()->sync($request->customer_id);


        if (!is_null($request->image_titles))
        {
            foreach ($request->image_titles as $key => $title)
            {
                $image_title                = [];
                $image_title['report_id']   = $model->id;
                $image_title['image_index'] = $key;
                $image_title['title']       = $title;
                ImageTrackTitle::create($image_title);
            }
        }


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


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
        $response = Model::find($id)->delete();


        return $this->sendResponse($response, $this->name . ' deleted successfully.');
    }


}
