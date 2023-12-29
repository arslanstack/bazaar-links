<?php

namespace App\Http\Controllers\API;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use App\Mail\ResetMail;
use Carbon\Carbon;

class UserAuthController extends Controller
{
    protected $guard = 'api';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'sendRegisterOTP', 'register', 'sendResetOTP', 'verifyResetOTP', 'resetPassword', 'patternizePhone']]);
    }
    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'phone_no' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }
        $data['phone_no'] = $this->patternizePhone($data['phone_no']);

        $credentials = [
            'phone_no' => $data['phone_no'],
            'password' => $data['password'],
        ];

        if ($token = auth()->attempt($credentials)) {
            $user = auth()->user();
            if ($user->is_blocked == 1) {
                auth()->logout();
                session()->flush();
                return response()->json(['msg' => 'error', 'response' => 'Your account has been blocked by admin due to violation. Please contact support team.'], 401);
            }
            else if($user->status == 0){
                auth()->logout();
                session()->flush();
                return response()->json(['msg' => 'error', 'response' => 'You have inactivated your account. Please contact support team to get it reactivated.'], 401);
            }

            $response = 'User Logged In Successfully';
            $user->city = $user->city->city_name;
            return response()->json([
                'msg' => 'success',
                'response' => $response,
                'token' => $this->respondWithToken(JWTAuth::fromUser(auth()->user())),
                'user' => auth()->user(),
            ]);
        }

        return response()->json(['msg' => 'error', 'response' => 'Invalid phone_no or password!'], 401);
    }
    public function sendRegisterOTP(Request $request)
    {
        $data = $request->all();
        isset($data['phone_no']) ? $data['phone_no'] = $this->patternizePhone($data['phone_no']) : '';
        $data['otp'] = rand(1000, 9999);
        // Validation for email and Password
        if (isset($data['email'])) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                return response()->json(['msg' => 'error', 'response' => 'Email already exists.']);
            }
        }
        if (isset($data['phone_no'])) {
            $user = User::where('phone_no', $data['phone_no'])->first();
            if ($user) {
                return response()->json(['msg' => 'error', 'response' => 'Phone Number already exists.']);
            }
        }
        // Verification based on scenerio
        if (isset($data['email']) && !isset($data['phone_no'])) {
            // verify mail
            $verification = Mail::to($data['email'])->send(new VerificationMail($data['otp']));
            if ($verification) {
                return response()->json(['msg' => 'success', 'response' => 'OTP sent successfully to email', 'otp' => $data['otp'], 'email' => $data['email']]);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not verify email.']);
            }
        } else {
            // verify phone
            $verification = Mail::to($data['email'])->send(new VerificationMail($data['otp']));
            if ($verification) {
                return response()->json(['msg' => 'success', 'response' => 'OTP sent successfully to phone', 'otp' => $data['otp'], 'phone_no' => $data['phone_no']]);
            } else {
                return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not verify email.']);
            }
        }
        return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not reach verification API.']);
    }
    public function register(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required|min:6',
            'phone_no' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }
        $data['phone_no'] = $this->patternizePhone($data['phone_no']);
        $user = User::where('phone_no', $data['phone_no'])->orWhere('email', $data['email'])->first();
        if ($user) {
            return response()->json(['msg' => 'error', 'response' => 'User with this email or phone_no already exists.']);
        }

        $data['password_decrypt'] = $data['password'];
        $data['password'] = bcrypt($data['password']);
        $data['image_name'] = 'user.png';
        $data['image_path'] = url('/public/assets/upload_images/') . '/' . $data['image_name'];
        $data['status'] = 1;
        $user = User::create($data);

        if ($user) {
            $credentials = [
                'phone_no' => $data['phone_no'],
                'password' => $data['password_decrypt'],
            ];
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['msg' => 'error', 'response' => 'Could Not Authenticate After Account Creation!'], 401);
            }
            return response()->json([
                'msg' => 'success',
                'response' => 'User Registered Successfully',
                'token' => $this->respondWithToken(JWTAuth::fromUser(auth()->user())),
                'user' => $user,
            ]);
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could Not Create User.']);
        }
    }
    public function user_profile()
    {
        $user = auth()->user();
        $user->city = $user->city->city_name;
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
        isset($data['name']) ? $user->name = $data['name'] : $user->name = $user->name;
        if (isset($data['phone_no'])) {
            $data['phone_no'] = $this->patternizePhone($data['phone_no']);
            $user->phone_no = $data['phone_no'];
            $user->otp = NULL;
        }
        $city = City::where('id', $data['city'])->first();
        $user->city_id = $city->id;
        $user->address = $data['address'];
        $user->zip = $data['zip'];
        $query = $user->save();

        if ($request->hasFile('image')) {
            // if user->image_name != null or user.png then delete previous image
            if ($user->image_name != null && $user->image_name != 'user.png') {
                $image_path = public_path('assets/upload_images/') . '/' . $user->image_name;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            $image = $request->file('image');
            $file_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $data['image_name'] = 'dp_' . time() . '.' . $extension;


            $destinationPath = public_path('assets/upload_images/');
            $image->move($destinationPath, $data['image_name']);
            $data['image_path'] = url('/public/assets/upload_images/') . '/' . $data['image_name'];
            $user->image_name = $data['image_name'];
            $user->image_path = $data['image_path'];
            $queryTwo = $user->save();
        }
        return response()->json(['msg' => 'success', 'response' => 'Profile updated successfully.', 'user' => $user]);
    }
    public function send_update_phone_otp(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make($data, [
            'phone_no' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validation->errors(), 422));
        }

        $data['phone_no'] = $this->patternizePhone($data['phone_no']);

        $user = auth()->user();

        if ($user->phone_no == $data['phone_no']) {
            return response()->json(['msg' => 'error', 'response' => 'New phone number cannot be same as old phone number.'], 422);
        } else if (User::where('phone_no', $data['phone_no'])->first()) {
            return response()->json(['msg' => 'error', 'response' => 'A user with this phone number already exists.'], 422);
        }

        $user->otp = rand(1000, 9999);
        $query = $user->save();

        if ($query) {
            if (isset($data['email'])) {
                $verification = Mail::to($data['email'])->send(new VerificationMail($user->otp));
                if ($verification) {
                    return response()->json(['msg' => 'success', 'response' => 'OTP sent successfully to email address.', 'otp' => $user->otp, 'email' => $data['email']]);
                } else {
                    return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not verify email address.']);
                }
            } else {
                // later reset to phone sms verification $data['phone_no']
                $verification = Mail::to($user->email)->send(new VerificationMail($user->otp));
                if ($verification) {
                    return response()->json(['msg' => 'success', 'response' => 'OTP sent successfully to phone number.', 'otp' => $user->otp, 'phone_no' => $data['phone_no']]);
                } else {
                    return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not verify phone number.']);
                }
            }
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not Generate OTP.']);
        }
    }
    public function update_password(Request $request)
    {
        // dd($request->all());
        $data = $request->all();

        $validator = Validator::make($data, [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        $user = auth()->user();
        if (!password_verify($data['old_password'], $user->password)) {
            return response()->json(['msg' => 'error', 'response' => 'Old Password is incorrect.'], 422);
        }
        $user->password = bcrypt($data['password']);
        $query = $user->save();
        $user->city = $user->city->city_name;
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
        $validator = Validator::make($data, [
            'phone_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }
        $data['phone_no'] = $this->patternizePhone($data['phone_no']);



        $user = User::where('phone_no', $data['phone_no'])->first();
        if ($user) {
            $user->otp = rand(1000, 9999);
            $user->save();
            if (isset($data['email'])) {
                $reset = Mail::to($data['email'])->send(new ResetMail($user->otp));
                if ($reset) {
                    return response()->json(['msg' => 'success', 'response' => 'Reset OTP sent successfully to email address', 'otp' => $user->otp, 'email' => $data['email']]);
                } else {
                    return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not send OTP to email/phone.']);
                }
            } else {
                // Later will add sms verification $data['phone_no']
                $reset = Mail::to($user->email)->send(new ResetMail($user->otp));
                if ($reset) {
                    return response()->json(['msg' => 'success', 'response' => 'Reset OTP sent successfully to phone number', 'otp' => $user->otp, 'phone_no' => $data['phone_no']]);
                } else {
                    return response()->json(['msg' => 'error', 'response' => 'Something went wrong! Could not send OTP to email/phone.']);
                }
            }
        } else {
            return response()->json(['msg' => 'error', 'response' => 'Invalid Phone Number.']);
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

    public function patternizePhone($phone_no)
    {
        if (substr($phone_no, 0, 1) == 0) {
            $phone_no = '+92' . substr($phone_no, 1);
        } else if (substr($phone_no, 0, 2) == '92') {
            $phone_no = '+' . $phone_no;
        } else if (substr($phone_no, 0, 1) == '+') {
            $phone_no = $phone_no;
        } else {
            $phone_no = '+92' . $phone_no;
        }
        return $phone_no;
    }

    public function deleteAccount(Request $request){
        $user = auth()->user();
        $user->status = 0;
        $user->save();
        auth()->logout();
        session()->flush();
        return response()->json(['msg' => 'success', 'response' => 'Your account has been inactivated/deleted. Please contact support team to get it reactivated in 1 month or else the data would be permanently deleted.'], 401);
    }

    public function contact(Request $request){
        
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'name' => 'required',
            'phone_no' => 'required',
            'message' => 'required',
        ]);
        
        $data['phone_no'] = $this->patternizePhone($data['phone_no']);
        
        if ($validator->fails()) {
            return response()->json(array('msg' => 'error', 'response' => $validator->errors(), 422));
        }

        // contact us logic implemented over here

        return response()->json(['msg' => 'success', 'response' => 'Your message has been sent successfully. Support team will get back to you soon.']);

    }
}
