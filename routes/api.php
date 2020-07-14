<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('products', 'ProductController');
Route::resource('orders', 'OrderController');
Route::get('order-products/{order}', 'OrderProductController@index');
Route::post("order-products/{order}", "OrderProductController@store");
Route::delete("order-products/{order}/{product}", "OrderProductController@destroy");
Route::post("orders/{order}/add-coupon", "TransactionController@addCoupon");
Route::get("orders/update-status/{order}/{status}", "OrderController@updateStatus");
