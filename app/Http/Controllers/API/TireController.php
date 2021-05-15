<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Tire as Model;


class TireController extends BaseController
{

    protected $name = 'Tire';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new Model();

        $response = $model->addQuery($request->query())->index()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function indexGroup()
    {
        $model = new Model();

        $response = $model->index()->groupBy('fleet')->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function indexAvailable()
    {
        $model = new Model();

        $response = $model->addQuery(['where' => ['is_available' => 1]])->index()->groupBy('fleet')->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function indexAvailableOption()
    {
        $model = new Model();

        $response = $model->addQuery(['where' => ['is_available' => 1]])->index()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function indexByReason()
    {
        $response = Model::where('is_sold', 0)->where(function ($query) {
            $query->where(function ($query) {
                $query->where('is_available', 0)
                    ->where('reason_id', '!=', null)
                    ->where('reason_id', '!=', 6);
            })->orWhere('is_available', 1);
        })->get()->mapToGroups(function ($item) {
            $reason = $item['reason'];

            if (!is_null($reason))
            {
                $reason    = $reason->reason;
                $reason_id = $item['reason_id'];
                $fleet     = $item->last_placement->vehicle->fleet->name;
            } else
            {
                $reason    = 'Available';
                $reason_id = 0;
                $fleet     = $item->fleet->name;
            }

            return [$reason_id => ['fleet'             => $fleet,
                                   'fleet_id'          => $item->fleet_id,
                                   'id'                => $item->id,
                                   'serial'            => $item->serial,
                                   'purchase'          => $item->purchase,
                                   'tire_type'         => $item->tire_type_id,
                                   'brand'             => $item->brand->name,
                                   'tread_depth'       => $item->tread_depth,
                                   'is_available'      => $item->is_available,
                                   'reason'            => $reason,
                                   'reason_id'         => $reason_id,
                                   'tire_placement'    => $item->tire_placement,
                                   'current_placement' => $item->current_placement,
                                   'created_at'        => $item->created_at->toDateString()]];
        });

        foreach ($response as $reason => $tires)
        {
            $result[$reason] = $tires->groupBy('fleet');
        }

        return $this->sendResponse($result, $this->name . ' retrieved successfully.');
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

        $validate_rule = [];

        foreach ($request->tires as $key => $tire)
        {
            $validate_rule['tires.' . $key] = 'unique:tires,serial';
        }

        $validator = \Validator::make($input, $validate_rule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $common = [
            'initial_tread_depth' => $input['initial_tread_depth'],
            'brand_id'            => $input['brand_id'],
            'fleet_id'            => $input['fleet_id'],
            'price'               => $input['price'],
            'purchase_id'         => $input['purchase_id'],
            'is_available'        => $input['is_available'],
            'tire_type_id'        => $input['tire_type_id'],
        ];

        $response = [];


        foreach ($input['tires'] as $index => $tire)
        {
            $data = array_merge($common, ['serial' => $tire]);

            $response[$index] = Model::create($data);
        }


        return $this->sendResponse($response, $this->name . ' saved successfully.');
    }

    public function liffStore(Request $request)
    {
        $input = $request->all();

        $validator = \Validator::make($input, [
            'serial' => 'unique:tires,serial'
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

    public function edit($id)
    {

        $model = new \App\Tire();

        $response = $model->edit($id);

        if (is_null($model))
        {
            return $this->sendError($this->name . ' not found.');
        }


        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }


    public function update(Request $request, $id)
    {

        $input = $request->all();

//        $validator = \Validator::make($request->all(), $this->validateRule);
//
//
//        if ($validator->fails())
//        {
//            return $this->sendError('Validation Error.', $validator->errors());
//        }

        $model = Model::find($id);

        $response = $model->update($input);

        return $this->sendResponse($response, $this->name . ' updated successfully.');
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


    public function usedReport(Request $request)
    {
        $model = new Model();

        $response = $model->addQuery($request->query())->index()->toArray();

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }

    public function findWhereTireRegister($serial)
    {
        $response = Model::where('serial', $serial)->get()->map(function ($item) {
            return [
                'id'          => $item->id,
                'purchase'    => is_null($item->purchase) ? null : $item->purchase->purchase_order_number,
                'serial'      => $item->serial,
                'type'        => $item->tire_type->name,
                'size'        => $item->tire_type->size,
                'brand'       => $item->brand->name,
                'price'       => $item->price,
                'tread_depth' => is_null($item->last_placement) ? null : $item->last_placement->end_tread_depth,
                'license'     => is_null($item->last_placement) ? null : $item->last_placement->vehicle->license,
                'placement'   => is_null($item->last_placement) ? null : $item->last_placement->placement,
                'insert_date' => is_null($item->last_placement) ? null : $item->last_placement->start_date,
            ];
        })->groupBy('license');

        return $this->sendResponse($response, $this->name . ' retrieved successfully.');
    }
}
