<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Admin\Admin;
use Session;

class AdminLoginController extends Controller
{
    protected $guard = 'admin';
    protected $redirectTo = '/admin';
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function index()
    {
        return view('admin/login');
    }

    public function verify_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)){
            $isactive = Auth::guard('admin')->user()->status;
            if($isactive == 1) {
                return redirect('admin');
            } else {
                Session::flush();
                auth()->guard('admin')->logout();
                return redirect("admin/login")->withErrors('You are temporarily blocked. Please contact to admin!.');
            }
        } else {
            return redirect("admin/login")->withErrors('Invalid email or password!.');
        }
    }

    public function logout() {
        Session::flush();
        auth()->guard('admin')->logout();
        return redirect('admin/login');
    }
}
