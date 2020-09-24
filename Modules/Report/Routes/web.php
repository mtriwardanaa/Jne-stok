<?php

Route::middleware(['auth_user'])->prefix('report')->group(function() {
    Route::get('/', 'ReportController@index')->middleware('feature:18');
    Route::post('/print/stok', 'ReportController@stok')->middleware('feature:20');
    Route::post('/print', 'ReportController@print')->middleware('feature:19');
});