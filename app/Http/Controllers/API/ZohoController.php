<?php


namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Zoho as Model;
use GuzzleHttp\Client;


class ZohoController extends BaseController
{

	protected $name = 'Zoho';

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
	 * @param  \Illuminate\Http\Request $request
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
	 * @param  int $id
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
	 * @param  \Illuminate\Http\Request $request
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

    public function getcode()
    {
        $url = 'https://accounts.zoho.com/oauth/v2/auth?';

        $params = [
            'scope'         => 'ZohoBooks.fullaccess.all',
            'client_id'     => env('ZOHO_CLIENT_ID'),
            'state'         => 'eecl',
            'response_type' => 'code',
            'redirect_uri'  => env('ZOHO_BOOKS_REDIRECT'),
            'access_type'   => 'offline',
            'prompt' => 'Consent'
        ];

        $query = http_build_query($params, '', '&');

        $url .= $query;

        dd($url);

        return \Redirect::to($url);
    }

    public function oauth2callback(Request $request)
    {

        $params = $request->query->all();

        $url = 'https://accounts.zoho.com';

        $query = [
            'code'          => $params['code'],
            'grant_type'    => 'authorization_code',
            'client_id'     => env('ZOHO_CLIENT_ID'),
            'state'         => 'eecl',
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri'  => env('ZOHO_BOOKS_REDIRECT'),
            'scope'         => 'ZohoBooks.fullaccess.all'
        ];

        $client = new Client([
            'base_uri' => $url,
            'query'    => $query,
            'cookies'  => true,
        ]);

        $response = $client->post('/oauth/v2/token');

        $result = $response->getBody()->getContents();

        $body = $response->getBody();
        $stringBody = (string) $body;
        $result = json_decode($stringBody);

        $tokens = [
            'access_token' => $result->access_token,
            'refresh_token' => $result->refresh_token
        ];

        $response = \Cache::put('refreshToken', $result->refresh_token);

        return $tokens;
    }

    public function getToken()
    {
        $url = 'https://accounts.zoho.com';

        $query = [
            'refresh_token'     => env('ZOHO_REFRESH_TOKEN'),
            'client_id'     => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'redirect_uri'  => env('ZOHO_BOOKS_REDIRECT'),
            'grant_type'  => 'refresh_token'
        ];

        $client = new Client([
            'base_uri' => $url,
            'query'    => $query,
            'cookies'  => true,
        ]);

        $response = $client->post('/oauth/v2/token');

        $access_token = json_decode($response->getBody()->getContents())->access_token;

        $cache = \Cache::put('access_token', $access_token);
    }
}
