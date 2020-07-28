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

Route::middleware(['auth_user', 'stokga'])->prefix('barangkeluar')->group(function() {
    Route::get('/', 'BarangKeluarController@list');
    Route::get('/create', 'BarangKeluarController@create');
    Route::get('/edit/{id}', 'BarangKeluarController@edit');
    Route::get('/delete/{id}', 'BarangKeluarController@delete');
    Route::post('/store', 'BarangKeluarController@store');
});
