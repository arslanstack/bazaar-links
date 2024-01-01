<?php
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductPostController;
use App\Http\Controllers\Admin\ProductRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'  =>  'admin'], function () {
	Route::get('login', [AdminLoginController::class, 'index'])->name('login');
	Route::post('verify_login', [AdminLoginController::class, 'verify_login']);
	Route::get('logout', [AdminLoginController::class, 'logout']);

	Route::group(['middleware' => ['auth:admin']], function () {

		Route::get('/', [AdminController::class, 'index']);
		Route::get('admin', [AdminController::class, 'index']);
		Route::get('change_password', [AdminController::class, 'change_password']);
		Route::post('update_password', [AdminController::class, 'update_password']);

		Route::group(['prefix'  =>  'users'], function () {
			Route::get('/', [UserController::class, 'index']);
			Route::post('update_statuses', [UserController::class, 'update_statuses']);
			Route::get('detail/{id}', [UserController::class, 'user_details']);
		});

		Route::group(['prefix'  =>  'categories'], function () {
			Route::get('/', [CategoryController::class, 'index']);
			Route::get('add', [CategoryController::class, 'show_add_category']);
			Route::post('store', [CategoryController::class, 'store_category']);
			Route::post('update', [CategoryController::class, 'update_category']);
			Route::post('delete_category', [CategoryController::class, 'delete_category']);
			Route::post('update_statuses', [CategoryController::class, 'update_statuses']);
			Route::get('detail/{id}', [CategoryController::class, 'category_details']);

			Route::post('store_subcategory', [CategoryController::class, 'store_subcategory']);
			Route::post('subcategory_show', [CategoryController::class, 'subcategory_show']);
			Route::post('update_subcategory', [CategoryController::class, 'update_subcategory']);
			Route::post('update_subcategory_status', [CategoryController::class, 'update_subcategory_status']);
			Route::post('delete_subcategory', [CategoryController::class, 'delete_subcategory']);

		});

		Route::group(['prefix'  =>  'product-posts'], function () {
			Route::get('/', [ProductPostController::class, 'index']);
			Route::post('update_statuses', [ProductPostController::class, 'update_statuses']);
			Route::get('detail/{id}', [ProductPostController::class, 'post_details']);
		});
		Route::group(['prefix'  =>  'product-requests'], function () {
			Route::get('/', [ProductRequestController::class, 'index']);
			Route::post('update_statuses', [ProductRequestController::class, 'update_statuses']);
			Route::get('detail/{id}', [ProductRequestController::class, 'prod_req_details']);
		});
	});
});