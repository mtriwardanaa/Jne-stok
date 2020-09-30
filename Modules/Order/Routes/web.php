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

Route::middleware('auth_user')->prefix('order')->group(function() {
    Route::get('/', 'OrderController@list')->middleware('feature:13');
    Route::get('/create', 'OrderController@create')->middleware('feature:14');
    Route::post('/store', 'OrderController@store')->middleware('feature:14');
    Route::get('/edit/{id}', 'OrderController@edit')->middleware('feature:15');
    Route::post('/update/{id}', 'OrderController@update')->middleware('feature:15');
    Route::post('/approve', 'OrderController@approve')->middleware('feature:32');
    Route::post('/approve/update/{id}', 'OrderController@updateApprove')->middleware('feature:32');
    Route::get('/detail/{id}', 'OrderController@detail')->middleware('feature:13');
    Route::get('/delete/{id}', 'OrderController@delete')->middleware('feature:13');
});