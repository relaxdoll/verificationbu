<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use JavaScript;
use View;

class LiffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $tab = $request->tab;

        switch ($tab)
        {
            case 'fasttrack':
                return view('liff.image_tracks.create');

            case 'tire':
                return view('liff.tires.create');

            case 'maintenance':
                return view('liff.maintenances.create');

            case 'assignTire':
                return view('liff.assign_tires.create');

            case 'refuel':
                return view('liff.refuels.create');

            case 'tire_placement':
                return view('liff.tires.placement');

            case 'refuelJob':
                return view('liff.refuels.job');

            case 'imageTrackJob':
                return view('liff.image_tracks.job');

            case 'getCustomerGroup':

                JavaScript::put([
                    'code' => rand(1000, 9999),
                ]);

                return View::make('liff.get_customer_group');

            case 'forward':
                JavaScript::put([
                    'reportId' => $request->id,
                ]);

                return View::make('liff.refuels.forward');

            case 'maintenance-approval-driver':
                return view('liff.maintenances.approval.driver');

            case 'maintenance-approval-approver':
                return view('liff.maintenances.approval.approver');

            case 'maintenance-approval-technician-job':
                return view('liff.maintenances.approval.technician_job');

            case 'maintenance-approval-technician-create':
                return view('liff.maintenances.approval.technician_create');

            case 'maintenance-approval-technician-update':
                return view('liff.maintenances.approval.technician_update');

            case 'maintenance-vehicle-inspection-create':
                return view('liff.maintenances.vehicle_inspection.create');
//            default:
//                return view('liff.refuels.job');
        }
    }

    public function refuelJob()
    {
        return view('liff.refuels.job');
    }

    public function imageTrackJob()
    {
        return view('liff.image_tracks.job');
    }

    public function getCustomerGroup()
    {
        JavaScript::put([
            'code' => rand(1000, 9999),
        ]);

        return View::make('liff.get_customer_group');
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
