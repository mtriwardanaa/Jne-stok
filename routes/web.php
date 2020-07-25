<?php

Route::get('/', function () {
    return redirect('login');
});

Route::any('/login', 'Login@login')->name('login');

Route::middleware('auth_user')->get('dashboard', 'Dashboard@dashboard');

Route::get('logout', function(){
    session()->flush();
    return redirect('login')->with(['success' => ['Logout admin berhasil']]);
})->name('logout');