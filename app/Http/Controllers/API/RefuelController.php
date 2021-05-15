<?php


namespace App\Http\Controllers\API;

use App\Classes\Drive;
use App\Classes\RefuelGraph;
use App\Driver;
use App\Fleet;
use App\Refuel;
use App\RefuelJob;
use App\Vehicle;
use App\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Refuel as Model;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\AssignOp\Mod;


class RefuelController extends BaseController
{

    protected $name = 'Refuel';

    public function test()
    {
        $this->getRate(4562);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Model::paginate(30);

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');

        $model = new \App\Refuel();

        $response = $model->index()->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function indexGroup()
    {
        $model = new \App\Refuel();

        $response = $model->index()->groupBy('fleet')->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function getRate($id)
    {
        $refuel = Model::find($id);

        $response = Model::where('vehicle_id', $refuel['vehicle_id'])->where('id', '<', $refuel['id'])->where('deleted', '!=', 1)->latest()->first();

        if (!is_null($response))
        {
            $refuel['distance'] = $refuel['odometer'] - $response['odometer'];
            $refuel['rate']     = $refuel['distance'] / $refuel['quantity'];

            if ($refuel['rate'] > 10)
            {
                $refuel['rate'] = null;
            }

            $response = $refuel->save();
        }


        return $response;
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
            $image_array[$x] = $drive->uploadImage($file, $image_name, 2)['file_id'];

//            $image_array[$x] = Storage::disk('s3FastTrack')->putFileAs('refuel', $file, $image_name);
        }

        $image_array = json_encode($image_array);

        $input['image_array'] = $image_array;

        $input['fleet_id'] = Driver::getFleetID($input['driver_id']);

        $response = Model::create($input);

        $response['rate'] = $this->getRate($response->id);


        $controller = new \App\Http\Controllers\LineController();

        $flex = $controller->buildRefuel($response->id);

        Vehicle::find($input['vehicle_id'])->update(['mileage' => $input['odometer']]);

        RefuelJob::create(['refuel_id' => $response->id]);
//
//        $controller->bot->pushMessage($flex['to'], $flex['flexBuilder']);

        return $this->sendResponse($flex, $this->name . ' saved successfully.');
//
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

        $flex = $controller->buildRefuel($id);


        if (is_null($flex))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($flex, $this->name . ' retrieved successfully.');
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


    public function findFuelConsumptionRate(Request $request)
    {
        $vehicle_type_id = $request->vehicle_type_id;
        $byType          = $request->byType;

        $refuel_graph = new RefuelGraph($vehicle_type_id, [['rate', '<', 9], ['rate', '>', 1], ['rate', '!=', null]]);

        $response = null;

        switch ($byType)
        {
            case "byLicense":
                $response = $refuel_graph->byLicense();
                break;
            case "byFleet":
                $response = $refuel_graph->byFleet();
                break;
            case "byDriver":
                $response = $refuel_graph->byDriver();
                break;
            default:
                break;
        }

        return $this->sendResponse($response, $this->name . ' report retrieved successfully.');
    }

    public function query(Request $request)
    {

        $query = Model::query();

        $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d H:i:s', $request->date[0]), Carbon::createFromFormat('Y-m-d H:i:s', $request->date[1])]);

        if (!is_null($request->vehicle_id))
        {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if (!is_null($request->driver_id))
        {
            $query->where('driver_id', $request->driver_id);
        }

        $query->where('deleted', 0);

        $result = $query->get()->map(function ($item) {
            return ['fleet'    => $item->fleet->name,
                    'fleet_id' => $item->fleet_id,
                    'id'       => $item->id,
                    'driver'   => (is_null($item->driver)) ? null : $item->driver->name,
                    'quantity' => $item->quantity,
                    'distance' => $item->distance,
                    'odometer' => '' . $item->odometer,
                    'rate'     => $item->rate,
                    'date'     => $item->created_at->toDateString(),
                    'vehicle'  => (is_null($item->vehicle)) ? null : $item->vehicle->license,
            ];
        })->groupBy('fleet');

        return $this->sendResponse($result, $this->name . ' report retrieved successfully.');

    }

    public function error(Request $request)
    {

        $query = Model::query();

        $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d H:i:s', $request->date[0]), Carbon::createFromFormat('Y-m-d H:i:s', $request->date[1])]);

        if (!is_null($request->vehicle_id))
        {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if (!is_null($request->driver_id))
        {
            $query->where('driver_id', $request->driver_id);
        }

        $query->where(function ($query) {
            $query->where('rate', null)
                ->orWhere('distance', '>', 2000)
                ->orWhere('distance', 1)
                ->orWhere('rate', '<', 0.5);
        });

        $query->where('is_checked', 0);

        $result = $query->get()->map(function ($item) {
            return ['fleet'    => $item->fleet->name,
                    'fleet_id' => $item->fleet_id,
                    'id'       => $item->id,
                    'driver'   => (is_null($item->driver)) ? null : $item->driver->name,
                    'quantity' => $item->quantity,
                    'distance' => $item->distance,
                    'odometer' => '' . $item->odometer,
                    'rate'     => $item->rate,
                    'vehicle'  => (is_null($item->vehicle)) ? null : $item->vehicle->license,
                    'date'     => $item->created_at->toDateString(),
                    'time'     => $item->created_at->toTimeString(),
            ];
        })->groupBy('fleet');

        return $this->sendResponse($result, $this->name . ' report retrieved successfully.');

    }

    public function view($id)
    {

        $response = Model::where('id', $id)->get()->map(function ($item) {
            $date = new Carbon($item['created_at']);

            return ['driver'      => $item['driver']->name,
                    'avatar'      => $item['driver']->avatar,
                    'id'          => $item['id'],
                    'distance'    => $item['distance'],
                    'rate'        => $item['rate'],
                    'odometer'    => $item['odometer'],
                    'quantity'    => $item['quantity'],
                    'fleet'       => $item['fleet']->nameTH,
                    'vehicle'     => $item['vehicle']->license,
                    'date'        => $item->created_at->toDateString(),
                    'time'        => $item->created_at->toTimeString(),
                    'image_array' => json_decode($item['image_array']),
            ];
        })[0];

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');
    }

    public function hide(Request $request)
    {
        $model = Model::find($request->id);

        $vehicle_id = $model->vehicle_id;

        $next_model = Model::where('vehicle_id', $vehicle_id)->where('id', '>' , $request->id)->where('deleted', 0)->oldest()->first();

        $previous_model = Model::where('vehicle_id', $vehicle_id)->where('id', '<' , $request->id)->where('deleted', 0)->latest()->first();

        $model->update(['distance' => null, 'rate' => null, 'is_checked' => 1, 'deleted' => 1]);

        if (is_null($next_model))
        {
            Vehicle::find($vehicle_id)->update(['mileage' => $previous_model->odometer]);
        }else{
            $this->getRate($next_model->id);
        }
    }

    public function check(Request $request)
    {
        $model = Model::find($request->id);

        $vehicle_id = $model->vehicle_id;

        $next_model = Model::where('vehicle_id', $vehicle_id)->where('id', '>' , $request->id)->where('deleted', 0)->oldest()->first();

        $model->update(['odometer' => $request->odometer, 'quantity' => $request->quantity, 'is_checked' => 1]);

        if (is_null($next_model))
        {
            Vehicle::find($vehicle_id)->update(['mileage' => $request->odometer]);
            $this->getRate($request->id);
        }else{
            $this->getRate($request->id);
            $this->getRate($next_model->id);
        }
    }

}
