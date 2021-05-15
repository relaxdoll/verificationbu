<?php

namespace App\Http\Controllers;

use App\Onelink;
use App\Vehicle;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OnelinkController extends Controller
{
    public function getData()
    {
        $client   = new Client();
        $url      = 'http://api.onelink.co.th/v4/GPS/ItemsReal';
        $response = $client->request(
            'POST', /*instead of POST, you can use GET, PUT, DELETE, etc*/
            $url,
            [
                'auth'        => ['eec!@api', 'Ec0!5!ec$xyz'] /*if you don't need to use a password, just leave it null*/,
                'form_params' => ['user_id' => 'eecl_api']
            ]
        );

        return json_decode($response->getBody()->getContents())->results->items;
    }

    public function store()
    {
        $data = $this->getData();

        foreach ($data as $datum)
        {
            $datum = (array) $datum;

            $vehicle = Vehicle::where('license', $datum['licenseplate'])->first();

            if (!is_null($vehicle))
            {
                $datum['vehicle_id'] = $vehicle->id;
            } else
            {
                $datum['vehicle_id'] = null;
            }

            Onelink::updateOrCreate(['licenseplate' => $datum['licenseplate']], $datum);
        }
    }
}
