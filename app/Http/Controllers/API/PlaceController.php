<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Place as Model;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;


class PlaceController extends BaseController
{

    protected $name = 'Place';

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();


        $validator = \Validator::make($input, [
            'name' => 'required',
            'lat'  => 'required',
            'lng'  => 'required',
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
    public function destroy(Model $model)
    {
        $model->delete();


        return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
    }

    public function getOnelinkGeofence()
    {
        $client = new Client;
        $url    = 'https://5ybioj2on4.execute-api.ap-southeast-1.amazonaws.com/gateway/gateway/fleet/master/listgeofences';
        $header = ['headers' => ['Content_type' => 'application/json', 'username' => 'hc0853861806', 'password' => 'hc0853861806']];
        $res    = $client->request('GET', $url, $header);
        $data   = json_decode($res->getBody()->getContents())->result;

        foreach ($data as $value)
        {
            $place                       = new Model();
            $place->geofence_id          = $value->geofence_id;
            $place->geofence_type_name   = $value->geofence_type_name;
            $place->geofence_name        = $value->geofence_name;
            $place->tambon               = $value->tambon;
            $place->amphur               = $value->amphur;
            $place->province             = $value->province;
            $place->lat                  = $value->coordinate[0]->Lat;
            $place->lng                  = $value->coordinate[0]->Lng;
            $place->radius               = $value->radius;
            $place->geofence_description = $value->geofence_description;
            $place->save();
        }

        return $this->sendResponse('Success', $this->name . ' saved successfully.');
    }

}
