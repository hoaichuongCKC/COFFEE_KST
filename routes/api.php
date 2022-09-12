<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['prefix' => 'v2/AuthController'], function () {

    Route::post('/Login',[AuthController::class,'login']);
    
    Route::post('/change_password',[AuthController::class,'changePassword']);

    Route::post('/get_information_user',[AuthController::class,'getInformationUser']);

});
