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
    Route::get('/', 'OrderController@list');
    Route::get('/create', 'OrderController@create');
    Route::get('/approve/{id}', 'OrderController@approve');
    Route::get('/detail/{id}', 'OrderController@detail');
    Route::post('/approve/update/{id}', 'OrderController@updateApprove');
    Route::post('/store', 'OrderController@store');
});
