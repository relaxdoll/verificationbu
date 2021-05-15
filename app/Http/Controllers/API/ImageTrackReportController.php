<?php


namespace App\Http\Controllers\API;

use App\Classes\Drive;
use App\Customer;
use App\Http\Controllers\GoogleDriveController;
use App\ImageTrackJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\ImageTrackReport as Model;
use Illuminate\Support\Facades\Storage;


class ImageTrackReportController extends BaseController
{

    protected $name = 'PhotoReport';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test()
    {
        $carbon = Carbon::createFromFormat('d/m/Y', '24/09/2020');

        $image_tracks = Model::where('customer_id', 56)->whereDate('created_at', $carbon)->get();

        $date = $carbon->toDateString();

        $new = \Cache::get('image_track56' . $date);

        if (is_null($new))
        {
            $new = $this->createDateFolder(56,$date);
        }

        foreach ($image_tracks as $image_track)
        {
            $this->moveImageToFolder($image_track->id, $new);
        }
    }

    public function index()
    {
        $model = new Model();

        $response = $model->graph();


        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function nhp()
    {
        $now      = new Carbon();
        $response = ['NHP Jul' => Model::where('customer_id', 56)->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', '23/07/2020'),Carbon::createFromFormat('d/m/Y', '24/07/2020')])->get()->map(function ($item) {
            $date = new Carbon($item['created_at']);

            return ['driver'   => $item['driver']->name,
                    'id'       => $item['id'],
                    'customer' => $item['customer']->name,
                    'report'   => $item['report']->title,
                    'vehicle'  => $item['vehicle']->license,
                    'date'     => $date->toDateTimeString(),
            ];
        })->toArray(),
                     'NHP Aug' => Model::where('customer_id', 56)->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', '23/08/2020'),Carbon::createFromFormat('d/m/Y', '24/08/2020')])->get()->map(function ($item) {
                         $date = new Carbon($item['created_at']);

                         return ['driver'   => $item['driver']->name,
                                 'id'       => $item['id'],
                                 'customer' => $item['customer']->name,
                                 'report'   => $item['report']->title,
                                 'vehicle'  => $item['vehicle']->license,
                                 'date'     => $date->toDateTimeString(),
                         ];
                     })->toArray()
            ];

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

//		sleep(3);

//        return $this->sendResponse($input, $this->name . ' saved successfully.');
//		$validator = \Validator::make($input, [
//			'value' => 'required',
//		]);


//		if ($validator->fails())
//		{
//			return $this->sendError('Validation Error.', $validator->errors());
//		}
        $image_array = [];

        for ($x = 0; $x < $request->image_number; $x ++)
        {

            $file       = $request->file($x);
            $image_name = time() . $this->generateRandomString(2) . '.png';

            $drive           = new Drive();
            $image_array[$x] = $drive->uploadImage($file, $image_name, 4)['file_id'];

//            $image_array[$x] = Storage::disk('s3FastTrack')->putFileAs('fasttrack', $file, $image_name);
        }

        $image_array = json_encode($image_array);

        $input['image_array'] = $image_array;

        $response = Model::create($input);

        $controller = new \App\Http\Controllers\LineController();

        $flex = $controller->buildFastTrack($response->id);

        ImageTrackJob::create(['image_track_report_id' => $response->id]);

//        $controller->bot->pushMessage($flex['to'], $flex['flexBuilder']);

        return $this->sendResponse($flex, $this->name . ' saved successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $controller = new \App\Http\Controllers\LineController();

        $flex = $controller->buildFastTrack($id);


        if (is_null($flex))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($flex, $this->name . ' retrieved successfully.');
    }

    public function view($id)
    {

        $response = Model::where('id', $id)->get()->map(function ($item) {
            $date = new Carbon($item['created_at']);

            return ['driver'      => $item['driver']->name,
                    'id'          => $item['id'],
                    'customer'    => $item['customer']->name,
                    'report'      => $item['report']->title,
                    'vehicle'     => $item['vehicle']->license,
                    'date'        => $date->toDateString(),
                    'image_array' => json_decode($item['image_array']),
            ];
        })[0];

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
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

    public function createDateFolder($customer_id, $date)
    {

        $now  = new Carbon();

        $model = Customer::find($customer_id);

        $name = $model->nameTH;

        $drive = new GoogleDriveController();

        $folder_id = $drive->createFolder($model->image_track_drive->folder_id, $date);

        $response = \Cache::put('image_track' . $model->id . $date, $folder_id, now()->addWeek());

        return $folder_id;
    }

    public function moveImageToFolder($id, $new_parent)
    {

        $model = Model::find($id);

        if ($model->move === 1)
        {
            return 'moved';
        }

        $images = json_decode($model->image_array);

//        dd($images);

        $drive = new GoogleDriveController();

        $old_parent = $drive->getParentId($images[0]);

        foreach ($images as $image)
        {
            $drive->changeParent($image, $old_parent, $new_parent);
        }

        $model->update(['moved'=>1]);


    }
}
