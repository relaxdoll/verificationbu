<?php


namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Inventory as Model;


class InventoryController extends BaseController
{
    protected $name = 'Inventory';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
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

    public function cost()
    {
        $date = new Carbon();

        $startDate = new Carbon($date);
        $endDate   = new Carbon($date);

        $startDate->startOfMonth();
        $endDate->endOfMonth();

        $data = Inventory::get()
            ->filter(function ($value) use ($startDate, $endDate) {
                return ($value->date >= $startDate) && ($value->date <= $endDate);
            });

        return $this->sendResponse([$startDate, $endDate], $this->name . ' retrieved successfully.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input      = $request->all();
        $has_serial = $input['has_serial'];

        if ($has_serial)
        {
            $response = $this->saveSerialInventory($input);

        } else
        {
            $response = $this->saveNoSerialInventory($input);
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
        $data = Inventory::find($id);


        if (is_null($data))
        {
            return $this->sendError('Product not found.');
        }


        return $this->sendResponse($data->toArray(), $this->name . ' retrieved successfully.');
    }

    public function edit($id)
    {

        $model = new \App\Inventory();

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
     * @param Inventory $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        if ($input['current_quantity'] == 0)
        {
            $input['is_available'] = false;
        }

//		$validator = Validator::make($input, [
//			'name' => 'required',
//			'detail' => 'required'
//		]);
//
//
//		if($validator->fails()){
//			return $this->sendError('Validation Error.', $validator->errors());
//		}

//		$response = $model->update($input)
        $response = Model::find($id)->update($input);


        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Inventory $model
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Inventory $model)
    {
        $model->delete();


        return $this->sendResponse($model->toArray(), $this->name . ' deleted successfully.');
    }

    public function saveSerialInventory($input)
    {
        $validate_rule = [];

        foreach ($input['inventories'] as $key => $inventory)
        {
            $validate_rule['inventories.' . $key] = 'unique:inventories,serial';
        }

        $validator = \Validator::make($input, $validate_rule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $common = [
            'price'             => $input['price'],
            'quantity'          => $input['quantity'],
            'current_quantity'  => $input['current_quantity'],
            'inventory_type_id' => $input['inventory_type_id'],
            'brand_id'          => $input['brand_id'],
            'purchase_id'       => $input['purchase_id'],
            'fleet_id'          => $input['fleet_id'],
        ];

        $response = [];


        foreach ($input['inventories'] as $index => $inventory)
        {
            $data = array_merge($common, ['serial' => $inventory]);

            $response[$index] = Model::create($data);
        }

        return $response;
    }

    public function saveNoSerialInventory($input)
    {
        $validate_rule = [
            'price'             => 'required',
            'quantity'          => 'required',
            'current_quantity'  => 'required',
            'inventory_type_id' => 'required',
            'brand_id'          => 'required',
            'purchase_id'       => 'required',
            'fleet_id'          => 'required',
        ];

        $validator = \Validator::make($input, $validate_rule);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return Model::create($input);
    }


    public function getInventoryByTypeId($id)
    {
        $response = Model::where('inventory_type_id', $id)->get()->toArray();

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');

    }
}
