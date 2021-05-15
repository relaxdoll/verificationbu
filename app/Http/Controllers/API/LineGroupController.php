<?php


namespace App\Http\Controllers\API;

use App\Jobs\GetGroupName;
use App\Jobs\InitGroupScrap;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\LineGroup as Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;


class LineGroupController extends BaseController
{

    protected $name = 'LineGroup';

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
            'lineId' => 'required',
            'name' => 'required',
            'fleet_id' => 'required',
            'avatar' => 'required',
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

    public function initScrapGroup(Request $request)
    {

        $input = $request->all();

        $validator = \Validator::make($input, [
            'search' => 'required',
        ]);


        if ($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = [
            'updated'    => false,
            'status'     => 'off',
            'hasResult'  => false,
            'search'     => $request->search,
            'groups'     => [],
            'avatars'    => [],
            'reEntry'    => false,
            'forceClose' => false,
            'code'       => null
        ];

        $jsonString = json_encode($data);

        $file = Storage::disk('puppet')->put('line_groups/' . $input['user']['id'] . '.json', $jsonString);

        InitGroupScrap::dispatch($input['user']['id']);

        return $this->sendResponse(true, $this->name . 's retrieved successfully.');
    }

    public function getGroupName($id)
    {
        function getOption($groups, $avatars)
        {
            $options = [];
            foreach ($groups as $key => $group)
            {
                $options[$key]['value']  = $key;
                $options[$key]['text']   = $group;
                $options[$key]['avatar'] = $avatars[$key];

            }

            return $options;
        }

        $file = Storage::disk('puppet')->get('line_groups/' . $id . '.json');
        $json = (array) json_decode($file);

        $response = [];

        $response['updated']   = $json['updated'];
        $response['hasResult'] = $json['hasResult'];
        $response['code']      = $json['code'];
        $response['options']   = getOption($json['groups'], $json['avatars']);

        return $this->sendResponse($response, $this->name . 's retrieved successfully.');

    }

    public function editGroupName(Request $request)
    {
        $input = $request->all();

        $json = json_decode(file_get_contents('puppet/line_groups/' . $input['user']['id'] . ".json"));

        $json->updated   = false;
        $json->hasResult = false;
        $json->search    = $request->search;
        $json->reEntry   = true;

        $jsonString = json_encode($json);

        $file = Storage::disk('puppet')->put('line_groups/' . $input['user']['id'] . '.json', $jsonString);

        $response = $this->getGroupName($input['user']['id']);

        return $this->sendResponse($response, 'Edit search successfully.');
    }

    public function shareMessage(Request $request)
    {
        $input = $request->all();

        $json = json_decode(file_get_contents('puppet/line_groups/' . $input['user']['id'] . ".json"));

        $json->search = $request->name;
        $json->share  = true;

        $jsonString = json_encode($json);

        $file = Storage::disk('puppet')->put('line_groups/' . $input['user']['id'] . '.json', $jsonString);

        return $this->sendResponse(true, 'Message shared successfully.');
    }

    public function closeScrapper($id)
    {
        $json = json_decode(file_get_contents('puppet/line_groups/' . $id . ".json"));

        $json->forceClose = true;

        $jsonString = json_encode($json);

        $file = Storage::disk('puppet')->put('line_groups/' . $input['user']['id'] . '.json', $jsonString);

        return $this->sendResponse(true, 'Scrapper close successfully.');
    }

    public function getGroupId($code)
    {

        $groupId = \Cache::get($code);

        return $this->sendResponse($groupId, $this->name . 's retrieved successfully.');

    }

}
