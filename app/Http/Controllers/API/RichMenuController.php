<?php


namespace App\Http\Controllers\API;

use App\Classes\Drive;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\RichMenu as Model;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;


class RichMenuController extends BaseController
{

    protected $name = 'RichMenu';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test()
    {
    }

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

        $bounds  = $input['areas'];
        $actions = $input['action'];

        $areas  = [];
        $groups = [];
        $count  = 0;

        foreach ($actions as $key => $action)
        {
            $groups[$key]['action'] = $action;
            $groups[$key]['bound']  = $bounds[$count]['bounds'];
            $count ++;
        }

        foreach ($groups as $group)
        {
            $ibound = new RichMenuAreaBoundsBuilder(
                $group['bound']['x'],
                $group['bound']['y'],
                $group['bound']['width'],
                $group['bound']['height']
            );

            switch ($group['action']['type'])
            {
                case 'postback':
                    $iaction = new PostbackTemplateActionBuilder('menu', $group['action']['data']);
                    break;
                case 'uri':
                    $iaction = new UriTemplateActionBuilder('menu', $group['action']['uri']);
                    break;
            };

            array_push($areas, new RichMenuAreaBuilder($ibound, $iaction));
        }

        $richMenu = new RichMenuBuilder(
            new RichMenuBuilder\RichMenuSizeBuilder($input['size']['height'], $input['size']['width']),
            $input['selected'],
            $input['name'],
            $input['chatBarText'],
            $areas
        );

        $controller = new \App\Http\Controllers\LineController();
        $response   = $controller->bot->createRichMenu($richMenu);

        $menuId = $response->getJSONDecodedBody()['richMenuId'];


        $store = [
            'name'        => $input['name'],
            'size'        => $input['rawSize'],
            'selected'    => $input['selected'],
            'chatBarText' => $input['chatBarText'],
            'richMenuId'  => $menuId,
            'areas'       => json_encode($groups),
        ];

        $default = Model::where('selected', 1)->first();

        if (!is_null($default))
        {
            $default->update(['selected' => false]);
        }

        $store_response = Model::create($store);

        return $this->sendResponse($store_response, $this->name . ' saved successfully.');
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
    public function update(Request $request, $id)
    {
        $model = Model::find($id);

//        $controller = new \App\Http\Controllers\LineController();
//        $response = $controller->bot->uploadRichMenuImage($model->richMenuId, $request->image, 'image/png');

//        dd($response->getRawBody());
        dd($request);


        $model->link = $link;
        $model->save();


        return $this->sendResponse(true, $this->name . ' updated successfully.');
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

    public function uploadRichmenu(Request $request)
    {

        $model = Model::find($request->id);

        $file = $request->file('image');

        $controller = new \App\Http\Controllers\LineController();
        $response   = $controller->bot->uploadRichMenuImage($model->richMenuId, $file, 'image/png');

        $image_name = time() . $this->generateRandomString(2) . '.png';

        $drive = new Drive();
        $link  = $drive->uploadImage($file, $image_name, 23)['link'];

        $model->link = $link;
        $model->save();

        return $this->sendResponse($link, $this->name . ' updated successfully.');
    }

    public function link(Request $request)
    {
        $menu = Model::find($request->id);

        $menu_id = $menu['richMenuId'];

        $line_controller = new \App\Http\Controllers\LineController();

        $response = $line_controller->bot->bulkLinkRichMenu($request->driver_id, $menu_id);

        dd($response);

        return $this->sendResponse($response->getJSONDecodedBody(), $this->name . ' updated successfully.');
    }
}
