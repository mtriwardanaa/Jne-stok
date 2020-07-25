<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class Dashboard extends Controller
{
	public function __construct()
    {
        $this->middleware('auth_user');
    }
    
    public function dashboard()
    {
    	if (Auth::user()->id_divisi == 10) {
	    	return view('dashboard_ga');
    	}

    	return view('dashboard_user');
    }
}
