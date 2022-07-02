<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllUserController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

//ALL USERS

Route::get('/registration',[AllUserController::class,'registration'])->name('user.registration');
Route::post('/registration',[AllUserController::class,'registrationSubmit'])->name('user.registration.submit');

Route::get('/registration/{type}',[AllUserController::class,'register'])->name('user.register');
Route::post('/registration/{type}',[AllUserController::class,'registerSubmit'])->name('user.register.submit');

Route::get('/login',[AllUserController::class,'login'])->name('user.login');
Route::post('/login',[AllUserController::class,'loginSubmit'])->name('user.login.submit');

Route::get('/logout',[AllUserController::class,'logout'])->name('logout');

Route::get('/back',[AllUserController::class,'back'])->name('back');

//CUSTOMER
Route::get('/customer/home',[CustomerController::class,'customerHome'])->name('customer.home');
Route::get('/customer/account/{name}',[CustomerController::class,'customerAccount'])->name('customer.account');

Route::get('/customer/account/modify/{name}',[CustomerController::class,'customerModifyAccount'])->name('customer.modify.account');
Route::post('/customer/account/modify/{name}',[CustomerController::class,'customerModifiedAccount'])->name('customer.modified.account');


Route::get('/customer/show/MedicineList',[CustomerController::class,'showMed'])->name('customer.show.med');
Route::post('/customer/show/MedicineList',[CustomerController::class,'addToCart'])->name('customer.add.to.cart');

Route::get('/customer/cart',[CustomerController::class,'showCart'])->name('customer.show.cart');


//MANAGER
Route::get('/manager/home',[ManagerController::class,'managerHome'])->name('manager.home')->middleware('managerAuth');
Route::post('/manager/home',[ManagerController::class,'HomeAction'])->name('manager.HomeAction')->middleware('managerAuth');

Route::get('/manager/table/select',[ManagerController::class,'tableSelect'])->name('manager.tableSelect')->middleware('managerAuth');
Route::post('/manager/table/select',[ManagerController::class,'viewTable'])->name('manager.tableView')->middleware('managerAuth');


Route::get('/manager/table/customer',[ManagerController::class,'viewCustomer'])->name('manager.tableCustomer')->middleware('managerAuth');
Route::get('/manager/table/vendor',[ManagerController::class,'viewVendor'])->name('manager.tableVendor')->middleware('managerAuth');
Route::get('/manager/table/courier',[ManagerController::class,'viewCourier'])->name('manager.tableCourier')->middleware('managerAuth');
Route::get('/manager/table/manager',[ManagerController::class,'viewManager'])->name('manager.tableManager')->middleware('managerAuth');

Route::get('/manager/table/info/{id}',[ManagerController::class, 'userInfo'])->name('user.info')->middleware('managerAuth');
Route::get('/manager/table/info/delete/{id}',[ManagerController::class, 'userDelete'])->name('user.delete')->middleware('managerAuth');

Route::get('/manager/table/medicine',[ManagerController::class,'viewMed'])->name('manager.tableMedicine')->middleware('managerAuth');
Route::get('/manager/table/info/med/{id}',[ManagerController::class, 'medInfo'])->name('med.info')->middleware('managerAuth');
Route::get('/manager/table/info/med/delete/{id}',[ManagerController::class, 'medDelete'])->name('med.delete')->middleware('managerAuth');

Route::get('/manager/table/order',[ManagerController::class,'viewOrder'])->name('manager.tableOrder')->middleware('managerAuth');
Route::get('/manager/table/info/order/{id}',[ManagerController::class, 'orderInfo'])->name('order.info')->middleware('managerAuth');

Route::get('/manager/table/contract',[ManagerController::class,'viewContract'])->name('manager.tableContracts')->middleware('managerAuth');
Route::get('/manager/table/info/contract/{id}',[ManagerController::class, 'contractInfo'])->name('contract.info')->middleware('managerAuth');
Route::get('/manager/table/info/contract/delete/{id}',[ManagerController::class, 'contractDelete'])->name('contract.delete')->middleware('managerAuth');

//Courier
Route::get('/courier/home',[CourierController::class,'courierHome'])->name('courier.home');
Route::get('/courier/order',[CourierController::class,'orderView'])->name('courier.order');
Route::get('/courier/acceptedOrder',[CourierController::class,'AcceptedOrderView'])->name('courier.AcceptedOrder');

