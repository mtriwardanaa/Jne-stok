<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class Login extends Controller
{
    public function login(Request $request)
    {
    	if (Auth::check()) {
    		return redirect('dashboard');
    	}

    	$method = $request->method();
		$post = $request->except('_token');

		if ($method == 'GET') {
		    return view('login');
		}

		if (Auth::attempt($post)) {
			return redirect('dashboard');
		}

		return redirect('login')->withErrors(['Username atau Password salah']);
    }

    public function username() {
        return 'username';
    }
}