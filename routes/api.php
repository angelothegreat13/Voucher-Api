<?php

use Illuminate\Http\Request;

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

Route::post('/register','Api\AuthController@register');
Route::post('/login','Api\AuthController@login');

Route::get('/puregold/test','Api\PureGoldApiController@test');

Route::post('/puregold/gateway','Api\PureGoldApiController@gateway');

Route::middleware('auth:api')->group(function() {
    Route::get('/user/detail','Api\UsersController@detail')->name('user.detail');
   
    Route::get('/products','Api\ProductsController@index')->name('products.index');
    Route::post('/products/store','Api\ProductsController@store')->name('products.store');
    Route::get('/products/{id}','Api\ProductsController@show')->name('products.show');
    Route::patch('/products/{id}','Api\ProductsController@update')->name('products.update');
    Route::delete('/products/{id}','Api\ProductsController@destroy')->name('products.destroy');

    Route::post('/puregold-vouchers/store','Api\PuregoldVouchersController@store')->name('puregold-vouchers.store');
    Route::get('/puregold-vouchers/{code}','Api\PuregoldVouchersController@show')->name('puregold-vouchers.show');
});
