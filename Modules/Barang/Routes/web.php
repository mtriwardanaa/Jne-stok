<?php

Route::middleware(['auth_user'])->prefix('barang')->group(function () {
    Route::get('/', 'BarangController@list')->middleware('feature:21');
    Route::get('/create', 'BarangController@create')->middleware('feature:22');
    Route::get('/import', 'BarangController@import')->middleware('feature:25');
    Route::post('/store', 'BarangController@store')->middleware('feature:22');
    Route::post('/save', 'BarangController@save')->middleware('feature:25');
    Route::get('/edit/{id}', 'BarangController@edit')->middleware('feature:23');
    Route::get('/print/{status}', 'BarangController@print')->middleware('feature:22');
    Route::get('/history/{id}', 'BarangController@history')->middleware('feature:22');
    Route::post('/update/{id}', 'BarangController@update')->middleware('feature:23');
    Route::get('/delete/{id}', 'BarangController@delete')->middleware('feature:24');
});
