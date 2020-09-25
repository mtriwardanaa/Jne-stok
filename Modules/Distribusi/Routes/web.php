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

Route::prefix('distribusi')->group(function() {
    Route::get('/', 'DistribusiController@list')->middleware('feature:33');
    Route::get('/detail/{id}', 'DistribusiController@detail')->middleware('feature:33');
    Route::get('/update/{id}', 'DistribusiController@update')->middleware('feature:34');
});