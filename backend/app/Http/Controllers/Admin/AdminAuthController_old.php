<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    protected $guard = 'admin';

    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['showLoginForm', 'login', 'showRegistrationForm', 'register']]);
    }

    public function showLoginForm()
    {
        if (Auth::guard($this->guard)->check()) {
            return redirect('/admin/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard($this->guard)->attempt($credentials)) {
            // Authentication passed
            return redirect()->intended('/admin/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function showRegistrationForm()
    {
        if (Auth::guard($this->guard)->check()) {
            return redirect('/admin/dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::guard($this->guard)->login($admin);

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        Auth::guard($this->guard)->logout();

        return redirect('/admin/login');
    }

    public function dashboard()
    {
        // Your logic to display the admin dashboard
        return view('home');
    }
}
