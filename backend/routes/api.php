<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('activate', [UserAuthController::class, 'activate']);
    Route::post('update-profile', [UserAuthController::class, 'update_profile']);
    Route::post('update-password', [UserAuthController::class, 'update_password']);
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::post('refresh', [UserAuthController::class, 'refresh']);
    Route::get('me', [UserAuthController::class, 'user_profile']);

    // Forgot Password Routes
    Route::post('forgot-password', [UserAuthController::class, 'sendResetOTP']);
    Route::post('verify-otp', [UserAuthController::class, 'verifyResetOTP']);
    Route::post('reset-password', [UserAuthController::class, 'resetPassword']);
});
