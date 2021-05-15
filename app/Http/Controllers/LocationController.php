<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Location;
use App\Onelink;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('locations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function send()
    {

        $model = new location();

        $customers = $model->index()->toarray();

        $line = new LineController();

        foreach ($customers as $customer)
        {
            $locations = [];

            $to = $customer[0]['customer']->toarray();

            $to['fleet'] = $customer[0]['fleet'];

            foreach ($customer as $vehicle)
            {
                $location = onelink::where('vehicle_id', $vehicle['vehicle_id'])->first();

                if (!is_null($location))
                {
                    array_push($locations, $location);
                }
            }

            $response = $line->buildlocationflex($locations, $to);

        }
    }

    public function sendOne(Request $request)
    {

        $model = new location();

        $customers = $model->addQuery($request->query())->index()->toarray();

        $line = new LineController();

        foreach ($customers as $customer)
        {
            $locations = [];

            $to = $customer[0]['customer']->toarray();

            $to['fleet'] = $customer[0]['fleet'];

            foreach ($customer as $vehicle)
            {
                $location = onelink::where('vehicle_id', $vehicle['vehicle_id'])->first();

                if (!is_null($location))
                {
                    array_push($locations, $location);
                }
            }

            $response = $line->buildlocationflex($locations, $to);

        }
    }


}
