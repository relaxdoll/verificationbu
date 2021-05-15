<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->getUserDetail();

    }

    public function vehicle()
    {
        return view('settings.vehicle');
    }
}
