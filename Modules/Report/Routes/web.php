<?php

Route::middleware(['auth_user', 'stokga'])->prefix('report')->group(function() {
    Route::get('/', 'ReportController@index');
    Route::post('/print/stok', 'ReportController@stok');
    Route::post('/print', 'ReportController@print');
});