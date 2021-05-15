<?php

namespace App\Http\Controllers;

use App\ContainerDetail;
use Illuminate\Http\Request;
use Javascript;

class ContainerDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('container_detail.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('container_detail.index');
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
     * @param  \App\ContainerDetail  $containerDetail
     * @return \Illuminate\Http\Response
     */
    public function show(ContainerDetail $containerDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ContainerDetail  $containerDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        JavaScript::put([
            'id' => $id,
        ]);
        return view('container_detail.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ContainerDetail  $containerDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContainerDetail $containerDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContainerDetail  $containerDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContainerDetail $containerDetail)
    {
        //
    }
}
