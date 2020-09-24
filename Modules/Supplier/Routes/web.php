<?php

Route::middleware(['auth_user'])->prefix('supplier')->group(function() {
    Route::get('/', 'SupplierController@list')->middleware('feature:26');
    Route::get('/create', 'SupplierController@create')->middleware('feature:27');
    Route::post('/store', 'SupplierController@store')->middleware('feature:27');
    Route::get('/edit/{id}', 'SupplierController@edit')->middleware('feature:28');
    Route::post('/update/{id}', 'SupplierController@update')->middleware('feature:28');
    Route::get('/delete/{id}', 'SupplierController@delete')->middleware('feature:29');
});
