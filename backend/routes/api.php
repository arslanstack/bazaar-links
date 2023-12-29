<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserAuthController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductPostController;
use App\Http\Controllers\API\ProductRequestController;
use App\Http\Controllers\API\ManagePostsController;
use App\Http\Controllers\API\FavouritesController;


Route::group(['middleware' => 'api'], function ($router) {
    Route::get('get-cities', [CommonController::class, 'get_cities']);

    // Auth Routes
    Route::post('send-register-otp', [UserAuthController::class, 'sendRegisterOTP']);
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('update-profile', [UserAuthController::class, 'update_profile']);
    Route::post('send-update-phone-otp', [UserAuthController::class, 'send_update_phone_otp']);
    Route::post('update-password', [UserAuthController::class, 'update_password']);
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::post('refresh', [UserAuthController::class, 'refresh']);
    Route::get('me', [UserAuthController::class, 'user_profile']);

    // Contact
    Route::post('contact', [UserAuthController::class, 'contact']);

    // Delete / Inactivate Account
    Route::post('delete-account', [UserAuthController::class, 'deleteAccount']);

    // Forgot Password Routes
    Route::post('forgot-password', [UserAuthController::class, 'sendResetOTP']);
    Route::post('reset-password', [UserAuthController::class, 'resetPassword']);

    // Category Routes group
    Route::group(['prefix' => 'category'], function () {
        Route::get('all', [CategoryController::class, 'index']);
        Route::get('show/{id}', [CategoryController::class, 'show_category']);
        Route::get('all-subcategories', [CategoryController::class, 'all_subcategories']);
        Route::get('specific-subcategories/{parent_id}', [CategoryController::class, 'specific_subcategories']);
    });

    // Product Posts Routes group
    Route::group(['prefix' => 'product-post'], function () {

        // Non Authenticated Requests for Guest Users As Well As Authenticated Users
        Route::get('all', [ProductPostController::class, 'index']);
        Route::get('show/{post_id}', [ProductPostController::class, 'show']);
        Route::get('show-subcategory-posts/{subcategory_id}', [ProductPostController::class, 'showSubCategoryPosts']);
        Route::get('show-category-posts/{category_id}', [ProductPostController::class, 'showCategoryPosts']);
        Route::get('show-city-posts/{city_id}', [ProductPostController::class, 'showCityPosts']);

        // Only For Authenticated Users
        Route::post('store', [ProductPostController::class, 'store']);
        Route::post('deactivate', [ProductPostController::class, 'deactivate']);
        Route::post('activate', [ProductPostController::class, 'activate']);
        Route::post('delete', [ProductPostController::class, 'destroy']);
    });

    // Product Request Routes group
    Route::group(['prefix' => 'product-request'], function () {

        // Non Authenticated Requests for Guest Users As Well As Authenticated Users
        Route::get('all', [ProductRequestController::class, 'index']);
        Route::get('show/{req_id}', [ProductRequestController::class, 'show']);
        Route::get('show-subcategory-requests/{subcategory_id}', [ProductRequestController::class, 'showSubCategoryRequests']);
        Route::get('show-category-requests/{category_id}', [ProductRequestController::class, 'showCategoryRequests']);
        Route::get('show-city-requests/{city_id}', [ProductRequestController::class, 'showCityRequests']);

        // Only For Authenticated Users
        Route::post('store', [ProductRequestController::class, 'store']);
        Route::post('deactivate', [ProductRequestController::class, 'deactivate']);
        Route::post('activate', [ProductRequestController::class, 'activate']);
        Route::post('delete', [ProductRequestController::class, 'destroy']);
    });

    // Manage Posts + Requests Routes group
    Route::group(['prefix' => 'manage-post'], function () {
        Route::get('all', [ManagePostsController::class, 'all']);
        Route::get('posts', [ManagePostsController::class, 'activePosts']);
        Route::get('requests', [ManagePostsController::class, 'activeRequests']);
    });
    // Favourites Routes group
    Route::group(['prefix' => 'favourite'], function () {
        Route::get('all', [FavouritesController::class, 'getFavourites']);
        Route::get('favUnFave-product-post/{postID}', [FavouritesController::class, 'favUnfavPost']);
        Route::get('favUnFave-product-request/{reqID}', [FavouritesController::class, 'favUnfavRequest']);
    });
});
