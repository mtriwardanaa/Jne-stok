<?php

Route::prefix('report')->group(function() {
    Route::get('/', 'ReportController@index');
    Route::post('/print', 'ReportController@print');
});
