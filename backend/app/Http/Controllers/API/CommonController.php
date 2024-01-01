<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Unit;

class CommonController extends Controller
{
    public function get_cities(){
        $cities = City::all();
        return response()->json(['msg' => 'success', 'response' => $cities]);
    }
    public function get_units(){
        $units = Unit::all();
        return response()->json(['msg' => 'success', 'response' => $units]);
    }
}
