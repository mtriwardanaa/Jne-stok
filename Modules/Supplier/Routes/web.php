<?php

Route::middleware(['auth_user', 'stokga'])->prefix('supplier')->group(function() {
    Route::get('/', 'SupplierController@list');
    Route::get('/create', 'SupplierController@create');
    Route::post('/store', 'SupplierController@store');
    Route::get('/edit/{id}', 'SupplierController@edit');
    Route::post('/update/{id}', 'SupplierController@update');
    Route::get('/delete/{id}', 'SupplierController@delete');
});
