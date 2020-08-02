<?php

Route::prefix('report')->group(function() {
    Route::get('/', 'ReportController@index');
    Route::get('/stok', 'ReportController@stok');
    Route::post('/print', 'ReportController@print');
});
