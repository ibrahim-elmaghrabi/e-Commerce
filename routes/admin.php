<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CityController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\ColorController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\StoreController;
use App\Http\Controllers\Api\Admin\CouponController;
use App\Http\Controllers\Api\Admin\AddressController;
use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\Auth\LoginController;
use App\Http\Controllers\Api\Admin\ReturningRequestController;



Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {

    Route::resources([
        'countries'=>  CountryController::class,
        'cities' =>  CityController::class,
        'categories'=>  CategoryController::class,
        'admins'=>  AdminController::class,
        'colors'=> ColorController::class,
        'coupons'=> CouponController::class,
        'products'=> ProductController::class,
        'stores'=> StoreController::class
    ], ['except' => ['create']]);

    Route::resource('returning_requests', ReturningRequestController::class)->except('create', 'store', 'destroy');
    Route::resource('orders', OrderController::class)->except('create', 'store', 'destroy');
    Route::resource('addresses', AddressController::class)->except('create', 'store', 'edit','update', 'destroy');
    Route::resource('orders', OrderController::class)->except('create', 'store', 'update', 'edit');
    Route::post('logout', [LoginController::class, 'logout']);

});









