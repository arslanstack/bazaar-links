<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function update_profile(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required',
            'last_name' => 'required',
            'country' => 'required',
            'seaport' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_no' => 'required',
            'Currency' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $file_name = explode('.', $image->getClientOriginalName())[0];
            $data['image'] = $file_name . '_' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/upload_images');
            $image->move($destinationPath, $data['image']);
            DB::table('users')->where('id', auth()->user()->id)->update([
                'image_name' => $data['image'],
            ]);
        }

        if (isset($data['newsletter'])) {
            $data['newsletter'] = 1;
        } else {
            $data['newsletter'] = 0;
        }
        if (isset($data['stock_update'])) {
            $data['stock_update'] = 1;
        } else {
            $data['stock_update'] = 0;
        }
        $status = User::where('id', auth()->user()->id)->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'company_name' => $data['company_name'],
            'currency' => $data['Currency'],
            'country_id' => $data['country'],
            'seaport_id' => $data['seaport'],
            'address' => $data['address'],
            'city' => $data['city'],
            'phone_no' => $data['phone_no'],
            'newsletter' => $data['newsletter'],
            'stock_update' => $data['stock_update'],
        ]);

        if ($status > 0) {
            return response()->json(array('msg' => 'success', 'response' => 'User successfully updated.'));
        } else {
            return response()->json(array('msg' => 'error', 'response' => 'Something went wrong!'));
        }
    }
}
