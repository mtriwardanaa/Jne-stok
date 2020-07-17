<?php

Route::prefix('barang')->group(function() {
    Route::get('/', 'BarangController@list');
    Route::get('/create', 'BarangController@create');
    Route::post('/store', 'BarangController@store');
    Route::get('/edit/{id}', 'BarangController@edit');
    Route::get('/history/{id}', 'BarangController@history');
    Route::post('/update/{id}', 'BarangController@update');
    Route::get('/delete/{id}', 'BarangController@delete');
});
