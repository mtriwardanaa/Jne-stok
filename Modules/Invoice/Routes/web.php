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

Route::prefix('invoice')->group(function() {
    Route::get('/', 'InvoiceController@list')->middleware('feature:16');
    Route::get('/detail/{id}', 'InvoiceController@detail')->middleware('feature:16');
    Route::post('/generate', 'InvoiceController@generate')->middleware('feature:17');
});
