<?php

use App\Http\Controllers\APIAllUserController;
use App\Http\Controllers\ApiCourierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//GET USER
Route::get('/user/get',[APIAllUserController::class,'getUsers']);
Route::get('/user/get/{email}',[APIAllUserController::class,'getUser']);

//CREATE USER
Route::post('/user/create',[APIAllUserController::class,'createUser']);


//courier
Route::get('/courier/orders',[ApiCourierController::class,'orderView']);