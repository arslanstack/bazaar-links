<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CommonController extends Controller
{
    public function get_cities(){
        $cities = City::all();
        return response()->json(['msg' => 'success', 'response' => $cities]);
    }
}
