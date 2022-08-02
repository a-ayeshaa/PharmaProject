<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APICustomerController extends Controller
{
    //
    function home()
    {
        return response()->json(200);
    }
}