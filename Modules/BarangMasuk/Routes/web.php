<?php

Route::middleware(['auth_user'])->prefix('barangmasuk')->group(function() {
    Route::get('/', 'BarangMasukController@list')->middleware('feature:2');
    Route::get('/create', 'BarangMasukController@create')->middleware('feature:3');
    Route::post('/store', 'BarangMasukController@store')->middleware('feature:3');
    Route::get('/detail/{id}', 'BarangMasukController@detail')->middleware('feature:6');
    Route::get('/edit/{id}', 'BarangMasukController@edit')->middleware('feature:4');
    Route::post('/update/{id}', 'BarangMasukController@update')->middleware('feature:4');
    Route::get('/delete/{id}', 'BarangMasukController@delete')->middleware('feature:5');
});
