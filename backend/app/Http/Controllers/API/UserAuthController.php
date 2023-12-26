<?php

namespace App\Http\Controllers\API;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use App\Mail\ResetMail;
use Carbon\Carbon;

class UserAuthController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'sendResetOTP', 'verifyResetOTP', 'resetPassword']]);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        if (substr($data['phone_no'], 0, 1) == 0) {
            $data['phone_no'] = '+92' . substr($data['phone_no'], 1);
        } else if (substr($data['phone_no'], 0, 2) == '92') {
            $data['phone_no'] = '+' . $data['phone_no'];
        } else if (substr($data['phone_no'], 0, 1) == '+') {
            $data['phone_no'] = $data['phone_no'];
        } else {
            $data['phone_no'] = '+92' . $data['phone_no'];
        }

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6',
            'phone_no' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }
        $data['password_decrypt'] = $data['password'];
        $data['password'] = bcrypt($data['password']);
        $data['image_name'] = 'user.png';
        $data['status'] = 0;
        $data['otp'] = rand(1000, 9999);

        $user = User::create($data);

        if ($user) {
            $verification = Mail::to($user->email)->send(new VerificationMail($user->otp));
            if ($verification) {
                $credentials = [
                    'phone_no' => $data['phone_no'],
                    'password' => $data['password_decrypt'],
                ];
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(['msg' => 'error', 'response' => 'Could Not Authenticate After Account Creation!'], 401);
                }
                return response()->json([
                    'msg' => 'success',
                    'response' => 'OTP sent successfully',
                    'token' => $this->respondWithToken(JWTAuth::fromUser(auth()->user())),
                    'user' => auth()->user(),
                ]);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not verify email/phone.']);
            }
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could Not Create User.']);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $credentials = request(['phone_no', 'password']);

        if (substr($credentials['phone_no'], 0, 1) == 0) {
            $credentials['phone_no'] = '+92' . substr($credentials['phone_no'], 1);
        } else if (substr($credentials['phone_no'], 0, 2) == '92') {
            $credentials['phone_no'] = '+' . $credentials['phone_no'];
        } else if (substr($credentials['phone_no'], 0, 1) == '+') {
            $credentials['phone_no'] = $credentials['phone_no'];
        } else {
            $credentials['phone_no'] = '+92' . $credentials['phone_no'];
        }


        if ($token = auth()->attempt($credentials)) {
            return $this->respondWithToken(JWTAuth::fromUser(auth()->user()));
        }

        return response()->json(['msg' => 'error', 'response' => 'Invalid phone_no or password!'], 401);
    }

    public function user_profile()
    {
        $user = auth()->user();
        return response()->json(['msg' => 'success', 'response' => 'success', 'data' => $user]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['msg' => 'success', 'response' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addDays(5)->timestamp,
            // 'expires_in' => JWTAuth::factory()->getTTL() * 2880,
        ]);
    }
    public function activate(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'otp' => 'required|min:4|max:4',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }
        $user = User::where('otp', $request->otp)->first();
        if ($user) {
            $user->status = 1;
            $user->otp = null;
            $user->save();
            return response()->json(['msg' => 'success', 'response' => 'Account activated successfully.', 'user' => $user]);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Invalid OTP.'], 422);
        }
    }

    public function update_profile(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = auth()->user();
        $user->city = $data['city'];
        $user->address = $data['address'];
        $user->zip = $data['zip'];
        // if image has file then
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $data['image_name'] = $file_name . '_' . time() . '.' . $extension;

            $destinationPath = public_path('/assets/upload_images');
            $image->move($destinationPath, $data['image_name']);

            $data['image_path'] = 'https://explorelogicsit.net/mandilinks/public/assets/upload_images/' . $data['image_name'];
        }
        $query = $user->save();
        if ($query) {
            return response()->json(['msg' => 'success', 'response' => 'Profile updated successfully.', 'user' => $user]);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not update profile.']);
        }
    }
    public function update_password(Request $request)
    {
        // dd($request->all());
        $data = $request->all();

        $validator = Validator::make($data, [
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = auth()->user();
        $user->password = bcrypt($data['password']);
        $query = $user->save();
        if ($query) {
            return response()->json(['msg' => 'success', 'response' => 'Password updated successfully.', 'user' => $user]);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not update password.']);
        }
    }
    // Forgot Password Functions

    public function sendResetOTP(Request $request)
    {
        $data = $request->all();
        if (substr($data['phone_no'], 0, 1) == 0) {
            $data['phone_no'] = '+92' . substr($data['phone_no'], 1);
        } else if (substr($data['phone_no'], 0, 2) == '92') {
            $data['phone_no'] = '+' . $data['phone_no'];
        } else if (substr($data['phone_no'], 0, 1) == '+') {
            $data['phone_no'] = $data['phone_no'];
        } else {
            $data['phone_no'] = '+92' . $data['phone_no'];
        }
        $validator = Validator::make($data, [
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = User::where('phone_no', $data['phone_no'])->first();
        if ($user) {
            $user->otp = rand(1000, 9999);
            $user->save();
            $reset = Mail::to($user->email)->send(new ResetMail($user->otp));
            if ($reset) {
                return response()->json(['msg' => 'success', 'response' => 'Reset OTP sent successfully', 'reset_OTP' => $user->otp]);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not send OTP to email/phone.']);
            }
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Invalid Phone Number.']);
        }
    }
    public function verifyResetOTP(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $validator = Validator::make($data, [
            'otp' => 'required|min:4|max:4',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = User::where('otp', $request->otp)->first();
        if ($user) {
            return response()->json(['msg' => 'success', 'response' => 'OTP verified successfully.']);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Invalid OTP.'], 422);
        }
    }
    public function resetPassword(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        $validator = Validator::make($data, [
            'otp' => 'required|min:4|max:4',
            'password' => 'required|min:6',
            'confirm-password' => 'required|min:6|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = User::where('otp', $request->otp)->first();
        if ($user) {
            $user->password = bcrypt($data['password']);
            $user->otp = null;
            $user->save();
            return response()->json(['msg' => 'success', 'response' => 'Password reset successfully. Please Proceed to Login']);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Invalid OTP. No User Found Against Requested OTP Code'], 422);
        }
    }
}
