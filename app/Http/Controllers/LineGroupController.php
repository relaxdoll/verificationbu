<?php

namespace App\Http\Controllers;

use App\Jobs\GetGroupName;
use App\LineGroup;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class LineGroupController extends Controller
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
        return view('lines.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('lines.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\LineGroup $lineGroup
     * @return \Illuminate\Http\Response
     */
    public function show(LineGroup $lineGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\LineGroup $lineGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(LineGroup $lineGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\LineGroup $lineGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LineGroup $lineGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\LineGroup $lineGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineGroup $lineGroup)
    {
        //
    }
}
