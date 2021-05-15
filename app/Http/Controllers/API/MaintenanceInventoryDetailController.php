<?php


namespace App\Http\Controllers\API;

use App\Inventory;
use App\MaintenanceDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\MaintenanceInventoryDetail as Model;


class MaintenanceInventoryDetailController extends BaseController
{

    protected $name = 'MaintenanceInventoryDetail';

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
        $response = null;

        $input = ['maintenance_detail_id' => $request->maintenance_detail_id];


        for ($x = 1; $x <= $request->numberList; $x ++)
        {
            $maxQuantityName = 'max_quantity' . $x;
            $quantityName    = 'quantity' . $x;
            $inventoryName   = 'inventory_id' . $x;

            $validator = \Validator::make($request->all(), [
                $quantityName => ['required', 'lte:' . $maxQuantityName],
            ]);

            if ($validator->fails())
            {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $result1       = array_merge($input, ['quantity_used' => $request->$quantityName]);
            $result2       = array_merge($result1, ['inventory_id' => $request->$inventoryName]);
            $response      = Model::create($result2);

            $this->calculateInventoryBalance($request->$inventoryName);
        }

        $this->setMaintenanceDetailToBeUpdated($input['maintenance_detail_id']);

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

    public function calculateInventoryBalance($inventory_id)
    {
        $inventory = Inventory::find($inventory_id);
        $quantity  = $inventory->pluck('quantity')->first();

        $quantityUsed                = Model::where('inventory_id', $inventory_id)->get()->sum('quantity_used');
        $inventory->current_quantity = $quantity - $quantityUsed;
        $inventory->save();

        return $inventory;
    }

    public function setMaintenanceDetailToBeUpdated($maintenance_detail_id)
    {
        $maintenance_detail            = MaintenanceDetail::find($maintenance_detail_id);
        $maintenance_detail->is_update = 1;
        $maintenance_detail->save();

        return $maintenance_detail;
    }
}
