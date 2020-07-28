<?php

Route::middleware(['auth_user', 'stokga'])->prefix('barang')->group(function() {
    Route::get('/', 'BarangController@list');
    Route::get('/create', 'BarangController@create');
    Route::get('/import', 'BarangController@import');
    Route::post('/store', 'BarangController@store');
    Route::post('/save', 'BarangController@save');
    Route::get('/edit/{id}', 'BarangController@edit');
    Route::get('/history/{id}', 'BarangController@history');
    Route::post('/update/{id}', 'BarangController@update');
    Route::get('/delete/{id}', 'BarangController@delete');
});
