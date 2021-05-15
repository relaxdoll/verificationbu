<?php


namespace App\Http\Controllers\API;

use App\Driver;
use App\Line;
use App\LineUser;
use App\ImageTrackReport;
use App\TirePressureAndTread;
use App\Tool\Graph;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Driver as Model;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use function GuzzleHttp\headers_from_lines;
use function GuzzleHttp\Psr7\uri_for;


class DriverController extends BaseController
{

    protected $name = 'Driver';

    protected $validateRule = [
        'firstName' => 'required',
        'lastName'  => 'required',
        'lineId'    => 'required',
        'fleet_id'  => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $data = $this->separateLastName();

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

    public function separateLastName()
    {

        $all = Model::all();

        $response = [];

        foreach ($all as $driver)
        {
            $id = $driver['id'];

            $model = Model::find($id);

            $name = $model->firstName;

            $hasSpace = strpos($name, ' ');

            if ($hasSpace > 0)
            {
                $model->firstName = substr($name, 0, $hasSpace);
                $model->lastName  = substr($name, $hasSpace + 1, 100);
            } else
            {
                $model->firstName = $name;

                if (is_null($model->lastName))
                {
                    $model->lastName = null;
                } else
                {
                    $model->lastName = str_replace(' ', '', $model->lastName);
                }
            }

            $response[$id] = $model->save();
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

        $validator = \Validator::make($input, [
            'firstName' => Rule::unique('drivers')->where(function ($query) use ($request) {
                return $query->where('lastName', $request->lastName);
            }),
            'lastName'  => 'required',
            'lineId'    => 'required',
            'fleet_id'  => 'required',
        ],
            [
                'firstName.unique' => $request->firstName . ' ' . $request->lastName . ' already existed.',
            ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $line = LineUser::find($request->lineId);

        $input['lineId'] = $line->lineId;
        $input['avatar'] = $line->avatar;

        $response = Model::create($input);

        $controller = new \App\Http\Controllers\LineController();

        $controller->linkDriverMenu($line->lineId);

        $line->driver_id = $response->id;

        $line->save();

        $head = $response->vehicles()->sync($request->vehicle_id);
        $tail = $response->tails()->sync($request->tail_id);


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

        $model = new \App\Driver();

        $response = $model->show($id);

        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function edit($id)
    {

        $model = new \App\Driver();

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

        $input = $request->except('lineId');

        $validator = \Validator::make($request->all(), $this->validateRule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $model = Model::find($id);

        $response = $model->update($input);
        $head     = $model->vehicles()->sync($request->vehicle_id);
        $tail     = $model->tails()->sync($request->tail_id);


        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }

    public function updateAvatar(Request $request, $id)
    {

        $input = $request->all();

        $model = Model::find($id);

        $response = $model->update($input);

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Model $model
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Model::find($id)->delete();

        return $this->sendResponse($response, $this->name . ' deleted successfully.');
    }

    public function refreshProfileAvatar()
    {
        $drivers = Driver::all()->pluck('lineId', 'id');

        $line = new \App\Http\Controllers\LineController();

        foreach ($drivers as $id => $driverLineId)
        {
            $data = json_decode($line->bot->getProfile($drivers[$id])->getRawBody());

            if (property_exists($data, 'pictureUrl'))
            {
                $response[$id] = Driver::where('id', $id)->update(['avatar' => $data->pictureUrl]);
            } else
            {
                $response[$id] = Driver::where('id', $id)->update(['avatar' => null]);
            }
        }

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }

    public function getDriverIdByLineId($lineId)
    {
        $driver = Model::where('lineId', $lineId)->first();

        return $this->sendResponse($driver, $this->name . 's retrieved successfully.');
    }

    public function getAssignedHead($id)
    {
        $driver   = Model::find($id);
        $vehicles = $driver->vehicles;

        if (count($vehicles) === 0)
        {
            $vehicles = Vehicle::getHeadOrStandAlone($driver->fleet_id);
        }

        return $this->sendResponse($vehicles, 'Vehicle retrieved successfully.');
    }

    public function getAssignedTail($id)
    {
        $driver   = Model::find($id);
        $vehicles = $driver->tails;

        if (count($vehicles) === 0)
        {
            $vehicles = [];
        }

        return $this->sendResponse($vehicles, 'Vehicle retrieved successfully.');
    }

    public function getAssignedVehicle($id)
    {
        $driver = Model::find($id);
        $heads  = $driver->vehicles->map(Vehicle::mapActiveTire())->toArray();
        $tails  = $driver->tails->map(Vehicle::mapActiveTire())->toArray();

        $vehicles = array_merge($heads, $tails);

        return $this->sendResponse($vehicles, 'Vehicle retrieved successfully.');
    }

    public function getLiffType($id)
    {
        $driver = Model::find($id);
        $heads  = $driver->vehicles->toArray();
        $tails  = $driver->tails->toArray();

        $vehicles = array_merge($heads, $tails);

        foreach ($vehicles as $vehicle)
        {
            if (!Vehicle::hasAllTire($vehicle['id']))
            {
                return $this->sendResponse(false, 'Not complete');
            }
        }

        return $this->sendResponse(true, 'All complete');
    }

    public function getPersonNotFillAllData()
    {
        $drivers = Model::whereHas('vehicles', function ($query) {
            $query->doesntHave('activeTire');
        })->get()->mapWithKeys(function ($item) {

            return [$item->firstName . ' ' . $item->lastName => array_merge(Vehicle::whereHas('drivers', function ($query) use ($item) {
                $query->where('lineId', $item->lineId);
            })
                ->doesntHave('activeTire')
                ->get()
                ->pluck('license')
                ->toArray(),
                Vehicle::whereHas('tailDrivers', function ($query) use ($item) {
                    $query->where('lineId', $item->lineId);
                })
                    ->doesntHave('activeTire')
                    ->get()
                    ->pluck('license')
                    ->toArray())
            ];
        });

        dd($drivers);
    }

    public function getDriverNotUpdateTireThisWeek()
    {
        function mapNotUpdateVehicle(string $param, $item)
        {
            return Vehicle::whereHas($param, function ($query) use ($item) {
                $query->where('driver_id', $item['id']);
            })->get()->map(function ($item) {
                if (!Vehicle::find($item['id'])->allTireUpdateThisWeek())
                {
                    return $item['license'];
                } else
                {
                    return false;
                }
            })->reject(function ($value) {
                return $value === false;
            })->toArray();
        }

        return Driver::all()->map(function ($item) {
            return [
                'fleet'   => $item->fleet->nameTH,
                'name'    => $item['firstName'] . ' ' . $item['lastName'],
                'vehicle' => array_merge(mapNotUpdateVehicle('drivers', $item), mapNotUpdateVehicle('tailDrivers', $item))
            ];
        })->reject(function ($value) {
            return $value['vehicle'] === [];
        })->groupBy('fleet')->toArray();
    }

}
