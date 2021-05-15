<?php

namespace App\Http\Controllers;

use App\FastTrackBackground;
use Illuminate\Http\Request;

class FastTrackBackgroundController extends Controller
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
        return view('fasttracks.backgrounds.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fasttracks.backgrounds.create');
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
     * @param  \App\FastTrackBackground  $fastTrackBackground
     * @return \Illuminate\Http\Response
     */
    public function show(FastTrackBackground $fastTrackBackground)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FastTrackBackground  $fastTrackBackground
     * @return \Illuminate\Http\Response
     */
    public function edit(FastTrackBackground $fastTrackBackground)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FastTrackBackground  $fastTrackBackground
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FastTrackBackground $fastTrackBackground)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FastTrackBackground  $fastTrackBackground
     * @return \Illuminate\Http\Response
     */
    public function destroy(FastTrackBackground $fastTrackBackground)
    {
        //
    }
}
