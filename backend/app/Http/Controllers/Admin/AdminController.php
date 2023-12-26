<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash, Session, Validator, DB;
use App\Models\Admin\Admin;
class AdminController extends Controller
{

    public function index()
    {
        return view('admin/dashboard');
    }
    public function change_password()
    {
        return view('admin/change_password');
    }
    public function update_password(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
            'c_password' => 'required|same:new_password'
        ],
        [
            'c_password.required'=> 'The confirm password field is required.',
            'c_password.same'=> 'The confirm password must be same with new password.'
        ]);
        if ($validator->fails()) {
            $finalResult = response()->json(array('msg' => 'lvl_error', 'response'=>$validator->errors()->all()));
            return $finalResult;
        }
        if (Hash::check($data['old_password'], Auth::guard('admin')->user()->password)) {
            try {
                $data_array = Admin::findOrFail(Auth::guard('admin')->user()->id);
                $data_array->password = Hash::make($data['new_password']);
                $data_array->save();
                $finalResult = response()->json(array('msg' => 'success', 'response'=>'Password successfully updated.'));
                return $finalResult;
            } catch(\Illuminate\Database\QueryException $ex){
                $finalResult = response()->json(array('msg' => 'error', 'response'=> $ex->getMessage()));
                return $finalResult;
            }
        } else {
            $finalResult = response()->json(array('msg' => 'error', 'response'=>'Your password is wrong.'));
            return $finalResult;
        }
    }
}