<?php


namespace App\Http\Controllers\API;

use App\RefuelJob;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\RefuelJob as Model;
use PhpParser\Node\Expr\AssignOp\Mod;


class RefuelJobController extends BaseController
{

    protected $name = 'RefuelJob';

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
            'value' => 'required',
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

        $controller = new \App\Http\Controllers\LineController();

        switch ($model->model)
        {
            case 'refuel':
                $flex = $controller->buildRefuel($model->refuel_id);
                break;

            case 'pressure':
                $flex = $controller->buildNotUpdateTireReport($model->refuel_id);
                break;

            case 'pressure_weekly_report':
                $flex = $controller->buildTimesDriverDoTirePressureJob($model->refuel_id);
                break;

            case 'maintenance_approval_driver':
                $flex = $controller->buildMaintenanceApprovalReceipt($model->refuel_id, 'driver');
                break;

            case 'maintenance_approval_approver':
                $flex = $controller->buildMaintenanceApprovalReceipt($model->refuel_id, 'approver');
                break;

        }

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

    public function sent($id)
    {
        $response = RefuelJob::find($id)->update(['sent' => 1]);

        return $this->sendResponse($response, $this->name . ' updated successfully.');
    }

}
