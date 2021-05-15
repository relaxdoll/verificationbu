<?php

namespace App\Http\Controllers;

use App\Approver;
use Illuminate\Http\Request;
use JavaScript;

class ApproverController extends Controller
{
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
        return view('approvers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('approvers.create');
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
     * @param  \App\Approver  $approver
     * @return \Illuminate\Http\Response
     */
    public function show(Approver $approver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Approver  $approver
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        JavaScript::put([
            'id' => $id,
        ]);
        return \View::make('approvers.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Approver  $approver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Approver $approver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Approver  $approver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Approver $approver)
    {
        //
    }
}
