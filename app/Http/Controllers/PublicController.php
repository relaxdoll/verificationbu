<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        \JavaScript::put([
            'user'    => 'test',
            'miniNav' => \Cache::get('miniNav1'),
        ]);

        return view('image_tracks.nhp');
    }
}
