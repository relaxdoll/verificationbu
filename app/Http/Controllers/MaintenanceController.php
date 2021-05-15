<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{

    public function __construct()
    {
        $this->getUserDetail();

    }

    public function tire()
    {
        return view('maintenances.tire');
    }

    public function replaceTire()
    {
        return view('maintenances.replace_tire');
    }

    public function expiredTireReport()
    {
        return view('maintenances.expired_tire_report');
    }
}
