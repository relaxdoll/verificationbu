<?php

namespace App\Http\Controllers;

use App\Tire;
use Illuminate\Http\Request;

class TireController extends Controller
{
    /**
     * DriverController constructor.
     */

    public function __construct()
    {
        $this->getUserDetail();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Tire  $tire
     * @return \Illuminate\Http\Response
     */
    public function show(Tire $tire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tire  $tire
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        \JavaScript::put([
            'id' => $id,
        ]);
        return \View::make('maintenances.edit_tire');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tire  $tire
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tire $tire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tire  $tire
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tire $tire)
    {
        //
    }
}
