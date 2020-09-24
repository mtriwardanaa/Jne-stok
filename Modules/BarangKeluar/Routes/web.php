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

Route::middleware(['auth_user'])->prefix('barangkeluar')->group(function() {
    Route::get('/', 'BarangKeluarController@list')->middleware('feature:7');
    Route::get('/create', 'BarangKeluarController@create')->middleware('feature:8');
    Route::get('/detail/{id}', 'BarangKeluarController@detail')->middleware('feature:11');
    Route::get('/suratjalan/{id}', 'BarangKeluarController@suratJalan')->middleware('feature:12');
    Route::get('/edit/{id}', 'BarangKeluarController@edit')->middleware('feature:9');
    Route::get('/delete/{id}', 'BarangKeluarController@delete')->middleware('feature:10');
    Route::post('/store', 'BarangKeluarController@store')->middleware('feature:8');
});
