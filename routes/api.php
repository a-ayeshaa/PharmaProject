<?php

use App\Http\Controllers\APIAllUserController;
use App\Http\Controllers\ApiCourierController;
use App\Http\Controllers\APICustomerController;
use App\Http\Controllers\ApiManagerController;
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


//ALL USERS ----------

//GET USER
Route::get('/alluser/get',[APIAllUserController::class,'getUsers'])->middleware("AuthUser");
Route::get('/user/get/{email}',[APIAllUserController::class,'getUser']);

//LOGIN
Route::post('/login',[APIAllUserController::class,'login']);
//LOGOUT
Route::post('/logout',[APIAllUserController::class,'logout']);

//CREATE USER
Route::post('/user/create',[APIAllUserController::class,'createUser']);

//SEND OTP CODE
Route::post('/otp',[APIAllUserController::class,'sendOTP']);
Route::post('/otp/verify',[APIAllUserController::class,'OTPVerify']);
Route::post('/change/password',[APIAllUserController::class,'ChangePassword']);


//courier---Tahmid
Route::get('/courier/orders',[ApiCourierController::class,'orderView']);
Route::get('/courier/acceptedOrders',[ApiCourierController::class,'AcceptedOrderView']);
Route::get('/courier/deliveredOrder',[ApiCourierController::class,'deliveredOrder']);
Route::get('/courier/{order_id}',[CourierController::class,'acceptOrder'])->name('order.accept')->middleware('courierAuth');

//CUSTOMER --->AYESHA
Route::get('/customer/home',[APICustomerController::class,'home'])->middleware("AuthUserCustomer");
Route::post('/customer/account',[APICustomerController::class,'getInfo'])->middleware("AuthUserCustomer");
Route::post('/customer/modify/account',[APICustomerController::class,'customerModify'])->middleware("AuthUserCustomer");
Route::get('/customer/medlist',[APICustomerController::class,'showMed'])->middleware("AuthUserCustomer");
Route::post('/customer/add/cart',[APICustomerController::class,'addToCart'])->middleware("AuthUserCustomer");
Route::get('/customer/cart',[APICustomerController::class,'showCart'])->middleware("AuthUserCustomer");
Route::post('/customer/deleteItem',[APICustomerController::class,'deleteItem'])->middleware("AuthUserCustomer");
Route::get('/customer/grandtotal',[APICustomerController::class,'getGrandTotal'])->middleware("AuthUserCustomer");
Route::post('/customer/confirmOrder',[APICustomerController::class,'confirmOrder'])->middleware("AuthUserCustomer");
Route::post('/customer/orders',[APICustomerController::class,'showOrders'])->middleware("AuthUserCustomer");
Route::get('/customer/{order_id}',[APICustomerController::class,'showItems'])->middleware("AuthUserCustomer");
Route::get('/customer/order/cancel/{order_id}',[APICustomerController::class,'cancelOrder'])->middleware("AuthUserCustomer");
Route::post('/customer/item/return',[APICustomerController::class,'returnItems'])->middleware("AuthUserCustomer");
Route::get('/customer/item/return/{id}',[APICustomerController::class,'return'])->middleware("AuthUserCustomer");
Route::post('/customer/search',[APICustomerController::class,'search'])->middleware("AuthUserCustomer");
Route::post('/customer/complain',[APICustomerController::class,'complainEmail'])->middleware("AuthUserCustomer");


//MANAGER ---> TONMOY
//homepage
Route::get('/manager/home',[ApiManagerController::class,'homepage']);
//medicine table
Route::get('/manager/medicine',[ApiManagerController::class,'viewMed']);
//user table
Route::get('/manager/user',[ApiManagerController::class,'viewUser']);
//order table
Route::get('/manager/orders',[ApiManagerController::class,'viewOrders']);
//delete medicine
Route::post('/manager/deleteMed',[ApiManagerController::class,'deleteMed']);
//supply table
Route::get('/manager/supply',[ApiManagerController::class,'showSupply']);
//go to cart
Route::get('/manager/cart',[ApiManagerController::class,'showSupply']);
//add item to cart
Route::post('/manager/addItem',[ApiManagerController::class,'addItem']);
//view final cart
Route::get('/manager/cart/view',[ApiManagerController::class,'finalCart']);
//view cart
Route::get('/manager/cart/table',[ApiManagerController::class,'viewCart']);
//confirm order
Route::post('/manager/confirm',[ApiManagerController::class,'confirm']);
//contract table
Route::get('/manager/contract',[ApiManagerController::class,'showContract']);
//delete contract
Route::post('/manager/deleteContract',[ApiManagerController::class,'deleteContract']);
//query table
Route::get('/manager/query',[ApiManagerController::class,'showQuery']);
//accept query
Route::post('/manager/acceptQuery',[ApiManagerController::class,'acceptQuery']);
//reject query
Route::post('/manager/declineQuery',[ApiManagerController::class,'declineQuery']);
//account table
Route::get('/manager/account',[ApiManagerController::class,'showAccount']);
//med details
Route::post('/manager/med/detail/{id}',[ApiManagerController::class,'medDetail']);
//order details
Route::post('/manager/orders/detail/{id}',[ApiManagerController::class,'ordersDetail']);
//contract details
Route::post('/manager/contract/detail/{id}',[ApiManagerController::class,'contractDetail']);
//supply details
Route::post('/manager/supply/detail/{id}',[ApiManagerController::class,'supplyDetail']);
//search view
Route::get('/manager/searching',[ApiManagerController::class,'searchView']);
//search
Route::post('/manager/search/user',[ApiManagerController::class,'searchUser']);
//Route::post('/manager/confirm',[ApiManagerController::class,'confirm']);

