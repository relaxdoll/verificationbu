<?php


namespace App\Http\Controllers\API;

use App\Customer;
use App\GoogleDrive;
use App\Http\Controllers\GoogleDriveController;
use App\ImageTrack;
use App\Line;
use App\LineGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Customer as Model;
use Symfony\Component\Process\Process;


class CustomerController extends BaseController
{

    protected $name = 'Customer';

    protected $validateRule = [
        'name'          => 'required',
        'nameTH'        => 'required',
        'line_group_id' => 'required',
        'fleet_id'      => 'required',
    ];

    public function test()
    {
        $customers = Customer::all();
        foreach ($customers as $customer)
        {
            $this->createImageTrackFolder($customer->id);
        }
    }

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


        $validator = \Validator::make($input, $this->validateRule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $line   = LineGroup::find($input['line_group_id']);
        $linked = $line->update(['isLinked' => true]);

        $request->merge(['lineName' => $line->name]);
        $request->merge(['lineId' => $line->lineId]);


        $response = Model::create($request->all());

        $this->createImageTrackFolder($response->id);

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

        $model = new Model();

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
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = \Validator::make($input, $this->validateRule);

        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $line   = LineGroup::find($input['line_group_id']);
        $linked = $line->update(['isLinked' => true]);

        $request->merge(['lineName' => $line->name]);
        $request->merge(['lineId' => $line->lineId]);

        $model = Model::find($id);

        $response = $model->update($request->all());

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

    public function getCustomerByFleetId($fleet_id)
    {
        $response = Model::getCustomerByFleetId($fleet_id);

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function getCustomerReport($customer_id)
    {
        $customer = Model::find($customer_id);
        $reports  = ImageTrack::whereHas('customers', function ($query) use ($customer_id) {
            return $query->where('customer_id', $customer_id);
        })->with('image_title')->get()->toArray();

        if (count($reports) === 0)
        {
//            $reports = ImageTrack::where('fleet_id', $customer->fleet_id)->doesnthave('customers')->get()->toArray();
            $reports = ImageTrack::where('fleet_id', $customer->fleet_id)->with('image_title')->get()->toArray();

        }

        return $this->sendResponse($reports, 'Report retrieved successfully.');
    }

    public function createImageTrackFolder($id)
    {
        $model = Model::find($id);

        $name = $model->nameTH;

        $drive = new GoogleDriveController();

        $data = [
            'folder_id' => $drive->createFolder(GoogleDrive::find(5)->folder_id, $name),
            'name'      => $name];

        $response = GoogleDrive::create($data);

        $model->update(['image_track_drive_id' => $response->id]);

        return $response;
    }

    public function testCustomerGroup(Request $request)
    {

    }


}
