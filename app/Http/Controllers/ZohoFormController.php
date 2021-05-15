<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZohoFormController extends Controller
{
    public function advance()
    {
        return view('zoho_forms.advance');
    }

    public function invoice()
    {
        return view('zoho_forms.invoice');
    }
}
