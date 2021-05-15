<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\RoutePivot as Model;


class RoutePivotController extends BaseController
{

    protected $name = 'RoutePivot';

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
        $response = '';
        $input    = $request->all();

        for ($i = 1; $i <= max(array_keys($input)); $i ++)
        {
            $input['place_id'] = $input[$i];
            $input['order']    = $i;
            $response          = Model::create($input);

        }

//		$validator = \Validator::make($input, [
//			'value' => 'required',
//		]);
//
//
//		if ($validator->fails())
//		{
//			return $this->sendError('Validation Error.', $validator->errors());
//		}


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
    public function update(Request $request, $route_id)
    {
        $response = null;
        $input    = $request->all();
        for ($i = 1; $i <= max(array_keys($input)); $i ++)
        {
            $updatedData = null;
            $model       = Model::where('route_id', $route_id)->where('order', $i)->get();
            if (count($model) > 0)
            {
                $updatedData['place_id'] = $input[$i];
                $updatedData['order']    = $i;
                $response                = $model[0]->update($updatedData);
            } else
            {
                $updatedData['place_id'] = $input[$i];
                $updatedData['order']    = $i;
                $updatedData['route_id'] = $route_id;
                $response                = Model::create($updatedData);
            }
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

        return $this->sendResponse($response, $this->name . ' updated successfully.');
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

}
