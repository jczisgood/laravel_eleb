<?php

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
    return view('welcome');
});
Route::get('/shops','ApiController@shops');
Route::get('/foods','ApiController@foods');
Route::get('/show','ApiController@show');
//Route::get('/text',function (){
//\Illuminate\Support\Facades\Redis::setex('name',300,'123');
//
//
//});