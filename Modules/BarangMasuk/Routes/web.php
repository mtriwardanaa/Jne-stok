<?php

Route::middleware(['auth_user', 'stokga'])->prefix('barangmasuk')->group(function() {
    Route::get('/', 'BarangMasukController@list');
    Route::get('/create', 'BarangMasukController@create');
    Route::post('/store', 'BarangMasukController@store');
    Route::get('/edit/{id}', 'BarangMasukController@edit');
    Route::get('/delete/{id}', 'BarangMasukController@delete');
});
